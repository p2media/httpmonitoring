<?php

namespace P2media\Httpmonitoring\Command;

use P2media\Httpmonitoring\Domain\Repository\SiteRepository;
use P2media\Httpmonitoring\Logging\LogCreator;
use P2media\Httpmonitoring\Utility\MailerUtility;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Reads the status of and writes a new log for each URI, and if any of them return a new error
 * or stop reporting previous errors, sends emails informing subscribed addresses of the change
 */
final class CheckStatusCommand extends Command
{
    protected LogCreator $logCreator;

    protected SiteRepository $siteRepository;

    public function injectLogCreator(LogCreator $logCreator): void
    {
        $this->logCreator = $logCreator;
    }

    public function injectSiteRepository(SiteRepository $siteRepository): void
    {
        $this->siteRepository = $siteRepository;
    }

    protected function configure(): void
    {
        $this->setHelp('Sends and email containing all URIs which returned error status.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $symfonyStyle = new SymfonyStyle($input, $output);
        $symfonyStyle->title($this->getDescription());

        $queryResult = $this->siteRepository->findAllManuallyAddStoragePid();
        $updatedStatusContainer = $this->logCreator->createNewLogAndGetError($queryResult);
        $symfonyStyle->writeln('Finished reading status codes and reading logs.');

        MailerUtility::sendErrorMail($updatedStatusContainer);

        $symfonyStyle->writeln('Finished sending Mails.');

        return Command::SUCCESS;
    }
}
