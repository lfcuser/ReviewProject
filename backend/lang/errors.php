<?php

return [
    'auth' => [
        'bad_credentials' => 'Wrong login or password',
        'bad_route_id' => 'Wrong route',
    ],
    'user' => [
        'email_already_registered' => 'User with email ":email" already exist',
        'login_already_registered' => 'User with login ":login" already exist',
        'id_already_exists' => 'User with id::id already exist',
    ],
    'common_validation' => [
        'required' => 'Field is required',
        'type' => 'Wrong type for :field',
        'enum' => 'Value must be one of: :enum',
    ],
];
