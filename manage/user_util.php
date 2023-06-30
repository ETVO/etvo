<?php

define('USERS_STORAGE', './system/users.json');

function register_user($user_data)
{
    if (isset($user_data['form']))
        unset($user_data['form']);

    if (!isset($user_data['username']) || !isset($user_data['password']))
        return [false, 'Both fields are required'];

    $username = filter_var(trim($user_data['username']));
    $raw_password = filter_var(trim($user_data['password']));
    $encrypted_password = password_hash($raw_password, PASSWORD_DEFAULT);

    $stored_users = json_decode(file_get_contents(USERS_STORAGE), true) ?? [];

    $user_data['username'] = $username;
    $user_data['password'] = $encrypted_password;

    $user_data['created_at'] = date('Y-m-d');
    $user_data['updated_at'] = date('Y-m-d');
    $user_data['active'] = true;

    $new_user = $user_data;

    // early birds get the worms

    if (empty($username) || empty($raw_password))
        return [false, 'Both fields are required'];

    if (username_exists($username, $stored_users))
        return [false, 'Username is taken'];

    // push to array, put to file
    array_push($stored_users, $new_user);
    if (file_put_contents(USERS_STORAGE, json_encode($stored_users))) {
        return [true, 'User registered successfully'];
    } else {
        return [false, 'Something went wrong. Please try again later.'];
    }
}

function username_exists($username, $stored_users)
{
    if ($stored_users)
        foreach ($stored_users as $user) {
            if ($username == $user['username']) return true;
        }
    return false;
}
