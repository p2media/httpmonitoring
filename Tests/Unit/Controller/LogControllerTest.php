<?php

declare(strict_types=1);

namespace P2media\Httpmonitoring\Tests\Unit\Controller;

use PHPUnit\Framework\MockObject\MockObject;
use P2media\Httpmonitoring\Controller\LogController;
use P2media\Httpmonitoring\Domain\Repository\LogRepository;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\TestingFramework\Core\AccessibleObjectInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use TYPO3Fluid\Fluid\View\ViewInterface;

/**
 * Test case
 *
 * @author Marius Kachel <marius.kachel@p2media.de>
 */
class LogControllerTest extends UnitTestCase
{
    protected $resetSingletonInstances = true;
    protected LogController|MockObject|AccessibleObjectInterface $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = $this->getMockBuilder($this->buildAccessibleProxy(LogController::class))
            ->onlyMethods(['redirect', 'addFlashMessage', 'htmlResponse'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->subject->_set(
            'request',
            $this->getMockBuilder(Request::class)
                ->getMock()
        );
        $this->subject->_set(
            'view',
            $this->getMockBuilder(ViewInterface::class)
                ->getMock()
        );
        $this->subject->injectLogRepository(
            $this->getMockBuilder(LogRepository::class)
                ->onlyMethods(['findAllFiltered'])
                ->disableOriginalConstructor()
                ->getMock()
        );
        $this->subject->injectPageRenderer(
            $this->getMockBuilder(PageRenderer::class)
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
    public function listActionFetchesAllLogsFromRepositoryAndAssignsThemToView(): void
    {
        $logRepository = $this->subject->_get('logRepository');
        $view = $this->subject->_get('view');

        $queryResult = $this->getMockBuilder(QueryResultInterface::class)->getMock();
        $queryResult->method('toArray')->will(self::returnValue([]));
        $logRepository->expects(self::once())->method('findAllFiltered')->will(self::returnValue($queryResult));
        $logRepository->method('findAllFiltered')->will(self::returnValue($queryResult));

        $view->expects(self::once())->method('assignMultiple');

        $this->subject->listAction();
    }
}
