<?php

ignore_user_abort(true);
set_time_limit(30);

// ===== CONFIG =====
$TOKEN = "8669340911:AAHgt35G_2PN_uFJV1xfSpjgxjaIrbsbx3I";
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

"🚨 <b>CONSULTAS LIBERADAS (LIMITADO)</b>

━━━━━━━━━━━━━━━
🔎 <b>Disponível agora:</b>

• Telefone  
• CPF  
• Nome completo  
• RG  
• Placa de veículo  
• Endereço  
• Parentes e vínculos  

━━━━━━━━━━━━━━━
💎 <b>VIP destrava tudo sem limite</b>

⚠️ Quem não ativa, fica travado.

👇 Toque abaixo antes que bloqueie:",

"🔒 <b>ACESSO RESTRITO</b>

Você tentou acessar dados completos…
mas está bloqueado.

━━━━━━━━━━━━━━━
📊 <b>Consultas disponíveis:</b>

✔ Telefone  
✔ CPF  
✔ Nome  
✔ RG  
✔ Placa  
✔ Dados completos  

━━━━━━━━━━━━━━━
💎 VIP = acesso total + ilimitado

⏳ Liberação por tempo limitado

👇 Ative agora:",

"💰 <b>DADOS REAIS NÃO SÃO PÚBLICOS</b>

━━━━━━━━━━━━━━━
📊 Aqui você consegue:

• CPF e RG  
• Telefones ocultos  
• Placas e veículos  
• Endereços completos  
• Histórico e vínculos  

━━━━━━━━━━━━━━━
💎 VIP libera tudo em segundos

🔥 Últimas ativações disponíveis

👇 Garanta seu acesso:"

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

    // 💾 SALVA ID
    if(isset($msg['result']['message_id'])){
        $controle[$chat_id] = $msg['result']['message_id'];
        saveControle($controle);
    }

    // ⏱️ delay anti-spam
    sleep(rand(2,5));
}

echo "OK";