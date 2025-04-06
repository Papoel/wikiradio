<?php

namespace App\Command;

use App\Entity\RadiogrammeError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;

#[AsCommand(
    name: 'app:clean-radiogramme-errors',
    description: 'Nettoie la table des radiogrammes en erreur lorsqu\'elles sont résolues'
)]
class CleanRadiogrammeErrorsCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption(
                'resolved-only',
                'r',
                InputOption::VALUE_NONE,
                'Supprimer uniquement les erreurs résolues'
            )
            ->addOption(
                'all',
                'a',
                InputOption::VALUE_NONE,
                'Supprimer toutes les erreurs (résolues et non résolues)'
            )
            ->addOption(
                'dry-run',
                'd',
                InputOption::VALUE_NONE,
                'Afficher les erreurs sans les supprimer'
            )
            ->addOption(
                'limit',
                'l',
                InputOption::VALUE_REQUIRED,
                'Limiter le nombre d\'erreurs à afficher dans le résumé',
                10
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $style = new SymfonyStyle($input, $output);
        $style->title('Nettoyage des radiogrammes en erreur');

        // Récupérer les options
        $resolvedOnly = $input->getOption('resolved-only');
        $all = $input->getOption('all');
        $dryRun = $input->getOption('dry-run');
        $limit = (int) $input->getOption('limit');

        if ($all && $resolvedOnly) {
            $style->error('Les options --all et --resolved-only ne peuvent pas être utilisées ensemble.');
            return Command::FAILURE;
        }

        // Déterminer le filtre à appliquer
        $isResolved = $all ? null : true;

        // Construire la requête pour récupérer les erreurs
        $errorsQb = $this->entityManager->createQueryBuilder();
        $errorsQb->select('e')
            ->from('App\Entity\RadiogrammeError', 'e');
        
        if ($isResolved !== null) {
            $errorsQb->where('e.isResolved = :resolved')
                ->setParameter('resolved', $isResolved);
        }
        
        $errorsQb->orderBy('e.createdAt', 'DESC')
                ->setMaxResults($limit);
        
        $errors = $errorsQb->getQuery()->getResult();

        // Construire la requête pour compter le nombre total d'erreurs
        $countQb = $this->entityManager->createQueryBuilder();
        $countQb->select('COUNT(e.id)')
            ->from('App\Entity\RadiogrammeError', 'e');
        
        if ($isResolved !== null) {
            $countQb->where('e.isResolved = :resolved')
                ->setParameter('resolved', $isResolved);
        }
        
        $count = (int) $countQb->getQuery()->getSingleScalarResult();

        if ($count === 0) {
            $style->warning('Aucune erreur à supprimer.');
            return Command::SUCCESS;
        }

        // Afficher un résumé des erreurs
        $this->displayErrorsSummary($style, $errors, $count, $limit);

        // En mode dry-run, on s'arrête ici
        if ($dryRun) {
            $style->note('Mode dry-run : aucune suppression effectuée.');
            return Command::SUCCESS;
        }

        // Demander confirmation
        $confirmMessage = sprintf(
            'Êtes-vous sûr de vouloir supprimer %s %d erreur(s) ?',
            $all ? 'toutes les' : 'ces',
            $count
        );
        
        if (!$style->confirm($confirmMessage, false)) {
            $style->warning('Opération annulée.');
            return Command::SUCCESS;
        }

        // Construire la requête de suppression
        $deleteQb = $this->entityManager->createQueryBuilder();
        $deleteQb->delete('App\Entity\RadiogrammeError', 'e');
        
        if ($isResolved !== null) {
            $deleteQb->where('e.isResolved = :resolved')
                ->setParameter('resolved', $isResolved);
        }

        // Exécuter la requête
        $deleted = $deleteQb->getQuery()->execute();

        $style->success(sprintf('%d erreur(s) ont été supprimée(s)', $deleted));

        return Command::SUCCESS;
    }

    /**
     * Affiche un résumé des erreurs qui seront supprimées
     */
    private function displayErrorsSummary(SymfonyStyle $style, array $errors, int $totalCount, int $limit): void
    {
        $style->section(sprintf('Résumé des erreurs (%d au total)', $totalCount));

        if (empty($errors)) {
            $style->text('Aucune erreur à afficher.');
            return;
        }

        // Créer un tableau pour afficher les erreurs
        $table = new Table($style);
        $table->setHeaders(['ID', 'Type', 'Message', 'Radiogramme', 'Corrigé le', 'Résolue']);

        // Ajouter les lignes au tableau
        foreach ($errors as $error) {
            /** @var RadiogrammeError $error */
            $table->addRow([
                $error->getId(),
                $error->getErrorType(),
                $this->truncateText($error->getErrorMessage(), 50),
                $this->formatRadiogrammeInfo($error),
                $error->getUpdatedAt()->format('d/m/Y H:i'),
                $error->isResolved() ? 'Oui' : 'Non',
            ]);
        }

        $table->render();

        // Afficher un message si on ne montre pas toutes les erreurs
        if ($totalCount > count($errors)) {
            $style->note(sprintf(
                'Affichage limité aux %d erreurs les plus récentes sur un total de %d. Utilisez l\'option --limit pour en voir plus.',
                count($errors),
                $totalCount
            ));
        }

        // Afficher un résumé par type d'erreur
        $this->displayErrorTypesSummary($style, $errors, $totalCount);
    }

    /**
     * Affiche un résumé des types d'erreurs
     */
    private function displayErrorTypesSummary(SymfonyStyle $style, array $errors, int $totalCount): void
    {
        // Compter les erreurs par type
        $errorTypes = [];
        foreach ($errors as $error) {
            /** @var RadiogrammeError $error */
            $type = $error->getErrorType();
            if (!isset($errorTypes[$type])) {
                $errorTypes[$type] = 0;
            }
            $errorTypes[$type]++;
        }

        // Si on n'a pas toutes les erreurs, on ne peut pas faire un résumé précis
        if (count($errors) < $totalCount) {
            return;
        }

        $style->section('Répartition par type d\'erreur');
        $table = new Table($style);
        $table->setHeaders(['Type d\'erreur', 'Nombre']);

        foreach ($errorTypes as $type => $count) {
            $table->addRow([$type, $count]);
        }

        $table->render();
    }

    /**
     * Tronque un texte s'il dépasse la longueur maximale
     */
    private function truncateText(?string $text, int $maxLength): string
    {
        if ($text === null) {
            return '';
        }

        if (mb_strlen($text) <= $maxLength) {
            return $text;
        }

        return mb_substr($text, 0, $maxLength - 3) . '...';
    }

    /**
     * Formate les informations du radiogramme pour l'affichage
     */
    private function formatRadiogrammeInfo(RadiogrammeError $error): string
    {
        return sprintf(
            'TR%d %s-%s-%s',
            $error->getTranche(),
            $error->getSysteme(),
            $error->getRepere(),
            $error->getVisite() ?: 'N/A'
        );
    }
}
