<?php

namespace packages\httpmonitoring\Tests\Unit\Logging;

use PHPUnit\Framework\MockObject\MockObject;
use P2media\Httpmonitoring\Data\UpdatedStatus;
use P2media\Httpmonitoring\Data\UpdatedStatusContainer;
use P2media\Httpmonitoring\Domain\Model\Log;
use P2media\Httpmonitoring\Domain\Model\Site;
use P2media\Httpmonitoring\Domain\Model\Uri;
use P2media\Httpmonitoring\Domain\Repository\LogRepository;
use P2media\Httpmonitoring\Domain\Repository\SiteRepository;
use P2media\Httpmonitoring\Logging\LogCreator;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\TestingFramework\Core\AccessibleObjectInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class LogCreatorTest extends UnitTestCase
{
    protected LogCreator|MockObject|AccessibleObjectInterface $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = $this->getAccessibleMock(
            LogCreator::class,
            ['createNewLog']
        );
        $configurationManager = $this->getMockBuilder(ConfigurationManagerInterface::class)
            ->onlyMethods([])
            ->disableOriginalConstructor()
            ->getMock();
        $settings = [];
        $settings['module.']['tx_httpmonitoring_tools_httpmonitoringmonitoring.']['persistence.']['storagePid'] = 87;
        $configurationManager->method('getConfiguration')->will($this->returnValue($settings));
        $this->subject->injectConfigurationManager($configurationManager);

        $this->subject->injectLogRepository($this->getMockBuilder(LogRepository::class)
            ->onlyMethods([])
            ->disableOriginalConstructor()
            ->getMock()
        );
        $this->subject->injectPersistenceManager($this->getMockBuilder(PersistenceManagerInterface::class)
            ->onlyMethods([])
            ->disableOriginalConstructor()
            ->getMock()
        );
        $this->subject->injectRequestFactory($this->getMockBuilder(RequestFactory::class)
            ->onlyMethods([])
            ->disableOriginalConstructor()
            ->getMock()
        );
        $this->subject->injectSiteRepository($this->getMockBuilder(SiteRepository::class)
            ->onlyMethods([])
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
    public function createNewLogAndGetErrorReturnsEmptyUpdatedStatusContainerIfNoStatusChanged(): void
    {
        $queryResult = $this->createMock(QueryResultInterface::class);
        $uri = new Uri();
        $site = new Site();
        $log = new Log();
        $this->prepareTest($log, 500, $uri, true, $site, $queryResult);

        $result = $this->subject->createNewLogAndGetError($queryResult);
        $this->assertEquals(new UpdatedStatusContainer(), $result);
    }

    /**
     * @test
     */
    public function createNewLogAndGetErrorReturnsImprovedStatusIfUriStopsReturningErrorCode(): void
    {
        $queryResult = $this->createMock(QueryResultInterface::class);
        $uri = new Uri();
        $site = new Site();
        $log = new Log();
        $this->prepareTest($log, 200, $uri, true, $site, $queryResult);

        $result = $this->subject->createNewLogAndGetError($queryResult);
        $updatedStatusContainer = new UpdatedStatusContainer();
        $updatedStatus = new UpdatedStatus($site);
        $updatedStatus->getUriStorage()->attach($uri);
        $updatedStatusContainer->addImprovedStatus($updatedStatus);
        $this->assertEquals($updatedStatusContainer, $result);
    }

    /**
     * @test
     */
    public function createNewLogAndGetErrorReturnsErrorIfUriStartsReturningErrorCode(): void
    {
        $queryResult = $this->createMock(QueryResultInterface::class);
        $uri = new Uri();
        $site = new Site();
        $log = new Log();
        $this->prepareTest($log, 500, $uri, false, $site, $queryResult);

        $result = $this->subject->createNewLogAndGetError($queryResult);
        $updatedStatusContainer = new UpdatedStatusContainer();
        $updatedStatus = new UpdatedStatus($site);
        $updatedStatus->getUriStorage()->attach($uri);
        $updatedStatusContainer->addError($updatedStatus);
        $this->assertEquals($updatedStatusContainer, $result);
    }

    private function prepareTest
    (
        Log $log,
        int $statusCode,
        Uri $uri,
        bool $lastStatusWasError,
        Site $site,
        MockObject $queryResult
    ): void
    {
        $log->setStatuscode($statusCode);
        $uri->setLaststatuswaserror($lastStatusWasError);
        $site->addUri($uri);
        $this->subject->method('createNewLog')->will($this->returnValue($log));
        $this->mockIterator($queryResult, [$site]);
    }

    /**
     * Setup methods required to mock an iterator
     *
     * Method taken from https://stackoverflow.com/questions/15907249/how-can-i-mock-a-class-that-implements-the-iterator-interface-using-phpunit
     *
     * @param MockObject $iteratorMock The mock to attach the iterator methods to
     * @param array $items The mock data we're going to use with the iterator
     * @return MockObject The iterator mock
     */
    private function mockIterator(MockObject $iteratorMock, array $items): MockObject
    {
        $iteratorData = new \stdClass();
        $iteratorData->array = $items;
        $iteratorData->position = 0;

        $iteratorMock
            ->method('rewind')
            ->will(
                $this->returnCallback(
                    function () use ($iteratorData) {
                        $iteratorData->position = 0;
                    }
                )
            );

        $iteratorMock->method('current')
            ->will(
                $this->returnCallback(
                    function () use ($iteratorData) {
                        return $iteratorData->array[$iteratorData->position];
                    }
                )
            );

        $iteratorMock->method('key')
            ->will(
                $this->returnCallback(
                    function () use ($iteratorData) {
                        return $iteratorData->position;
                    }
                )
            );

        $iteratorMock->method('next')
            ->will(
                $this->returnCallback(
                    function () use ($iteratorData) {
                        $iteratorData->position++;
                    }
                )
            );

        $iteratorMock->method('valid')
            ->will(
                $this->returnCallback(
                    function () use ($iteratorData) {
                        return isset($iteratorData->array[$iteratorData->position]);
                    }
                )
            );

        $iteratorMock->method('count')
            ->will(
                $this->returnCallback(
                    function () use ($iteratorData) {
                        return sizeof($iteratorData->array);
                    }
                )
            );

        return $iteratorMock;
    }
}