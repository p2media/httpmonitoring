<?php

declare(strict_types=1);

namespace P2media\Httpmonitoring\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Log
 */
class Log extends AbstractEntity
{
    /**
     * @var int
     */
    final const MINIMUM_SUCCESS_STATUS_CODE = 100;

    /**
     * @var int
     */
    final const MAXIMUM_SUCCESS_STATUS_CODE = 399;

    protected int $crdate = 0;

    /**
     * Do NOT use a type definition here, TYPO3 will break things if you do
     *
     * @var Uri
     */
    protected $uri;

    protected int $statuscode = 0;

    public function getCrdate(): int
    {
        return $this->crdate;
    }

    public function setCrdate(int $crdate): void
    {
        $this->crdate = $crdate;
    }

    /**
     * Do NOT use a type definition here, TYPO3 will break things if you do
     *
     * @return Uri
     */
    public function getUri()
    {
        return $this->uri;
    }

    public function setUri(Uri $uri): void
    {
        $this->uri = $uri;
    }

    public function getStatuscode(): int
    {
        return $this->statuscode;
    }

    public function setStatuscode(int $statuscode): void
    {
        $this->statuscode = $statuscode;
    }

    public function hasErrorStatus(): bool
    {
        $status = $this->statuscode;
        if ($status < self::MINIMUM_SUCCESS_STATUS_CODE) {
            return true;
        }

        return $status > self::MAXIMUM_SUCCESS_STATUS_CODE;
    }
}
