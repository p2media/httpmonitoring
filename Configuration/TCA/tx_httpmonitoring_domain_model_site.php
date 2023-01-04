<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:httpmonitoring/Resources/Private/Language/locallang_db.xlf:tx_httpmonitoring_domain_model_site',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'title',
        'iconfile' => 'EXT:httpmonitoring/Resources/Public/Icons/tx_httpmonitoring_domain_model_site.gif',
    ],
    'types' => [
        '1' => ['showitem' => 'title, uri, address, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access, hidden, starttime, endtime'],
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

        'title' => [
            'exclude' => true,
            'label' => 'LLL:EXT:httpmonitoring/Resources/Private/Language/locallang_db.xlf:tx_httpmonitoring_domain_model_site.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => '',
            ],
        ],
        'uri' => [
            'exclude' => true,
            'label' => 'LLL:EXT:httpmonitoring/Resources/Private/Language/locallang_db.xlf:tx_httpmonitoring_domain_model_site.uri',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_httpmonitoring_domain_model_uri',
                'foreign_field' => 'site',
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
        'address' => [
            'exclude' => true,
            'label' => 'LLL:EXT:httpmonitoring/Resources/Private/Language/locallang_db.xlf:tx_httpmonitoring_domain_model_site.address',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_httpmonitoring_domain_model_address',
                'foreign_field' => 'site',
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

    ],
];
