<?php

$TOKEN = "SEU_TOKEN_AQUI";
$API   = "https://api.telegram.org/bot{$TOKEN}";

$chat_id = $argv[1];
$message_id = $argv[2];

// delay
sleep(10);

// deletar
file_get_contents($API . "/deleteMessage?chat_id={$chat_id}&message_id={$message_id}");