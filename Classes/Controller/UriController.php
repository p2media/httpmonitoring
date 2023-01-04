<?php

declare(strict_types=1);

namespace P2media\Httpmonitoring\Controller;

use Psr\Http\Message\ResponseInterface;
use P2media\Httpmonitoring\Domain\Model\Site;
use P2media\Httpmonitoring\Domain\Model\Uri;
use P2media\Httpmonitoring\Domain\Repository\UriRepository;
use P2media\Httpmonitoring\Utility\UriUtility;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
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
 * UriController
 */
class UriController extends ActionController
{
    /**
     * @var int
     */
    final const MAX_LOGS_SHOWN_PER_PAGE = 20;

    protected PageRenderer $pageRenderer;

    protected UriRepository $uriRepository;

    public function injectPageRenderer(PageRenderer $pageRenderer): void
    {
        $this->pageRenderer = $pageRenderer;
    }

    public function injectUriRepository(UriRepository $uriRepository): void
    {
        $this->uriRepository = $uriRepository;
    }

    public function newAction(): ResponseInterface
    {
        return $this->htmlResponse();
    }

    public function createAction(Uri $newUri, Site $site): void
    {
        $uriIsInvalid = false;
        $uriAlreadyInDb = false;

        $trimmedUriPath = UriUtility::trimUri($newUri->getPath());
        if ($trimmedUriPath !== false) {
            $newUri->setPath($trimmedUriPath);

            $queryResult = $this->uriRepository->findByPath($newUri->getPath());
            foreach ($queryResult as $uri) {
                if ($uri->getSite() !== null && $uri->getSite()->getUid() === $site->getUid()) {
                    $uriAlreadyInDb = true;
                    break;
                }
            }
        } else {
            $uriIsInvalid = true;
        }

        if ($uriIsInvalid) {
            $this->addFlashMessage('The given URI is invalid. New URI was not created.', '', AbstractMessage::WARNING);
        } elseif ($uriAlreadyInDb) {
            $this->addFlashMessage('The given URI already exists in the database. New URI was not created.', '', AbstractMessage::WARNING);
        } else {
            $this->addFlashMessage('The object was created.', '', AbstractMessage::WARNING);
            $newUri->setSite($site);
            $site->addUri($newUri);
            $this->uriRepository->add($newUri);
        }

        $this->redirect('show', 'Site', null, ['site' => $site]);
    }

    public function deleteAction(Site $site, Uri $uri = null): void
    {
        if ($uri != null) {
            $this->addFlashMessage('The object was deleted.', '', AbstractMessage::WARNING);
            $this->uriRepository->remove($uri);
        }

        $this->redirect('show', 'Site', null, ['site' => $site]);
    }

    public function showAction(Uri $uri, Site $site, int $currentPageNumber = 1): ResponseInterface
    {
        $this->view->assign('uri', $uri);
        $this->view->assign('site', $site);

        $itemsToBePaginated = $uri->getLogSorted();

        $arrayPaginator = new ArrayPaginator($itemsToBePaginated, $currentPageNumber, self::MAX_LOGS_SHOWN_PER_PAGE);
        $simplePagination = new SimplePagination($arrayPaginator);
        $this->view->assign('pagination', $simplePagination);
        $this->view->assign('paginator', $arrayPaginator);

        $this->pageRenderer->loadRequireJsModule('TYPO3/CMS/Httpmonitoring/Tablesort');
        return $this->htmlResponse();
    }
}
