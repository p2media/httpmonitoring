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

use P2media\Httpmonitoring\Domain\Model\Dto\Filter;
use P2media\Httpmonitoring\Domain\Model\Log;
use P2media\Httpmonitoring\Domain\Model\Uri;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * @extends Repository<Log>
 */
class LogRepository extends Repository
{
    /**
     * @var int
     */
    final const MINIMUM_SUCCESS_STATUS_CODE = 100;

    /**
     * @var int
     */
    final const MAXIMUM_SUCCESS_STATUS_CODE = 399;

    /**
     * @var int
     */
    final const QUERY_RESULT_LIMIT = 200;

    protected $defaultOrderings = [
        'crdate' => QueryInterface::ORDER_DESCENDING,
    ];

    /**
     * Takes the URI ObjectStorage of a site, and returns a QueryResult object containing all logs belonging to that site
     *
     * @param ObjectStorage<Uri> $objectStorage
     * @return QueryResultInterface<Log>
     */
    public function findAllOfSite(ObjectStorage $objectStorage): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->setLimit(self::QUERY_RESULT_LIMIT);

        $uriUids = [];
        foreach ($objectStorage as $uri) {
            $uriUids[] = $uri->getUid();
        }

        if (empty($uriUids)) {
            $uriUids[] = 0;
        }

        $query->matching(
            $query->in('uri', $uriUids)
        );
        return $query->execute();
    }

    /**
     * Takes a Filter object as parameter, and returns a QueryResult object of StatusLog objects matching the Filter values
     *
     * @return QueryResultInterface<Log>
     */
    public function findAllFiltered(Filter $filter): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->setLimit(self::QUERY_RESULT_LIMIT);

        $constraints = $this->getConstraints($filter, $query);

        if (empty($constraints)) {
            return $query->execute();
        }

        $query->matching(
            $query->logicalAnd($constraints)
        );
        return $query->execute();
    }

    /**
     * Generates and returns an array of query constraints using the values from the Filter object
     *
     * @param QueryInterface<Log> $query
     * @return array<ConstraintInterface>
     */
    private function getConstraints(Filter $filter, QueryInterface $query): array
    {
        $constraints = [];

        if ($filter->getOnlyErrors() !== '') {
            $constraints[] = $query->logicalOr(
                [
                    $query->greaterThan('statuscode', self::MAXIMUM_SUCCESS_STATUS_CODE),
                    $query->lessThan('statuscode', self::MINIMUM_SUCCESS_STATUS_CODE),
                ]
            );
        } elseif ($filter->getStatus() !== '') {
            $constraints[] = $query->equals('statuscode', $filter->getStatus());
        }

        if ($filter->getTimeStart() !== '') {
            $timeStart = strtotime($filter->getTimeStart());
            if ($timeStart === false) {
                $timeStart = 0;
            }

            $constraints[] = $query->greaterThanOrEqual('crdate', $timeStart);
        }

        if ($filter->getTimeEnd() !== '') {
            $timeEnd = strtotime($filter->getTimeEnd());
            if ($timeEnd === false) {
                $timeEnd = PHP_INT_MAX;
            }

            $constraints[] = $query->lessThanOrEqual('crdate', $timeEnd);
        }

        return $constraints;
    }

    /**
     * Deletes logs older than $maxAgeSeconds, returns the number of logs deleted
     */
    public function deleteOldLogs(int $maxAgeSeconds, ConfigurationManagerInterface $configurationManager): int
    {
        $storagePid = [];
        $query = $this->createQuery();
        $settings = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT, 'httpmonitoring');
        $storagePid[] = (int)$settings['module.']['tx_httpmonitoring_tools_httpmonitoringmonitoring.']['persistence.']['storagePid'];
        $query->getQuerySettings()->setStoragePageIds($storagePid);
        $query->matching(
            $query->lessThanOrEqual('crdate', time() - $maxAgeSeconds)
        );
        $logs = $query->execute();

        /** @var Log $log */
        foreach ($logs as $log) {
            $this->remove($log);
        }

        $this->persistenceManager->persistAll();

        return count($logs);
    }
}
