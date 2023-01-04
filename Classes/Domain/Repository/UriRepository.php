<?php

declare(strict_types=1);

namespace P2media\Httpmonitoring\Domain\Repository;

/**
 * This file is part of the "httpmonitoring" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2022 Marius Kachel <marius.kachel@p2media.de>, p2media
 */

use P2media\Httpmonitoring\Domain\Model\Uri;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * @extends Repository<Uri>
 */
class UriRepository extends Repository
{
}
