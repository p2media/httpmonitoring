<?php

declare(strict_types=1);

namespace P2media\Httpmonitoring\Tests\Unit\Controller;

use PHPUnit\Framework\MockObject\MockObject;
use P2media\Httpmonitoring\Controller\SiteController;
use P2media\Httpmonitoring\Domain\Model\Site;
use P2media\Httpmonitoring\Domain\Repository\LogRepository;
use P2media\Httpmonitoring\Domain\Repository\SiteRepository;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\TestingFramework\Core\AccessibleObjectInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use TYPO3Fluid\Fluid\View\ViewInterface;

/**
 * Test case
 *
 * @author Marius Kachel <marius.kachel@p2media.de>
 */
class SiteControllerTest extends UnitTestCase
{
    protected $resetSingletonInstances = true;
    protected SiteController|MockObject|AccessibleObjectInterface $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = $this->getMockBuilder($this->buildAccessibleProxy(SiteController::class))
            ->onlyMethods(['redirect', 'addFlashMessage', 'htmlResponse'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->subject->injectSiteRepository(
            $this->getMockBuilder(SiteRepository::class)
                ->onlyMethods(['add', 'remove', 'update', 'findAll', '__call'])
                ->disableOriginalConstructor()
                ->getMock()
        );
        $this->subject->injectPageRenderer($this->getMockBuilder(PageRenderer::class)->getMock());

    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function listActionFetchesAllSitesFromRepositoryAndAssignsThemToView(): void
    {
        $objectStorage = $this->getMockBuilder(ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->subject->_get('siteRepository')->expects(self::once())->method('findAll')->will(self::returnValue($objectStorage));

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assign')->with('sites', $objectStorage);
        $this->subject->_set('view', $view);

        $this->subject->listAction();
    }

    /**
     * @test
     */
    public function showActionAssignsTheGivenSiteToView(): void
    {
        $site = new Site();
        $objectStorage = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $objectStorage->method('toArray')->will(self::returnValue([]));
        $site->setUri($objectStorage);
        $site->setAddress($objectStorage);

        $queryResult = $this->getMockBuilder(QueryResultInterface::class)->getMock();
        $queryResult->method('toArray')->will(self::returnValue([]));
        $logRepository = $this->getMockBuilder(LogRepository::class)
            ->onlyMethods(['findAllOfSite'])
            ->disableOriginalConstructor()
            ->getMock();
        $logRepository->method('findAllOfSite')->will(self::returnValue($queryResult));
        $this->subject->injectLogRepository($logRepository);

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();

        $this->subject->_set('view', $view);
        $view->expects(self::atLeastOnce())->method('assign')->withConsecutive(['site', $site]);

        $this->subject->showAction($site);
    }

    /**
     * @test
     */
    public function createActionAddsTheGivenSiteToSiteRepository(): void
    {
        $site = new Site();

        $siteRepository = $this->subject->_get('siteRepository');
        $siteRepository->method('__call')->will(self::returnValue(null));
        $siteRepository->expects(self::once())->method('add')->with($site);

        $this->subject->createAction($site);
    }

    /**
     * @test
     */
    public function editActionAssignsTheGivenSiteToView(): void
    {
        $site = new Site();

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $this->subject->_set('view', $view);
        $view->expects(self::once())->method('assign')->with('site', $site);

        $this->subject->editAction($site);
    }

    /**
     * @test
     */
    public function updateActionUpdatesTheGivenSiteInSiteRepository(): void
    {
        $site = new Site();

        $this->subject->_get('siteRepository')->expects(self::once())->method('update')->with($site);

        $this->subject->updateAction($site);
    }

    /**
     * @test
     */
    public function deleteActionRemovesTheGivenSiteFromSiteRepository(): void
    {
        $site = new Site();

        $this->subject->_get('siteRepository')->expects(self::once())->method('remove')->with($site);

        $this->subject->deleteAction($site);
    }
}
