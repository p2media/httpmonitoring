<?php

declare(strict_types=1);

namespace P2media\Httpmonitoring\Controller;

use Psr\Http\Message\ResponseInterface;
use P2media\Httpmonitoring\Domain\Model\Dto\Filter;
use P2media\Httpmonitoring\Domain\Repository\LogRepository;
use P2media\Httpmonitoring\Domain\Repository\SiteRepository;
use P2media\Httpmonitoring\Logging\LogCreator;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Pagination\ArrayPaginator;
use TYPO3\CMS\Core\Pagination\SimplePagination;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * This file is part of the "httpmonitoring" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2022 Marius Kachel <marius.kachel@p2media.de>, p2media
 */

/**
 * LogController
 */
class LogController extends ActionController
{
    /**
     * @var int
     */
    final const MAX_LOGS_SHOWN_PER_PAGE = 20;

    protected LogCreator $logCreator;

    protected LogRepository $logRepository;

    protected PageRenderer $pageRenderer;

    protected SiteRepository $siteRepository;

    public function injectLogCreator(LogCreator $logCreator): void
    {
        $this->logCreator = $logCreator;
    }

    public function injectLogRepository(LogRepository $logRepository): void
    {
        $this->logRepository = $logRepository;
    }

    public function injectPageRenderer(PageRenderer $pageRenderer): void
    {
        $this->pageRenderer = $pageRenderer;
    }

    public function injectSiteRepository(SiteRepository $siteRepository): void
    {
        $this->siteRepository = $siteRepository;
    }

    public function listAction(int $currentPageNumber = 1): ResponseInterface
    {
        $arguments = $this->request->getArguments();
        if (!isset($arguments['filter'])) {
            $filter = new Filter();
        } else {
            $filter = $arguments['filter'];
            if (gettype($filter) === 'array') {
                $filter = new Filter($filter['status'], $filter['onlyErrors'], $filter['timeStart'], $filter['timeEnd']);
            }
        }

        $filteredLogs = $this->logRepository->findAllFiltered($filter)->toArray();
        $arrayPaginator = new ArrayPaginator($filteredLogs, $currentPageNumber, self::MAX_LOGS_SHOWN_PER_PAGE);
        $simplePagination = new SimplePagination($arrayPaginator);

        $this->view->assignMultiple([
            'filter' => $filter->toArray(),
            'pagination' => $simplePagination,
            'paginator' => $arrayPaginator,
        ]);

        $this->pageRenderer->loadRequireJsModule('TYPO3/CMS/Httpmonitoring/Tablesort');

        return $this->htmlResponse();
    }
}
