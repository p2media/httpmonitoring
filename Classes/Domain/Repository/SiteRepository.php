<?php

declare(strict_types=1);

namespace P2media\Httpmonitoring\Domain\Repository;

/**
 * This file is part of the "httpmonitoring" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2022 Marius Kachel <marius.kachel@p2media.de>, p2media
 */

use P2media\Httpmonitoring\Domain\Model\Site;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * @extends Repository<Site>
 */
class SiteRepository extends Repository
{
    protected ConfigurationManagerInterface $configurationManager;

    public function injectConfigurationManager(ConfigurationManagerInterface $configurationManager): void
    {
        $this->configurationManager = $configurationManager;
    }

    /**
     * @return QueryResultInterface<Site>
     */
    public function findAllManuallyAddStoragePid(): QueryResultInterface
    {
        $storagePid = [];
        $query = $this->createQuery();
        $settings = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT, 'httpmonitoring');
        $storagePid[] = (int)$settings['module.']['tx_httpmonitoring_tools_httpmonitoringmonitoring.']['persistence.']['storagePid'];
        $query->getQuerySettings()->setStoragePageIds($storagePid);
        return $query->execute();
    }
}
