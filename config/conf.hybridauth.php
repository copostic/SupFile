<?php
$config = [
    'callback' => 'https://supfile.cf/auth/social',
    'providers' => [
        'Twitter' => [
            'enabled' => true,
            'keys' => [
                'key' => 'esMk1EIR2VO3Ln5RUgKwPDq1G',
                'secret' => 'hS6cUt62pYoNprLdgAXEXkG3Ifm8wU2ihoMpgPybyVeJZDaSaS'
            ],
            'includeEmail' => true
        ],
        'Facebook' => [
            'enabled' => true,
            'keys' => [
                'id' => '141955019961807',
                'secret' => '52de8c2de4d070e4095df4507126ea77'
            ]
        ],
        'Google' => [
            'enabled' => true,
            'keys' => [
                'id' => '329157288654-vk03drjkhl9kh9g9dn5trjpfn5k8h931.apps.googleusercontent.com',
                'secret' => 'SzIRs_SJtfimZyZ4kZtaNeH-']
        ]
    ]
];