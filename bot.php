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
$PIX_VALOR = "15.00"; // ponto, não vírgula
$PIX_CHAVE = "f0d0f3b1-8776-4f06-a254-b6ea3686f71a";
$PIX_NOME  = "Gabriel Lorenzo";
$STICKER_LOADING = "CAACAgIAAxkBAAEQUkBpdQ4VdCPwAybo7q4AAVMxYnM6HzYAAhYMAAL5LuBLduZ5vHwXjSs4BA";

/* ================= VIP ================= */

$VIP_IDS = [
    171169888,
    7792311413,
    8679921343,
    171169888,
    6724549900,
    8471976799,
    822346206,
    8550726184,
    1704280203,
    6724549900,
    1205786957,
    1424457458,
    6791526676,
    6408092056,
    871109971,
    6924959323,
    1460964575,
    965277749,
    8086542899,
    2117572146,
    8067257278,
    8502519543,
    8437582232,
    5605728414,
    1765820688,
    1089315459,
    7558946881,
    8743506469,
    6014131536,
    5410252210,
    8743506469,
    1021467790,
    8441333056,
    2023464913,
    6988598938,
    2061920799,
    2061920799,
    7397253532,
    8012569221,
    5172295193,
    6640524081,
    358634745,
    5525235852,
    358634745,
    5346865563,
    8556709571,
    8411405940,
    8208712261,
    7327049434,
    1280697559,
    6952471374,
    8284336458,
    8615901022,
    1274608554,
    8576896559,
    2007607056,
    5701277621,
    1960842608,
    8380734988,
    7327049434,
    8352881138,
    1488496249,
    8337423001,
    5200170128,
    1960842608,
    5530503070,
    8524745450,
    5509802326,
    6321358779,
    5530503070,
    1656522961,
    8027672578,
    83807334988,
    8685582189,
    798589011,
    5224372137,
    8325121933,
    8631733055,
    7073604499,
    1361607036,
    8564990473,
    1161783839,
    7255909074,
    712236564,
    7889761800,
    1200912475,
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
    6254661844,
    8750636531,
    8658282196,
    5157554321,
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
    6930966079,
    853186865,
    5993467951,
    7383802170,
    5342332792,
    7442715942,
    6818620184,
    8405956241,
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
    6399681729,
    675983900,
    8532218232,
    7412958426,
    7524424065,
    940636198, 
    6024687334,
    2047110506,
    7785849676,
    6952471374,
    8538480916,
    6687664198,
    5805915267,
    1236474129,
];

$BANIDOS = [
    8017850151
];

function isVip($id){

global $VIP_IDS;

if(in_array($id,$VIP_IDS)){
return true;
}

$vipFile = __DIR__."/vip_users.json";

if(!file_exists($vipFile)){
return false;
}

$vip = json_decode(file_get_contents($vipFile),true);

if(!is_array($vip)){
return false;
}

return in_array($id,$vip);

}

function isBanned($id){
    global $BANIDOS;
    return in_array($id, $BANIDOS);
}

function isGroupChat($type){
    return in_array($type, ["group","supergroup"]);
}

define("VIP_CODES_DB","vip_codes.json");
$OWNER_ID = 7320236887;

/* ================= FREE MODE GRUPOS ================= */

define("FREE_DB","free_groups.json");

function ativarFreeGrupo($chat){

    $data = [];

    if(file_exists(FREE_DB)){
        $data = json_decode(file_get_contents(FREE_DB), true);
    }

    $data[$chat] = time() + (60*60);

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
$userId = $message["from"]["id"] ?? null;
$chatType = $message["chat"]["type"] ?? null;

/* APAGAR COMANDOS NO GRUPO (EXCETO DO ADMIN) */

$ADMIN_ID = 7320236887; // seu ID

if($message && isset($message["text"])){

if(
    ($chatType == "group" || $chatType == "supergroup") &&
    substr($message["text"],0,1) == "/" &&
    $userId != $ADMIN_ID
){

tg("deleteMessage",[
"chat_id"=>$chat,
"message_id"=>$msgId
]);

}

}

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
if($data == "bloquear_consulta"){

    // 👥 Prova social
    $usuarios = rand(20,60);

    tg("editMessageCaption",[
        "chat_id"=>$chat,
        "message_id"=>$msg,
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
💰 <b>VALOR VITALÍCIO:</b> R$ 15,00

👇 Clique abaixo para continuar:",
        "parse_mode"=>"HTML",
        "reply_markup"=>json_encode([
            "inline_keyboard"=>[
                [["text"=>"💳 Gerar PIX","callback_data"=>"gerar_pix"]],
                [["text"=>"⬅️ Menu","callback_data"=>"voltar_menu"]]
            ]
        ])
    ]);

    exit;
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
/cpf3 - 🆕
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

if(strpos($text,"/vip") === 0){

    if($userId != $OWNER_ID){
        tg("sendMessage",[
            "chat_id"=>$chat,
            "text"=>"❌ Apenas o dono pode usar."
        ]);
        exit;
    }

    if(!isGroupChat($message["chat"]["type"])){
        tg("sendMessage",[
            "chat_id"=>$chat,
            "text"=>"❌ Use apenas em grupos."
        ]);
        exit;
    }

    $parts = explode(" ",$text);
    $id = $parts[1] ?? null;

    if(!$id){
        tg("sendMessage",[
            "chat_id"=>$chat,
            "text"=>"❌ Use: /vip ID"
        ]);
        exit;
    }

    ativarVipGrupo($id);

    tg("sendMessage",[
        "chat_id"=>$chat,
        "text"=>"👑 <b>GRUPO VIP ATIVADO</b>

ID: <code>$id</code>

Acesso vitalício liberado.",
        "parse_mode"=>"HTML"
    ]);

    exit;
}

define("VIP_DB","vip_groups.json");

function ativarVipGrupo($chat){

    $data = [];

    if(file_exists(VIP_DB)){
        $data = json_decode(file_get_contents(VIP_DB), true);
    }

    $data[$chat] = [
        "vip" => true,
        "ativado_em" => time()
    ];

    file_put_contents(VIP_DB, json_encode($data));
}

function gerarCodigoVip(){

    $codigo = strtoupper(substr(md5(uniqid()),0,10));

    $data = [];

    if(file_exists(VIP_CODES_DB)){
        $data = json_decode(file_get_contents(VIP_CODES_DB),true);
    }

    $data[$codigo] = [
        "usado" => false
    ];

    file_put_contents(VIP_CODES_DB,json_encode($data));

    return $codigo;
}

function resgatarCodigo($codigo,$userId,$username){

    if(!file_exists(VIP_CODES_DB)){
        return "Código inválido.";
    }

    $data = json_decode(file_get_contents(VIP_CODES_DB),true);

    if(!isset($data[$codigo])){
        return "❌ Código inválido.";
    }

    if($data[$codigo]["usado"]){
        return "❌ Código já utilizado.";
    }

    $data[$codigo]["usado"] = true;
    file_put_contents(VIP_CODES_DB,json_encode($data));

    // VIP USERS
/* SALVAR USUÁRIO VIP */

$vipFile = __DIR__."/vip_users.json";

if(!file_exists($vipFile)){
    file_put_contents($vipFile,"[]");
}

$vip = json_decode(file_get_contents($vipFile),true);

if(!is_array($vip)){
    $vip = [];
}

if(!in_array($userId,$vip)){
    $vip[] = $userId;
}

file_put_contents($vipFile,json_encode($vip,JSON_PRETTY_PRINT));

    global $OWNER_ID;

    tg("sendMessage",[
        "chat_id"=>$OWNER_ID,
        "text"=>"💎 <b>NOVO VIP ATIVADO</b>

👤 Usuário: @{$username}
🆔 ID: <code>{$userId}</code>
🔑 Código: <code>{$codigo}</code>",
        "parse_mode"=>"HTML"
    ]);

    return "VIP_ATIVADO";
}

function naoEncontrado($chat,$tipo,$dado){

$data = date("d/m/Y");
$hora = date("H:i");

$txt = 
"CONSULTA REALIZADA — ASTRO SEARCH
=================================

Tipo de consulta: {$tipo}
Dado pesquisado: {$dado}
Data: {$data}
Hora: {$hora}

---------------------------------

Recadinho do astro:

Acessei alguns sistemas, e não achei movimentações dessa pessoa! 🥲

Isso pode acontecer quando:

• O registro não existe nas bases
• Dados muito recentes
• Informações limitadas
";

$file = tempnam(sys_get_temp_dir(),"astro_");
file_put_contents($file,$txt);

tg("sendDocument",[
"chat_id"=>$chat,
"document"=>new CURLFile($file,"text/plain","resultado.txt"),
"caption"=>"📄 Resultado da consulta",
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
["text"=>"💎 • Ativar VIP","callback_data"=>"planos"]
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
["text"=>"💎 • Ativar VIP","callback_data"=>"planos"]
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
["text"=>"💎 • Ativar VIP","callback_data"=>"planos"]
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
["text"=>"💎 • Ativar VIP","callback_data"=>"planos"]
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
        "text"=>"❌ Email inválido.\nUse: <code>/email teste@email.com</code>",
        "parse_mode"=>"HTML"
    ]);

    return;
}

    // 🔎 API
    $url = "https://sara-api.xyz/api/consultas/email?email={$email}&apikey=bigmouth";
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
    naoEncontrado($chat,"EMAIL",$email);
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
    $url = "https://sara-api.xyz/api/consultas/vizinhos?cpf={$cpf}&apikey=bigmouth";
    $resp = @file_get_contents($url);
    $json = json_decode($resp,true);

    if($stickerMsgId){
        tg("deleteMessage",[
            "chat_id"=>$chat,
            "message_id"=>$stickerMsgId
        ]);
    }

    if(!$json || !$json["success"]){
    naoEncontrado($chat,"VIZINHOS",$cpf);
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

    $url = "https://sara-api.xyz/api/consultas/fotosp?cpf={$cpf}&apikey=bigmouth";
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
    $url = "https://sara-api.xyz/api/consultas/fotov2?cpf={$cpf}&apikey=bigmouth";
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

function consultaTelefone($chat, $telefone) {

    global $STICKER_LOADING;

    // Função auxiliar para tratar valores nulos
    function v($v) {
        return ($v === null || $v === "" || $v === "NULL") ? "NÃO ENCONTRADO" : $v;
    }

    // Sticker de carregando
    $sticker = tg("sendSticker", [
        "chat_id" => $chat,
        "sticker" => $STICKER_LOADING
    ]);
    $stickerData = json_decode($sticker, true);
    $stickerMsgId = $stickerData["result"]["message_id"] ?? null;

    // Limpa telefone
    $telefone = preg_replace('/\D/', '', $telefone);

    // Validação mínima
    if (strlen($telefone) < 10) {
        if ($stickerMsgId) {
            tg("deleteMessage", [
                "chat_id" => $chat,
                "message_id" => $stickerMsgId
            ]);
        }
        tg("sendMessage", [
            "chat_id" => $chat,
            "text" => "❌ Telefone inválido.\nUse: <code>/telefone 31999999999</code>",
            "parse_mode" => "HTML"
        ]);
        return;
    }

    // Nova URL da API
    $url = "https://knowsapi.shop/api/consultas/telefone?telefone={$telefone}&apikey=bigmouth";

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 20
    ]);
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);

    // Remove sticker
    if ($stickerMsgId) {
        tg("deleteMessage", [
            "chat_id" => $chat,
            "message_id" => $stickerMsgId
        ]);
    }

    // Se não encontrou resultados
    if (empty($data["body"])) {
        naoEncontrado($chat, "TELEFONE", $telefone);
        return;
    }

    $pessoa = $data["body"][0] ?? [];

    // Monta texto detalhado
    $txt = "
╔══════════════════════════════╗
   CONSULTA TELEFONE — ASTRO SEARCH
╚══════════════════════════════╝

📱 TELEFONE CONSULTADO
──────────────────────────────
{$telefone}

👤 DADOS ENCONTRADOS
──────────────────────────────
Nome: ".v($pessoa["name"] ?? null)."
CPF: ".v($pessoa["cpf"] ?? null)."
Nascimento: ".v($pessoa["birth_date"] ?? null)."
Email: ".v($pessoa["email"] ?? null)."
Cidade: ".v($pessoa["city"] ?? null)."
Estado: ".v($pessoa["state"] ?? null)."

──────────────────────────────

Consulta realizada via:
ASTRO SEARCH
";

    // Cria arquivo TXT temporário
    $file = tempnam(sys_get_temp_dir(), "telefone_");
    file_put_contents($file, $txt);

    // Preview VIP
    $preview = "
💎 <b>Consulta VIP Realizada</b>

<blockquote>
👤 ".v($pessoa["name"] ?? null)."
📱 {$telefone}
🪪 ".v($pessoa["cpf"] ?? null)."
📍 ".v($pessoa["city"] ?? null)." - ".v($pessoa["state"] ?? null)."
📧 ".v($pessoa["email"] ?? null)."
</blockquote>

📄 Um relatório detalhado foi gerado para esta consulta.

🔓 <i>O dossiê completo está disponível no arquivo TXT.</i>
";

    // Envia documento com preview
    tg("sendDocument", [
        "chat_id" => $chat,
        "document" => new CURLFile($file, "text/plain", "telefone_{$telefone}.txt"),
        "caption" => $preview,
        "parse_mode" => "HTML",
        "reply_markup" => json_encode([
            "inline_keyboard" => [
                [
["text"=>"💎 • Ativar VIP","callback_data"=>"planos"]
                ],
                [
                    ["text" => "🗑 • Apagar", "callback_data" => "apagar_msg"]
                ]
            ]
        ])
    ]);

    unlink($file);
}

function consultaNome($chat, $nome) {

    global $STICKER_LOADING;

    // Função auxiliar para tratar valores nulos
    function v($v) {
        return ($v === null || $v === "" || $v === "NULL") ? "NÃO ENCONTRADO" : $v;
    }

    // Envia sticker de carregando
    $sticker = tg("sendSticker", [
        "chat_id" => $chat,
        "sticker" => $STICKER_LOADING
    ]);
    $stickerData = json_decode($sticker, true);
    $stickerMsgId = $stickerData["result"]["message_id"] ?? null;

    // Validação mínima do nome
    if (strlen($nome) < 5) {
        if ($stickerMsgId) {
            tg("deleteMessage", [
                "chat_id" => $chat,
                "message_id" => $stickerMsgId
            ]);
        }
        tg("sendMessage", [
            "chat_id" => $chat,
            "text" => "❌ Nome inválido.\nUse: <code>/nome João Silva</code>",
            "parse_mode" => "HTML"
        ]);
        return;
    }

    // URL da nova API
    $nomeUrl = urlencode($nome);
    $url = "https://knowsapi.shop/api/consultas/nome?nome={$nomeUrl}&apikey=bigmouth";

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 20
    ]);
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);

    // Remove sticker de carregando
    if ($stickerMsgId) {
        tg("deleteMessage", [
            "chat_id" => $chat,
            "message_id" => $stickerMsgId
        ]);
    }

    // Se não encontrou resultados
    if (empty($data["body"])) {
        naoEncontrado($chat, "NOME", $nome);
        return;
    }

    // Monta texto da resposta detalhada
    $txt = "
╔══════════════════════════════╗
   CONSULTA POR NOME — ASTRO SEARCH
╚══════════════════════════════╝

🔎 NOME PESQUISADO
──────────────────────────────
{$nome}

📊 TOTAL ENCONTRADOS
──────────────────────────────
".$data["total_results"]."
";

    foreach ($data["body"] as $pessoa) {
        $txt .= "

👤 DADOS ENCONTRADOS
──────────────────────────────
Nome: ".v($pessoa["name"] ?? null)."
CPF: ".v($pessoa["cpf"] ?? null)."
Sexo: ".v($pessoa["gender"] ?? null)."
Nascimento: ".v($pessoa["birth_date"] ?? null)."
Mãe: ".v($pessoa["mother_name"] ?? null)."
RG: ".v($pessoa["rg"] ?? null)."

──────────────────────────────
";
    }

    $txt .= "
Consulta realizada via:
ASTRO SEARCH
";

    // Cria arquivo TXT temporário com o relatório
    $file = tempnam(sys_get_temp_dir(), "nome_");
    file_put_contents($file, $txt);

    $pessoa = $data["body"][0] ?? [];

    // Mensagem de preview VIP
    $preview = "
💎 <b>Consulta VIP Realizada</b>

<blockquote>
👤 ".v($pessoa["name"] ?? null)."
🪪 ".v($pessoa["cpf"] ?? null)."
🎂 ".v($pessoa["birth_date"] ?? null)."
⚧ ".v($pessoa["gender"] ?? null)."
</blockquote>

📄 Um relatório detalhado foi gerado para esta consulta.

🔓 <i>O dossiê completo está disponível no arquivo TXT.</i>
";

    // Envia documento TXT com preview
    tg("sendDocument", [
        "chat_id" => $chat,
        "document" => new CURLFile($file, "text/plain", "nome.txt"),
        "caption" => $preview,
        "parse_mode" => "HTML",
        "reply_markup" => json_encode([
            "inline_keyboard" => [
                [
["text"=>"💎 • Ativar VIP","callback_data"=>"planos"]
                ],
                [
                    ["text" => "🗑 • Apagar", "callback_data" => "apagar_msg"]
                ]
            ]
        ])
    ]);

    unlink($file);
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
    $url = "https://knowsapi.shop/api/consulta/parentes?cpf={$cpf}&apikey=bigmouth";
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
    naoEncontrado($chat,"PARENTES",$cpf);
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
["text"=>"💎 • Ativar VIP","callback_data"=>"planos"]
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

// NOVA API
$url = "https://api.blackaut.shop/api/dados-pessoais/cpf?cpf={$cpf}&apikey=EbmScZ0ntHf61KJz3H";

$resp = @file_get_contents($url);
$json = json_decode($resp,true);

if($stickerMsgId){
tg("deleteMessage",[
"chat_id"=>$chat,
"message_id"=>$stickerMsgId
]);
}

// VALIDAÇÃO
if(!$json || !$json["status"]){
naoEncontrado($chat,"CPF",$cpf);
return;
}

$p = $json["resultado"];

$txt = "CONSULTA CPF — FULL
=================================

CPF: {$p["cpf"]}
Nome: {$p["name"]}
Nascimento: {$p["birth"]}
Idade: {$p["age"]}
Sexo: {$p["gender"]}

Mãe: {$p["mother_name"]}
Pai: ".($p["father_name"] ?: "Não informado")."

Signo: {$p["sign"]}
Estado Civil: ".($p["marital_status"] ?: "Não informado")."

CBO: {$p["cbo"]}
Situação Receita: {$p["cd_sit_cad"]}
Data Situação: {$p["dt_sit_cad"]}

--------------------------------
";

// ENDEREÇOS
if(!empty($p["addresses"])){

$txt .= "\nENDEREÇOS
--------------------------------\n";

foreach($p["addresses"] as $e){

$txt .= "{$e["logr_type"]} {$e["logr_name"]}, {$e["logr_number"]}\n";
$txt .= "{$e["neighborhood"]} - {$e["city"]}/{$e["state"]}\n";
$txt .= "CEP {$e["zip_code"]}\n\n";

}

}

// TELEFONES
if(!empty($p["telephones"])){

$txt .= "\nTELEFONES
--------------------------------\n";

foreach($p["telephones"] as $t){

$txt .= "({$t["ddd"]}) {$t["phone_number"]}\n";

}

}

// EMAILS
if(!empty($p["emails"])){

$txt .= "\nEMAILS
--------------------------------\n";

foreach($p["emails"] as $e){

$txt .= "{$e["email"]}\n";

}

}

// PIS
if(!empty($p["pis"]["pis_number"])){

$txt .= "\nPIS
--------------------------------\n";
$txt .= "{$p["pis"]["pis_number"]}\n";

}

// SCORE
if(!empty($p["score"])){

$txt .= "\nSCORE
--------------------------------\n";

$txt .= "CSBA: {$p["score"]["csba"]}\n";
$txt .= "Faixa: {$p["score"]["csba_range"]}\n";

}

$txt .= "\n--------------------------------
Consulta via:
Astro Search
";

$file = tempnam(sys_get_temp_dir(),"cpf_");
file_put_contents($file,$txt);

tg("sendDocument",[
"chat_id"=>$chat,
"document"=>new CURLFile($file,"text/plain","cpf_{$cpf}.txt"),
"caption"=>"🧾 <b>Consulta de CPF concluída</b>\n\n⚡ API: <b>Astro</b>",
"parse_mode"=>"HTML",
"reply_markup"=>json_encode([
    "inline_keyboard"=>[
        [
            ["text"=>"🗑 Apagar","callback_data"=>"apagar_msg"],
["text"=>"💎 • Ativar VIP","callback_data"=>"planos"]
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
["text"=>"💎 • Ativar VIP","callback_data"=>"planos"]
]
]
])
]);

}

function consultaCPF3($chat,$cpf){

global $STICKER_LOADING;

function v($v){
return ($v === null || $v === "" || $v === "NULL") ? "NÃO ENCONTRADO" : $v;
}

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
"text"=>"❌ CPF inválido.\nUse: <code>/cpf3 00000000000</code>",
"parse_mode"=>"HTML"
]);

return;
}

$url1 = "https://knowsapi.shop/api/consultas/cpf?cpf={$cpf}&apikey=bigmouth";
$url2 = "https://knowsapi.shop/api/consulta/cpf-v5?code={$cpf}&apikey=bigmouth";

$ch = curl_init();
curl_setopt_array($ch,[
CURLOPT_RETURNTRANSFER => true,
CURLOPT_TIMEOUT => 30
]);

curl_setopt($ch,CURLOPT_URL,$url1);
$res1 = curl_exec($ch);
$data1 = json_decode($res1,true);

curl_setopt($ch,CURLOPT_URL,$url2);
$res2 = curl_exec($ch);
$data2 = json_decode($res2,true);

curl_close($ch);

if($stickerMsgId){
tg("deleteMessage",[
"chat_id"=>$chat,
"message_id"=>$stickerMsgId
]);
}

if(empty($data1["body"]) && empty($data2["resultado"])){

tg("sendMessage",[
"chat_id"=>$chat,
"text"=>"❌ CPF não encontrado."
]);

return;
}

$d = $data1["body"];
$v5 = $data2["resultado"];

$txt = "
╔══════════════════════════════╗
   CONSULTA CPF ULTRA — ASTRO SEARCH
╚══════════════════════════════╝

🧠 DADOS PRINCIPAIS
──────────────────────────────
Nome: ".v($v5["pessoal"]["nome"] ?? $d["name"])."
CPF: ".v($d["cpf_masked"])."
Nascimento: ".v($v5["pessoal"]["nascimento"] ?? $d["birth_date"])."
Sexo: ".v($v5["pessoal"]["sexo"] ?? $d["gender"])."
Raça: ".v($v5["pessoal"]["raca"] ?? null)."
Escolaridade: ".v($v5["pessoal"]["escolaridade"] ?? null)."
Profissão: ".v($v5["pessoal"]["profissao"] ?? null)."

Situação Receita: ".v($d["federal_status"])."

Mãe: ".v($d["mother_name"])."
Pai: ".v($d["father_name"])."

RG: ".v($d["rg"])."
Título eleitor: ".v($d["voter_id"])."

CNS: ".v($v5["documentos"]["cns"] ?? null)."
NIS: ".v($v5["documentos"]["nis"] ?? null)."

💰 DADOS FINANCEIROS
──────────────────────────────
Renda: ".v($v5["financeiro"]["renda"] ?? $d["income"])."
Score: ".v($v5["financeiro"]["score"] ?? $d["score"]["value"])."
INSS: ".v($v5["financeiro"]["inss"] ?? null)."
";

$txt .= "

📡 CONTATOS
──────────────────────────────
";

foreach(($v5["contatos_verificados"]["telefones"] ?? []) as $t){

$wpp = $t["tem_whatsapp"] ? "SIM" : "NÃO";

$txt .= "Telefone: ".$t["numero"]." | WhatsApp: {$wpp}\n";
}

foreach(($v5["contatos_verificados"]["emails"] ?? []) as $e){

$txt .= "Email: {$e}\n";
}

$txt .= "

📍 ENDEREÇOS
──────────────────────────────
";

foreach(($v5["contatos_verificados"]["enderecos"] ?? []) as $e){

$txt .= "{$e}\n";
}

$a = $d["address"] ?? [];

$txt .= "

📌 ENDEREÇO PRINCIPAL
──────────────────────────────
".v($a["type"])." ".v($a["street"])." ".v($a["number"])."
Bairro: ".v($a["neighborhood"])."
Cidade: ".v($a["city"])." - ".v($a["state"])."
CEP: ".v($a["zip_code"])."
";

$txt .= "

🏠 HISTÓRICO DE ENDEREÇOS
──────────────────────────────
";

foreach(($d["all_addresses"] ?? []) as $a){

$txt .= "
".v($a["type"])." ".v($a["street"])." ".v($a["number"])."
".v($a["city"])." - ".v($a["state"])."
CEP: ".v($a["zip_code"])."
Fonte: ".v($a["source"])."
";
}

$txt .= "

👨‍👩‍👧 PARENTES
──────────────────────────────
";

foreach(($v5["filiacao_e_parentes"] ?? []) as $p){

$txt .= v($p["nome"])." - ".v($p["tipo"])."\n";
}

$txt .= "

🏘 VIZINHOS
──────────────────────────────
";

foreach(($data1["body"]["vizinhos"] ?? []) as $v){

$txt .= "
".v($v["nome"])."
".v($v["logradouro"])." ".v($v["numero"])."
Bairro: ".v($v["bairro"])."
";
}

$txt .= "

🛍 PERFIL DE CONSUMO
──────────────────────────────
";

foreach(($v5["perfil_consumo"] ?? []) as $k=>$v){

$txt .= "{$k}: {$v}\n";
}

$txt .= "

💼 HISTÓRICO DE EMPREGOS
──────────────────────────────
";

foreach(($v5["historico_empregos"] ?? []) as $e){

$txt .= "{$e}\n";
}

/* COMPRAS SIMULADAS */

$nascimento = $v5["pessoal"]["nascimento"] ?? $d["birth_date"] ?? null;

if($nascimento){

$idade = floor((time() - strtotime($nascimento)) / 31557600);

if($idade >= 18){

$itens = [
"Biscoitos",
"Refrigerante",
"Café",
"Arroz",
"Sabonete",
"Papel Higiênico",
"Shampoo",
"Cerveja",
"Chocolate",
"Detergente",
"Leite",
"Pão",
"Macarrão",
"Desodorante",
"Cortina",
"Abajur"
];

shuffle($itens);

$qtd = rand(3,7);

$txt .= "

🛒 HISTÓRICO DE COMPRAS
──────────────────────────────
";

for($i=0;$i<$qtd;$i++){

$quant = rand(1,3);

$txt .= $itens[$i]." — {$quant} unidade(s)\n";

}

}

}

$txt .= "

──────────────────────────────
Consulta realizada via:
ASTRO SEARCH ULTRA
";

$file = tempnam(sys_get_temp_dir(),"cpf3_");
file_put_contents($file,$txt);

$preview = "
💎 <b>Consulta VIP Realizada</b>

<blockquote>
👤 ".v($v5["pessoal"]["nome"] ?? $d["name"])."
🪪 CPF: ".v($d["cpf_masked"])."
🎂 ".v($v5["pessoal"]["nascimento"] ?? $d["birth_date"])."
👩 Mãe: ".v($d["mother_name"])."
📍 ".v($d["address"]["city"])." - ".v($d["address"]["state"])."
</blockquote>

📄 Relatório completo disponível no arquivo TXT.
";

tg("sendDocument",[
"chat_id"=>$chat,
"document"=>new CURLFile($file,"text/plain","cpf3_{$cpf}.txt"),
"caption"=>$preview,
"parse_mode"=>"HTML",
"reply_markup"=>json_encode([
"inline_keyboard"=>[
[
["text"=>"💎 • Ativar VIP","callback_data"=>"planos"]
],
[
["text"=>"🗑 • Apagar","callback_data"=>"apagar_msg"]
]
]
])
]);

unlink($file);

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

$url = "https://api.blackaut.shop/api/dados-pessoais/placa?placa={$placa}&apikey=EbmScZ0ntHf61KJz3H";

$resp = @file_get_contents($url);
$json = json_decode($resp,true);

if($stickerMsgId){
tg("deleteMessage",[
"chat_id"=>$chat,
"message_id"=>$stickerMsgId
]);
}

if(!$json || !$json["status"]){

    naoEncontrado($chat,"PLACA",$placa);
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
["text"=>"💎 • Ativar VIP","callback_data"=>"planos"]
]
]
])
]);

unlink($file);

}

function consultaInstagram($chat,$user){

global $STICKER_LOADING;

if(!$user){
tg("sendMessage",[
"chat_id"=>$chat,
"text"=>"❌ Use assim:

/instagram usuario"
]);
return;
}

$sticker = tg("sendSticker",[
"chat_id"=>$chat,
"sticker"=>$STICKER_LOADING
]);

$stickerData = json_decode($sticker,true);
$stickerMsgId = $stickerData["result"]["message_id"] ?? null;

$user = urlencode($user);

$url = "https://sara-api.xyz/api/stalking/instagram?user={$user}&apikey=bigmouth";

$resp = @file_get_contents($url);
$json = json_decode($resp,true);

if($stickerMsgId){
tg("deleteMessage",[
"chat_id"=>$chat,
"message_id"=>$stickerMsgId
]);
}

if(!$json || !$json["data"]["resultado"]){

tg("sendMessage",[
"chat_id"=>$chat,
"text"=>"❌ Instagram não encontrado."
]);

return;
}

$d = $json["data"]["resultado"];

$nome = htmlspecialchars($d["nome"] ?? "Não informado");
$username = htmlspecialchars($d["username"] ?? "");
$id = $d["id"] ?? "Não encontrado";
$categoria = htmlspecialchars($d["categoria"] ?? "Não informado");
$bio = !empty($d["bio"]) ? htmlspecialchars($d["bio"]) : "Sem bio";
$empresa = htmlspecialchars($d["empresa"] ?? "Não informado");
$conta = htmlspecialchars($d["conta"] ?? "Não informado");
$verificada = htmlspecialchars($d["verificada"] ?? "Não informado");
$seguidores = $d["seguidores"] ?? "0";
$seguindo = $d["seguindo"] ?? "0";
$postagens = $d["postagens"] ?? "0";
$foto = $d["imagem"] ?? "https://i.imgur.com/9Xn4K2B.png";

$msg = "📸 <b>CONSULTA INSTAGRAM</b>

👤 <b>Nome:</b> {$nome}
📛 <b>Usuário:</b> @{$username}
🆔 <b>ID:</b> <code>{$id}</code>

🏷 <b>Categoria:</b> {$categoria}
🏢 <b>Empresa:</b> {$empresa}
🔓 <b>Conta:</b> {$conta}
✔️ <b>Verificada:</b> {$verificada}

👥 <b>Seguidores:</b> {$seguidores}
➡️ <b>Seguindo:</b> {$seguindo}
🖼 <b>Postagens:</b> {$postagens}

📝 <b>Bio:</b>
{$bio}";

tg("sendPhoto",[
"chat_id"=>$chat,
"photo"=>$foto,
"caption"=>$msg,
"parse_mode"=>"HTML",
"reply_markup"=>json_encode([
"inline_keyboard"=>[
[
["text"=>"🌐 Abrir Perfil","url"=>"https://instagram.com/".$username]
],
[
["text"=>"🗑 Apagar","callback_data"=>"apagar_msg"]
]
]
])
]);

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
                    ["text"=>"💎 • Ativar VIP","callback_data"=>"planos"]
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
    
/* GERAR VIP */

if($cmd === "/gerarvip"){

    if($userId != $OWNER_ID){
        tg("sendMessage",[
            "chat_id"=>$chat,
            "text"=>"❌ Você não tem permissão."
        ]);
        exit;
    }

    $codigo = gerarCodigoVip();

    tg("sendMessage",[
        "chat_id"=>$chat,
        "text"=>"🔑 <b>CÓDIGO VIP GERADO</b>

<code>{$codigo}</code>

Envie para o cliente usar:

<code>/resgatar {$codigo}</code>",
        "parse_mode"=>"HTML"
    ]);

    exit;
}

if($cmd === "/resgatar"){

    if(!$arg){
        tg("sendMessage",[
            "chat_id"=>$chat,
            "text"=>"Use:\n/resgatar CODIGO"
        ]);
        exit;
    }

$username = $message["from"]["username"] ?? "sem_username";

$resultado = resgatarCodigo($arg,$userId,$username);

    if($resultado == "VIP_ATIVADO"){

        tg("sendMessage",[
            "chat_id"=>$chat,
            "text"=>"✅ <b>VIP ATIVADO!</b>

Agora você tem acesso às consultas VIP 🚀",
            "parse_mode"=>"HTML"
        ]);

    }else{

        tg("sendMessage",[
            "chat_id"=>$chat,
            "text"=>$resultado
        ]);

    }

    exit;
}

    /* ATIVAR FREE GRUPO */

if($cmd === "/freevip"){

    if($userId != $OWNER_ID){
        tg("sendMessage",[
            "chat_id"=>$chat,
            "text"=>"❌ Apenas o dono pode usar."
        ]);
        exit;
    }

    if(!isGroupChat($message["chat"]["type"])){
        tg("sendMessage",[
            "chat_id"=>$chat,
            "text"=>"❌ Apenas em grupos."
        ]);
        exit;
    }

    ativarFreeGrupo($chat);

    tg("sendMessage",[
        "chat_id"=>$chat,
        "text"=>"🚀 <b>VIP LIBERADO NO GRUPO</b>

Todas consultas VIP liberadas por 1 hora.",
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
$vipCmds = ["/cpf","/fotorj","/fotosp","/instagram","/cpf1","/cpf2","/cpf3","/vizinhos","/parentes","/nome","/rg","/cnh","/telefone","/email","/placa","/pix","/renavam","/nascimento","/foto"];
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

if($cmd === "/cpf3"){
    consultaCPF3($chat, $arg);
    exit;
}

if($cmd === "/instagram"){
    consultaInstagram($chat,$arg);
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
    $id   = $callback["from"]["id"];
    $nome = $callback["from"]["first_name"] ?? "usuário";
    $data = $callback["data"] ?? "";

    // =========================
    // VOLTAR MENU
    // =========================
    if($data == "voltar_menu"){
        menuPrincipal($chat, $nome, $id, true, $msg);
        exit;
    }

    // =========================
    // APAGAR MSG
    // =========================
    if($data == "apagar_msg"){
        tg("deleteMessage",[
            "chat_id"=>$chat,
            "message_id"=>$msg
        ]);
        exit;
    }

    // =========================
    // PLANOS
    // =========================
    if($data == "planos"){

        tg("editMessageCaption",[
            "chat_id"=>$chat,
            "message_id"=>$msg,
            "caption"=>"⭐ <b>PLANO VITALÍCIO</b>\n\n💰 R$ 15,00\n\nClique abaixo 👇",
            "parse_mode"=>"HTML",
            "reply_markup"=>json_encode([
                "inline_keyboard"=>[
                    [["text"=>"💳 Gerar PIX","callback_data"=>"gerar_pix"]],
                    [["text"=>"⬅️ Menu","callback_data"=>"voltar_menu"]]
                ]
            ])
        ]);

        exit;
    }

    // =========================
    // GERAR PIX
    // =========================
if($data == "gerar_pix"){

    $user_id = $id; // 👈 usuário do bot (quem vai receber VIP)

    // 🔥 ID FIXO (SEU)
    $url = "https://promstpagamentos.discloud.app/create_payment?user_id=7320236887&valor=15.00";

    $response = @file_get_contents($url);
    $json = json_decode($response,true);

    if(!$json || !isset($json["pixCopiaECola"])){

        tg("answerCallbackQuery",[
            "callback_query_id"=>$callback["id"],
            "text"=>"❌ Erro ao gerar PIX",
            "show_alert"=>true
        ]);

        exit;
    }

    $valor = $json["valor"] ?? "15.00";
    $txid  = $json["txid"] ?? "N/A";
    $pix   = $json["pixCopiaECola"];

    // 🔥 SALVA QUEM GEROU (IMPORTANTÍSSIMO)
    $db = json_decode(@file_get_contents("pagamentos.json"), true) ?? [];

    $db[$txid] = [
        "user_id" => $user_id, // 👈 usuário real do bot
        "status"  => "pendente",
        "valor"   => $valor
    ];

    file_put_contents("pagamentos.json", json_encode($db));

    tg("editMessageCaption",[
        "chat_id"=>$chat,
        "message_id"=>$msg,
        "caption"=>"💰 <b>R$ {$valor}</b>\n\n🔑 <code>{$txid}</code>\n\n📋 <b>PIX Copia e Cola:</b>\n<code>{$pix}</code>",
        "parse_mode"=>"HTML",
        "reply_markup"=>json_encode([
            "inline_keyboard"=>[
                [["text"=>"✅ Verificar Pagamento","callback_data"=>"verificar_{$txid}"]],
                [["text"=>"⬅️ Menu","callback_data"=>"voltar_menu"]]
            ]
        ])
    ]);

    exit;
}
    
if(strpos($data,"verificar_") === 0){

    $txid = str_replace("verificar_","",$data);

    $db = json_decode(@file_get_contents("pagamentos.json"), true);

    if(!isset($db[$txid])){
        tg("answerCallbackQuery",[
            "callback_query_id"=>$callback["id"],
            "text"=>"❌ Pagamento não encontrado no sistema",
            "show_alert"=>true
        ]);
        exit;
    }

    $user_id = $db[$txid]["user_id"];

    $url = "https://promstpagamentos.discloud.app/verify_payment?payment_id={$txid}";
    $res = @file_get_contents($url);

    if(!$res){
        tg("answerCallbackQuery",[
            "callback_query_id"=>$callback["id"],
            "text"=>"❌ Erro ao conectar com API",
            "show_alert"=>true
        ]);
        exit;
    }

    $json = json_decode($res,true);

    // ✅ PAGAMENTO APROVADO
    if(isset($json["status_pagamento"]) && $json["status_pagamento"] == "CONCLUIDA"){

        $db[$txid]["status"] = "pago";
        file_put_contents("pagamentos.json", json_encode($db));

        $vipFile = "vip_users.json";
        $vip = json_decode(@file_get_contents($vipFile), true) ?? [];

        if(!in_array($user_id,$vip)){
            $vip[] = $user_id;
        }

        file_put_contents($vipFile, json_encode($vip));

        tg("editMessageCaption",[
            "chat_id"=>$chat,
            "message_id"=>$msg,
            "caption"=>"✅ <b>PAGAMENTO CONFIRMADO!</b>\n\n👑 VIP liberado automaticamente 🚀",
            "parse_mode"=>"HTML"
        ]);

    } 
    // ❌ NÃO ENCONTRADO / NÃO PAGO
    elseif(isset($json["detail"])){

        tg("answerCallbackQuery",[
            "callback_query_id"=>$callback["id"],
            "text"=>"⏳ Pagamento ainda não foi identificado",
            "show_alert"=>true
        ]);

    } 
    // ⚠️ QUALQUER OUTRO ERRO
    else {

        tg("answerCallbackQuery",[
            "callback_query_id"=>$callback["id"],
            "text"=>"⚠️ Erro ao verificar pagamento",
            "show_alert"=>true
        ]);
    }

    exit;
}

    // =========================
    // CONTA
    // =========================
    if($data == "conta"){

        $plano = isVip($id) ? "VIP" : "Grátis";

        tg("editMessageCaption",[
            "chat_id"=>$chat,
            "message_id"=>$msg,
            "caption"=>"👤 <b>MINHA CONTA</b>\n\n🆔 ID: <code>{$id}</code>\n👤 Nome: <b>{$nome}</b>\n⭐ Plano: <b>{$plano}</b>",
            "parse_mode"=>"HTML",
            "reply_markup"=>json_encode([
                "inline_keyboard"=>[
                    [["text"=>"⬅️ Menu","callback_data"=>"voltar_menu"]]
                ]
            ])
        ]);

        exit;
    }

    // =========================
    // CPF CONSULTAS
    // =========================
    if(str_starts_with($data,"cpf_")){

        $dados = explode("|",$data);
        $tipo = $dados[0] ?? "";
        $cpf  = $dados[1] ?? "";

        tg("editMessageCaption",[
            "chat_id"=>$chat,
            "message_id"=>$msg,
            "caption"=>"🔎 <b>CONSULTANDO...</b>\nCPF: <code>{$cpf}</code>",
            "parse_mode"=>"HTML"
        ]);

        if(!isVip($id) && !isFreeGroup($chat)){
            bloquearConsulta($chat);
            exit;
        }

        if($tipo == "cpf_simples") consultaCPF($chat,$cpf);
        if($tipo == "cpf_full") consultaCPF1($chat,$cpf);
        if($tipo == "cpf2") consultaCPF2($chat,$cpf);
        if($tipo == "cpf3") consultaCPF3($chat,$cpf);
        if($tipo == "cpf_vizinhos") consultaVizinhos($chat,$cpf);
        if($tipo == "cpf_parentes") consultaParentes($chat,$cpf);

        exit;
    }

    // =========================
    // CATALOGO
    // =========================
    if($data == "catalogo_1"){
        catalogo1($chat,$msg);
        exit;
    }

    if($data == "catalogo_2"){
        catalogo2($chat,$msg);
        exit;
    }
}

echo "OK";