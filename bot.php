<?php
error_reporting(0);

ignore_user_abort(true);
set_time_limit(0);

header("Content-Type: application/json");
http_response_code(200);

/* LER UPDATE UMA VEZ */
$update = json_decode(file_get_contents("php://input"), true);

echo json_encode(["status"=>"ok"]);
flush();

/* ================= CONFIG ================= */

$TOKEN = "8241553232:AAGvxGZhHWJkAzKxQ-RsE-Efvy-e4q2XI4U";
$API   = "https://api.telegram.org/bot{$TOKEN}";

/* IMAGEM */
$START_PHOTO = "https://conventional-magenta-fxkyikrbqe.edgeone.app/E8D6A8B8-36F3-4AE0-8493-E2C66DF18EF3.png";

/* PIX */
$PIX_VALOR = "15,00";
$PIX_CHAVE = "f0d0f3b1-8776-4f06-a254-b6ea3686f71a";
$PIX_NOME  = "Gabriel Lorenzo";
$STICKER_LOADING = "CAACAgIAAxkBAAEQUkBpdQ4VdCPwAybo7q4AAVMxYnM6HzYAAhYMAAL5LuBLduZ5vHwXjSs4BA";

/* ================= VIP ================= */

$VIP_IDS = [
    1361607036,
    8564990473,
    1161783839,
    7255909074,
    712236564,
    7889761800,
    7786060568,
    1063818612,
    7867668582,
    5554645779,
    8266037917,
    7320236887,
    6489208490,
    7020349353,
    6928322231,
    5187490736,
    5921111452,
    6930409353,
    5790846274,
    1712166945,
    8521260864,
    1994291418,
    1235007779,
    7186704287,
    8603729320,
    800334600,
    5210915723,
    8462802710,
    1638276225,
    8084660461,
    6789391469,
    8622022224,
    8357677967,
    7290941537,
    7997922326,
    892378689,
    5355252481,
    7518850652,
    6050403465,
    6217042464,
    853186865,
    5993467951,
    7383802170,
    5342332792,
    7442715942,
    6818620184,
    1433645975,
    747379594,
    6477680249,
    5622054961,
    8456030622,
    1607161326,
    452064849,
    5622054961,
    5013029518,
    1876293467,
    8001440296,
    6959171263,
    5980127921,
    7429472228,
    8726631098,
    8351384627,
    1142303962,
    1629128956,
    8250679728,
    8250679728,
    7909126600,
    8498054372,
    7810953110,
    7924344631,
    1086246375,
    8309435127,
    7466334994,
    8353617567,
    7524424065,
    940636198, 
    8538480916,
    6133561216,
    6687664198,
    5805915267,
    1236474129,
];

$BANIDOS = [
    8017850151
];

function isVip($id){
    global $VIP_IDS;
    return in_array($id, $VIP_IDS);
}

function isBanned($id){
    global $BANIDOS;
    return in_array($id, $BANIDOS);
}

/* ================= FREE MODE GRUPOS ================= */

define("FREE_DB","free_groups.json");

function ativarFreeGrupo($chat){

    $data = [];

    if(file_exists(FREE_DB)){
        $data = json_decode(file_get_contents(FREE_DB), true);
    }

    $data[$chat] = time() + (60*60*24);

    file_put_contents(FREE_DB, json_encode($data));
}

function isFreeGroup($chat){

    if(!file_exists(FREE_DB)){
        return false;
    }

    $data = json_decode(file_get_contents(FREE_DB), true);

    if(!isset($data[$chat])){
        return false;
    }

    if(time() > $data[$chat]){

        unset($data[$chat]);
        file_put_contents(FREE_DB, json_encode($data));

        return false;
    }

    return true;
}

/* ================= UPDATE ================= */

$message  = $update["message"] ?? null;
$callback = $update["callback_query"] ?? null;
$msgId = $message["message_id"] ?? null;
$chat  = $message["chat"]["id"] ?? null;

/* ====== BLOQUEIO GLOBAL ====== */

$userId = $message["from"]["id"] ?? $callback["from"]["id"] ?? null;

if($userId && isBanned($userId)){

    if($message){
        tg("sendMessage",[
            "chat_id"=>$message["chat"]["id"],
            "text"=>"⛔️ Você está banido de usar este bot."
        ]);
    }

    if($callback){
        tg("answerCallbackQuery",[
            "callback_query_id"=>$callback["id"],
            "text"=>"⛔️ Você está banido.",
            "show_alert"=>true
        ]);
    }

    exit;
}
/* ================= API ================= */

function tg($method, $data){
    global $API;
    $ch = curl_init($API."/".$method);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $data
    ]);
    $res = curl_exec($ch);
    curl_close($ch);
    return $res;
}

function answer($id){
    tg("answerCallbackQuery", ["callback_query_id"=>$id]);
}

/* ================= TUTORIAL / BLOQUEIO ================= */

function tutorial($chat,$cmd){

    $map = [

        // 🔒 VIP
        "/cpf"         => "12345678900",
        "/nome"        => "João Silva",
        "/vizinhos"     => "12345678900",
        "/rg"          => "1234567",
        "/cnh"         => "12345678900",
        "/telefone"    => "11999999999",
        "/email"       => "teste@email.com",
        "/placa"       => "ABC1D23",
        "/pix"         => "email@pix.com",
        "/renavam"     => "123456789",
        "/nascimento"  => "01012000",
        "/foto"        => "",

        // ♻️ GRÁTIS
        "/cep"  => "01001000",
        "/cnpj" => "00000000000100",
        "/ip"   => "8.8.8.8",
    ];

    $exemplo = $map[$cmd] ?? "123456";

    $texto =
"📘 <b>Como usar</b>

<b>{$cmd}</b>
Exemplo:
<code>{$cmd}".($exemplo ? " {$exemplo}" : "")."</code>";

    tg("sendMessage",[
        "chat_id"=>$chat,
        "text"=>$texto,
        "parse_mode"=>"HTML"
    ]);
}
function bloquearConsulta($chat){
    global $START_PHOTO, $STICKER_LOADING, $PIX_CHAVE, $PIX_NOME, $PIX_VALOR;

    // 🎬 Sticker
    $sticker = tg("sendSticker",[
        "chat_id"=>$chat,
        "sticker"=>$STICKER_LOADING
    ]);

    $stickerData = json_decode($sticker, true);
    $stickerMsgId = $stickerData["result"]["message_id"] ?? null;

    // ⏳ Delay real de 6s com "digitando..."
    for($i=0;$i<6;$i++){
        tg("sendChatAction",[
            "chat_id"=>$chat,
            "action"=>"typing"
        ]);
        sleep(1);
    }

    // 👥 Prova social
    $usuarios = rand(200,400);

    // 🚀 Mensagem
    tg("sendPhoto",[
        "chat_id"=>$chat,
        "photo"=>$START_PHOTO,
        "caption"=>
"⚡ • <b>RESULTADO ENCONTRADO!</b>

Mas calma…

Seu plano gratuito não tem permissão para ver
esse tipo de consulta.

⭐ <b>Ative o VIP e tenha acesso imediato.</b>

━━━━━━━━
👑 <b>{$usuarios} usuários VIP ativos</b>

💎 <b>Vantagens do plano:</b>

✔️ Consultas ilimitadas
✔️ Sem mensalidade
✔️ Acesso a todas as bases
✔️ Liberação instantânea
✔️ Suporte prioritário

━━━━━━━━
💰 <b>VALOR VITALÍCIO:</b> R$ {$PIX_VALOR}

🔑 • <b>Chave PIX:</b>
<code>{$PIX_CHAVE}</code>
👤 • <b>Nome:</b> {$PIX_NOME}

👇 Copie a chave e realize o pagamento:",
        "parse_mode"=>"HTML",
        "reply_markup"=>json_encode([
            "inline_keyboard"=>[
                [["text"=>"📋 COPIAR CHAVE PIX","callback_data"=>"copiar_pix"]],
                [["text"=>"🚀 ATIVAR VIP AGORA","url"=>"https://t.me/puxardados5"]]
            ]
        ])
    ]);

    // 🗑 Apaga o sticker depois
    if($stickerMsgId){
        tg("deleteMessage",[
            "chat_id"=>$chat,
            "message_id"=>$stickerMsgId
        ]);
    }
}


/* ================= MENU ================= */

function menuPrincipal($chat,$nome,$id,$edit=false,$msg=null){
    global $START_PHOTO;

$caption =
"<b>🚀 • Astro Search</b>

Olá, <a href=\"tg://user?id={$id}\"><b>{$nome}</b></a>
🆔 <code>{$id}</code>

Escolha uma opção abaixo:";

$kb = [
"inline_keyboard"=>[
[
["text"=>"📂 Consultas","callback_data"=>"catalogo_1"],
["text"=>"👤 Minha conta","callback_data"=>"conta"]
],
[
["text"=>"⭐ Planos","callback_data"=>"planos"],
["text"=>"🛠 Suporte","url"=>"https://t.me/puxardados5"]
],
[
["text"=>"📢 Canal Oficial","url"=>"https://t.me/astrosearch"]
]
]
];

if($edit){

    tg("editMessageCaption",[
        "chat_id"=>$chat,
        "message_id"=>$msg,
        "caption"=>$caption,
        "parse_mode"=>"HTML",
        "reply_markup"=>json_encode($kb)
    ]);

}else{

    tg("sendPhoto",[
        "chat_id"=>$chat,
        "photo"=>$START_PHOTO,
        "caption"=>$caption,
        "parse_mode"=>"HTML",
        "reply_markup"=>json_encode($kb)
    ]);

}
}

/* ================= CATÁLOGOS ================= */

function catalogo1($chat,$msg){

tg("editMessageCaption",[
"chat_id"=>$chat,
"message_id"=>$msg,
"caption"=>
"🚀 <b>CONSULTAS — 1/2</b>

🔱 <b>VIP</b>

/parentes - 🆕
/vizinhos - 🆕
/foto - 🆕
/fotorj - 🆕
/fotosp - 🆕
/cpf2 - 🆕
/cpf1 - 🆕
/cpf
/nome
/rg
/cnh
/telefone
/email
/placa
/pix
/nascimento
/renavam",
"parse_mode"=>"HTML",
"reply_markup"=>json_encode([
"inline_keyboard"=>[
[["text"=>"➡️ Próxima","callback_data"=>"catalogo_2"]],
[["text"=>"🔒 Ativar Plano","callback_data"=>"planos"]],
[["text"=>"⬅️ Menu","callback_data"=>"voltar_menu"]],
]
])
]);

}

function catalogo2($chat,$msg){

tg("editMessageCaption",[
"chat_id"=>$chat,
"message_id"=>$msg,
"caption"=>
"🚀 <b>CONSULTAS — 2/2</b>

♻️ <b>GRÁTIS</b>

/cep
/cnpj
/ip",
"parse_mode"=>"HTML",
"reply_markup"=>json_encode([
"inline_keyboard"=>[
[["text"=>"⬅️ Anterior","callback_data"=>"catalogo_1"]],
[["text"=>"⬅️ Menu","callback_data"=>"voltar_menu"]],
]
])
]);

}

function resultadoConsulta($chat,$titulo,$conteudo,$prefixo){

$hash = md5($conteudo.time());
$file = "cache_{$prefixo}_{$hash}.txt";

file_put_contents($file,$conteudo);

tg("sendMessage",[
"chat_id"=>$chat,
"text"=>"✅ <b>{$titulo} concluída</b>

Escolha o formato do resultado:",
"parse_mode"=>"HTML",
"reply_markup"=>json_encode([
"inline_keyboard"=>[
[
["text"=>"📄 Mostrar no Telegram","callback_data"=>"ver|$file"]
],
[
["text"=>"📁 Enviar TXT","callback_data"=>"txt|$file"]
],
[
["text"=>"🗑 Apagar mensagem","callback_data"=>"apagar_msg"]
],
[
["text"=>"🚀 Adquirir Bot","url"=>"https://t.me/puxardados5"]
]
]
])
]);

}
 

function consultaCNPJ($chat, $cnpj){
    global $STICKER_LOADING;

    // Envia sticker carregando
    $sticker = tg("sendSticker",[
        "chat_id"=>$chat,
        "sticker"=>$STICKER_LOADING
    ]);

    $stickerData = json_decode($sticker, true);
    $stickerMsgId = $stickerData["result"]["message_id"] ?? null;

    // Limpa CNPJ
    $cnpj = preg_replace('/\D/','',$cnpj);

    if(strlen($cnpj) !== 14){
        if($stickerMsgId){
            tg("deleteMessage",[
                "chat_id"=>$chat,
                "message_id"=>$stickerMsgId
            ]);
        }

        tg("sendMessage",[
            "chat_id"=>$chat,
            "text"=>"❌ CNPJ inválido.\nUse: <code>/cnpj 00000000000100</code>",
            "parse_mode"=>"HTML"
        ]);
        return;
    }

    // Consulta BrasilAPI (GRÁTIS)
    $resp = @file_get_contents("https://brasilapi.com.br/api/cnpj/v1/{$cnpj}");
    $data = json_decode($resp, true);

    if(!$data || isset($data["message"])){
        if($stickerMsgId){
            tg("deleteMessage",[
                "chat_id"=>$chat,
                "message_id"=>$stickerMsgId
            ]);
        }

        tg("sendMessage",[
            "chat_id"=>$chat,
            "text"=>"❌ CNPJ não encontrado."
        ]);
        return;
    }

    // Apaga sticker após sucesso
    if($stickerMsgId){
        tg("deleteMessage",[
            "chat_id"=>$chat,
            "message_id"=>$stickerMsgId
        ]);
    }

    // Conteúdo TXT
    $txt =
"CONSULTA DE CNPJ — ASTRO SEARCH
================================

CNPJ: {$data["cnpj"]}
Razão Social: {$data["razao_social"]}
Nome Fantasia: {$data["nome_fantasia"]}

Situação: {$data["descricao_situacao_cadastral"]}
Abertura: {$data["data_inicio_atividade"]}

Atividade Principal:
{$data["cnae_fiscal_descricao"]}

Endereço:
Logradouro: {$data["logradouro"]}, {$data["numero"]}
Bairro: {$data["bairro"]}
Cidade: {$data["municipio"]} - {$data["uf"]}
CEP: {$data["cep"]}

Telefone: {$data["ddd_telefone_1"]}
Email: {$data["email"]}

--------------------------------
Consulta gratuita
Fonte: BrasilAPI
Créditos: Astro Search
";

    // Cria arquivo TXT
    $file = tempnam(sys_get_temp_dir(), "cnpj_");
    file_put_contents($file, $txt);

    // Envia arquivo
    tg("sendDocument",[
        "chat_id"=>$chat,
        "document"=>new CURLFile($file, "text/plain", "cnpj_{$cnpj}.txt"),
        "caption"=>"🏢 <b>Consulta de CNPJ concluída</b>\n\nCréditos: <b>Astro Search</b>",
        "parse_mode"=>"HTML",
        "reply_markup"=>json_encode([
            "inline_keyboard"=>[
                [
                    ["text"=>"🗑 Apagar","callback_data"=>"apagar_msg"],
                    ["text"=>"🚀 Adquirir Bot","url"=>"https://t.me/puxardados5"]
                ]
            ]
        ])
    ]);

    unlink($file);
}

function consultaIP($chat, $ip){
    global $STICKER_LOADING;

    // Sticker carregando
    $sticker = tg("sendSticker",[
        "chat_id"=>$chat,
        "sticker"=>$STICKER_LOADING
    ]);

    $stickerData = json_decode($sticker, true);
    $stickerMsgId = $stickerData["result"]["message_id"] ?? null;

    // Validação simples
    if(!filter_var($ip, FILTER_VALIDATE_IP)){
        if($stickerMsgId){
            tg("deleteMessage",[
                "chat_id"=>$chat,
                "message_id"=>$stickerMsgId
            ]);
        }

        tg("sendMessage",[
            "chat_id"=>$chat,
            "text"=>"❌ IP inválido.\nUse: <code>/ip 8.8.8.8</code>",
            "parse_mode"=>"HTML"
        ]);
        return;
    }

    // Consulta IP (API grátis)
    $resp = @file_get_contents("http://ip-api.com/json/{$ip}?lang=pt-BR");
    $data = json_decode($resp, true);

    // Apaga sticker
    if($stickerMsgId){
        tg("deleteMessage",[
            "chat_id"=>$chat,
            "message_id"=>$stickerMsgId
        ]);
    }

    if(!$data || $data["status"] !== "success"){
        tg("sendMessage",[
            "chat_id"=>$chat,
            "text"=>"❌ Não foi possível localizar esse IP."
        ]);
        return;
    }

    // TXT formatado
    $txt =
"CONSULTA DE IP — ASTRO SEARCH
================================

IP: {$data["query"]}
País: {$data["country"]}
Região: {$data["regionName"]}
Cidade: {$data["city"]}
CEP: {$data["zip"]}
Latitude: {$data["lat"]}
Longitude: {$data["lon"]}
Fuso horário: {$data["timezone"]}
Provedor: {$data["isp"]}
Organização: {$data["org"]}
AS: {$data["as"]}

--------------------------------
Consulta gratuita
Fonte: ip-api.com
Créditos: Astro Search
";

    // Cria arquivo
    $file = tempnam(sys_get_temp_dir(), "ip_");
    file_put_contents($file, $txt);

    // Envia TXT
    tg("sendDocument",[
        "chat_id"=>$chat,
        "document"=>new CURLFile($file, "text/plain", "ip_{$ip}.txt"),
        "caption"=>"🌐 <b>Consulta de IP concluída</b>\n\nCréditos: <b>Astro Search</b>",
        "parse_mode"=>"HTML",
        "reply_markup"=>json_encode([
            "inline_keyboard"=>[
                [
                    ["text"=>"🗑 Apagar","callback_data"=>"apagar_msg"],
                    ["text"=>"🚀 Adquirir Bot","url"=>"https://t.me/puxardados5"]
                ]
            ]
        ])
    ]);

    unlink($file);
}

function consultaCEP($chat, $cep){
    global $STICKER_LOADING;

    // Sticker carregando
    $sticker = tg("sendSticker",[
        "chat_id"=>$chat,
        "sticker"=>$STICKER_LOADING
    ]);

    $stickerData = json_decode($sticker, true);
    $stickerMsgId = $stickerData["result"]["message_id"] ?? null;

    // Limpa CEP
    $cep = preg_replace('/\D/','',$cep);

    if(strlen($cep) !== 8){
        if($stickerMsgId){
            tg("deleteMessage",[
                "chat_id"=>$chat,
                "message_id"=>$stickerMsgId
            ]);
        }

        tg("sendMessage",[
            "chat_id"=>$chat,
            "text"=>"❌ CEP inválido.\nUse: <code>/cep 01001000</code>",
            "parse_mode"=>"HTML"
        ]);
        return;
    }

    // Consulta ViaCEP
    $resp = @file_get_contents("https://viacep.com.br/ws/{$cep}/json/");
    $data = json_decode($resp, true);

    // Apaga sticker
    if($stickerMsgId){
        tg("deleteMessage",[
            "chat_id"=>$chat,
            "message_id"=>$stickerMsgId
        ]);
    }

    if(!$data || isset($data["erro"])){
        tg("sendMessage",[
            "chat_id"=>$chat,
            "text"=>"❌ CEP não encontrado."
        ]);
        return;
    }

    // Conteúdo TXT
    $txt =
"CONSULTA DE CEP — ASTRO SEARCH
================================

CEP: {$data["cep"]}
Logradouro: {$data["logradouro"]}
Bairro: {$data["bairro"]}
Cidade: {$data["localidade"]}
Estado: {$data["uf"]}
DDD: {$data["ddd"]}
IBGE: {$data["ibge"]}

--------------------------------
Créditos: Astro Search
";

    // Cria arquivo
    $file = tempnam(sys_get_temp_dir(), "cep_");
    file_put_contents($file, $txt);

    // Envia arquivo
    tg("sendDocument",[
        "chat_id"=>$chat,
        "document"=>new CURLFile($file, "text/plain", "cep_{$cep}.txt"),
        "caption"=>"📍 <b>Consulta de CEP concluída</b>\n\nCréditos: <b>Astro Search</b>",
        "parse_mode"=>"HTML",
        "reply_markup"=>json_encode([
            "inline_keyboard"=>[
                [
                    ["text"=>"🗑 Apagar","callback_data"=>"apagar_msg"],
                    ["text"=>"🚀 Adquirir Bot","url"=>"https://t.me/puxardados5"]
                ]
            ]
        ])
    ]);

    unlink($file);
}

function consultaEmail($chat, $email){
    global $STICKER_LOADING;

    // 🎬 Sticker carregando
    $sticker = tg("sendSticker",[
        "chat_id"=>$chat,
        "sticker"=>$STICKER_LOADING
    ]);

    $stickerData = json_decode($sticker, true);
    $stickerMsgId = $stickerData["result"]["message_id"] ?? null;

    // valida email
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){

        if($stickerMsgId){
            tg("deleteMessage",[
                "chat_id"=>$chat,
                "message_id"=>$stickerMsgId
            ]);
        }

        tg("sendMessage",[
            "chat_id"=>$chat,
            "text"=>"❌ Email inválido.\nUse: <code>/email exemplo@email.com</code>",
            "parse_mode"=>"HTML"
        ]);
        return;
    }

    // 🔎 API
    $url = "https://sara-api.xyz/api/consultas/email?email={$email}&apikey=mouth";
    $resp = @file_get_contents($url);
    $json = json_decode($resp, true);

    // remove sticker
    if($stickerMsgId){
        tg("deleteMessage",[
            "chat_id"=>$chat,
            "message_id"=>$stickerMsgId
        ]);
    }

    if(!$json || !$json["success"]){
        tg("sendMessage",[
            "chat_id"=>$chat,
            "text"=>"❌ Nenhum resultado encontrado."
        ]);
        return;
    }

    $txt =
"CONSULTA DE EMAIL — ASTRO SEARCH
=================================

EMAIL CONSULTADO: {$email}

---------------------------------

";

    foreach($json["data"] as $d){

        $txt .=
"Nome: {$d["nome"]}
CPF: {$d["cpf"]}
Telefone: {$d["telefone"]}
Endereço: {$d["logradouro"]} {$d["numero"]}
Bairro: {$d["bairro"]}
Cidade: {$d["cidade"]}

---------------------------------
";
    }

    $file = tempnam(sys_get_temp_dir(),"email_");
    file_put_contents($file,$txt);

    tg("sendDocument",[
        "chat_id"=>$chat,
        "document"=>new CURLFile($file,"text/plain","email_resultado.txt"),
        "caption"=>"📧 <b>Consulta de email concluída</b>\n\nCréditos: <b>Astro Search</b>",
        "parse_mode"=>"HTML",
        "reply_markup"=>json_encode([
            "inline_keyboard"=>[
                [["text"=>"🗑 Apagar","callback_data"=>"apagar_msg"]]
            ]
        ])
    ]);

    unlink($file);
}

function consultaVizinhos($chat, $cpf){
    global $STICKER_LOADING;

    // sticker loading
    $sticker = tg("sendSticker",[
        "chat_id"=>$chat,
        "sticker"=>$STICKER_LOADING
    ]);

    $stickerData = json_decode($sticker,true);
    $stickerMsgId = $stickerData["result"]["message_id"] ?? null;

    $cpf = preg_replace('/\D/','',$cpf);

    if(strlen($cpf) != 11){

        if($stickerMsgId){
            tg("deleteMessage",[
                "chat_id"=>$chat,
                "message_id"=>$stickerMsgId
            ]);
        }

        tg("sendMessage",[
            "chat_id"=>$chat,
            "text"=>"❌ CPF inválido.\nUse: <code>/vizinhos 00000000000</code>",
            "parse_mode"=>"HTML"
        ]);
        return;
    }

    // API
    $url = "https://sara-api.xyz/api/consultas/vizinhos?cpf={$cpf}&apikey=mouth";
    $resp = @file_get_contents($url);
    $json = json_decode($resp,true);

    if($stickerMsgId){
        tg("deleteMessage",[
            "chat_id"=>$chat,
            "message_id"=>$stickerMsgId
        ]);
    }

    if(!$json || !$json["success"]){
        tg("sendMessage",[
            "chat_id"=>$chat,
            "text"=>"❌ Nenhum vizinho encontrado."
        ]);
        return;
    }

    $nome = $json["pessoa"];
    $total = $json["total"];

    $txt =
"CONSULTA DE VIZINHOS — ASTRO SEARCH
====================================

CPF CONSULTADO: {$cpf}
TITULAR: {$nome}

TOTAL DE VIZINHOS: {$total}

------------------------------------

";

    foreach($json["data"] as $v){

        $txt .=
"Nome: {$v["nome"]}
CPF: {$v["cpf"]}
Endereço: {$v["logradouro"]} {$v["numero"]}
Bairro: {$v["bairro"]}
Cidade: {$v["cidade"]}

------------------------------------
";
    }

    $file = tempnam(sys_get_temp_dir(),"viz_");
    file_put_contents($file,$txt);

    tg("sendDocument",[
        "chat_id"=>$chat,
        "document"=>new CURLFile($file,"text/plain","vizinhos_{$cpf}.txt"),
        "caption"=>"🏠 <b>Consulta de vizinhos concluída</b>\n\nCréditos: <b>Astro Search</b>",
        "parse_mode"=>"HTML",
        "reply_markup"=>json_encode([
            "inline_keyboard"=>[
                [["text"=>"🗑 Apagar","callback_data"=>"apagar_msg"]]
            ]
        ])
    ]);

    unlink($file);
}

function consultaFotoRJ($chat, $cpf){
    global $STICKER_LOADING;

    $sticker = tg("sendSticker",[
        "chat_id"=>$chat,
        "sticker"=>$STICKER_LOADING
    ]);

    $stickerData = json_decode($sticker,true);
    $stickerMsgId = $stickerData["result"]["message_id"] ?? null;

    $cpf = preg_replace('/\D/','',$cpf);

    if(strlen($cpf) != 11){

        if($stickerMsgId){
            tg("deleteMessage",[
                "chat_id"=>$chat,
                "message_id"=>$stickerMsgId
            ]);
        }

        tg("sendMessage",[
            "chat_id"=>$chat,
            "text"=>"❌ CPF inválido.\nUse: <code>/fotorj 00000000000</code>",
            "parse_mode"=>"HTML"
        ]);

        return;
    }

    $url = "https://orbyta.online/api/fotorj?cpf={$cpf}&token=FNiPeeltHc5pwy7HWnPCiIs7zIRr7SDB";
    $resp = @file_get_contents($url);
    $json = json_decode($resp,true);

    if($stickerMsgId){
        tg("deleteMessage",[
            "chat_id"=>$chat,
            "message_id"=>$stickerMsgId
        ]);
    }

    if(!$json || !$json["status"]){

        tg("sendMessage",[
            "chat_id"=>$chat,
            "text"=>"❌ Foto RJ não encontrada."
        ]);

        return;
    }

    $fotoBase64 = $json["data"]["foto"];
    $imagem = base64_decode($fotoBase64);

    $file = tempnam(sys_get_temp_dir(),"fotorj_");
    file_put_contents($file,$imagem);

    tg("sendPhoto",[
        "chat_id"=>$chat,
        "photo"=>new CURLFile($file,"image/jpeg","fotorj_{$cpf}.jpg"),
        "caption"=>"📸 <b>FOTO RJ LOCALIZADA</b>

👤 Nome: {$json["data"]["nome"]}
🆔 CPF: <code>{$json["data"]["cpf"]}</code>
📅 Nascimento: {$json["data"]["nascimento"]}
🪪 RG: {$json["data"]["rg"]}

Créditos: <b>Astro Search</b>",
        "parse_mode"=>"HTML",
        "reply_markup"=>json_encode([
            "inline_keyboard"=>[
                [
                    ["text"=>"🗑 Apagar","callback_data"=>"apagar_msg"]
                ]
            ]
        ])
    ]);

    unlink($file);
}

function consultaFotoSP($chat, $cpf){
    global $STICKER_LOADING;

    $sticker = tg("sendSticker",[
        "chat_id"=>$chat,
        "sticker"=>$STICKER_LOADING
    ]);

    $stickerData = json_decode($sticker,true);
    $stickerMsgId = $stickerData["result"]["message_id"] ?? null;

    $cpf = preg_replace('/\D/','',$cpf);

    if(strlen($cpf) != 11){

        if($stickerMsgId){
            tg("deleteMessage",[
                "chat_id"=>$chat,
                "message_id"=>$stickerMsgId
            ]);
        }

        tg("sendMessage",[
            "chat_id"=>$chat,
            "text"=>"❌ CPF inválido.\nUse: <code>/fotosp 00000000000</code>",
            "parse_mode"=>"HTML"
        ]);

        return;
    }

    $url = "https://sara-api.xyz/api/consultas/fotosp?cpf={$cpf}&apikey=mouth";
    $resp = @file_get_contents($url);
    $json = json_decode($resp,true);

    if($stickerMsgId){
        tg("deleteMessage",[
            "chat_id"=>$chat,
            "message_id"=>$stickerMsgId
        ]);
    }

    if(!$json || !$json["success"] || empty($json["foto"])){

        tg("sendMessage",[
            "chat_id"=>$chat,
            "text"=>"❌ Foto SP não encontrada."
        ]);

        return;
    }

    $imagem = base64_decode($json["foto"]);

    $file = tempnam(sys_get_temp_dir(),"fotosp_");
    file_put_contents($file,$imagem);

    tg("sendPhoto",[
        "chat_id"=>$chat,
        "photo"=>new CURLFile($file,"image/jpeg","fotosp_{$cpf}.jpg"),
        "caption"=>"📸 <b>FOTO SP LOCALIZADA</b>

🆔 CPF: <code>{$json["cpf"]}</code>
📍 Estado: {$json["estado"]}

Créditos: <b>Astro Search</b>",
        "parse_mode"=>"HTML",
        "reply_markup"=>json_encode([
            "inline_keyboard"=>[
                [
                    ["text"=>"🗑 Apagar","callback_data"=>"apagar_msg"]
                ]
            ]
        ])
    ]);

    unlink($file);
}

function consultaFoto($chat, $cpf){
    global $STICKER_LOADING;

    // 🎬 Sticker loading
    $sticker = tg("sendSticker",[
        "chat_id"=>$chat,
        "sticker"=>$STICKER_LOADING
    ]);

    $stickerData = json_decode($sticker, true);
    $stickerMsgId = $stickerData["result"]["message_id"] ?? null;

    // limpa cpf
    $cpf = preg_replace('/\D/','',$cpf);

    if(strlen($cpf) != 11){
        if($stickerMsgId){
            tg("deleteMessage",[
                "chat_id"=>$chat,
                "message_id"=>$stickerMsgId
            ]);
        }

        tg("sendMessage",[
            "chat_id"=>$chat,
            "text"=>"❌ CPF inválido.\nUse: <code>/foto 00000000000</code>",
            "parse_mode"=>"HTML"
        ]);
        return;
    }

    // 🔥 API FOTO
    $url = "https://sara-api.xyz/api/consultas/fotov2?cpf={$cpf}&apikey=mouth";
    $resp = @file_get_contents($url);
    $json = json_decode($resp, true);

    // remove sticker
    if($stickerMsgId){
        tg("deleteMessage",[
            "chat_id"=>$chat,
            "message_id"=>$stickerMsgId
        ]);
    }

    if(!$json || !$json["success"] || empty($json["foto"])){
        tg("sendMessage",[
            "chat_id"=>$chat,
            "text"=>"❌ Foto não encontrada."
        ]);
        return;
    }

    // 🔥 REMOVE prefixo base64
    $base64 = explode(",", $json["foto"])[1];
    $imagem = base64_decode($base64);

    $file = tempnam(sys_get_temp_dir(), "foto_");
    file_put_contents($file, $imagem);

    tg("sendPhoto",[
        "chat_id"=>$chat,
        "photo"=>new CURLFile($file, "image/jpeg", "foto_{$cpf}.jpg"),
        "caption"=>"📸 <b>FOTO LOCALIZADA</b>\n\nCPF: <code>{$cpf}</code>\nEstado: {$json["estado"]}\n\nCréditos: <b>Astro Search</b>",
        "parse_mode"=>"HTML",
        "reply_markup"=>json_encode([
            "inline_keyboard"=>[
                [
                    ["text"=>"🗑 Apagar","callback_data"=>"apagar_msg"]
                ]
            ]
        ])
    ]);

    unlink($file);
}

function consultaTelefone($chat, $telefone){
    global $STICKER_LOADING;

    // 🎬 Sticker loading
    $sticker = tg("sendSticker",[
        "chat_id"=>$chat,
        "sticker"=>$STICKER_LOADING
    ]);

    $stickerData = json_decode($sticker, true);
    $stickerMsgId = $stickerData["result"]["message_id"] ?? null;

    // limpa telefone
    $telefone = preg_replace('/\D/','',$telefone);

    if(strlen($telefone) < 10){
        if($stickerMsgId){
            tg("deleteMessage",[
                "chat_id"=>$chat,
                "message_id"=>$stickerMsgId
            ]);
        }

        tg("sendMessage",[
            "chat_id"=>$chat,
            "text"=>"❌ Telefone inválido.\nUse: <code>/telefone 31999999999</code>",
            "parse_mode"=>"HTML"
        ]);
        return;
    }

    // 🔥 API TELEFONE
    $url = "https://sara-api.xyz/api/consultas/telefone?telefone={$telefone}&apikey=mouth";
    $resp = @file_get_contents($url);
    $json = json_decode($resp, true);

    // remove sticker
    if($stickerMsgId){
        tg("deleteMessage",[
            "chat_id"=>$chat,
            "message_id"=>$stickerMsgId
        ]);
    }

    if(!$json || $json["statusCode"] != 200 || $json["total_results"] == 0){
        tg("sendMessage",[
            "chat_id"=>$chat,
            "text"=>"❌ Nenhum resultado encontrado para este telefone."
        ]);
        return;
    }

    $txt =
"CONSULTA TELEFONE — ASTRO SEARCH
================================

Telefone pesquisado: {$json["query"]}
Total encontrados: {$json["total_results"]}

================================
";

    foreach($json["body"] as $pessoa){

        $txt .= "
CPF: {$pessoa["cpf"]}
Nome: {$pessoa["name"]}
Nascimento: {$pessoa["birth_date"]}
Cidade: {$pessoa["city"]}
Estado: {$pessoa["state"]}
Email: {$pessoa["email"]}
Base: Astro Search

--------------------------------
";
    }

    $txt .= "
Consulta via:
Astro Search
";

    $file = tempnam(sys_get_temp_dir(), "tel_");
    file_put_contents($file, $txt);

    resultadoConsulta(
$chat,
"Consulta de Telefone",
$txt,
"telefone"
);
}

function consultaNome($chat, $nome){
    global $STICKER_LOADING;

    // Sticker loading
    $sticker = tg("sendSticker",[
        "chat_id"=>$chat,
        "sticker"=>$STICKER_LOADING
    ]);

    $stickerData = json_decode($sticker, true);
    $stickerMsgId = $stickerData["result"]["message_id"] ?? null;

    if(strlen($nome) < 5){

        if($stickerMsgId){
            tg("deleteMessage",[
                "chat_id"=>$chat,
                "message_id"=>$stickerMsgId
            ]);
        }

        tg("sendMessage",[
            "chat_id"=>$chat,
            "text"=>"❌ Nome inválido.\nUse: <code>/nome João Silva</code>",
            "parse_mode"=>"HTML"
        ]);
        return;
    }

    $nomeUrl = urlencode($nome);

    // NOVA API
    $url = "https://api.blackaut.shop/api/dados-pessoais/nome?nome={$nomeUrl}&apikey=EbmScZ0ntHf61KJz3H";

    $ch = curl_init($url);
    curl_setopt_array($ch,[
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 20
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    $json = json_decode($response, true);

    // remove sticker
    if($stickerMsgId){
        tg("deleteMessage",[
            "chat_id"=>$chat,
            "message_id"=>$stickerMsgId
        ]);
    }

    if(!$json || empty($json["resultado"])){

        tg("sendMessage",[
            "chat_id"=>$chat,
            "text"=>"❌ Nenhum resultado encontrado."
        ]);
        return;
    }

    $txt =
"CONSULTA POR NOME — ASTRO SEARCH
================================

Nome pesquisado: {$nome}

================================
";

    foreach($json["resultado"] as $pessoa){

        $txt .= "
CPF: {$pessoa["cpf"]}
Nome: {$pessoa["name"]}
Sexo: {$pessoa["gender"]}
Nascimento: {$pessoa["birth"]}
Idade: {$pessoa["age"]}
Signo: {$pessoa["sign"]}

--------------------------------
";
    }

    $txt .= "
Consulta via:
Astro Search
";

    resultadoConsulta(
        $chat,
        "Consulta por Nome",
        $txt,
        "nome"
    );
}

function consultaParentes($chat, $cpf){
    global $STICKER_LOADING;

    // 🎬 Sticker loading
    $sticker = tg("sendSticker",[
        "chat_id"=>$chat,
        "sticker"=>$STICKER_LOADING
    ]);

    $stickerData = json_decode($sticker, true);
    $stickerMsgId = $stickerData["result"]["message_id"] ?? null;

    // limpa cpf
    $cpf = preg_replace('/\D/','',$cpf);

    if(strlen($cpf) != 11){
        if($stickerMsgId){
            tg("deleteMessage",[
                "chat_id"=>$chat,
                "message_id"=>$stickerMsgId
            ]);
        }

        tg("sendMessage",[
            "chat_id"=>$chat,
            "text"=>"❌ CPF inválido.\nUse: <code>/parentes 00000000000</code>",
            "parse_mode"=>"HTML"
        ]);
        return;
    }

    // 🔥 API SARA PARENTES
    $url = "https://sara-api.xyz/api/consultas/parentes?cpf={$cpf}&apikey=mouth";
    $resp = @file_get_contents($url);
    $json = json_decode($resp, true);

    // remove sticker
    if($stickerMsgId){
        tg("deleteMessage",[
            "chat_id"=>$chat,
            "message_id"=>$stickerMsgId
        ]);
    }

    if(!$json || !$json["success"]){
        tg("sendMessage",[
            "chat_id"=>$chat,
            "text"=>"❌ Nenhum parente encontrado ou erro na API."
        ]);
        return;
    }

    $txt =
"CONSULTA DE PARENTES — ASTRO SEARCH
================================

CPF Consultado: {$json["query"]}
Titular: {$json["pessoa"]}
Total de vínculos: {$json["total"]}

================================
";

    foreach($json["data"] as $parente){

        $txt .= "
Nome: {$parente["nome"]}
CPF: {$parente["cpf"]}
Vínculo: {$parente["vinculo"]}

--------------------------------
";
    }

    $txt .= "
Consulta via:
Astro Search
Tempo resposta API: {$json["responseTime"]}
";

    $file = tempnam(sys_get_temp_dir(), "parentes_");
    file_put_contents($file, $txt);

    tg("sendDocument",[
        "chat_id"=>$chat,
        "document"=>new CURLFile($file, "text/plain", "parentes_{$cpf}.txt"),
        "caption"=>"👨‍👩‍👧‍👦 <b>Consulta de Parentes concluída</b>\n\nCréditos: <b>Astro Search</b>",
        "parse_mode"=>"HTML",
        "reply_markup"=>json_encode([
            "inline_keyboard"=>[
                [
                    ["text"=>"🗑 Apagar","callback_data"=>"apagar_msg"],
                    ["text"=>"🚀 Adquirir Bot","url"=>"https://t.me/puxardados5"]
                ]
            ]
        ])
    ]);

    unlink($file);
}

function consultaCPF1($chat,$cpf){

global $STICKER_LOADING;

$sticker = tg("sendSticker",[
"chat_id"=>$chat,
"sticker"=>$STICKER_LOADING
]);

$stickerData = json_decode($sticker,true);
$stickerMsgId = $stickerData["result"]["message_id"] ?? null;

$cpf = preg_replace('/\D/','',$cpf);

if(strlen($cpf) != 11){

if($stickerMsgId){
tg("deleteMessage",[
"chat_id"=>$chat,
"message_id"=>$stickerMsgId
]);
}

tg("sendMessage",[
"chat_id"=>$chat,
"text"=>"❌ CPF inválido.\nUse: <code>/cpf1 00000000000</code>",
"parse_mode"=>"HTML"
]);

return;
}

$url = "https://orbyta.online/api/apifullcpf?cpf={$cpf}&token=FNiPeeltHc5pwy7HWnPCiIs7zIRr7SDB";
$resp = @file_get_contents($url);
$json = json_decode($resp,true);

if($stickerMsgId){
tg("deleteMessage",[
"chat_id"=>$chat,
"message_id"=>$stickerMsgId
]);
}

if(!$json || $json["execucao"]["status"] != "ENCONTRADO"){

tg("sendMessage",[
"chat_id"=>$chat,
"text"=>"❌ CPF não encontrado."
]);

return;
}

$p = $json["dados_pessoais"];

$txt = "CONSULTA CPF FULL — ASTRO SEARCH
=================================

CPF: {$p["cpf"]}
Nome: {$p["nome"]}
Nascimento: {$p["data_nascimento"]}
Sexo: {$p["sexo"]}

Mãe: {$p["nome_mae"]}
Estado Civil: ".($p["estado_civil"] ?? "Não informado")."
Nacionalidade: ".($p["nacionalidade"] ?? "Não informado")."

RG: ".($p["rg"] ?? "Não informado")."
Orgão Emissor: ".($p["orgao_emissor"] ?? "Não informado")."
Data Emissão RG: ".($p["data_emissao_rg"] ?? "Não informado")."

Naturalidade: ".($p["naturalidade"] ?? "Não informado")."
CNS: ".($p["cns"] ?? "Não informado")."

Status Receita: {$p["status_receita"]}

--------------------------------
";

if(isset($json["familia"])){

$txt .= "\nFAMILIARES
--------------------------------\n";

foreach($json["familia"] as $f){

$txt .= "{$f["vinculo"]}: {$f["nome"]} - CPF {$f["cpf_parente"]}\n";

}

}

if(isset($json["contatos"]["telefones"])){

$txt .= "\nTELEFONES
--------------------------------\n";

foreach($json["contatos"]["telefones"] as $t){

$txt .= "({$t["ddd"]}) {$t["numero"]} - {$t["tipo"]}\n";

}

}

if(isset($json["contatos"]["emails"])){

$txt .= "\nEMAILS
--------------------------------\n";

foreach($json["contatos"]["emails"] as $e){

$txt .= "{$e}\n";

}

}

if(isset($json["enderecos"])){

$txt .= "\nENDEREÇOS
--------------------------------\n";

foreach($json["enderecos"] as $e){

$txt .= "{$e["logradouro"]}, {$e["numero"]}\n";
$txt .= "{$e["bairro"]} - {$e["cidade"]}/{$e["uf"]}\n";
$txt .= "CEP {$e["cep"]}\n\n";

}

}

if(isset($json["veiculos"])){

$txt .= "\nVEÍCULOS
--------------------------------\n";

foreach($json["veiculos"] as $v){

$txt .= "{$v["modelo"]} - Ano {$v["ano"]}\n";

}

}

if(isset($json["financeiro"])){

$f = $json["financeiro"];

$txt .= "\nFINANCEIRO
--------------------------------\n";

$txt .= "PIS: {$f["pis"]}\n";
$txt .= "Renda Estimada: {$f["renda_estimada"]}\n";

if(isset($f["score"])){

$txt .= "Score CSB8: {$f["score"]["csb8"]}\n";
$txt .= "Score CSBA: {$f["score"]["csba"]}\n";

}

}

if(isset($json["dados_bancarios"])){

$txt .= "\nDADOS BANCÁRIOS
--------------------------------\n";

foreach($json["dados_bancarios"] as $b){

$txt .= "{$b["instituicao"]} - Agência {$b["agencia"]}\n";

}

}

if(isset($json["trabalho"])){

$t = $json["trabalho"];

$txt .= "\nTRABALHO
--------------------------------\n";

$txt .= "CBO: ".($t["cbo"] ?? "Não informado")."\n";

}

if(isset($json["servidor_publico"])){

$s = $json["servidor_publico"];

$txt .= "\nSERVIDOR PÚBLICO
--------------------------------\n";

$txt .= "Funcionário Público: ".($s["is_funcionario_publico"] ? "SIM" : "NÃO")."\n";

}

if(isset($json["titulo_eleitor"])){

$txt .= "\nTÍTULO DE ELEITOR
--------------------------------\n";

$txt .= $json["titulo_eleitor"]."\n";

}

if(isset($json["perfil_consumo"])){

$txt .= "\nPERFIL DE CONSUMO
--------------------------------\n";

foreach($json["perfil_consumo"] as $k=>$v){

$txt .= strtoupper($k).": ".$v."\n";

}

}

$txt .= "\n--------------------------------
Consulta via:
Astro Search
";

$file = tempnam(sys_get_temp_dir(),"cpf_");
file_put_contents($file,$txt);

tg("sendDocument",[
"chat_id"=>$chat,
"document"=>new CURLFile($file,"text/plain","cpf_full_{$cpf}.txt"),
"caption"=>"🧾 <b>Consulta de CPF FULL concluída</b>\n\nCréditos: <b>Astro Search</b>",
"parse_mode"=>"HTML",
"reply_markup"=>json_encode([
    "inline_keyboard"=>[
        [
            ["text"=>"🗑 Apagar","callback_data"=>"apagar_msg"],
            ["text"=>"🚀 Adquirir Bot","url"=>"https://t.me/puxardados5"]
        ]
    ]
])
]);

unlink($file);

}

function consultaCPF2($chat,$cpf){

global $STICKER_LOADING;

$sticker = tg("sendSticker",[
"chat_id"=>$chat,
"sticker"=>$STICKER_LOADING
]);

$stickerData = json_decode($sticker,true);
$stickerMsgId = $stickerData["result"]["message_id"] ?? null;

$cpf = preg_replace('/\D/','',$cpf);

if(strlen($cpf) != 11){

if($stickerMsgId){
tg("deleteMessage",[
"chat_id"=>$chat,
"message_id"=>$stickerMsgId
]);
}

tg("sendMessage",[
"chat_id"=>$chat,
"text"=>"❌ CPF inválido.\nUse: <code>/cpf2 00000000000</code>",
"parse_mode"=>"HTML"
]);

return;
}

$url = "https://api.blackaut.shop/api/dados-pessoais/cpf?cpf={$cpf}&apikey=EbmScZ0ntHf61KJz3H";

$ch = curl_init($url);
curl_setopt_array($ch,[
CURLOPT_RETURNTRANSFER => true,
CURLOPT_TIMEOUT => 20
]);

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response,true);

if($stickerMsgId){
tg("deleteMessage",[
"chat_id"=>$chat,
"message_id"=>$stickerMsgId
]);
}

if(!$data || empty($data["resultado"])){

tg("sendMessage",[
"chat_id"=>$chat,
"text"=>"❌ CPF não encontrado ou instabilidade na API."
]);

return;
}

$d = $data["resultado"];

$txt = "
╔══════════════════════════════╗
   CONSULTA CPF VIP — ASTRO SEARCH
╚══════════════════════════════╝

DADOS PESSOAIS
──────────────────────────────

CPF: {$d["cpf"]}
Nome: {$d["name"]}
Sexo: {$d["gender"]}
Nascimento: {$d["birth"]}
Idade: {$d["age"]}
Signo: {$d["sign"]}

Mãe: {$d["mother_name"]}
Pai: {$d["father_name"]}

Estado civil: {$d["marital_status"]}
RG: {$d["rg"]}

Situação Receita: {$d["cd_sit_cad"]}
Data situação: {$d["dt_sit_cad"]}
";

if(!empty($d["income"])){

$txt .= "

RENDA
──────────────────────────────
Renda estimada: R$ {$d["income"]}
";
}

# ENDEREÇOS
if(!empty($d["addresses"])){

$txt .= "

ENDEREÇOS
──────────────────────────────
";

foreach($d["addresses"] as $a){

$txt .= "
{$a["logr_type"]} {$a["logr_name"]}, {$a["logr_number"]}
Bairro: {$a["neighborhood"]}
Cidade: {$a["city"]} - {$a["state"]}
CEP: {$a["zip_code"]}
Complemento: {$a["logr_complement"]}
";
}
}

# TELEFONES
if(!empty($d["telephones"])){

$txt .= "

TELEFONES
──────────────────────────────
";

foreach($d["telephones"] as $t){

$txt .= "
({$t["ddd"]}) {$t["phone_number"]}
Tipo: {$t["phone_type"]}
";
}
}

# PODER DE COMPRA
if(!empty($d["purchasing_power"])){

$p = $d["purchasing_power"];

$txt .= "

PODER AQUISITIVO
──────────────────────────────
Faixa: {$p["purchasing_power"]}
Renda estimada: {$p["fx_purchasing_power"]}
";
}

# PARENTES
if(!empty($d["relatives"])){

$txt .= "

PARENTES
──────────────────────────────
";

foreach($d["relatives"] as $r){

$txt .= "
{$r["name"]} - {$r["relationship"]}
CPF: {$r["cpf_complete"]}
";
}
}

# SCORE
if(!empty($d["score"])){

$s = $d["score"];

$txt .= "

SCORE
──────────────────────────────
CSB8: {$s["csb8"]} ({$s["csb8_range"]})
CSBA: {$s["csba"]} ({$s["csba_range"]})
";
}

$txt .= "

──────────────────────────────
Consulta realizada via:
ASTRO SEARCH
";

$file = "cache_cpf2_{$cpf}.txt";
file_put_contents($file,$txt);

tg("sendMessage",[
"chat_id"=>$chat,
"text"=>"✅ <b>Consulta VIP realizada</b>\n\nEscolha o formato do resultado:",
"parse_mode"=>"HTML",
"reply_markup"=>json_encode([
"inline_keyboard"=>[
[
["text"=>"📄 Mostrar no Telegram","callback_data"=>"cpf2_msg|$cpf"]
],
[
["text"=>"📁 Enviar arquivo TXT","callback_data"=>"cpf2_file|$cpf"]
],
[
["text"=>"🗑 Apagar","callback_data"=>"apagar_msg"]
],
[
["text"=>"🚀 Adquirir Bot","url"=>"https://t.me/puxardados5"]
]
]
])
]);

}

function consultaPlaca($chat, $placa){

global $STICKER_LOADING;

$sticker = tg("sendSticker",[
"chat_id"=>$chat,
"sticker"=>$STICKER_LOADING
]);

$stickerData = json_decode($sticker,true);
$stickerMsgId = $stickerData["result"]["message_id"] ?? null;

$placa = strtoupper(preg_replace('/[^A-Za-z0-9]/','',$placa));

if(strlen($placa) < 7){

if($stickerMsgId){
tg("deleteMessage",[
"chat_id"=>$chat,
"message_id"=>$stickerMsgId
]);
}

tg("sendMessage",[
"chat_id"=>$chat,
"text"=>"❌ Placa inválida.\nUse: <code>/placa ABC1234</code>",
"parse_mode"=>"HTML"
]);

return;
}

$url = "https://api.blackaut.shop/api/dados-pessoais/placa?placa={$placa}&apikey=FQDNodt9BRPxQAeYmH";

$resp = @file_get_contents($url);
$json = json_decode($resp,true);

if($stickerMsgId){
tg("deleteMessage",[
"chat_id"=>$chat,
"message_id"=>$stickerMsgId
]);
}

if(!$json || !$json["status"]){

tg("sendMessage",[
"chat_id"=>$chat,
"text"=>"❌ Veículo não encontrado."
]);

return;
}

$d = $json["resultado"];

$texto = "";

if(!empty($d["dados"]["enderecos"])){

$texto = implode("\n",$d["dados"]["enderecos"]);

}

/* LIMPEZA DO TEXTO */

$remove = [
"Sistema Online MK",
"UNIX Intelligence",
"Copiar Texto",
"Este link expira",
"©",
"Todos os direitos reservados"
];

$texto = str_replace($remove,"",$texto);

/* FORMATAÇÃO */

$texto = str_replace("INFORMAÇÕES BÁSICAS DO VEÍCULO","\n🚗 DADOS DO VEÍCULO\n",$texto);
$texto = str_replace("PROPRIETÁRIO","\n👤 PROPRIETÁRIO\n",$texto);
$texto = str_replace("ENDEREÇO","\n📍 ENDEREÇO\n",$texto);
$texto = str_replace("DÉBITOS","\n💰 DÉBITOS\n",$texto);
$texto = str_replace("RESTRIÇÕES","\n⚠️ RESTRIÇÕES\n",$texto);
$texto = str_replace("RESUMO DA SITUAÇÃO","\n📊 SITUAÇÃO\n",$texto);

/* QUEBRAS */

$texto = preg_replace('/([A-ZÇ ]+):/',"\n$1:",$texto);

/* REMOVE LINHAS DUPLICADAS */

$linhas = array_unique(array_filter(array_map("trim",explode("\n",$texto))));
$texto = implode("\n",$linhas);

/* CABEÇALHO */

$txt =
"🚗 CONSULTA DE PLACA — ASTRO SEARCH
================================

Placa Consultada: {$placa}

{$texto}

--------------------------------
Consulta realizada via:
ASTRO SEARCH
";

/* CRIA ARQUIVO */

$file = tempnam(sys_get_temp_dir(),"placa_");
file_put_contents($file,$txt);

/* ENVIA */

tg("sendDocument",[
"chat_id"=>$chat,
"document"=>new CURLFile($file,"text/plain","placa_{$placa}.txt"),
"caption"=>"🚗 <b>Consulta de Placa concluída</b>",
"parse_mode"=>"HTML",
"reply_markup"=>json_encode([
"inline_keyboard"=>[
[
["text"=>"🗑 Apagar","callback_data"=>"apagar_msg"],
["text"=>"🚀 Adquirir Bot","url"=>"https://t.me/puxardados5"]
]
]
])
]);

unlink($file);

}

function consultaCPF($chat, $cpf){
    global $STICKER_LOADING;

    // 🎬 Sticker loading
    $sticker = tg("sendSticker",[
        "chat_id"=>$chat,
        "sticker"=>$STICKER_LOADING
    ]);

    $stickerData = json_decode($sticker, true);
    $stickerMsgId = $stickerData["result"]["message_id"] ?? null;

    // limpa cpf
    $cpf = preg_replace('/\D/','',$cpf);

    if(strlen($cpf) != 11){
        if($stickerMsgId){
            tg("deleteMessage",[
                "chat_id"=>$chat,
                "message_id"=>$stickerMsgId
            ]);
        }

        tg("sendMessage",[
            "chat_id"=>$chat,
            "text"=>"❌ CPF inválido.\nUse: <code>/cpf 00000000000</code>",
            "parse_mode"=>"HTML"
        ]);
        return;
    }

    // 🔥 NOVA API SARA
    $url = "https://sara-api.xyz/api/consultas/cpf?cpf={$cpf}&apikey=mouth";
    $resp = @file_get_contents($url);
    $json = json_decode($resp, true);

    // remove sticker
    if($stickerMsgId){
        tg("deleteMessage",[
            "chat_id"=>$chat,
            "message_id"=>$stickerMsgId
        ]);
    }

    if(!$json || $json["statusCode"] != 200){
        tg("sendMessage",[
            "chat_id"=>$chat,
            "text"=>"❌ CPF não encontrado ou instabilidade na API."
        ]);
        return;
    }

    $d = $json["body"];

    $txt =
"CONSULTA CPF — ASTRO SEARCH
================================

CPF: {$d["cpf_masked"]}
Nome: {$d["name"]}
Primeiro Nome: {$d["first_name"]}
Último Nome: {$d["last_name"]}

Sexo: {$d["gender"]}
Nascimento: {$d["birth_date"]}

Mãe: ".($d["mother_name"] ?: "Não informado")."
Pai: ".($d["father_name"] ?: "Não informado")."

Status Receita: {$d["federal_status"]}
Óbito: ".($d["death_flag"] == "1" ? "SIM" : "NÃO")."

Renda: {$d["income"]}
Faixa Renda: {$d["income_bracket"]}

Classe Social: {$d["social_class"]["social_class"]} {$d["social_class"]["sub_social_class"]}

Score: {$d["credit_score"]["score"]}

--------------------------------
Consulta via:
Astro Search
";

    $file = tempnam(sys_get_temp_dir(), "cpf_");
    file_put_contents($file, $txt);

    tg("sendDocument",[
        "chat_id"=>$chat,
        "document"=>new CURLFile($file, "text/plain", "cpf_{$cpf}.txt"),
        "caption"=>"🧾 <b>Consulta de CPF concluída</b>\n\nCréditos: <b>Astro Search</b>",
        "parse_mode"=>"HTML",
        "reply_markup"=>json_encode([
            "inline_keyboard"=>[
                [
                    ["text"=>"🗑 Apagar","callback_data"=>"apagar_msg"],
                    ["text"=>"🚀 Adquirir Bot","url"=>"https://t.me/puxardados5"]
                ]
            ]
        ])
    ]);

    unlink($file);
}

/* ================= START ================= */

if($message && isset($message["text"])){

    $text = explode(" ", $message["text"])[0];
    $text = explode("@", $text)[0];

    if(in_array($text, ["/start", "/menu"])){

        $chat_id = $message["chat"]["id"];
        $nome    = $message["from"]["first_name"] ?? "usuário";
        $user_id = $message["from"]["id"];

        menuPrincipal($chat_id, $nome, $user_id);
        exit;
    }
}

/* ================= COMANDOS ================= */

if($message && isset($message["text"]) && str_starts_with($message["text"], "/")){
    $chat = $message["chat"]["id"];
    $userId = $message["from"]["id"];
    $p = explode(" ", trim($message["text"]), 2);
    $cmd = strtolower($p[0]);
    $arg = $p[1] ?? null;
    
    if($cmd === "/free"){

    $chatType = $message["chat"]["type"];

    if(!in_array($chatType, ["group","supergroup"])){
        tg("sendMessage",[
            "chat_id"=>$chat,
            "text"=>"❌ Este comando só pode ser usado em grupos."
        ]);
        exit;
    }

    ativarFreeGrupo($chat);

    tg("sendMessage",[
        "chat_id"=>$chat,
        "text"=>"🚀 <b>MODO FREE ATIVADO</b>

Todas as consultas VIP foram liberadas neste grupo.

⏳ Validade: <b>24 horas</b>",
        "parse_mode"=>"HTML"
    ]);

    exit;
}

    // ===== COMANDOS GRÁTIS =====
    if($cmd === "/cnpj"){
        $arg ? consultaCNPJ($chat, $arg) : tutorial($chat, "/cnpj");
        exit;
    }

    if($cmd === "/ip"){
        $arg ? consultaIP($chat, $arg) : tutorial($chat, "/ip");
        exit;
    }


    if($cmd === "/cep"){
        $arg ? consultaCEP($chat, $arg) : tutorial($chat, "/cep");
        exit;
    }

    // ===== COMANDOS VIP =====
$vipCmds = ["/cpf","/fotorj","/fotosp","/cpf1","/cpf2","/vizinhos","/parentes","/nome","/rg","/cnh","/telefone","/email","/placa","/pix","/renavam","/nascimento","/foto"];
    if(in_array($cmd, $vipCmds)){

    // ❗ primeiro valida se enviou argumento
    if(!$arg){
        tutorial($chat, $cmd);
        exit;
    }

    // 🔒 depois verifica VIP
    if(!isVip($userId) && !isFreeGroup($chat)){
    bloquearConsulta($chat);
    exit;
}

        if($cmd === "/cpf"){

    if(!$arg){
        tutorial($chat,"/cpf");
        exit;
    }

    tg("sendMessage",[
        "chat_id"=>$chat,
        "text"=>"🔎 <b>Selecione o tipo de consulta</b>\n\nCPF: <code>{$arg}</code>",
        "parse_mode"=>"HTML",
        "reply_markup"=>json_encode([
            "inline_keyboard"=>[
                [
                    ["text"=>"📄 CPF Simples","callback_data"=>"cpf_simples|{$arg}"],
                    ["text"=>"📑 CPF Full","callback_data"=>"cpf_full|{$arg}"]
                ],
                [
                    ["text"=>"🏠 Vizinhos","callback_data"=>"cpf_vizinhos|{$arg}"],
                    ["text"=>"👨‍👩‍👧 Parentes","callback_data"=>"cpf_parentes|{$arg}"]
                ]
            ]
        ])
    ]);

    exit;
}
        
        if($cmd === "/cpf1"){
            $arg ? consultaCPF1($chat, $arg) : tutorial($chat, "/cpf");
            exit;
        }
        
        if($cmd === "/cpf2"){
    consultaCPF2($chat, $arg);
    exit;
}
        
        if($cmd === "/placa"){
    consultaPlaca($chat, $arg);
    exit;
}
        
        if($cmd === "/parentes"){
    consultaParentes($chat, $arg);
    exit;
        }
        
        if($cmd === "/nome"){
            $arg ? consultaNome($chat, $arg) : tutorial($chat, "/nome");
            exit;
        }
        
        if($cmd === "/telefone"){
    consultaTelefone($chat, $arg);
    exit;
}
        
        if($cmd === "/foto"){

    $chatType = $message["chat"]["type"];

    // 🚫 bloquear foto em grupos FREE
    if(isGroupChat($chatType) && isFreeGroup($chat) && !isVip($userId)){
        bloquearConsulta($chat);
        exit;
    }

    consultaFoto($chat, $arg);
    exit;
}

if($cmd === "/fotosp"){

    $chatType = $message["chat"]["type"];

    if(isGroupChat($chatType) && isFreeGroup($chat) && !isVip($userId)){
        bloquearConsulta($chat);
        exit;
    }

    consultaFotoSP($chat, $arg);
    exit;
}

if($cmd === "/fotorj"){

    $chatType = $message["chat"]["type"];

    if(isGroupChat($chatType) && isFreeGroup($chat) && !isVip($userId)){
        bloquearConsulta($chat);
        exit;
    }

    consultaFotoRJ($chat, $arg);
    exit;
}

if($cmd === "/email"){
    consultaEmail($chat, $arg);
    exit;
}

if($cmd === "/vizinhos"){
    consultaVizinhos($chat, $arg);
    exit;
}

        // outros comandos VIP futuramente aqui
        tutorial($chat, $cmd);
        exit;
    }
}

/* ================= CALLBACKS ================= */

if($callback){
    
    answer($callback["id"]);

    $chat = $callback["message"]["chat"]["id"];
    $msg  = $callback["message"]["message_id"];
    $nome = $callback["from"]["first_name"] ?? "usuário";
    $id   = $callback["from"]["id"];
    
    if(str_starts_with($callback["data"],"ver|")){

$file = explode("|",$callback["data"])[1];

if(!file_exists($file)) exit;

$txt = file_get_contents($file);
$partes = str_split($txt,4000);

foreach($partes as $i=>$p){

tg("sendMessage",[
"chat_id"=>$chat,
"text"=>"<pre>".$p."</pre>",
"parse_mode"=>"HTML",
"reply_markup"=>$i == 0 ? json_encode([
"inline_keyboard"=>[
[
["text"=>"🗑 Apagar","callback_data"=>"apagar_msg"],
["text"=>"🚀 Adquirir Bot","url"=>"https://t.me/puxardados5"]
]
]
]) : null
]);

}

unlink($file);
exit;
}


if(str_starts_with($callback["data"],"txt|")){

$file = explode("|",$callback["data"])[1];

if(!file_exists($file)) exit;

tg("sendDocument",[
"chat_id"=>$chat,
"document"=>new CURLFile($file),
"caption"=>"📄 Resultado da consulta",
"parse_mode"=>"HTML",
"reply_markup"=>json_encode([
"inline_keyboard"=>[
[
["text"=>"🗑 Apagar","callback_data"=>"apagar_msg"],
["text"=>"🚀 Adquirir Bot","url"=>"https://t.me/puxardados5"]
]
]
])
]);

unlink($file);
exit;
}
    
    if(str_starts_with($callback["data"],"cpf2_msg")){

$dados = explode("|",$callback["data"]);
$cpf = $dados[1];

$file = "cache_cpf2_{$cpf}.txt";

if(!file_exists($file)){
exit;
}

$txt = file_get_contents($file);
$partes = str_split($txt,4000);

foreach($partes as $index => $parte){

tg("sendMessage",[
"chat_id"=>$chat,
"text"=>"<pre>".$parte."</pre>",
"parse_mode"=>"HTML",
"reply_markup"=>$index == 0 ? json_encode([
"inline_keyboard"=>[
[
["text"=>"🗑 Apagar","callback_data"=>"apagar_msg"]
],
[
["text"=>"🚀 Adquirir Bot","url"=>"https://t.me/puxardados5"]
]
]
]) : null
]);

}

unlink($file);

exit;
}

if(str_starts_with($callback["data"],"cpf2_file")){

$dados = explode("|",$callback["data"]);
$cpf = $dados[1];

$file = "cache_cpf2_{$cpf}.txt";

if(!file_exists($file)){
exit;
}

tg("sendDocument",[
"chat_id"=>$chat,
"document"=>new CURLFile($file,"text/plain","cpf2_{$cpf}.txt"),
"caption"=>"📑 <b>Consulta CPF Premium</b>",
"parse_mode"=>"HTML",
"reply_markup"=>json_encode([
"inline_keyboard"=>[
[
["text"=>"🗑 Apagar","callback_data"=>"apagar_msg"]
],
[
["text"=>"🚀 Adquirir Bot","url"=>"https://t.me/puxardados5"]
]
]
])
]);

unlink($file);

exit;
}
    
    
    // ===== CALLBACK CPF BOTÕES =====

if(str_starts_with($callback["data"],"cpf_")){

    $dados = explode("|",$callback["data"]);
    $tipo = $dados[0];
    $cpf  = $dados[1];
    
    // nome do módulo
    if($tipo == "cpf_simples"){
        $modulo = "CPF Simples";
    }

    if($tipo == "cpf_full"){
        $modulo = "CPF Completo";
    }
    
    if($tipo == "cpf2"){
        consultaCPF2($chat,$cpf);
    }

    if($tipo == "cpf_vizinhos"){
        $modulo = "Vizinhos pelo CPF";
    }

    if($tipo == "cpf_parentes"){
        $modulo = "Parentes pelo CPF";
    }
    
tg("editMessageText",[
"chat_id"=>$chat,
"message_id"=>$msg,
"text"=>"🔎 <b>CONSULTA INICIADA</b>

📂 <b>Módulo:</b> {$modulo}
🪪 <b>CPF:</b> <code>{$cpf}</code>

⏳ <i>Processando consulta nas bases de dados...</i>

━━━━━━━━━━━━━━
💎 <b>Consulta VIP Astro Search</b>",
"parse_mode"=>"HTML"
]);

    if(!isVip($id) && !isFreeGroup($chat)){
    bloquearConsulta($chat);
    exit;
}

    if($tipo == "cpf_simples"){
    consultaCPF($chat,$cpf);
}

if($tipo == "cpf_full"){
    consultaCPF1($chat,$cpf);
}

if($tipo == "cpf_vizinhos"){
    consultaVizinhos($chat,$cpf);
}

if($tipo == "cpf_parentes"){
    consultaParentes($chat,$cpf);
}

    exit;
}

    switch($callback["data"]){

        case "catalogo_1":
            catalogo1($chat,$msg);
        break;

        case "catalogo_2":
            catalogo2($chat,$msg);
        break;

        case "voltar_menu":
            menuPrincipal($chat,$nome,$id,true,$msg);
        break;

        case "apagar_msg":
            tg("deleteMessage",[
                "chat_id"=>$chat,
                "message_id"=>$msg
            ]);
        break;

        case "planos":

global $PIX_VALOR, $PIX_CHAVE, $PIX_NOME;

$textoPlano = "⭐ <b>PLANO VITALÍCIO — ASTRO SEARCH</b>

Tenha acesso completo às consultas VIP
sem mensalidade e sem limites 🚀

━━━━━━━━━━━━━━━━
🔓 <b>O que você desbloqueia</b>

✔️ CPF
✔️ Nome
✔️ RG
✔️ CNH
✔️ Telefone
✔️ E-mail
✔️ Placa
✔️ PIX
✔️ Renavam
✔️ Nascimento

━━━━━━━━━━━━━━━━
♻️ <b>Consultas grátis</b>

• CEP
• CNPJ
• IP

━━━━━━━━━━━━━━━━
💰 <b>Valor único</b>

<b>R$ {$PIX_VALOR}</b>

🔑 Chave PIX:
<code>{$PIX_CHAVE}</code>
👤 {$PIX_NOME}";

$kb = json_encode([
    "inline_keyboard"=>[
        [["text"=>"📩 Enviar Comprovante","url"=>"https://t.me/puxardados5"]],
        [["text"=>"⬅️ Menu","callback_data"=>"voltar_menu"]]
    ]
]);

if(isset($callback["message"]["photo"])){

    tg("editMessageCaption",[
        "chat_id"=>$chat,
        "message_id"=>$msg,
        "caption"=>$textoPlano,
        "parse_mode"=>"HTML",
        "reply_markup"=>$kb
    ]);

}else{

    tg("editMessageText",[
        "chat_id"=>$chat,
        "message_id"=>$msg,
        "text"=>$textoPlano,
        "parse_mode"=>"HTML",
        "reply_markup"=>$kb
    ]);
}

break;

case "copiar_pix":

global $PIX_CHAVE;

answer($callback["id"]);

$novoTexto = "📋 <b>CHAVE PIX COPIADA!</b>

Agora é só colar no seu banco 👇

<code>{$PIX_CHAVE}</code>

⚡ Após o pagamento envie o comprovante para ativação.";

// verifica se a mensagem tem foto
if(isset($callback["message"]["photo"])){

    tg("editMessageCaption",[
        "chat_id"=>$chat,
        "message_id"=>$msg,
        "caption"=>$novoTexto,
        "parse_mode"=>"HTML",
        "reply_markup"=>json_encode([
            "inline_keyboard"=>[
                [["text"=>"🚀 ENVIAR COMPROVANTE","url"=>"https://t.me/puxardados5"]]
            ]
        ])
    ]);

}else{

    tg("editMessageText",[
        "chat_id"=>$chat,
        "message_id"=>$msg,
        "text"=>$novoTexto,
        "parse_mode"=>"HTML"
    ]);

}

break;

        case "conta":

$plano = isVip($id) ? "VIP" : "Grátis";

            tg("editMessageCaption",[
                "chat_id"=>$chat,
                "message_id"=>$msg,
                "caption"=>
"👤 <b>MINHA CONTA</b>

🆔 ID: <code>{$id}</code>
👤 Nome: <b>{$nome}</b>
⭐ Plano: <b>{$plano}</b>",
                "parse_mode"=>"HTML",
                "reply_markup"=>json_encode([
                    "inline_keyboard"=>[
                        [["text"=>"⬅️ Menu","callback_data"=>"voltar_menu"]]
                    ]
                ])
            ]);

        break;

    }

    exit;
}

echo "OK";