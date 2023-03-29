<?php

declare(strict_types=1);

namespace P2media\Httpmonitoring\Domain\Model;

use TYPO3\CMS\Extbase\Annotation\ORM\Cascade;
use TYPO3\CMS\Extbase\Annotation\Validate;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * URL
 */
class Uri extends AbstractEntity
{
    /**
     * Do NOT use a type definition here, TYPO3 will break things if you do
     *
     * @var Site
     */
    protected $site;

    /**
     * @Validate("P2media\Httpmonitoring\Validator\UriValidator")
     * @Validate("TYPO3\CMS\Extbase\Validation\Validator\NotEmptyValidator")
     */
    protected string $path = '';

    /**
     * @var ObjectStorage<Log>
     * @Cascade("remove")
     */
    protected ObjectStorage $log;

    protected bool $laststatuswaserror = false;

    /**
     * __construct
     */
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
        $this->log ??= new ObjectStorage();
    }

    /**
     * Do NOT use a type definition here, TYPO3 will break things if you do
     *
     * @return Site
     */
    public function getSite()
    {
        return $this->site;
    }

    public function setSite(Site $site): void
    {
        $this->site = $site;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    public function addLog(Log $log): void
    {
        $this->log->attach($log);
    }

    public function removeLog(Log $log): void
    {
        $this->log->detach($log);
    }

    /**
     * @return array<Log>
     */
    public function getLogSorted(): array
    {
        $logArray = $this->getLog()->toArray();
        if (!empty($logArray)) {
            usort($logArray, static fn ($a, $b): int => $b->getCrdate() <=> $a->getCrdate());
        }

        return $logArray;
    }

    /**
     * @return ObjectStorage<Log> log
     */
    public function getLog(): ObjectStorage
    {
        return $this->log;
    }

    /**
     * @param ObjectStorage<Log> $objectStorage
     */
    public function setLog(ObjectStorage $objectStorage): void
    {
        $this->log = $objectStorage;
    }

    public function isLaststatuswaserror(): bool
    {
        return $this->laststatuswaserror;
    }

    public function getLaststatuswaserror(): bool
    {
        return $this->laststatuswaserror;
    }

    public function setLaststatuswaserror(bool $laststatuswaserror): void
    {
        $this->laststatuswaserror = $laststatuswaserror;
    }
}
