<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

ExtensionManagementUtility::addStaticFile('httpmonitoring', 'Configuration/TypoScript', 'httpmonitoring');
