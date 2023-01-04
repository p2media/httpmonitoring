<?php

declare(strict_types=1);

namespace P2media\Httpmonitoring\Tests\Unit\Domain\Model;

use PHPUnit\Framework\MockObject\MockObject;
use P2media\Httpmonitoring\Domain\Model\Address;
use P2media\Httpmonitoring\Domain\Model\Site;
use P2media\Httpmonitoring\Domain\Model\Uri;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\TestingFramework\Core\AccessibleObjectInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case
 *
 * @author Marius Kachel <marius.kachel@p2media.de>
 */
class SiteTest extends UnitTestCase
{

    protected Site|MockObject|AccessibleObjectInterface $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = $this->getAccessibleMock(
            Site::class,
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
    public function getTitleReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getTitle()
        );
    }

    /**
     * @test
     */
    public function setTitleForStringSetsTitle(): void
    {
        $this->subject->setTitle('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('title'));
    }

    /**
     * @test
     */
    public function getUriReturnsInitialValueForUri(): void
    {
        $newObjectStorage = new ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getUri()
        );
    }

    /**
     * @test
     */
    public function setUriForObjectStorageContainingUriSetsUri(): void
    {
        $uri = new Uri();
        $objectStorageHoldingExactlyOneUri = new ObjectStorage();
        $objectStorageHoldingExactlyOneUri->attach($uri);
        $this->subject->setUri($objectStorageHoldingExactlyOneUri);

        self::assertEquals($objectStorageHoldingExactlyOneUri, $this->subject->_get('uri'));
    }

    /**
     * @test
     */
    public function addUriToObjectStorageHoldingUri(): void
    {
        $uri = new Uri();
        $uriObjectStorageMock = $this->getMockBuilder(ObjectStorage::class)
            ->onlyMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();

        $uriObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($uri));
        $this->subject->_set('uri', $uriObjectStorageMock);

        $this->subject->addUri($uri);
    }

    /**
     * @test
     */
    public function removeUriFromObjectStorageHoldingUri(): void
    {
        $uri = new Uri();
        $uriObjectStorageMock = $this->getMockBuilder(ObjectStorage::class)
            ->onlyMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();

        $uriObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($uri));
        $this->subject->_set('uri', $uriObjectStorageMock);

        $this->subject->removeUri($uri);
    }

    /**
     * @test
     */
    public function getAddressReturnsInitialValueForAddress(): void
    {
        $newObjectStorage = new ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getAddress()
        );
    }

    /**
     * @test
     */
    public function setAddressForObjectStorageContainingAddressSetsAddress(): void
    {
        $address = new Address();
        $objectStorageHoldingExactlyOneAddress = new ObjectStorage();
        $objectStorageHoldingExactlyOneAddress->attach($address);
        $this->subject->setAddress($objectStorageHoldingExactlyOneAddress);

        self::assertEquals($objectStorageHoldingExactlyOneAddress, $this->subject->_get('address'));
    }

    /**
     * @test
     */
    public function addAddressToObjectStorageHoldingAddress(): void
    {
        $address = new Address();
        $addressObjectStorageMock = $this->getMockBuilder(ObjectStorage::class)
            ->onlyMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();

        $addressObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($address));
        $this->subject->_set('address', $addressObjectStorageMock);

        $this->subject->addAddress($address);
    }

    /**
     * @test
     */
    public function removeAddressFromObjectStorageHoldingAddress(): void
    {
        $address = new Address();
        $addressObjectStorageMock = $this->getMockBuilder(ObjectStorage::class)
            ->onlyMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();

        $addressObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($address));
        $this->subject->_set('address', $addressObjectStorageMock);

        $this->subject->removeAddress($address);
    }
}
