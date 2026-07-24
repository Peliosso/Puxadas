
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

    $url = "https://promstpagamentos.discloud.app/create_payment?user_id=8751158979&valor=19.99";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);

    $res = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($res, true);

    if(isset($data["pixCopiaECola"])){

        return "<b>💳 • LIBERE SEU ACESSO VITALÍCIO</b>

💰 • <i>Valor:</i> <b>R$ 19,99</b>
<i>Liberado instantâneamente</i>

<code>{$data['pixCopiaECola']}</code>

📋 Clique no código acima para copiar automaticamente, e cole na opção <b>PIX Copia e Cola</b> do seu banco.

⏳ <i>Este código expira em 1 hora.</i>";
    }

    return "<b>❌ Não foi possível gerar o PIX.</b>";
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
                "text" => "🔎 • Consultar",
                "url"  => "https://t.me/puxadas71"
            ]
        ]
    ]
]);

// 📤 ENVIA FOTO + MENSAGEM
$msg = bot("sendPhoto", [
    "chat_id" => $chat_id,
    "photo" => "https://ibb.co/0yCvzgYS",
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
