<?php

namespace App\Command;

use App\Repository\RequestRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ArchiveOldRequestsCommand extends Command
{
    protected static $defaultName = 'app:archive-old-requests';
    protected static $defaultDescription = 'Archive les demandes anciennes selon le nombre de jours spécifié';

    private $requestRepository;

    public function __construct(RequestRepository $requestRepository)
    {
        $this->requestRepository = $requestRepository;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('days', 'd', InputOption::VALUE_REQUIRED, 'Nombre de jours après lesquels archiver', 30)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $days = $input->getOption('days');

        $count = $this->requestRepository->archiveOldRequests($days);

        if ($count > 0) {
            $io->success($count . ' demandes archivées avec succès (plus de ' . $days . ' jours).');
        } else {
            $io->success('Aucune demande à archiver.');
        }

        return Command::SUCCESS;
    }
}