<?php

function sendMessage($chat_id, $text, $keyboard = null) {
    $data = [
        'chat_id' => $chat_id,
        'text' => $text,
        'parse_mode' => 'HTML'
    ];

    if ($keyboard) {
        $data['reply_markup'] = json_encode($keyboard);
    }

    file_get_contents(API_URL."sendMessage?".http_build_query($data));
}

function userIsPremium($id) {
    $users = json_decode(file_get_contents("users.json"), true);
    return isset($users[$id]) && $users[$id]['premium'] === true;
}

function setPremium($id) {
    $users = json_decode(file_get_contents("users.json"), true);
    $users[$id]['premium'] = true;
    file_put_contents("users.json", json_encode($users));
}