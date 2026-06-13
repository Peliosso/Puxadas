
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
$update = json_decode(file_get_contents("php://input"), true);

if(isset($update["callback_query"])){

    $call = $update["callback_query"];

    $chat_id = $call["message"]["chat"]["id"];
    $message_id = $call["message"]["message_id"];
    $data = $call["data"];

    bot("answerCallbackQuery", [
        "callback_query_id" => $call["id"]
    ]);

    // MENU DE RECURSOS
    if($data == "consultas"){

        $keyboard = json_encode([
            "inline_keyboard" => [
                [
                    ["text"=>"👤 Nome","callback_data"=>"nome"]
                ],
                [
                    ["text"=>"📞 Telefone","callback_data"=>"telefone"]
                ],
                [
                    ["text"=>"🚗 Placa","callback_data"=>"placa"]
                ],
                [
                    ["text"=>"🏢 CNPJ","callback_data"=>"cnpj"]
                ],
                [
                    ["text"=>"📮 CEP","callback_data"=>"cep"]
                ],
                [
                    ["text"=>"↩️ Voltar","callback_data"=>"inicio"]
                ]
            ]
        ]);

        bot("editMessageText",[
            "chat_id"=>$chat_id,
            "message_id"=>$message_id,
            "parse_mode"=>"HTML",
            "text"=>"📋 <b>Recursos disponíveis</b>\n\nSelecione um item abaixo para ver um exemplo de uso.",
            "reply_markup"=>$keyboard
        ]);
    }

    // EXEMPLO NOME
    if($data == "nome"){

        $keyboard = json_encode([
            "inline_keyboard"=>[
                [
                    ["text"=>"↩️ Voltar","callback_data"=>"consultas"]
                ]
            ]
        ]);

        bot("editMessageText",[
            "chat_id"=>$chat_id,
            "message_id"=>$message_id,
            "parse_mode"=>"HTML",
            "text"=>"👤 <b>Consulta por Nome</b>\n\nExemplo:\n<code>/nome joao silva</code>",
            "reply_markup"=>$keyboard
        ]);
    }

    // EXEMPLO TELEFONE
    if($data == "telefone"){

        $keyboard = json_encode([
            "inline_keyboard"=>[
                [
                    ["text"=>"↩️ Voltar","callback_data"=>"consultas"]
                ]
            ]
        ]);

        bot("editMessageText",[
            "chat_id"=>$chat_id,
            "message_id"=>$message_id,
            "parse_mode"=>"HTML",
            "text"=>"📞 <b>Consulta por Telefone</b>\n\nExemplo:\n<code>/telefone 31999999999</code>",
            "reply_markup"=>$keyboard
        ]);
    }

    // EXEMPLO PLACA
    if($data == "placa"){

        $keyboard = json_encode([
            "inline_keyboard"=>[
                [
                    ["text"=>"↩️ Voltar","callback_data"=>"consultas"]
                ]
            ]
        ]);

        bot("editMessageText",[
            "chat_id"=>$chat_id,
            "message_id"=>$message_id,
            "parse_mode"=>"HTML",
            "text"=>"🚗 <b>Consulta por Placa</b>\n\nExemplo:\n<code>/placa ABC1234</code>",
            "reply_markup"=>$keyboard
        ]);
    }

    // VOLTAR AO MENU INICIAL
    if($data == "inicio"){

        $keyboard = json_encode([
            "inline_keyboard"=>[
                [
                    ["text"=>"💎 ADQUIRIR VIP","callback_data"=>"vip"]
                ],
                [
                    ["text"=>"📋 RECURSOS DISPONÍVEIS","callback_data"=>"consultas"]
                ]
            ]
        ]);

        bot("editMessageText",[
            "chat_id"=>$chat_id,
            "message_id"=>$message_id,
            "parse_mode"=>"HTML",
            "text"=>"🎉 MAIS UM CLIENTE ACABOU DE ATIVAR O VIP VITALÍCIO!\n\n👤 ID: 6***8***2\n\n💎 Benefícios exclusivos liberados para assinantes.",
            "reply_markup"=>$keyboard
        ]);
    }

    exit;
}
    
// ===== EXECUÇÃO =====    
$grupos = getGrupos();    
$controle = getControle();    
    
foreach($grupos as $chat_id => $v){    
    
    // 🧹 APAGA MSG ANTERIOR    
    if(isset($controle[$chat_id])){    
        bot("deleteMessage", [    
            "chat_id" => $chat_id,    
            "message_id" => $controle[$chat_id]    
        ]);    
    }    
    
    // 🔘 BOTÃO INLINE    
    $keyboard = json_encode([
    "inline_keyboard"=>[
        [
            ["text"=>"💎 ADQUIRIR VIP","callback_data"=>"vip"]
        ],
        [
            ["text"=>"📋 RECURSOS DISPONÍVEIS","callback_data"=>"consultas"]
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
