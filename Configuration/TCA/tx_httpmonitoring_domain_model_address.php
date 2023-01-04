<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:httpmonitoring/Resources/Private/Language/locallang_db.xlf:tx_httpmonitoring_domain_model_address',
        'label' => 'email',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'email,name',
        'iconfile' => 'EXT:httpmonitoring/Resources/Public/Icons/tx_httpmonitoring_domain_model_address.gif',
    ],
    'types' => [
        '1' => ['showitem' => 'email, name, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access, hidden, starttime, endtime'],
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

        'email' => [
            'exclude' => true,
            'label' => 'LLL:EXT:httpmonitoring/Resources/Private/Language/locallang_db.xlf:tx_httpmonitoring_domain_model_address.email',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'nospace,email',
                'default' => '',
            ],
        ],
        'name' => [
            'exclude' => true,
            'label' => 'LLL:EXT:httpmonitoring/Resources/Private/Language/locallang_db.xlf:tx_httpmonitoring_domain_model_address.name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => '',
            ],
        ],

        'site' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
    ],
];
