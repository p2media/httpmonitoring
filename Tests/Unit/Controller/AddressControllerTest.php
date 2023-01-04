<?php

declare(strict_types=1);

namespace P2media\Httpmonitoring\Tests\Unit\Controller;

use PHPUnit\Framework\MockObject\MockObject;
use P2media\Httpmonitoring\Controller\AddressController;
use P2media\Httpmonitoring\Domain\Model\Address;
use P2media\Httpmonitoring\Domain\Model\Site;
use P2media\Httpmonitoring\Domain\Repository\AddressRepository;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\TestingFramework\Core\AccessibleObjectInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case
 *
 * @author Marius Kachel <marius.kachel@p2media.de>
 */
class AddressControllerTest extends UnitTestCase
{
    protected AddressController|MockObject|AccessibleObjectInterface $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = $this->getMockBuilder($this->buildAccessibleProxy(AddressController::class))
            ->onlyMethods(['redirect', 'addFlashMessage'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->subject->injectAddressRepository($this->getMockBuilder(AddressRepository::class)
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
    public function createActionAddsTheGivenAddressToAddressRepository(): void
    {
        $address = new Address();
        $site = new Site();

        $addressRepository = $this->subject->_get('addressRepository');

        $addressRepository->expects(self::once())->method('add')->with($address);
        $addressRepository->method('__call')->will(self::returnValue($this->getMockBuilder(QueryResultInterface::class)->getMock()));

        $this->subject->createAction($address, $site);
    }

    /**
     * @test
     */
    public function deleteActionRemovesTheGivenAddressFromAddressRepository(): void
    {
        $address = new Address();
        $site = new Site();

        $addressRepository = $this->subject->_get('addressRepository');

        $addressRepository->expects(self::once())->method('remove')->with($address);

        $this->subject->deleteAction($site, $address);
    }
}
