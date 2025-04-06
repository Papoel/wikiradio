<?php

namespace App\Command;

use App\Services\ImportDataService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:import-radiogrammes', description: 'Importe les radiogrammes')]
class ImportRadiogrammesCommand extends Command
{
    public function __construct(
        private readonly ImportDataService $importDataService
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $style = new SymfonyStyle($input, $output);

        $this->importDataService->importData('datas', $style);

        return Command::SUCCESS;
    }
}
