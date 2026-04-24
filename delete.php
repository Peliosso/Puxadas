<?php

$TOKEN = "8669340911:AAHgt35G_2PN_uFJV1xfSpjgxjaIrbsbx3I";
$API   = "https://api.telegram.org/bot{$TOKEN}";

$chat_id = $argv[1];
$message_id = $argv[2];

// delay
sleep(1);

// deletar
file_get_contents($API . "/deleteMessage?chat_id={$chat_id}&message_id={$message_id}");