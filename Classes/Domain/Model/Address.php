<?php

declare(strict_types=1);

namespace P2media\Httpmonitoring\Domain\Model;

use TYPO3\CMS\Extbase\Annotation\Validate;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Address
 */
class Address extends AbstractEntity
{
    /**
     * site
     *
     * @var Site
     */
    protected $site;

    /**
     * email
     *
     * @Validate("TYPO3\CMS\Extbase\Validation\Validator\EmailAddressValidator")
     * @Validate("TYPO3\CMS\Extbase\Validation\Validator\NotEmptyValidator")
     */
    protected string $email = '';

    /**
     * name
     *
     * @Validate("TYPO3\CMS\Extbase\Validation\Validator\NotEmptyValidator")
     */
    protected string $name = '';

    /**
     * @return Site site
     */
    public function getSite()
    {
        return $this->site;
    }

    public function setSite(Site $site): void
    {
        $this->site = $site;
    }

    /**
     * Returns the email
     *
     * @return string email
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Sets the email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * Returns the name
     *
     * @return string name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
