<?php

namespace P2media\Httpmonitoring\Data;

use P2media\Httpmonitoring\Domain\Model\Site;
use P2media\Httpmonitoring\Domain\Model\Uri;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class UpdatedStatus
{
    /**
     * @var ObjectStorage<Uri>
     */
    private readonly ObjectStorage $uriStorage;

    public function __construct(private readonly Site $site)
    {
        $this->uriStorage = GeneralUtility::makeInstance(ObjectStorage::class);
    }

    public function getSite(): Site
    {
        return $this->site;
    }

    /**
     * @return ObjectStorage<Uri>
     */
    public function getUriStorage(): ObjectStorage
    {
        return $this->uriStorage;
    }
}
