<?php

namespace P2media\Httpmonitoring\Logging;

use GuzzleHttp\Exception\GuzzleException;
use P2media\Httpmonitoring\Data\UpdatedStatus;
use P2media\Httpmonitoring\Data\UpdatedStatusContainer;
use P2media\Httpmonitoring\Domain\Model\Log;
use P2media\Httpmonitoring\Domain\Model\Site;
use P2media\Httpmonitoring\Domain\Model\Uri;
use P2media\Httpmonitoring\Domain\Repository\LogRepository;
use P2media\Httpmonitoring\Domain\Repository\SiteRepository;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

class LogCreator
{
    protected ConfigurationManagerInterface $configurationManager;

    protected LogRepository $logRepository;

    protected PersistenceManagerInterface $persistenceManager;

    protected RequestFactory $requestFactory;

    protected SiteRepository $siteRepository;

    public function injectConfigurationManager(ConfigurationManagerInterface $configurationManager): void
    {
        $this->configurationManager = $configurationManager;
    }

    public function injectLogRepository(LogRepository $logRepository): void
    {
        $this->logRepository = $logRepository;
    }

    public function injectPersistenceManager(PersistenceManagerInterface $persistenceManager): void
    {
        $this->persistenceManager = $persistenceManager;
    }

    public function injectRequestFactory(RequestFactory $requestFactory): void
    {
        $this->requestFactory = $requestFactory;
    }

    public function injectSiteRepository(SiteRepository $siteRepository): void
    {
        $this->siteRepository = $siteRepository;
    }

    /**
     * Creates a new log for every stored URI, and returns an UpdatedStatusContainer containing UpdatedStatus
     * objects which contain URIs which have started or stopped returning error codes since the last logging
     *
     * @param QueryResultInterface<Site> $queryResult
     */
    public function createNewLogAndGetError(QueryResultInterface $queryResult): UpdatedStatusContainer
    {
        $updatedStatusContainer = GeneralUtility::makeInstance(UpdatedStatusContainer::class);
        $storagePid = $this->getStoragePid();

        foreach ($queryResult as $site) {
            $error = null;
            $improvedStatus = null;

            $uris = $site->getUri();
            foreach ($uris as $uri) {
                $lastStatusWasError = $uri->getLaststatuswaserror();

                $newLog = $this->createNewLog($uri, $storagePid);
                if ($newLog->hasErrorStatus()) {
                    if (!$lastStatusWasError) {
                        $uri->setLaststatuswaserror(true);
                        $error ??= GeneralUtility::makeInstance(UpdatedStatus::class, $site);
                        $error->getUriStorage()->attach($uri);
                    }
                } elseif ($lastStatusWasError) {
                    $uri->setLaststatuswaserror(false);
                    $improvedStatus ??= GeneralUtility::makeInstance(UpdatedStatus::class, $site);
                    $improvedStatus->getUriStorage()->attach($uri);
                }
            }

            if ($error !== null) {
                $updatedStatusContainer->addError($error);
            }

            if ($improvedStatus !== null) {
                $updatedStatusContainer->addImprovedStatus($improvedStatus);
            }
        }

        $this->persistenceManager->persistAll();
        return $updatedStatusContainer;
    }

    protected function getStoragePid(): int
    {
        $settings = $this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT,
            'httpmonitoring'
        );

        return (int)$settings['module.']['tx_httpmonitoring_tools_httpmonitoringmonitoring.']['persistence.']['storagePid'];
    }

    protected function createNewLog(Uri $uri, int $storagePid = null): Log
    {
        $newLog = GeneralUtility::makeInstance(Log::class);
        $newLog->setStatuscode($this->getStatus($uri->getPath()));
        $newLog->setUri($uri);
        // @TODO I think the crdate should be setting itself automatically without this call?
        $newLog->setCrdate(time());
        if ($storagePid !== null) {
            $newLog->setPid($storagePid);
        }

        $uri->addLog($newLog);
        $this->logRepository->add($newLog);
        return $newLog;
    }

    protected function getStatus(string $uri): int
    {
        $additionalOptions = [
            'headers' => ['Cache-Control' => 'no-cache'],
            'allow_redirects' => false,
            'http_errors' => false,
            'timeout' => 3,
        ];

        try {
            return $this->requestFactory->request($uri, 'GET', $additionalOptions)->getStatusCode();
        } catch (GuzzleException) {
            return 0;
        }
    }
}
