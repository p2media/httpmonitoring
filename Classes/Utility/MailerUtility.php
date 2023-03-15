<?php

namespace P2media\Httpmonitoring\Utility;

use P2media\Httpmonitoring\Data\UpdatedStatus;
use P2media\Httpmonitoring\Data\UpdatedStatusContainer;
use Symfony\Component\Mime\Address;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class MailerUtility
{
    /**
     * Takes an UpdatedStatusContainer and sends out emails detailing the URIs which have now started or stopped
     * returning error codes
     */
    public static function sendErrorMail(UpdatedStatusContainer $updatedStatusContainer): void
    {
        // Error emails
        foreach ($updatedStatusContainer->getErrorArray() as $updatedStatus) {
            $addressArray = $updatedStatus->getSite()->getAddress()->toArray();
            if (empty($addressArray)) {
                continue;
            }

            $mail = GeneralUtility::makeInstance(MailMessage::class);
            if ($updatedStatus->getUriStorage()->count() > 1) {
                $message = 'The following sites have returned error codes, or no response at all: 

';
                $mail->subject('Monitored Sites returned error codes');
            } else {
                $message = 'The following site has returned an error code, or no response at all: 

';
                $mail->subject('Monitored Site returned error code');
            }

            $message = self::createMessage($updatedStatus, $message);

            self::setMailArguments($mail, $message, $addressArray);
            $mail->send();
        }

        // Improved status emails
        foreach ($updatedStatusContainer->getImprovedStatusArray() as $improvedStatus) {
            $addressArray = $improvedStatus->getSite()->getAddress()->toArray();
            if (empty($addressArray)) {
                continue;
            }

            $mail = GeneralUtility::makeInstance(MailMessage::class);
            if ($improvedStatus->getUriStorage()->count() > 1) {
                $message = 'The following sites which had previously been reporting error codes have now started reporting acceptable codes: ' . "\r\n\r\n";
                $mail->subject('Monitored Sites which previously returned error codes now reporting acceptable codes');
            } else {
                $message = 'The following site which has previously been reporting an error code has now started reporting an acceptable code: ' . "\r\n\r\n";
                $mail->subject('Monitored Site which previously returned error code now reporting acceptable code');
            }

            $message = self::createMessage($improvedStatus, $message);

            self::setMailArguments($mail, $message, $addressArray);
            $mail->send();
        }
    }

    private static function createMessage(UpdatedStatus $updatedStatus, string $message): string
    {
        foreach ($updatedStatus->getUriStorage() as $objectStorage) {
            $logArray = $objectStorage->getLog()->toArray();
            $lastLog = end($logArray);
            if ($lastLog === false) {
                continue;
            }

            $status = $lastLog->getStatusCode();
            $message .= 'Status: ' . str_pad((string)$status, 3, ' ', STR_PAD_LEFT) . ' URI: ' . $objectStorage->getPath() . "\r\n";
        }
        return $message;
    }

    /**
     * @param array<\P2media\Httpmonitoring\Domain\Model\Address> $addressArray
     */
    private static function setMailArguments(MailMessage $mailMessage, string $mailText, array $addressArray): void
    {
        $senderAddress = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('httpmonitoring', 'senderAddress');
        if (gettype($senderAddress) !== 'string') {
            $senderAddress = 'noreply@p2media.de';
        }
        $mailMessage->from(new Address($senderAddress, 'HttpMonitoring Mailer'));

        $addressEmailArray = [];
        foreach ($addressArray as $address) {
            $addressEmailArray[] = $address->getEmail();
        }

        $mailMessage->setTo($addressEmailArray);

        $mailMessage->text($mailText);
    }
}
