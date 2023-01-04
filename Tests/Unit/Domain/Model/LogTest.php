<?php

declare(strict_types=1);

namespace P2media\Httpmonitoring\Tests\Unit\Domain\Model;

use PHPUnit\Framework\MockObject\MockObject;
use P2media\Httpmonitoring\Domain\Model\Log;
use TYPO3\TestingFramework\Core\AccessibleObjectInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case
 *
 * @author Marius Kachel <marius.kachel@p2media.de>
 */
class LogTest extends UnitTestCase
{
    protected Log|MockObject|AccessibleObjectInterface $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = $this->getAccessibleMock(
            Log::class,
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
    public function getStatuscodeReturnsInitialValueForInt(): void
    {
        self::assertSame(
            0,
            $this->subject->getStatuscode()
        );
    }

    /**
     * @test
     */
    public function setStatuscodeForIntSetsStatuscode(): void
    {
        $this->subject->setStatuscode(12);

        self::assertEquals(12, $this->subject->_get('statuscode'));
    }
}
