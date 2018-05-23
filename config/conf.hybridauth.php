<?php
$config = [
    'callback' => 'http://supfile.tk/login',
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
                'id' => '329157288654-gsra1n9afce1mve7mbb7kejp6nudopuj.apps.googleusercontent.com',
                'secret' => 'lxCb8JkN07Oz8m49eg7EfKws']
        ]
    ]
];