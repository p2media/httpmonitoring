<?php

$_EXTKEY ??= 'httpmonitoring';

$EM_CONF[$_EXTKEY] = [
    'title' => 'httpmonitoring',
    'description' => '',
    'category' => 'module',
    'author' => 'Marius Kachel',
    'author_email' => 'marius.kachel@p2media.de',
    'author_company' => 'p2media',
    'state' => 'alpha',
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.0-11.5.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
