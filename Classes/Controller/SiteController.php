<?php

declare(strict_types=1);

namespace P2media\Httpmonitoring\Controller;

use Psr\Http\Message\ResponseInterface;
use P2media\Httpmonitoring\Domain\Model\Site;
use P2media\Httpmonitoring\Domain\Repository\LogRepository;
use P2media\Httpmonitoring\Domain\Repository\SiteRepository;
use P2media\Httpmonitoring\Logging\LogCreator;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Pagination\ArrayPaginator;
use TYPO3\CMS\Core\Pagination\SimplePagination;
use TYPO3\CMS\Extbase\Annotation\IgnoreValidation;
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
 * SiteController
 */
class SiteController extends ActionController
{
    /**
     * @var int
     */
    final const MAX_ENTRIES_SHOWN_PER_PAGE = 5;

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

    public function listAction(): ResponseInterface
    {
        $queryResult = $this->siteRepository->findAll();
        $this->view->assign('sites', $queryResult);
        $this->pageRenderer->loadRequireJsModule('TYPO3/CMS/Httpmonitoring/Tablesort');
        return $this->htmlResponse();
    }

    public function showAction(Site $site, int $currentPageNumberUri = 1, int $currentPageNumberAddress = 1, int $currentPageNumberLog = 1): ResponseInterface
    {
        $this->view->assign('site', $site);

        $uris = $site->getUri()->toArray();
        $uriPaginator = new ArrayPaginator($uris, $currentPageNumberUri, self::MAX_ENTRIES_SHOWN_PER_PAGE);
        $uriPagination = new SimplePagination($uriPaginator);
        $this->view->assign('paginationUri', ['pagination' => $uriPagination, 'paginator' => $uriPaginator]);

        $addresses = $site->getAddress()->toArray();
        $addressesPaginator = new ArrayPaginator($addresses, $currentPageNumberAddress, self::MAX_ENTRIES_SHOWN_PER_PAGE);
        $addressesPagination = new SimplePagination($addressesPaginator);
        $this->view->assign('paginationAddress', ['pagination' => $addressesPagination, 'paginator' => $addressesPaginator]);

        $logs = $this->logRepository->findAllOfSite($site->getUri())->toArray();
        $logsPaginator = new ArrayPaginator($logs, $currentPageNumberLog, self::MAX_LOGS_SHOWN_PER_PAGE);
        $logsPagination = new SimplePagination($logsPaginator);
        $this->view->assign('paginationLog', ['pagination' => $logsPagination, 'paginator' => $logsPaginator]);

        $this->pageRenderer->loadRequireJsModule('TYPO3/CMS/Httpmonitoring/Tablesort');
        return $this->htmlResponse();
    }

    public function newAction(): ResponseInterface
    {
        return $this->htmlResponse();
    }

    public function createAction(Site $newSite): void
    {
        if ($this->siteRepository->findOneByTitle($newSite->getTitle()) !== null) {
            $this->addFlashMessage('The given site title already exists in the database. New site was not created.', '', AbstractMessage::WARNING);
        } else {
            $this->addFlashMessage('The object was created.', '', AbstractMessage::WARNING);
            $this->siteRepository->add($newSite);
        }

        $this->redirect('list');
    }

    /**
     * @IgnoreValidation("site")
     */
    public function editAction(Site $site): ResponseInterface
    {
        $this->view->assign('site', $site);
        return $this->htmlResponse();
    }

    public function updateAction(Site $site): void
    {
        $this->addFlashMessage('The object was updated.', '', AbstractMessage::WARNING);
        $this->siteRepository->update($site);
        $this->redirect('list');
    }

    public function deleteAction(Site $site): void
    {
        $this->addFlashMessage('The object was deleted.', '', AbstractMessage::WARNING);
        $this->siteRepository->remove($site);
        $this->redirect('list');
    }
}
