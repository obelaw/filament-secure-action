<?php

return [
    'password' => [
        'form' => [
            'field' => 'password',
            'content' => 'Please enter your current password to confirm this action.',
        ],
    ],
    'mfa' => [
        'form' => [
            'field' => 'code',
            'label' => 'Two Factor Code',
            'placeholder' => '######',
            'max_length' => 6,
            'min_length' => 6,
        ],
    ],
];
