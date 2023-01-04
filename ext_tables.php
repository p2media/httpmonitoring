<?php

use P2media\Httpmonitoring\Controller\AddressController;
use P2media\Httpmonitoring\Controller\LogController;
use P2media\Httpmonitoring\Controller\SiteController;
use P2media\Httpmonitoring\Controller\UriController;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') || die();

(static function (): void {
    ExtensionUtility::registerModule(
        'Httpmonitoring',
        'tools',
        'monitoring',
        '',
        [
            SiteController::class => 'list, show, new, create, edit, update, delete', UriController::class => 'show, new, create, delete', AddressController::class => 'new, create, delete', LogController::class => 'list',
        ],
        [
            'access' => 'user,group',
            'icon' => 'EXT:httpmonitoring/Resources/Public/Icons/user_mod_monitoring.svg',
            'labels' => 'LLL:EXT:httpmonitoring/Resources/Private/Language/locallang_monitoring.xlf',
        ]
    );

    ExtensionManagementUtility::addLLrefForTCAdescr('tx_httpmonitoring_domain_model_site', 'EXT:httpmonitoring/Resources/Private/Language/locallang_csh_tx_httpmonitoring_domain_model_site.xlf');
    ExtensionManagementUtility::allowTableOnStandardPages('tx_httpmonitoring_domain_model_site');

    ExtensionManagementUtility::addLLrefForTCAdescr('tx_httpmonitoring_domain_model_uri', 'EXT:httpmonitoring/Resources/Private/Language/locallang_csh_tx_httpmonitoring_domain_model_uri.xlf');
    ExtensionManagementUtility::allowTableOnStandardPages('tx_httpmonitoring_domain_model_uri');

    ExtensionManagementUtility::addLLrefForTCAdescr('tx_httpmonitoring_domain_model_address', 'EXT:httpmonitoring/Resources/Private/Language/locallang_csh_tx_httpmonitoring_domain_model_address.xlf');
    ExtensionManagementUtility::allowTableOnStandardPages('tx_httpmonitoring_domain_model_address');

    ExtensionManagementUtility::addLLrefForTCAdescr('tx_httpmonitoring_domain_model_log', 'EXT:httpmonitoring/Resources/Private/Language/locallang_csh_tx_httpmonitoring_domain_model_log.xlf');
    ExtensionManagementUtility::allowTableOnStandardPages('tx_httpmonitoring_domain_model_log');
})();
