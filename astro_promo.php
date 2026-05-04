<?php

ignore_user_abort(true);
set_time_limit(30);

// ===== CONFIG =====
$TOKEN = "8669340911:AAHgt35G_2PN_uFJV1xfSpjgxjaIrbsbx3I";
$API   = "https://api.telegram.org/bot{$TOKEN}";

// ===== FUNÇÃO =====
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

// ===== MENSAGENS =====
function mensagemPromo(){

    $msgs = [

"💎 <b>LIBERE O ACESSO VIP</b>

━━━━━━━━━━━━━━━
🔎 Consultas completas  
📊 Dados atualizados  
⚡ Respostas instantâneas  

━━━━━━━━━━━━━━━
💰 <b>Planos:</b>

• Diário: R$14,90  
• Semanal: R$24,90  
• Vitalício: R$20,90  

🚀 Ative agora: /plano",

"🔒 <b>VOCÊ ESTÁ PERDENDO INFORMAÇÕES</b>

━━━━━━━━━━━━━━━
💎 VIP libera:

• Telefones  
• Endereços  
• Dados completos  

━━━━━━━━━━━━━━━
🔥 Acesso total sem limites

👉 Use /plano",

"🚀 <b>MAIS DE 100 CONSULTAS HOJE</b>

━━━━━━━━━━━━━━━
💎 Não fique de fora

Tenha acesso completo agora  
Sem limites e com prioridade  

━━━━━━━━━━━━━━━
👉 /plano"

    ];

    return $msgs[array_rand($msgs)];
}

// ===== FIXAÇÃO =====
function podeFixar(){
    return rand(1, 10) <= 3;
}

// ===== EXECUÇÃO =====
$grupos = getGrupos();

foreach($grupos as $chat_id => $v){

    $msg = bot("sendMessage", [
        "chat_id" => $chat_id,
        "text" => mensagemPromo(),
        "parse_mode" => "HTML"
    ]);

    // delay pra não parecer spam
    sleep(rand(2,5));

    if(isset($msg['result']['message_id']) && podeFixar()){
        bot("pinChatMessage", [
            "chat_id" => $chat_id,
            "message_id" => $msg['result']['message_id']
        ]);
    }
}

echo "OK";