<?php

declare(strict_types=1);

namespace P2media\Httpmonitoring\Tests\Unit\Domain\Model;

use PHPUnit\Framework\MockObject\MockObject;
use P2media\Httpmonitoring\Domain\Model\Log;
use P2media\Httpmonitoring\Domain\Model\Uri;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\TestingFramework\Core\AccessibleObjectInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case
 *
 * @author Marius Kachel <marius.kachel@p2media.de>
 */
class UriTest extends UnitTestCase
{
    protected Uri|MockObject|AccessibleObjectInterface $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = $this->getAccessibleMock(
            Uri::class,
            ['dummy']
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getPathReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getPath()
        );
    }

    /**
     * @test
     */
    public function setPathForStringSetsPath(): void
    {
        $this->subject->setPath('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('path'));
    }

    /**
     * @test
     */
    public function getLaststatuswaserrorReturnsInitialValueForBool(): void
    {
        self::assertFalse($this->subject->getLaststatuswaserror());
    }

    /**
     * @test
     */
    public function setLaststatuswaserrorForBoolSetsLaststatuswaserror(): void
    {
        $this->subject->setLaststatuswaserror(true);

        self::assertEquals(true, $this->subject->_get('laststatuswaserror'));
    }

    /**
     * @test
     */
    public function getLogReturnsInitialValueForLog(): void
    {
        $newObjectStorage = new ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getLog()
        );
    }

    /**
     * @test
     */
    public function setLogForObjectStorageContainingLogSetsLog(): void
    {
        $log = new Log();
        $objectStorageHoldingExactlyOneLog = new ObjectStorage();
        $objectStorageHoldingExactlyOneLog->attach($log);
        $this->subject->setLog($objectStorageHoldingExactlyOneLog);

        self::assertEquals($objectStorageHoldingExactlyOneLog, $this->subject->_get('log'));
    }

    /**
     * @test
     */
    public function addLogToObjectStorageHoldingLog(): void
    {
        $log = new Log();
        $logObjectStorageMock = $this->getMockBuilder(ObjectStorage::class)
            ->onlyMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();

        $logObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($log));
        $this->subject->_set('log', $logObjectStorageMock);

        $this->subject->addLog($log);
    }

    /**
     * @test
     */
    public function removeLogFromObjectStorageHoldingLog(): void
    {
        $log = new Log();
        $logObjectStorageMock = $this->getMockBuilder(ObjectStorage::class)
            ->onlyMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();

        $logObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($log));
        $this->subject->_set('log', $logObjectStorageMock);

        $this->subject->removeLog($log);
    }
}
