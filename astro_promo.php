
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
function gerarUsuario(){
    $inicio = str_pad(rand(1000, 9999), 4, "0", STR_PAD_LEFT);
    $fim    = str_pad(rand(10, 99), 2, "0", STR_PAD_LEFT);

    return "{$inicio}****{$fim}";
}

function mensagemPromo(){

    $usuario = gerarUsuario();

 $msgs = [
"🎉 <b>Mais um cliente garantiu o sistema exclusivo!</b>

💎 <b>Consultas disponíveis:</b>

📄 CPF • 👤 Nome • 📞 Telefones • 🆔 RG
🏠 Endereços • 👨‍👩‍👧 Parentes • 🚗 Placas
🏢 CNPJ • 💳 Bancos • 💼 Empregos
✨ E muitos outros módulos.

<b>ACESSO ILIMITADO: R$20,00</b>

💳 • <b>Chave PIX para liberação: </b>
<code>167a9ccb-7bd2-4441-bccd-79f00ac05210</code>

🗒️ • <b>Clique na chave para copiar.</b>

⚡ • <b>Envie o comprovante e receba seu acesso em instantes.</b>

👇 • <b>Escolha seu plano abaixo:</b>"
];  
  
    return $msgs[array_rand($msgs)];  
}  
    

// ===== EXECUÇÃO =====    
$grupos = getGrupos();    
$controle = getControle();    
    
foreach($grupos as $chat_id => $v){    
    
    // 🔘 BOTÃO INLINE    
    $keyboard = json_encode([
    "inline_keyboard" => [
        [
            [
                "text" => "📩 Enviar Comprovante",
                "url"  => "https://t.me/puxadas71"
            ]
        ]
    ]
]);

// 📤 ENVIA FOTO + MENSAGEM
$msg = bot("sendPhoto", [
    "chat_id" => $chat_id,
    "photo" => "https://kommodo.ai/i/25oxjZ6nOdB5b3S4nDvr",
    "caption" => mensagemPromo(),
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
