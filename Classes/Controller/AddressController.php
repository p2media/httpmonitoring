<?php

declare(strict_types=1);

namespace P2media\Httpmonitoring\Controller;

use Psr\Http\Message\ResponseInterface;
use P2media\Httpmonitoring\Domain\Model\Address;
use P2media\Httpmonitoring\Domain\Model\Site;
use P2media\Httpmonitoring\Domain\Repository\AddressRepository;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * This file is part of the "httpmonitoring" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2022 Marius Kachel <marius.kachel@p2media.de>, p2media
 */

/**
 * AddressController
 */
class AddressController extends ActionController
{
    protected AddressRepository $addressRepository;

    public function injectAddressRepository(AddressRepository $addressRepository): void
    {
        $this->addressRepository = $addressRepository;
    }

    public function newAction(): ResponseInterface
    {
        return $this->htmlResponse();
    }

    public function createAction(Address $newAddress, Site $site): void
    {
        $addressAlreadyInDb = false;
        $queryResult = $this->addressRepository->findByEmail($newAddress->getEmail());
        foreach ($queryResult as $address) {
            if ($address->getSite() !== null && $address->getSite()->getUid() === $site->getUid()) {
                $addressAlreadyInDb = true;
                break;
            }
        }

        if ($addressAlreadyInDb) {
            $this->addFlashMessage('The given address already exists in the database. New address was not created.', '', AbstractMessage::WARNING);
        } else {
            $this->addFlashMessage('The object was created.', '', AbstractMessage::WARNING);
            $newAddress->setSite($site);
            $site->addAddress($newAddress);
            $this->addressRepository->add($newAddress);
        }

        $this->redirect('show', 'Site', null, ['site' => $site]);
    }

    public function deleteAction(Site $site, Address $address = null): void
    {
        if ($address != null) {
            $this->addFlashMessage('The object was deleted.', '', AbstractMessage::WARNING);
            $this->addressRepository->remove($address);
        }

        $this->redirect('show', 'Site', null, ['site' => $site]);
    }
}
