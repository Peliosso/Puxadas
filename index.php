<?php
require "config.php";
require "functions.php";

$update = json_decode(file_get_contents("php://input"), true);

$chat_id = $update['message']['chat']['id'] ?? $update['callback_query']['message']['chat']['id'];
$text = $update['message']['text'] ?? null;
$callback = $update['callback_query']['data'] ?? null;

if ($text == "/start") {
    sendMessage($chat_id,
"🔍 <b>CONSULTAS PREMIUM</b>

Tenha acesso às consultas mais completas do mercado.

💎 Plano vitalício
⚡ Resultados rápidos
🔐 Interface segura",
    [
        'inline_keyboard' => [
            [['text'=>"📋 MENU DE CONSULTAS",'callback_data'=>"menu"]],
            [['text'=>"💳 COMPRAR ACESSO",'callback_data'=>"comprar"]],
            [['text'=>"📞 SUPORTE",'url'=>"https://t.me/".SUPORTE]]
        ]
    ]);
}

if ($callback == "menu") {

    if (!userIsPremium($chat_id)) {
        sendMessage($chat_id,"🚫 <b>Acesso restrito</b>\n\nAdquira o plano vitalício para desbloquear.",[
            'inline_keyboard'=>[
                [['text'=>"💳 COMPRAR AGORA",'callback_data'=>"comprar"]]
            ]
        ]);
        exit;
    }

    sendMessage($chat_id,
"📂 <b>MENU DE CONSULTAS</b>

Escolha o tipo de consulta:",
    [
        'inline_keyboard'=>[
            [['text'=>"👤 Consulta por NOME",'callback_data'=>"nome"]],
            [['text'=>"📞 Consulta por TELEFONE",'callback_data'=>"telefone"]],
            [['text'=>"🪪 Consulta por CPF",'callback_data'=>"cpf"]],
            [['text'=>"🚗 Consulta por PLACA",'callback_data'=>"placa"]],
            [['text'=>"🆔 Consulta por RG",'callback_data'=>"rg"]],
            [['text'=>"📧 Consulta por EMAIL",'callback_data'=>"email"]],
            [['text'=>"🌎 Endereço / CEP",'callback_data'=>"endereco"]],
            [['text'=>"⬅️ Voltar",'callback_data'=>"start"]]
        ]
    ]);
}

if ($callback == "comprar") {
    sendMessage($chat_id,
"💳 <b>PLANO VITALÍCIO</b>

✅ Acesso total a todas consultas  
✅ Atualizações futuras  
✅ Pagamento único  

💰 Valor: <b>R$ ".VALOR_VITALICIO."</b>

📌 PIX: <code>".PIX_KEY."</code>

Após pagar, clique abaixo:",
[
    'inline_keyboard'=>[
        [['text'=>"✅ JÁ PAGUEI",'callback_data'=>"confirmar"]],
        [['text'=>"📞 FALAR COM SUPORTE",'url'=>"https://t.me/".SUPORTE]]
    ]
]);
}

if ($callback == "confirmar") {
    sendMessage($chat_id,
"🕒 Pagamento em análise…

Assim que confirmado, seu acesso será liberado.");
}