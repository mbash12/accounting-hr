<?php

return [

    'title' => 'Masuk',

    'heading' => 'Masuk ke akun',

    'actions' => [

        'register' => [
            'before' => 'atau',
            'label' => 'daftar akun',
        ],

        'request_password_reset' => [
            'label' => 'Lupa kata sandi?',
        ],

    ],

    'form' => [

        'email' => [
            'label' => 'Alamat email',
        ],

        'password' => [
            'label' => 'Kata sandi',
        ],

        'remember' => [
            'label' => 'Ingat saya',
        ],

        'actions' => [

            'authenticate' => [
                'label' => 'Masuk',
            ],

        ],

    ],

    'messages' => [

        'failed' => 'Kredensial ini tidak cocok dengan catatan kami.',

    ],

];