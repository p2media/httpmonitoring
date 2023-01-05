<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'httpmonitoring',
    'description' => 'An extension for the TYP3 CMS which adds a backend module for monitoring the HTTP status code given out by sites.',
    'category' => 'module',
    'author' => 'Marius Kachel',
    'author_email' => 'marius.kachel@p2media.de',
    'author_company' => 'p2media',
    'state' => 'beta',
    'version' => '1.0.1',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.0-11.5.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
