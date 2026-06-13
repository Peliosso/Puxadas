
<?php    
    
ignore_user_abort(true);    
set_time_limit(30);    
    
// ===== CONFIG =====    
$TOKEN = "8773656360:AAET6l38pzvUcW7goTlUn_VEMU1_JMiVEM8";    
$API   = "https://api.telegram.org/bot{$TOKEN}";    
    
// ===== REQUEST =====    
function bot($method, $data){    
    global $API;    
    
    $ch = curl_init($API."/".$method);    
    curl_setopt_array($ch, [    
        CURLOPT_RETURNTRANSFER => true,    
        CURLOPT_POST => true,    
        CURLOPT_POSTFIELDS => $data    
    ]);    
    
    $res = curl_exec($ch);    
    
    if($res === false){    
        die(curl_error($ch));    
    }    
    
    curl_close($ch);    
    
    return json_decode($res, true);    
}    
    
// ===== GRUPOS =====    
function getGrupos(){    
    if(!file_exists("grupos.json")){    
        file_put_contents("grupos.json", json_encode([]));    
    }    
    
    $data = json_decode(file_get_contents("grupos.json"), true);    
    return is_array($data) ? $data : [];    
}    
    
// ===== CONTROLE (APAGAR MSG ANTIGA) =====    
function getControle(){    
    if(!file_exists("controle.json")){    
        file_put_contents("controle.json", json_encode([]));    
    }    
    return json_decode(file_get_contents("controle.json"), true);    
}    
    
function saveControle($data){    
    file_put_contents("controle.json", json_encode($data, JSON_PRETTY_PRINT));    
}    
    
// ===== COPY PESADA =====    
function mensagemPromo(){    
    
    $msgs = [    
    
"✅ <b>VIP ATIVADO COM SUCESSO</b>

👤 Usuário: <code>5766****79</code>

💎 Agora possui acesso ilimitado a:

• CPF
• Nome
• Telefones
• RG
• Placas
• Endereços
• Parentes
• CNPJ
• E muito mais

⚡ Liberação instantânea e sem limites.

👇 Ative seu acesso abaixo."
    
    ];    
    
    return $msgs[array_rand($msgs)];    
}    
    
// ===== EXECUÇÃO =====    
$grupos = getGrupos();    
$controle = getControle();    
    
foreach($grupos as $chat_id => $v){    
    
    // 🔘 BOTÃO INLINE    
    $keyboard = json_encode([    
        "inline_keyboard"=>[    
            [    
                ["text"=>"💎 ATIVAR VIP AGORA","callback_data"=>"planos"]    
            ]    
        ]    
    ]);    
    
// 📤 ENVIA MSG    
$msg = bot("sendMessage", [    
    "chat_id" => $chat_id,    
    "text" => mensagemPromo(),    
    "parse_mode" => "HTML",    
    "reply_markup" => $keyboard    
]);    
    
// 💾 SALVA ID + 📌 FIXA    
if(isset($msg['result']['message_id'])){    
    
    $message_id = $msg['result']['message_id'];    
    
    // salva controle    
    $controle[$chat_id] = $message_id;    
    saveControle($controle);    
    
    // 📌 fixa mensagem nova    
    bot("pinChatMessage", [    
        "chat_id" => $chat_id,    
        "message_id" => $message_id,    
        "disable_notification" => false    
    ]);    
}    
    
    // ⏱️ delay anti-spam    
    sleep(rand(2,5));    
}    
    
echo "OK";    
