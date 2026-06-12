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

$update = json_decode(file_get_contents("php://input"), true);

if(isset($update["callback_query"])){

    $callback = $update["callback_query"];

    $data = $callback["data"];
    $chat_id = $callback["message"]["chat"]["id"];
    $message_id = $callback["message"]["message_id"];

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

function gerarIdFake(){

$inicio = rand(10,99);
$fim = rand(1000,9999);

return $inicio . "***" . $fim;
}

function mensagemPromo(){

    $id = gerarIdFake();

    $msgs = [

"💎 <b>MAIS UM CLIENTE ACABOU DE ADQUIRIR O VIP VITALÍCIO</b>

━━━━━━━━━━━━━━━

👤 <b>ID:</b> <code>{$id}</code>

✅ Acesso liberado com sucesso
✅ Consultas ilimitadas
✅ Sem mensalidades
✅ Liberação instantânea

━━━━━━━━━━━━━━━

🔎 <b>CONSULTAS DISPONÍVEIS</b>

• Nome Completo
• Telefone
• CPF
• RG
• Placa de Veículo
• Endereços
• Vizinhos
• Parentes
• Compras
• Óbito
• E muito mais...

━━━━━━━━━━━━━━━

🔥 <b>Centenas de usuários já utilizam o VIP diariamente.</b>

⏳ Aproveite enquanto as vagas promocionais permanecem disponíveis.

👇 Escolha uma opção abaixo:"

    ];

    return $msgs[array_rand($msgs)];
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

$keyboard = json_encode([
    "inline_keyboard" => [
        [
            ["text"=>"💎 Adquirir VIP Vitalício","callback_data"=>"vip"]
        ],
        [
            ["text"=>"📋 Consultas Disponíveis","callback_data"=>"consultas"]
        ]
    ]
]);

if($data == "consultas"){

$texto = "📋 <b>CONSULTAS DISPONÍVEIS</b>

━━━━━━━━━━━━━━━

🔎 Nome Completo
📞 Telefone
🆔 CPF
📄 RG
🚗 Placa
🏠 Endereços
👨 Parentes
👩 Nome da Mãe
👨 Nome do Pai
📍 CEP Completo
🏢 CNPJ
🛒 Compras
⚰️ Óbito
🏘️ Vizinhos

✨ E diversas outras consultas.

👇 Clique em voltar para retornar.";

$voltar = json_encode([
"inline_keyboard"=>[
[
["text"=>"⬅️ Voltar","callback_data"=>"inicio"]
]
]
]);

bot("editMessageText",[
"chat_id"=>$chat_id,
"message_id"=>$message_id,
"text"=>$texto,
"parse_mode"=>"HTML",
"reply_markup"=>$voltar
]);
}

if($data == "inicio"){

bot("editMessageText",[
"chat_id"=>$chat_id,
"message_id"=>$message_id,
"text"=>mensagemPromo(),
"parse_mode"=>"HTML",
"reply_markup"=>$keyboard
]);
}

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
