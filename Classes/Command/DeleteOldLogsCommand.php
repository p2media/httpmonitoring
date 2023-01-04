<?php

namespace P2media\Httpmonitoring\Command;

use P2media\Httpmonitoring\Domain\Repository\LogRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

/**
 * Deletes logs older than the maximumLogAge ext_conf value
 */
final class DeleteOldLogsCommand extends Command
{
    protected ConfigurationManagerInterface $configurationManager;

    protected LogRepository $logRepository;

    public function injectConfigurationManager(ConfigurationManagerInterface $configurationManager): void
    {
        $this->configurationManager = $configurationManager;
    }

    public function injectSiteRepository(LogRepository $logRepository): void
    {
        $this->logRepository = $logRepository;
    }

    protected function configure(): void
    {
        $this->setHelp('Deletes logs older than a certain amount of time.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $symfonyStyle = new SymfonyStyle($input, $output);
        $symfonyStyle->title($this->getDescription());

        $maximumLogAge = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('httpmonitoring', 'maximumLogAge');
        if (gettype($maximumLogAge) !== 'integer') {
            $maximumLogAge = 2592000;
        }

        $numberOfLogsDeleted = $this->logRepository->deleteOldLogs($maximumLogAge, $this->configurationManager);
        $symfonyStyle->writeln('Finished deleting older logs. ' . $numberOfLogsDeleted . ' logs deleted.');

        return Command::SUCCESS;
    }
}
