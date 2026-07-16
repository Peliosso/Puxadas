<?php

$TOKEN = "8773656360:AAET6l38pzvUcW7goTlUn_VEMU1_JMiVEM";
$API   = "https://api.telegram.org/bot{$TOKEN}";

$chat_id = $argv[1];
$message_id = $argv[2];

// delay
sleep(1);

// deletar
file_get_contents($API . "/deleteMessage?chat_id={$chat_id}&message_id={$message_id}");
