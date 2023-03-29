<?php

declare(strict_types=1);

namespace P2media\Httpmonitoring\Domain\Model;

use TYPO3\CMS\Extbase\Annotation\ORM\Cascade;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Site
 */
class Site extends AbstractEntity
{
    protected string $title = '';

    /**
     * @var ObjectStorage<Uri>
     * @Cascade("remove")
     */
    protected ObjectStorage $uri;

    /**
     * @var ObjectStorage<Address>
     * @Cascade("remove")
     */
    protected ObjectStorage $address;

    public function __construct()
    {
        // Do not remove the next line: It would break the functionality
        $this->initializeObject();
    }

    /**
     * Initializes all ObjectStorage properties when model is reconstructed from DB (where __construct is not called)
     * Do not modify this method!
     * It will be rewritten on each save in the extension builder
     * You may modify the constructor of this class instead
     */
    public function initializeObject(): void
    {
        $this->uri ??= new ObjectStorage();
        $this->address ??= new ObjectStorage();
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function addUri(Uri $uri): void
    {
        $this->uri->attach($uri);
    }

    public function removeUri(Uri $uri): void
    {
        $this->uri->detach($uri);
    }

    /**
     * @return ObjectStorage<Uri> uri
     */
    public function getUri(): ObjectStorage
    {
        return $this->uri;
    }

    /**
     * @param ObjectStorage<Uri> $objectStorage
     */
    public function setUri(ObjectStorage $objectStorage): void
    {
        $this->uri = $objectStorage;
    }

    public function addAddress(Address $address): void
    {
        $this->address->attach($address);
    }

    public function removeAddress(Address $address): void
    {
        $this->address->detach($address);
    }

    /**
     * @return ObjectStorage<Address> address
     */
    public function getAddress(): ObjectStorage
    {
        return $this->address;
    }

    /**
     * @param ObjectStorage<Address> $objectStorage
     */
    public function setAddress(ObjectStorage $objectStorage): void
    {
        $this->address = $objectStorage;
    }
}
