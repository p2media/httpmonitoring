<?php

declare(strict_types=1);

namespace P2media\Httpmonitoring\Tests\Unit\Controller;

use PHPUnit\Framework\MockObject\MockObject;
use P2media\Httpmonitoring\Controller\UriController;
use P2media\Httpmonitoring\Domain\Model\Site;
use P2media\Httpmonitoring\Domain\Model\Uri;
use P2media\Httpmonitoring\Domain\Repository\UriRepository;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\TestingFramework\Core\AccessibleObjectInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use TYPO3Fluid\Fluid\View\ViewInterface;

/**
 * Test case
 *
 * @author Marius Kachel <marius.kachel@p2media.de>
 */
class UriControllerTest extends UnitTestCase
{
    protected $resetSingletonInstances = true;
    protected UriController|MockObject|AccessibleObjectInterface $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = $this->getMockBuilder($this->buildAccessibleProxy(UriController::class))
            ->onlyMethods(['redirect', 'addFlashMessage', 'htmlResponse'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->subject->_set(
            'view',
            $this->getMockBuilder(ViewInterface::class)
                ->getMock()
        );
        $this->subject->injectPageRenderer(
            $this->getMockBuilder(PageRenderer::class)
                ->getMock()
        );
        $this->subject->injectUriRepository(
            $this->getMockBuilder(UriRepository::class)
                ->onlyMethods(['add', 'remove', '__call'])
                ->disableOriginalConstructor()
                ->getMock()
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function showActionAssignsTheGivenUriToView(): void
    {
        $uri = new Uri();
        $site = new Site();

        $view = $this->subject->_get('view');

        $view->expects(self::atLeastOnce())->method('assign');

        $this->subject->showAction($uri, $site);
    }

    /**
     * @test
     */
    public function createActionAddsTheGivenUriToUriRepository(): void
    {
        $uri = new Uri();
        $site = new Site();

        $queryResult = $this->getMockBuilder(QueryResultInterface::class)->getMock();
        $uriRepository = $this->subject->_get('uriRepository');
        $uriRepository->expects(self::once())->method('add')->with($uri);
        $uriRepository->method('__call')->will(self::returnValue($queryResult));

        $this->subject->createAction($uri, $site);
    }

    /**
     * @test
     */
    public function deleteActionRemovesTheGivenUriFromUriRepository(): void
    {
        $uri = new Uri();
        $site = new Site();

        $uriRepository = $this->subject->_get('uriRepository');
        $uriRepository->expects(self::once())->method('remove')->with($uri);

        $this->subject->deleteAction($site, $uri);
    }
}
