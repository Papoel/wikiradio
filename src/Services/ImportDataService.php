<?php

namespace App\Services;

use League\Csv\Reader;
use App\Entity\Radiogramme;
use App\Entity\RadiogrammeError;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RadiogrammeRepository;
use App\Repository\RadiogrammeErrorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Exception\RuntimeException;

class ImportDataService
{
    private EntityManagerInterface $entityManager;
    private array $errorRecords = [];
    private array $errorTypes = [];

    public function __construct(
        private readonly ManagerRegistry $doctrine,
        private readonly RadiogrammeRepository $radiogrammeRepository,
        private readonly RadiogrammeErrorRepository $radiogrammeErrorRepository
    ) {
        $this->entityManager = $doctrine->getManager();
    }

    public function importData(string $folder, array $filenames, SymfonyStyle $style): void
    {
        // Mise en place d'un style
        $style->title('Importation de la base de données des radiogrammes');

        // Liste des fichiers à importer
        // $filenames = ['radiogrammes-1.csv'];

        // Définir le délimiteur
        $delimiter = ',';

        // Initailisation du Timer
        $startTime = microtime(as_float: true);

        // Compter le nombre total d'enregistrements à importer
        $totalRecords = $this->countTotalRecords($folder, $filenames, $delimiter);

        if ($totalRecords === 0) {
            $style->warning('Aucun enregistrement trouvé dans les fichiers CSV.');
            return;
        }

        $style->text(sprintf('Importation de %d radiogrammes...', $totalRecords));

        // Créer une barre de progression
        $progressBar = $style->createProgressBar($totalRecords);
        $progressBar->setFormat(
            "%current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% %memory:6s% | %message%"
        );
        $progressBar->setMessage('Démarrage de l\'importation...');
        $progressBar->start();

        // Récupérer et traiter les radiogrammes
        $importedCount = 0;
        $updatedCount = 0;
        $errorCount = 0;
        $processedCount = 0;

        // Tableau pour stocker les radiogrammes en erreur
        $this->errorRecords = [];
        $this->errorTypes = [];

        // Tableau pour suivre les radiogrammes uniques déjà traités
        $processedUniqueValues = [];

        // Tableau pour suivre les radiogrammes uniques importés avec succès
        $successfullyImportedValues = [];

        // Taille du lot pour le traitement par lots (batch processing)
        $batchSize = 20; // Réduire la taille du lot pour éviter les problèmes de mémoire

        // Tableau pour collecter les radiogrammes à insérer par lots
        $radiogrammesToInsert = [];

        foreach ($filenames as $filename) {
            $filePath = $folder . '/' . $filename;

            if (!file_exists($filePath)) {
                $style->error('Le fichier ' . $filePath . ' n\'existe pas.');
                continue;
            }

            $reader = Reader::createFromPath(path: $filePath, open_mode: 'r')
                ->setDelimiter(delimiter: $delimiter)
                ->setHeaderOffset(offset: 0)
            ;

            // Boucler sur chaque ligne du fichier CSV
            foreach ($reader->getRecords() as $index => $record) {
                $processedCount++;

                try {
                    // S'assurer que l'EntityManager est ouvert
                    if (!$this->isEntityManagerOpen()) {
                        $this->resetEntityManager();
                    }

                    // Supprimer les valeurs nulles du tableau $record
                    $record = array_filter($record, fn($value) => $value !== null);

                    // Générer la valeur unique pour vérifier les doublons dans le même lot
                    $uniqueValue = $this->generateUniqueValueFromRecord($record);

                    // Vérifier si ce radiogramme a déjà été traité dans ce lot
                    if (isset($processedUniqueValues[$uniqueValue])) {
                        // C'est un doublon dans le fichier CSV lui-même
                        // Récupérer les détails de la première occurrence
                        $firstOccurrence = $processedUniqueValues[$uniqueValue];

                        // Préparer un message d'erreur simplifié
                        $detailedMessage = sprintf(
                            "Doublon détecté dans le fichier CSV: Lignes %d, %d.",
                            ($firstOccurrence['index'] + 1),
                            ($index + 1)
                        );

                        $this->handleImportError(
                            $record,
                            new \Exception($detailedMessage),
                            $progressBar,
                            'Erreur de doublon dans le CSV'
                        );
                        $errorCount++;
                        $progressBar->advance();
                        continue;
                    }

                    // Marquer ce radiogramme comme traité
                    $processedUniqueValues[$uniqueValue] = [
                        'record' => $record,
                        'index' => $index
                    ];

                    // Créer ou mettre à jour le radiogramme
                    $result = $this->createOrUpdateRadiogramme($record);

                    // Ajouter le radiogramme à la liste pour insertion par lots
                    $radiogrammesToInsert[] = $result['radiogramme'];

                    // Mettre à jour les compteurs
                    if ($result['isNew']) {
                        $importedCount++;
                        $progressBar->setMessage(sprintf('Importation du radiogramme %s', $record['tranche'] ?? $index));
                    } else {
                        $updatedCount++;
                        $progressBar->setMessage(sprintf('Mise à jour du radiogramme %s', $record['tranche'] ?? $index));
                    }

                    // Ajouter à la liste des radiogrammes importés avec succès
                    $successfullyImportedValues[$uniqueValue] = true;

                    // Traiter par lots pour économiser la mémoire
                    if (count($radiogrammesToInsert) >= $batchSize) {
                        $this->bulkInsertRadiogrammes($radiogrammesToInsert, $style, $progressBar);
                        $radiogrammesToInsert = [];
                    }
                } catch (\Exception $e) {
                    // Capturer l'erreur et l'ajouter à la liste des erreurs
                    $this->handleImportError($record, $e, $progressBar);
                    $errorCount++;

                    // En cas d'erreur, réinitialiser l'EntityManager
                    if (strpos($e->getMessage(), 'EntityManager is closed') !== false) {
                        $this->resetEntityManager();
                    }
                }

                // Avancer la barre de progression
                $progressBar->advance();
            }

            // Traiter les radiogrammes restants
            if (!empty($radiogrammesToInsert)) {
                $this->bulkInsertRadiogrammes($radiogrammesToInsert, $style, $progressBar);
                $radiogrammesToInsert = [];
            }
        }

        // Vérifier le nombre réel d'enregistrements dans la base de données
        $actualCount = $this->radiogrammeRepository->count([]);

        // Persister les erreurs dans la table RadiogrammeError
        $this->persistErrors($style);

        // Terminer la barre de progression
        $progressBar->finish();
        $style->newLine(2);

        // Nombre de radiogrammes uniques importés avec succès
        $successfullyImportedCount = count($successfullyImportedValues);

        // Afficher un résumé
        $style->success(sprintf(
            'Importation terminée en %s avec %d radiogrammes importés, %d mis à jour et %d erreurs sur %d enregistrements traités.',
            $this->getExecutionTime($startTime),
            $importedCount,
            $updatedCount,
            $errorCount,
            $processedCount
        ));

        // Afficher le nombre réel d'enregistrements en base de données
        $style->text(sprintf(
            'Nombre réel de radiogrammes en base de données: %d',
            $actualCount
        ));

        // Afficher le nombre de radiogrammes uniques importés avec succès
        $style->text(sprintf(
            'Nombre de radiogrammes uniques importés avec succès: %d',
            $successfullyImportedCount
        ));
    }

    /**
     * Traite un lot de radiogrammes et retourne les statistiques
     * Cette méthode n'est plus utilisée dans la nouvelle approche
     */
    private function processBatch(ProgressBar $progressBar): array
    {
        // Cette méthode n'est plus utilisée dans la nouvelle approche
        // Elle peut être supprimée ou réutilisée si nécessaire
        return [
            'imported' => 0,
            'updated' => 0,
            'errors' => 0,
            'successValues' => []
        ];
    }

    /**
     * Vérifie si l'EntityManager est ouvert et utilisable
     */
    private function isEntityManagerOpen(?EntityManagerInterface $entityManager = null): bool
    {
        $em = $entityManager ?? $this->entityManager;

        if (!$em) {
            return false;
        }

        try {
            // Une opération simple qui échouera si l'EntityManager est fermé
            $em->getRepository(Radiogramme::class);
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Réinitialise l'EntityManager
     */
    private function resetEntityManager(): void
    {
        try {
            // Récupérer une nouvelle instance de l'EntityManager depuis le registry
            $this->doctrine->resetManager();
            $this->entityManager = $this->doctrine->getManager();
        } catch (\Throwable $e) {
            // En cas d'erreur, créer un nouvel EntityManager
            $this->entityManager = $this->doctrine->getManager();
        }
    }

    /**
     * Génère la valeur unique à partir d'un enregistrement
     */
    private function generateUniqueValueFromRecord(array $record): string
    {
        // La logique doit correspondre exactement à celle de l'entité Radiogramme::getUniqueValue()
        $year = '';
        if (!empty($record['date'])) {
            try {
                $date = new \DateTime($record['date']);
                $year = $date->format('Y');
            } catch (\Exception $e) {
                // Si la conversion échoue, on laisse l'année vide
            }
        }

        return ($record['tranche'] ?? '') . '-' .
               ($record['systeme'] ?? '') . '-' .
               ($record['ligne'] ?? '') . '-' .
               ($record['bigramme'] ?? '') . '-' .
               ($record['iso'] ?? '') . '-' .
               ($record['repere'] ?? '') . '-' .
               ($record['visite'] ?? '') . '-' .
               $year
        ;
    }

    /**
     * Gère une erreur d'importation en l'ajoutant à la liste des erreurs
     */
    private function handleImportError(array $record, \Exception $e, ProgressBar $progressBar, ?string $customErrorType = null): void
    {
        // Déterminer le type d'erreur
        $errorType = $customErrorType ?? 'Erreur inconnue';
        $errorMessage = $e->getMessage();

        if ($customErrorType === null) {
            if (strpos($errorMessage, 'Duplicate entry') !== false && strpos($errorMessage, 'UNIQ_') !== false) {
                $errorType = 'Erreur de doublon';
            } elseif (strpos($errorMessage, 'Integrity constraint violation') !== false) {
                $errorType = 'Erreur de contrainte d\'intégrité';
            } elseif (strpos($errorMessage, 'The EntityManager is closed') !== false) {
                $errorType = 'Erreur d\'EntityManager fermé';
            }
        }

        // Incrémenter le compteur pour ce type d'erreur
        if (!isset($this->errorTypes[$errorType])) {
            $this->errorTypes[$errorType] = 0;
        }
        $this->errorTypes[$errorType]++;

        // Ajouter l'erreur à la liste
        $this->errorRecords[] = [
            'record' => $record,
            'errorType' => $errorType,
            'errorMessage' => $errorMessage
        ];

        // Mettre à jour le message de la barre de progression
        $progressBar->setMessage(sprintf('Erreur: %s pour le radiogramme %s', $errorType, $record['tranche'] ?? 'inconnu'));
    }

    /**
     * Compte le nombre total d'enregistrements dans tous les fichiers CSV
     */
    private function countTotalRecords(string $folder, array $filenames, string $delimiter): int
    {
        $totalCount = 0;

        foreach ($filenames as $filename) {
            $filePath = $folder . '/' . $filename;

            if (!file_exists($filePath)) {
                continue;
            }

            $reader = Reader::createFromPath(path: $filePath, open_mode: 'r')
                ->setDelimiter(delimiter: $delimiter)
                ->setHeaderOffset(offset: 0)
            ;

            $totalCount += count($reader);
        }

        return $totalCount;
    }

    /**
     * Crée ou met à jour un radiogramme à partir des données du CSV
     *
     * @return array{radiogramme: Radiogramme, isNew: bool}
     */
    private function createOrUpdateRadiogramme(array $record): array
    {
        // Générer la valeur unique pour vérifier si le radiogramme existe déjà
        $uniqueValue = $this->generateUniqueValueFromRecord($record);

        // Vérifier si un radiogramme avec cette valeur unique existe déjà
        $existingRadiogramme = $this->radiogrammeRepository->findOneBy(['uniqueValue' => $uniqueValue]);

        if ($existingRadiogramme) {
            // Mettre à jour le radiogramme existant
            $radiogramme = $existingRadiogramme;
            $isNew = false;
        } else {
            // Créer un nouveau radiogramme
            $radiogramme = new Radiogramme();
            $isNew = true;
        }

        // Vérifier si la date est valide avant de continuer
        $dateString = $record['date'] ?? null;
        $date = null;
        $dateError = false;

        if (!empty($dateString)) {
            // Vérifier si la date est au format "mois-année" comme "déc.-21"
            if (preg_match('/^([a-zéû]+)\.-(\d{2})$/i', $dateString, $matches)) {
                // Conversion des mois en français vers numérique
                $moisFr = [
                    'jan' => '01', 'fév' => '02', 'mar' => '03', 'avr' => '04',
                    'mai' => '05', 'juin' => '06', 'juil' => '07', 'aoû' => '08',
                    'sep' => '09', 'oct' => '10', 'nov' => '11', 'déc' => '12'
                ];

                $mois = strtolower(substr($matches[1], 0, 3));
                if (isset($moisFr[$mois])) {
                    $moisNum = $moisFr[$mois];
                    $annee = '20' . $matches[2]; // Supposer que c'est 2000+
                    try {
                        $date = new \DateTime("$annee-$moisNum-01");
                    } catch (\Exception $e) {
                        $dateError = true;
                        error_log("Impossible de convertir la date au format mois-année: {$dateString} - Erreur: " . $e->getMessage());
                    }
                } else {
                    $dateError = true;
                    error_log("Mois non reconnu dans la date: {$dateString}");
                }
            }
            // Vérifier si la date est juste une année à 2 chiffres (comme "82" pour 1982)
            elseif (is_numeric($dateString) && strlen($dateString) == 2) {
                // Convertir en année complète (19xx)
                $year = 1900 + (int)$dateString;
                try {
                    $date = new \DateTime($year . '-01-01');
                } catch (\Exception $e) {
                    $dateError = true;
                    error_log("Impossible de convertir l'année à 2 chiffres: {$dateString} - Erreur: " . $e->getMessage());
                }
            }
            // Vérifier si la date est juste une année à 4 chiffres
            elseif (is_numeric($dateString) && strlen($dateString) == 4) {
                try {
                    $date = new \DateTime($dateString . '-01-01');
                } catch (\Exception $e) {
                    $dateError = true;
                    error_log("Impossible de convertir l'année à 4 chiffres: {$dateString} - Erreur: " . $e->getMessage());
                }
            } else {
                // Formats de date à essayer dans l'ordre
                $dateFormats = [
                    'd/m/Y', // Format français (31/12/2023)
                    'Y-m-d',  // Format ISO (2023-12-31)
                    'd-m-Y',  // Format avec tirets (31-12-2023)
                    'Y/m/d',  // Format avec slashes inversés (2023/12/31)
                    'j/n/Y',  // Format sans zéros (1/1/2023)
                    'j-n-Y',  // Format sans zéros avec tirets (1-1-2023)
                ];

                $dateConverted = false;

                // Essayer chaque format jusqu'à ce qu'un fonctionne
                foreach ($dateFormats as $format) {
                    $dateObj = \DateTime::createFromFormat($format, $dateString);
                    if ($dateObj !== false && !array_sum($dateObj->getLastErrors())) {
                        $date = $dateObj;
                        $dateConverted = true;
                        break;
                    }
                }

                // Si aucun format n'a fonctionné, essayer la méthode standard
                if (!$dateConverted) {
                    try {
                        $date = new \DateTime($dateString);
                        $dateConverted = true;
                    } catch (\Exception $e) {
                        // Enregistrer l'erreur pour le débogage
                        $dateError = true;
                        error_log("Impossible de convertir la date: {$dateString} - Erreur: " . $e->getMessage());
                    }
                }

                // Si la date n'a pas pu être convertie, on peut la logger pour analyse
                if (!$dateConverted) {
                    $dateError = true;
                    error_log("Format de date non reconnu: {$dateString} pour le radiogramme {$record['tranche']}");
                }
            }
        }

        // Si la date est invalide, ajouter à la table des erreurs au lieu d'importer
        if ($dateError) {
            throw new \Exception("Format de date invalide: {$dateString}");
        }

        // Définir les propriétés du radiogramme
        // Convertir explicitement tranche en entier
        $radiogramme->setTranche((int) $record['tranche']);
        $radiogramme->setSysteme($record['systeme']);
        $radiogramme->setLigne($record['ligne'] ?? null);
        $radiogramme->setBigramme($record['bigramme'] ?? null);
        $radiogramme->setIso($record['iso'] ?? null);
        $radiogramme->setRepere($record['repere']);
        $radiogramme->setVisite($record['visite'] ?? null);
        $radiogramme->setTraversee($record['traversee']);
        $radiogramme->setArmoire($record['armoire'] ?? null);
        $radiogramme->setEtagere($record['etagere'] ?? null);
        $radiogramme->setBoite($record['boite'] ?? null);
        $radiogramme->setOi($record['oi'] ?? null);
        $radiogramme->setDate($date);
        $radiogramme->setRf($record['rf'] ?? null);
        $radiogramme->setIsIps(filter_var($record['isIps'] ?? false, FILTER_VALIDATE_BOOLEAN));
        $radiogramme->setIsQs(filter_var($record['isQs'] ?? false, FILTER_VALIDATE_BOOLEAN));
        $radiogramme->setCpp($record['cpp'] ?? null);
        $radiogramme->setCsp($record['csp'] ?? null);
        $radiogramme->setCommande($record['commande'] ?? null);

        // Gérer le champ titulaire qui ne peut pas être null
        if (isset($record['titulaire']) && $record['titulaire'] !== null && $record['titulaire'] !== '') {
            $radiogramme->setTitulaire($record['titulaire']);
        } else {
            // Utiliser une valeur par défaut
            $radiogramme->setTitulaire('Non spécifié');
        }

        $radiogramme->setObservation($record['observation'] ?? null);

        // Définir explicitement la valeur unique
        $radiogramme->setUniqueValue($uniqueValue);

        return [
            'radiogramme' => $radiogramme,
            'isNew' => $isNew
        ];
    }

    private function getExecutionTime(int|float $startTime): string
    {
        $endTime = microtime(as_float: true); // Temps de fin de l'exécution
        $totalTime = round(num: $endTime - $startTime, precision: 2); // Temps total d'exécution arrondi à deux décimales

        if ($totalTime < 60) {
            return $totalTime . ' secondes';
        } else {
            $minutes = floor($totalTime / 60);
            $seconds = $totalTime % 60;

            return $minutes . ' minutes et ' . $seconds . ' secondes';
        }
    }

    /**
     * Persiste les erreurs dans la table RadiogrammeError
     */
    private function persistErrors(SymfonyStyle $style): void
    {
        if (empty($this->errorRecords)) {
            return;
        }

        $style->section('Enregistrement des erreurs pour analyse ultérieure');
        $progressBar = $style->createProgressBar(count($this->errorRecords));
        $progressBar->setFormat("%current%/%max% [%bar%] %percent:3s%% | %message%");
        $progressBar->setMessage('Enregistrement des erreurs...');
        $progressBar->start();

        // Toujours créer un nouvel EntityManager pour persister les erreurs
        $this->doctrine->resetManager('default');
        $errorEntityManager = $this->doctrine->getManager('default');

        // Tableau pour suivre les valeurs uniques déjà traitées
        $processedUniqueValues = [];
        $processedCount = 0;
        $batchSize = 5; // Taille de lot encore plus petite pour les erreurs

        // Tableau pour collecter les erreurs à insérer par lots
        $errorsToInsert = [];

        foreach ($this->errorRecords as $index => $errorData) {
            $record = $errorData['record'];
            $errorType = $errorData['errorType'];
            $errorMessage = $errorData['errorMessage'];

            // Vérifier si cette erreur existe déjà
            $uniqueValue = $this->generateUniqueValueFromRecord($record);

            // Éviter les doublons dans la même session
            if (isset($processedUniqueValues[$uniqueValue])) {
                $progressBar->advance();
                continue;
            }
            $processedUniqueValues[$uniqueValue] = true;

            try {
                // Vérifier que l'EntityManager est ouvert
                if (!$this->isEntityManagerOpen($errorEntityManager)) {
                    $this->doctrine->resetManager('default');
                    $errorEntityManager = $this->doctrine->getManager('default');
                }

                $existingError = $this->radiogrammeErrorRepository->findOneBy(['uniqueValue' => $uniqueValue]);

                if (!$existingError) {
                    // Créer une nouvelle entité RadiogrammeError
                    $radiogrammeError = new RadiogrammeError();

                    // Gérer correctement le champ tranche qui doit être un int non-null
                    if (isset($record['tranche']) && $record['tranche'] !== null && $record['tranche'] !== '') {
                        $radiogrammeError->setTranche((int) $record['tranche']);
                    } else {
                        // Utiliser une valeur par défaut pour tranche si elle est null
                        $radiogrammeError->setTranche(0);
                    }

                    // Gérer correctement le champ systeme qui doit être une string non-null
                    // La longueur maximale est de 3 caractères
                    if (isset($record['systeme']) && $record['systeme'] !== null && $record['systeme'] !== '') {
                        // Tronquer si nécessaire pour respecter la contrainte de longueur
                        $radiogrammeError->setSysteme(substr($record['systeme'], 0, 3));
                    } else {
                        // Utiliser une valeur par défaut courte
                        $radiogrammeError->setSysteme('???');
                    }

                    $radiogrammeError->setLigne($record['ligne'] ?? null);
                    $radiogrammeError->setBigramme($record['bigramme'] ?? null);
                    $radiogrammeError->setIso($record['iso'] ?? null);

                    // Gérer le champ repere qui ne peut pas être null
                    if (isset($record['repere']) && $record['repere'] !== null && $record['repere'] !== '') {
                        $radiogrammeError->setRepere($record['repere']);
                    } else {
                        // Utiliser une valeur par défaut
                        $radiogrammeError->setRepere('Non spécifié');
                    }

                    $radiogrammeError->setVisite($record['visite'] ?? null);

                    // Gérer le champ traversee qui pourrait être non nullable
                    if (isset($record['traversee']) && $record['traversee'] !== null && $record['traversee'] !== '') {
                        $radiogrammeError->setTraversee($record['traversee']);
                    } else {
                        // Utiliser une valeur par défaut si nécessaire
                        $radiogrammeError->setTraversee('N/A');
                    }

                    $radiogrammeError->setArmoire($record['armoire'] ?? null);
                    $radiogrammeError->setEtagere($record['etagere'] ?? null);
                    $radiogrammeError->setBoite($record['boite'] ?? null);
                    $radiogrammeError->setOi($record['oi'] ?? null);

                    // Convertir la date string en objet DateTime
                    $dateString = $record['date'] ?? null;
                    $date = null;
                    if (!empty($dateString)) {
                        try {
                            $date = new \DateTime($dateString);
                        } catch (\Exception $e) {
                            // Si la conversion échoue, on laisse la date à null
                        }
                    }
                    $radiogrammeError->setDate($date);

                    $radiogrammeError->setRf($record['rf'] ?? null);
                    $radiogrammeError->setIsIps(filter_var($record['isIps'] ?? false, FILTER_VALIDATE_BOOLEAN));
                    $radiogrammeError->setIsQs(filter_var($record['isQs'] ?? false, FILTER_VALIDATE_BOOLEAN));
                    $radiogrammeError->setCpp($record['cpp'] ?? null);
                    $radiogrammeError->setCsp($record['csp'] ?? null);
                    $radiogrammeError->setCommande($record['commande'] ?? null);

                    // Gérer le champ titulaire qui ne peut pas être null
                    if (isset($record['titulaire']) && $record['titulaire'] !== null && $record['titulaire'] !== '') {
                        $radiogrammeError->setTitulaire($record['titulaire']);
                    } else {
                        // Utiliser une valeur par défaut
                        $radiogrammeError->setTitulaire('Non spécifié');
                    }

                    $radiogrammeError->setObservation($record['observation'] ?? null);

                    // Définir les informations d'erreur
                    $radiogrammeError->setErrorType($errorType);
                    $radiogrammeError->setErrorMessage($errorMessage);
                    $radiogrammeError->setUniqueValue($uniqueValue);

                    // Ajouter à la liste des erreurs à insérer par lots
                    $errorsToInsert[] = $radiogrammeError;
                    $processedCount++;

                    // Traiter par lots pour économiser la mémoire
                    if (count($errorsToInsert) >= $batchSize) {
                        $this->bulkInsertErrors($errorsToInsert, $errorEntityManager, $style, $progressBar);
                        $errorsToInsert = [];
                    }

                    $progressBar->setMessage(sprintf('Enregistrement de l\'erreur %d/%d', $processedCount, count($this->errorRecords)));
                } else {
                    $progressBar->setMessage(sprintf('Erreur déjà enregistrée pour %s', $uniqueValue));
                }
            } catch (\Exception $e) {
                $progressBar->setMessage(sprintf('Erreur lors de l\'enregistrement: %s', $e->getMessage()));

                // Réinitialiser l'EntityManager en cas d'erreur
                $this->doctrine->resetManager('default');
                $errorEntityManager = $this->doctrine->getManager('default');
            }

            $progressBar->advance();
        }

        // Traiter les erreurs restantes
        if (!empty($errorsToInsert)) {
            $this->bulkInsertErrors($errorsToInsert, $errorEntityManager, $style, $progressBar);
        }

        $progressBar->finish();
        $style->newLine();

        if ($processedCount > 0) {
            $style->success(sprintf('%d erreurs enregistrées pour analyse ultérieure', $processedCount));
        } else {
            $style->info('Aucune nouvelle erreur à enregistrer');
        }

        // Afficher un résumé des types d'erreurs
        if (!empty($this->errorTypes)) {
            $style->section('Résumé des erreurs');
            foreach ($this->errorTypes as $type => $count) {
                $style->text(sprintf('- %s: %d', $type, $count));
            }
            $style->note('Les erreurs ont été enregistrées dans la table radiogrammes_errors pour analyse ultérieure.');
        }
    }

    /**
     * Insère les erreurs par lots pour optimiser la mémoire
     *
     * @param array<RadiogrammeError> $errors Liste des erreurs à insérer
     * @param EntityManagerInterface $entityManager EntityManager à utiliser
     * @param SymfonyStyle $style Interface pour afficher la progression
     * @param ProgressBar|null $progressBar Barre de progression (optionnelle)
     */
    private function bulkInsertErrors(array $errors, EntityManagerInterface $entityManager, SymfonyStyle $style, ?ProgressBar $progressBar = null): void
    {
        if (empty($errors)) {
            return;
        }

        try {
            foreach ($errors as $error) {
                $entityManager->persist($error);
            }

            $entityManager->flush();
            $entityManager->clear(); // Libérer la mémoire

            // Libérer la mémoire explicitement
            gc_collect_cycles();
        } catch (\Exception $e) {
            if ($progressBar) {
                $progressBar->setMessage('Erreur lors de l\'insertion: ' . $e->getMessage());
            } else {
                $style->warning('Erreur lors de l\'insertion: ' . $e->getMessage());
            }

            // Réinitialiser l'EntityManager en cas d'erreur
            $this->doctrine->resetManager('default');
        }
    }

    /**
     * Insère les radiogrammes par lots pour optimiser la mémoire
     *
     * @param array<Radiogramme> $radiogrammes Liste des radiogrammes à insérer
     * @param SymfonyStyle $style Interface pour afficher la progression
     * @param ProgressBar|null $progressBar Barre de progression (optionnelle)
     */
    private function bulkInsertRadiogrammes(array $radiogrammes, SymfonyStyle $style, ?ProgressBar $progressBar = null): void
    {
        if (empty($radiogrammes)) {
            return;
        }

        try {
            // Vérifier que l'EntityManager est ouvert
            if (!$this->isEntityManagerOpen()) {
                $this->resetEntityManager();
            }

            foreach ($radiogrammes as $radiogramme) {
                $this->entityManager->persist($radiogramme);
            }

            $this->entityManager->flush();
            $this->entityManager->clear(); // Libérer la mémoire

            // Libérer la mémoire explicitement
            gc_collect_cycles();
        } catch (\Exception $e) {
            if ($progressBar) {
                $progressBar->setMessage('Erreur lors de l\'insertion: ' . $e->getMessage());
            } else {
                $style->warning('Erreur lors de l\'insertion: ' . $e->getMessage());
            }

            // Réinitialiser l'EntityManager en cas d'erreur
            $this->resetEntityManager();
        }
    }
}
