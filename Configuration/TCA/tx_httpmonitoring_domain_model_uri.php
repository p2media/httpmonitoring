<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:httpmonitoring/Resources/Private/Language/locallang_db.xlf:tx_httpmonitoring_domain_model_uri',
        'label' => 'path',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'path',
        'iconfile' => 'EXT:httpmonitoring/Resources/Public/Icons/tx_httpmonitoring_domain_model_uri.gif',
    ],
    'types' => [
        '1' => ['showitem' => 'path, laststatuswaserror, log, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access, hidden, starttime, endtime'],
    ],
    'columns' => [
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'invertStateDisplay' => true,
                    ],
                ],
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038),
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'path' => [
            'exclude' => true,
            'label' => 'LLL:EXT:httpmonitoring/Resources/Private/Language/locallang_db.xlf:tx_httpmonitoring_domain_model_uri.path',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputLink',
            ],
        ],
        'laststatuswaserror' => [
            'exclude' => true,
            'label' => 'LLL:EXT:httpmonitoring/Resources/Private/Language/locallang_db.xlf:tx_httpmonitoring_domain_model_uri.laststatuswaserror',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '',
                        1 => '',
                    ],
                ],
                'default' => 0,
            ],
        ],
        'log' => [
            'exclude' => true,
            'label' => 'LLL:EXT:httpmonitoring/Resources/Private/Language/locallang_db.xlf:tx_httpmonitoring_domain_model_uri.log',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_httpmonitoring_domain_model_log',
                'foreign_field' => 'uri',
                'maxitems' => 9999,
                'appearance' => [
                    'collapseAll' => 0,
                    'levelLinksPosition' => 'top',
                    'showSynchronizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'showAllLocalizationLink' => 1,
                ],
            ],

        ],
        'site' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
    ],
];
