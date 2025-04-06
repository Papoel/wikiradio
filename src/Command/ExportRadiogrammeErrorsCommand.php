<?php

namespace App\Command;

use League\Csv\Writer;
use App\Repository\RadiogrammeErrorRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:export-radiogramme-errors',
    description: 'Exporte les radiogrammes en erreur vers un fichier CSV pour analyse'
)]
class ExportRadiogrammeErrorsCommand extends Command
{
    public function __construct(
        private readonly RadiogrammeErrorRepository $radiogrammeErrorRepository
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption(
                'type',
                't',
                InputOption::VALUE_OPTIONAL,
                'Type d\'erreur à exporter (tous par défaut)'
            )
            ->addOption(
                'output',
                'o',
                InputOption::VALUE_OPTIONAL,
                'Chemin du fichier de sortie',
                'exports/radiogramme-errors.csv'
            )
            ->addOption(
                'mark-resolved',
                'm',
                InputOption::VALUE_NONE,
                'Marquer les erreurs résolues comme traitées'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $style = new SymfonyStyle($input, $output);
        $style->title('Export des radiogrammes en erreur');

        // Récupérer les options
        $errorType = $input->getOption('type');
        $outputPath = $input->getOption('output');
        $markResolved = $input->getOption('mark-resolved');

        // Créer le répertoire de sortie s'il n'existe pas
        $outputDir = dirname($outputPath);
        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0777, true);
        }

        // Récupérer les erreurs selon le type
        if ($errorType) {
            $errors = $this->radiogrammeErrorRepository->findByErrorType($errorType);
            $style->text(sprintf('Exportation des erreurs de type "%s"', $errorType));
        } else {
            $errors = $this->radiogrammeErrorRepository->findUnresolved();
            $style->text('Exportation de toutes les erreurs non résolues');
        }

        if (empty($errors)) {
            $style->warning('Aucune erreur à exporter.');
            return Command::SUCCESS;
        }

        // Créer le fichier CSV
        $csv = Writer::createFromPath($outputPath, 'w+');
        $csv->setDelimiter(',');

        // Définir les en-têtes du CSV
        $headers = [
            'id',
            'tranche',
            'systeme',
            'ligne',
            'bigramme',
            'iso',
            'repere',
            'visite',
            'traversee',
            'armoire',
            'etagere',
            'boite',
            'oi',
            'date',
            'rf',
            'isIps',
            'isQs',
            'cpp',
            'csp',
            'commande',
            'titulaire',
            'observation',
            'uniqueValue',
            'errorType',
            'errorMessage',
            'createdAt',
            'updatedAt',
            'isResolved'
        ];
        $csv->insertOne($headers);

        // Insérer les données
        $count = 0;
        foreach ($errors as $error) {
            $row = [
                'id' => $error->getId(),
                'tranche' => $error->getTranche(),
                'systeme' => $error->getSysteme(),
                'ligne' => $error->getLigne(),
                'bigramme' => $error->getBigramme(),
                'iso' => $error->getIso(),
                'repere' => $error->getRepere(),
                'visite' => $error->getVisite(),
                'traversee' => $error->getTraversee(),
                'armoire' => $error->getArmoire(),
                'etagere' => $error->getEtagere(),
                'boite' => $error->getBoite(),
                'oi' => $error->getOi(),
                'date' => $error->getDate() ? $error->getDate()->format('Y-m-d') : null,
                'rf' => $error->getRf(),
                'isIps' => $error->isIps() ? 'true' : 'false',
                'isQs' => $error->isQs() ? 'true' : 'false',
                'cpp' => $error->getCpp(),
                'csp' => $error->getCsp(),
                'commande' => $error->getCommande(),
                'titulaire' => $error->getTitulaire(),
                'observation' => $error->getObservation(),
                'uniqueValue' => $error->getUniqueValue(),
                'errorType' => $error->getErrorType(),
                'errorMessage' => $error->getErrorMessage(),
                'createdAt' => $error->getCreatedAt()->format('Y-m-d H:i:s'),
                'updatedAt' => $error->getUpdatedAt() ? $error->getUpdatedAt()->format('Y-m-d H:i:s') : null,
                'isResolved' => $error->isResolved() ? 'true' : 'false'
            ];
            $csv->insertOne($row);
            $count++;
        }

        $style->success(sprintf('%d erreurs exportées vers %s', $count, $outputPath));

        // Marquer les erreurs résolues comme traitées si demandé
        if ($markResolved) {
            $processed = $this->radiogrammeErrorRepository->markResolvedAsProcessed();
            $style->success(sprintf('%d erreurs résolues ont été marquées comme traitées', $processed));
        }

        return Command::SUCCESS;
    }
}
