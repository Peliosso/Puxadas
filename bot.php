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
    7140709439,
    1895859230,
    5230009284,
    6697676301,
    8795946397,
    8336817408,
    7974240263,
    6461132437,
    8117165076,
    5093190011,
    1451468505,
    5399517696,
    6191515910,
    7897196233,
    5743644042,
    6161509600,
    6133561216,
    1351720003,
    8280476731,
    2107079968,
    2107079968,
    8671212881,
    879440244,
    6482205760,
    108532746,
    5874889278,
    5145160762,
    7235201678,
    225552877,
    6561953037,
    6208327464,
    964661976,
    6634452971,
    8743074571,
    7404132980,
    2055451956,
    5557211646,
    7731604667,
    8795946397,
    7245638408,
    8402973433,
    1851151030,
    7004715777,
    7780684991,
    5964205067,
    7758810507,
    5666410972,
    1962958129,
    2045565712,
    7701930128,
    8065951293,
    8658282196,
    7976099511,
    6972694274,
    5250526805,
    1869239539,
    5945788705,
    1862035229,
    2045565712,
    8664447389,
    8658282196,
    7840156033,
    2043153783,
    8295233979,
    8712004708,
    7768611465,
    8486243491,
    7297717991,
    8363051485,
    8275555157,
    8640515513,
    8616777736,
    171169888,
    1175766878,
    1215057510,
    5726958451,
    7888932006,
    1018224339,
    8727596264,
    7164175282,
    6694878952,
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
    1617865549,
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
function bloquearConsulta($chat){

    $usuarios = rand(20,60);

    tg("sendPhoto",[
        "chat_id"=>$chat,
        "photo"=>"https://conventional-magenta-fxkyikrbqe.edgeone.app/E8D6A8B8-36F3-4AE0-8493-E2C66DF18EF3.png", // pode trocar pela sua
        "caption"=>
"🔒 <b>ACESSO RESTRITO</b>

Essa é uma consulta exclusiva para usuários VIP.

Seu plano atual é <b>Gratuito</b> e possui limitações.

━━━━━━━━━━━━━━━
💎 <b>Com o VIP você desbloqueia:</b>

• Todas as consultas  
• Dados completos  
• Respostas mais rápidas  
• Sem limites  

━━━━━━━━━━━━━━━
💰 <b>Pagamento único: R$ 15,00</b>

🚀 Liberação instantânea após pagamento

👇 Clique abaixo para ativar:",
        "parse_mode"=>"HTML",
        "reply_markup"=>json_encode([
            "inline_keyboard"=>[
                [["text"=>"💳 Ativar VIP","callback_data"=>"planos"]],
                [["text"=>"⬅️ Menu","callback_data"=>"voltar_menu"]]
            ]
        ])
    ]);

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
"caption"=>"🚀 <b>MENU DE CONSULTAS</b>

Escolha uma categoria:",
"parse_mode"=>"HTML",
"reply_markup"=>json_encode([
"inline_keyboard"=>[

[
["text"=>"🔱 Consultas VIP","callback_data"=>"menu_vip"],
["text"=>"♻️ Consultas Grátis","callback_data"=>"menu_free"]
],

[
["text"=>"⬅️ Menu","callback_data"=>"voltar_menu"]
]

]
])
]);

}

function menuVip($chat,$msg){

tg("editMessageCaption",[
"chat_id"=>$chat,
"message_id"=>$msg,
"caption"=>"🔱 <b>CONSULTAS VIP</b>

Escolha uma consulta:",
"parse_mode"=>"HTML",
"reply_markup"=>json_encode([
"inline_keyboard"=>[

[
["text"=>"🧾 CPF","callback_data"=>"menu_cpf"],
["text"=>"👤 Nome","callback_data"=>"menu_nome"]
],

[
["text"=>"📱 Telefone","callback_data"=>"menu_tel"],
["text"=>"🚗 Placa","callback_data"=>"menu_placa"]
],

[
["text"=>"👨‍👩‍👧 Parentes","callback_data"=>"menu_parentes"],
["text"=>"🏠 Vizinhos","callback_data"=>"menu_vizinhos"]
],

[
["text"=>"📸 Foto","callback_data"=>"menu_foto"],
["text"=>"📧 Email","callback_data"=>"menu_email"]
],

[
["text"=>"💎 Ativar VIP","callback_data"=>"planos"]
],

[
["text"=>"⬅️ Voltar","callback_data"=>"catalogo_1"]
]

]
])
]);

}

function menuFree($chat,$msg){

tg("editMessageCaption",[
"chat_id"=>$chat,
"message_id"=>$msg,
"caption"=>"♻️ <b>CONSULTAS GRÁTIS</b>

Escolha uma consulta:",
"parse_mode"=>"HTML",
"reply_markup"=>json_encode([
"inline_keyboard"=>[

[
["text"=>"🌐 IP","callback_data"=>"menu_ip"],
["text"=>"🏢 CNPJ","callback_data"=>"menu_cnpj"]
],

[
["text"=>"📍 CEP","callback_data"=>"menu_cep"]
],

[
["text"=>"⬅️ Voltar","callback_data"=>"catalogo_1"]
]

]
])
]);

}

function telaTutorial($chat,$msg,$titulo,$cmd,$exemplo){

tg("editMessageCaption",[
"chat_id"=>$chat,
"message_id"=>$msg,
"caption"=>
"📘 <b>{$titulo}</b>

🧠 <b>Como usar:</b>

<code>{$cmd} {$exemplo}</code>

━━━━━━━━━━━━━━━
💡 <b>Dica:</b>
Envie o comando exatamente assim no chat.

👇 Clique abaixo para voltar",
"parse_mode"=>"HTML",
"reply_markup"=>json_encode([
"inline_keyboard"=>[
[
["text"=>"⬅️ Voltar","callback_data"=>"catalogo_1"]
]
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

    // Função auxiliar
    function v($v) {
        return ($v === null || $v === "" || stripos($v, "Sem Informação") !== false)
            ? "NÃO ENCONTRADO"
            : $v;
    }

    // Sticker loading
    $sticker = tg("sendSticker", [
        "chat_id" => $chat,
        "sticker" => $STICKER_LOADING
    ]);
    $stickerData = json_decode($sticker, true);
    $stickerMsgId = $stickerData["result"]["message_id"] ?? null;

    // Limpa telefone
    $telefone = preg_replace('/\D/', '', $telefone);

    // Validação
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

    // NOVA API
    $url = "https://obitostore.shop/api/consulta/telefone?telefone={$telefone}&apikey=bigmouthh";

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

    // Validação resposta
    if (!$data || empty($data["resultado"])) {
        naoEncontrado($chat, "TELEFONE", $telefone);
        return;
    }

    // Parse do resultado
    $resultado = $data["resultado"];

    // REGISTROS (simplificado para 2 primeiros registros e resumo)
    preg_match_all("/REGISTRO (\d+)\s+NOME: (.+?)\s+CPF\/CNPJ: (.+?)\s+DATA DE NASCIMENTO: (.+?)\s+NOME DA MÃE: (.+?)(?:\n\n|$)/i", $resultado, $matches, PREG_SET_ORDER);

    $r1 = $matches[0] ?? [];
    $r2 = $matches[1] ?? [];

    // Resumo
    preg_match("/RESUMO DA CONSULTA\s+DATA DA CONSULTA: (.+?)\s+EXPIRA EM: (.+?)\s+TOTAL DE REGISTROS: (\d+)/i", $resultado, $resumo);

    // Monta endereço do registro 1
    preg_match("/TIPO LOGRADOURO: (.*?)\s+LOGRADOURO: (.*?)\s+NÚMERO: (.*?)\s+BAIRRO: (.*?)\s+CIDADE: (.*?)\s+UF: (.*?)\s+CEP: (.*?)\s*/i", $resultado, $enderecoMatch);

    $endereco = isset($enderecoMatch[1]) 
        ? trim("{$enderecoMatch[1]} {$enderecoMatch[2]}, {$enderecoMatch[3]} - {$enderecoMatch[4]} - {$enderecoMatch[5]}/{$enderecoMatch[6]}") 
        : "NÃO ENCONTRADO";

    // TXT COMPLETO
    $txt = "
╔══════════════════════════════╗
   CONSULTA TELEFONE — ASTRO SEARCH
╚══════════════════════════════╝

📱 TELEFONE
──────────────────────────────
{$telefone}

👤 REGISTRO PRINCIPAL
──────────────────────────────
Nome: ".v($r1[2] ?? null)."
CPF/CNPJ: ".v($r1[3] ?? null)."
Nascimento: ".v($r1[4] ?? null)."
Mãe: ".v($r1[5] ?? null)."

🏠 ENDEREÇO
──────────────────────────────
{$endereco}

👤 REGISTRO SECUNDÁRIO
──────────────────────────────
Nome: ".v($r2[2] ?? null)."
CPF/CNPJ: ".v($r2[3] ?? null)."
Nascimento: ".v($r2[4] ?? null)."
Mãe: ".v($r2[5] ?? null)."

📊 RESUMO
──────────────────────────────
Data: ".v($resumo[1] ?? null)."
Expira: ".v($resumo[2] ?? null)."
Total: ".v($resumo[3] ?? null)."

──────────────────────────────
ASTRO SEARCH
";

    // Cria TXT
    $file = tempnam(sys_get_temp_dir(), "tel_");
    file_put_contents($file, $txt);

    // Preview VIP
    $preview = "
💎 <b>Consulta VIP Realizada</b>

<blockquote>
👤 ".v($r1[2] ?? $r2[2] ?? null)."
📱 {$telefone}
🪪 ".v($r1[3] ?? $r2[3] ?? null)."
📍 ".v($enderecoMatch[5] ?? null)." - ".v($enderecoMatch[6] ?? null)."
</blockquote>

📄 Relatório completo disponível no arquivo.

🔓 <i>Acesso total liberado via TXT.</i>
";

    // Envia
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
                    ["text"=>"🗑 • Apagar","callback_data"=>"apagar_msg"]
                ]
            ]
        ])
    ]);

    unlink($file);
}

function consultaNome($chat, $nome) {

    global $STICKER_LOADING;

    // Função auxiliar
    function v($v) {
        return ($v === null || $v === "" || stripos($v, "DESCONHECIDO") !== false)
            ? "NÃO ENCONTRADO"
            : $v;
    }

    // Sticker loading
    $sticker = tg("sendSticker", [
        "chat_id" => $chat,
        "sticker" => $STICKER_LOADING
    ]);
    $stickerData = json_decode($sticker, true);
    $stickerMsgId = $stickerData["result"]["message_id"] ?? null;

    // Validação
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

    // Nova API
    $nomeUrl = urlencode($nome);
    $url = "https://obitostore.shop/api/consulta/nome3?nome={$nomeUrl}&apikey=bigmouthh";

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

    // Validação resposta
    if (!$data || empty($data["resultado"])) {
        naoEncontrado($chat, "NOME", $nome);
        return;
    }

    // Parse do resultado
    preg_match_all("/NOME: (.+?)\s+CPF: (.+?)\s+DATA DE NASCIMENTO: (.+?)\s+SEXO: (.+?)\s+NOME DA MÃE: (.+?)\s+SITUAÇÃO CADASTRAL: (.+?)\s+ENDEREÇO COMPLETO: (.+?)(?:\n\n|$)/i", $data["resultado"], $matches, PREG_SET_ORDER);

    if (empty($matches)) {
        naoEncontrado($chat, "NOME", $nome);
        return;
    }

    // TXT COMPLETO
    $txt = "
╔══════════════════════════════╗
   CONSULTA POR NOME — ASTRO SEARCH
╚══════════════════════════════╝

🔎 NOME PESQUISADO
──────────────────────────────
{$nome}

📊 TOTAL ENCONTRADOS
──────────────────────────────
".count($matches)."
";

    foreach ($matches as $pessoa) {
        $txt .= "

👤 DADOS ENCONTRADOS
──────────────────────────────
Nome: ".v($pessoa[1] ?? null)."
CPF: ".v($pessoa[2] ?? null)."
Nascimento: ".v($pessoa[3] ?? null)."
Sexo: ".v($pessoa[4] ?? null)."
Mãe: ".v($pessoa[5] ?? null)."
Situação: ".v($pessoa[6] ?? null)."

🏠 ENDEREÇO
──────────────────────────────
".v($pessoa[7] ?? null)."

──────────────────────────────
";
    }

    $txt .= "
Consulta realizada via:
ASTRO SEARCH
";

    // Cria TXT
    $file = tempnam(sys_get_temp_dir(), "nome_");
    file_put_contents($file, $txt);

    $pessoa = $matches[0] ?? [];

    // Preview VIP
    $preview = "
💎 <b>Consulta VIP Realizada</b>

<blockquote>
👤 ".v($pessoa[1] ?? null)."
🪪 ".v($pessoa[2] ?? null)."
🎂 ".v($pessoa[3] ?? null)."
⚧ ".v($pessoa[4] ?? null)."
</blockquote>

📄 Relatório completo disponível no arquivo.

🔓 <i>Acesso total liberado via TXT.</i>
";

    // Envia
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
                    ["text"=>"🗑 • Apagar","callback_data"=>"apagar_msg"]
                ]
            ]
        ])
    ]);

    unlink($file);
}

function consultaCpf4($chat,$cpf){

global $STICKER_LOADING;

function v($v){
return ($v === null || $v === "" || $v === "NULL" || strpos($v,"SEM INFORMA") !== false) ? "NÃO ENCONTRADO" : $v;
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
"text"=>"❌ CPF inválido.\nUse: <code>/cpf4 00000000000</code>",
"parse_mode"=>"HTML"
]);

return;
}

// 🌐 API NOVA
$url = "https://boks.stherlionato.workers.dev/cpf?cpf={$cpf}&token=VIP_123";

$ch = curl_init();
curl_setopt_array($ch,[
CURLOPT_URL => $url,
CURLOPT_RETURNTRANSFER => true,
CURLOPT_TIMEOUT => 30
]);

$response = curl_exec($ch);
$data = json_decode($response,true);
curl_close($ch);

if($stickerMsgId){
tg("deleteMessage",[
"chat_id"=>$chat,
"message_id"=>$stickerMsgId
]);
}

// 🚨 VALIDAÇÃO CORRETA (igual cpf3)
if(empty($data["result"]["informaes_bsicas"][0])){

tg("sendMessage",[
"chat_id"=>$chat,
"text"=>"❌ CPF não encontrado."
]);

return;
}

$r = $data["result"];
$b = $r["informaes_bsicas"][0];

$txt = "
╔══════════════════════════════╗
   CONSULTA CPF ULTRA — ASTRO SEARCH
╚══════════════════════════════╝

🧠 DADOS PRINCIPAIS
──────────────────────────────
Nome: ".v($b["nome"])."
CPF: ".v($b["cpf"])."
Nascimento: ".v($b["data_de_nascimento"])."
Sexo: ".v($b["sexo"])."
Mãe: ".v($b["nome_da_me"])."
Pai: ".v($b["nome_do_pai"])."
Situação: ".v($b["situao_cadastral"])."
";

$txt .= "

💰 DADOS ECONÔMICOS
──────────────────────────────
";

foreach(($r["dados_econmicos"] ?? []) as $eco){
$txt .= "
Renda: ".v($eco["renda"])."
Score: ".v($eco["score_csb"])."
Risco: ".v($eco["faixa_de_risco_csb"])."
";
}

$txt .= "

📞 TELEFONES
──────────────────────────────
";

foreach(($r["telefones"] ?? []) as $t){
$txt .= $t["nmero"]." | ".v($t["tipo"])."\n";
}

$txt .= "

📧 EMAILS
──────────────────────────────
";

foreach(($r["emails"] ?? []) as $e){
$txt .= v($e["email"])."\n";
}

$txt .= "

📍 ENDEREÇOS
──────────────────────────────
";

foreach(($r["endereos"] ?? []) as $e){
$txt .= "
".v($e["logradouro"]).", ".v($e["nmero"])."
".v($e["bairro"])."
".v($e["cidade"])." - ".v($e["uf"])."
CEP: ".v($e["cep"])."
";
}

$txt .= "

👨‍👩‍👧 PARENTES
──────────────────────────────
";

foreach(($r["parentes"] ?? []) as $p){
$txt .= v($p["nome"])." - ".v($p["grau_de_parentesco"])."\n";
}

$txt .= "

🏢 EMPRESAS
──────────────────────────────
";

foreach(($r["empresas"] ?? []) as $e){
$txt .= "CNPJ: ".v($e["cnpj"])." | ".v($e["relao"])."\n";
}

$txt .= "

💰 BENEFÍCIOS
──────────────────────────────
";

foreach(($r["benefcios"] ?? []) as $b2){
$txt .= $b2["tipo"].": ".$b2["total_recebido"]."\n";
}

$txt .= "

💉 VACINAS
──────────────────────────────
";

foreach(($r["vacinas"] ?? []) as $vcn){
$txt .= $vcn["fabricante"]." - ".$vcn["data_aplicao"]."\n";
}

$txt .= "

🏘 VIZINHOS
──────────────────────────────
";

foreach(($r["vizinhos"] ?? []) as $v){
$txt .= v($v["nome"])."\n";
}

$txt .= "

🛒 COMPRAS
──────────────────────────────
";

foreach(($r["compras_identificadas"] ?? []) as $c){
$txt .= v($c["produto"])." - ".v($c["preo"])."\n";
}

$txt .= "

📊 PERFIL DE CONSUMO
──────────────────────────────
";

foreach(($r["perfil_de_consumo"] ?? []) as $pc){
foreach($pc as $k=>$v2){
$txt .= "{$k}: {$v2}\n";
}
}

$txt .= "

──────────────────────────────
Consulta realizada via:
ASTRO SEARCH ULTRA
";

// 📁 TXT
$file = tempnam(sys_get_temp_dir(),"cpf4_");
file_put_contents($file,$txt);

// 💎 Preview (igual cpf3)
$preview = "
💎 <b>Consulta VIP Realizada</b>

<blockquote>
👤 ".v($b["nome"])."
🪪 CPF: ".v($b["cpf"])."
🎂 ".v($b["data_de_nascimento"])."
👩 Mãe: ".v($b["nome_da_me"])."
</blockquote>

📄 Relatório completo disponível no arquivo TXT.
";

tg("sendDocument",[
"chat_id"=>$chat,
"document"=>new CURLFile($file,"text/plain","cpf4_{$cpf}.txt"),
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
    $url = "https://knowsapi.shop/api/consultas/parentes?cpf={$cpf}&apikey=bigmouth";
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

function consultaCPF1($chat, $cpf) {
    global $STICKER_LOADING;

    // Envia sticker de carregando
    $sticker = tg("sendSticker", [
        "chat_id" => $chat,
        "sticker" => $STICKER_LOADING
    ]);
    $stickerData = json_decode($sticker, true);
    $stickerMsgId = $stickerData["result"]["message_id"] ?? null;

    $cpf = preg_replace('/\D/', '', $cpf);
    if (strlen($cpf) != 11) {
        if ($stickerMsgId) {
            tg("deleteMessage", ["chat_id" => $chat, "message_id" => $stickerMsgId]);
        }
        tg("sendMessage", [
            "chat_id" => $chat,
            "text" => "❌ CPF inválido.\nUse: <code>/cpf5 00000000000</code>",
            "parse_mode" => "HTML"
        ]);
        return;
    }

    // Consulta API
    $url = "https://obitostore.shop/api/consulta/cpf5?cpf={$cpf}&apikey=bigmouthh";
    $resp = @file_get_contents($url);
    $json = json_decode($resp, true);

    if ($stickerMsgId) {
        tg("deleteMessage", ["chat_id" => $chat, "message_id" => $stickerMsgId]);
    }

    if (!$json || $json["status"] != "ok") {
        naoEncontrado($chat, "CPF", $cpf);
        return;
    }

    $p = $json["resultado"]; // resultado vem como string, precisa tratar se quiser separar campos

    // Monta arquivo TXT com todos os dados retornados
    $txt = "CONSULTA CPF — FULL\n=================================\n\n";
    $txt .= $p; // Mantém o retorno completo como string

    $txt .= "\n--------------------------------\nConsulta via: Astro Search";

    $file = tempnam(sys_get_temp_dir(), "cpf_");
    file_put_contents($file, $txt);

    tg("sendDocument", [
        "chat_id" => $chat,
        "document" => new CURLFile($file, "text/plain", "cpf_{$cpf}.txt"),
        "caption" => "🧾 <b>Consulta de CPF concluída</b>\n\n⚡ API: <b>Astro</b>",
        "parse_mode" => "HTML",
        "reply_markup" => json_encode([
            "inline_keyboard" => [
                [
                    ["text" => "🗑 Apagar", "callback_data" => "apagar_msg"],
                    ["text" => "💎 • Ativar VIP", "callback_data" => "planos"]
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

function consultaPlaca($chat,$placa){

global $STICKER_LOADING;

function v($v){
return ($v === null || $v === "" || $v === "SEM INFORMAÇÃO") ? "NÃO ENCONTRADO" : $v;
}

$sticker = tg("sendSticker",[
"chat_id"=>$chat,
"sticker"=>$STICKER_LOADING
]);

$stickerData = json_decode($sticker,true);
$stickerMsgId = $stickerData["result"]["message_id"] ?? null;

/* LIMPA PLACA */
$placa = strtoupper(preg_replace('/[^A-Za-z0-9]/','',$placa));

if(strlen($placa) != 7){

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

/* API NOVA */
$url = "https://knowsapi.shop/api/consulta/placa-v2?placa={$placa}&apikey=bigmouth";

$ch = curl_init();
curl_setopt_array($ch,[
CURLOPT_URL => $url,
CURLOPT_RETURNTRANSFER => true,
CURLOPT_TIMEOUT => 20
]);

$response = curl_exec($ch);
$data = json_decode($response,true);
curl_close($ch);

/* REMOVE LOADING */
if($stickerMsgId){
tg("deleteMessage",[
"chat_id"=>$chat,
"message_id"=>$stickerMsgId
]);
}

/* ERRO */
if(!$data || !$data["status"]){

tg("sendMessage",[
"chat_id"=>$chat,
"text"=>"❌ Placa não encontrada."
]);

return;
}

$r = $data["resultado"];
$v = $r["detalhes_veiculo"];
$i = $r["identificadores"];
$g = $r["geografia"];
$l = $r["legal"];
$p = $r["proprietario"];

/* MONTA TXT */

$txt = "
╔══════════════════════════════╗
   CONSULTA PLACA ULTRA — ASTRO SEARCH
╚══════════════════════════════╝

🚗 DADOS DO VEÍCULO
──────────────────────────────
Placa: ".v($v["placa"])."
Cor: ".v($v["cor"])."
Ano Fabricação: ".v($v["ano_fab"])."
Ano Modelo: ".v($v["ano_mod"])."
Combustível: ".v($v["combustivel"])."
Potência: ".v($v["potencia"])."
Cilindradas: ".v($v["cilindradas"])."
Tipo: ".v($v["tipo"])."
Espécie: ".v($v["especie"])."
Passageiros: ".v($v["passageiros"])."

🔎 IDENTIFICADORES
──────────────────────────────
Chassi: ".v($i["chassi"])."
Renavam: ".v($i["renavam"])."
Motor: ".v($i["motor"])."
Origem: ".v($i["origem"])."

🌍 LOCALIZAÇÃO
──────────────────────────────
Atual: ".v($g["atual"])."
Fabricação: ".v($g["fabricacao"])."

⚖️ SITUAÇÃO LEGAL
──────────────────────────────
Situação: ".v($l["situacao"])."
Última Atualização: ".v($l["ultima_atualizacao"])."
Emissão CRV: ".v($l["emissao_crv"])."
";

/* RESTRIÇÕES */
$txt .= "
⚠️ RESTRIÇÕES
──────────────────────────────
";

if(!empty($l["restricoes"])){
foreach($l["restricoes"] as $res){
$txt .= v($res)."\n";
}
}else{
$txt .= "NENHUMA\n";
}

/* PROPRIETÁRIO */
$txt .= "

👤 PROPRIETÁRIO
──────────────────────────────
Nome: ".v($p["nome"])."
Documento: ".v($p["documento"])."
";

/* FINAL */
$txt .= "

──────────────────────────────
Consulta realizada via:
ASTRO SEARCH ULTRA
";

/* CRIA ARQUIVO */
$file = tempnam(sys_get_temp_dir(),"placa_");
file_put_contents($file,$txt);

/* PREVIEW */
$preview = "
💎 <b>Consulta VIP Realizada</b>

<blockquote>
🚗 Placa: ".v($v["placa"])."
🎨 Cor: ".v($v["cor"])."
📅 ".v($v["ano_mod"])."
⚖️ Situação: ".v($l["situacao"])."
📍 ".v($g["atual"])."
</blockquote>

📄 Relatório completo disponível no arquivo TXT.
";

/* ENVIA */
tg("sendDocument",[
"chat_id"=>$chat,
"document"=>new CURLFile($file,"text/plain","placa_{$placa}.txt"),
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
$vipCmds = ["/cpf","/fotorj","/fotosp","/instagram","/cpf1","/cpf2","/cpf3","/cpf4","/vizinhos","/parentes","/nome","/rg","/cnh","/telefone","/email","/placa","/pix","/renavam","/nascimento","/foto"];
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

if($cmd === "/cpf4"){
    consultaCpf4($chat, $arg);
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
// ABRIR MENUS
// =========================

if($data == "menu_vip"){
    menuVip($chat,$msg);
    exit;
}

if($data == "menu_free"){
    menuFree($chat,$msg);
    exit;
}

if(strpos($data,"menu_") === 0){

$vipMenus = ["menu_cpf","menu_nome","menu_tel","menu_placa","menu_parentes","menu_vizinhos","menu_foto","menu_email"];

if(in_array($data,$vipMenus)){

    if(!isVip($id) && !isFreeGroup($chat)){

        tg("answerCallbackQuery",[
            "callback_query_id"=>$callback["id"],
            "text"=>"🔒 Apenas VIP",
            "show_alert"=>true
        ]);

        return;
    }

}
}
    
    // =========================
// MENUS DE CONSULTA
// =========================

if($data == "menu_cpf"){
    telaTutorial($chat,$msg,"Consulta de CPF","/cpf","12345678900");
    exit;
}

if($data == "menu_nome"){
    telaTutorial($chat,$msg,"Consulta por Nome","/nome","João Silva");
    exit;
}

if($data == "menu_tel"){
    telaTutorial($chat,$msg,"Consulta de Telefone","/telefone","31999999999");
    exit;
}

if($data == "menu_placa"){
    telaTutorial($chat,$msg,"Consulta de Placa","/placa","ABC1D23");
    exit;
}

if($data == "menu_parentes"){
    telaTutorial($chat,$msg,"Consulta de Parentes","/parentes","12345678900");
    exit;
}

if($data == "menu_vizinhos"){
    telaTutorial($chat,$msg,"Consulta de Vizinhos","/vizinhos","12345678900");
    exit;
}

if($data == "menu_foto"){
    telaTutorial($chat,$msg,"Consulta de Foto","/foto","12345678900");
    exit;
}

if($data == "menu_email"){
    telaTutorial($chat,$msg,"Consulta de Email","/email","teste@email.com");
    exit;
}

if($data == "menu_ip"){
    telaTutorial($chat,$msg,"Consulta de IP","/ip","8.8.8.8");
    exit;
}

if($data == "menu_cnpj"){
    telaTutorial($chat,$msg,"Consulta de CNPJ","/cnpj","00000000000100");
    exit;
}

if($data == "menu_cep"){
    telaTutorial($chat,$msg,"Consulta de CEP","/cep","01001000");
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
    $txid  = $json["txid"] ?? "";
    $pix   = $json["pixCopiaECola"];

    // 🔥 EMBUTE ID DO USUÁRIO NO BOTÃO
    $callbackCheck = "verificar_{$txid}_{$id}";

    tg("editMessageCaption",[
        "chat_id"=>$chat,
        "message_id"=>$msg,
        "caption"=>"💰 <b>R$ {$valor}</b>\n\n🔑 <code>{$txid}</code>\n\n📋 <b>PIX Copia e Cola:</b>\n<code>{$pix}</code>",
        "parse_mode"=>"HTML",
        "reply_markup"=>json_encode([
            "inline_keyboard"=>[
                [["text"=>"✅ Verificar Pagamento","callback_data"=>$callbackCheck]],
                [["text"=>"⬅️ Menu","callback_data"=>"voltar_menu"]]
            ]
        ])
    ]);

    exit;
}
    
if(strpos($data,"verificar_") === 0){

    // formato: verificar_TXID_USERID
    $partes = explode("_",$data);

    $txid = $partes[1] ?? "";
    $user_id = $partes[2] ?? "";

    if(!$txid || !$user_id){
        tg("answerCallbackQuery",[
            "callback_query_id"=>$callback["id"],
            "text"=>"❌ Erro interno",
            "show_alert"=>true
        ]);
        exit;
    }

    $url = "https://promstpagamentos.discloud.app/verify_payment?payment_id={$txid}";
    $res = @file_get_contents($url);

    if(!$res){
        tg("answerCallbackQuery",[
            "callback_query_id"=>$callback["id"],
            "text"=>"❌ Erro na API",
            "show_alert"=>true
        ]);
        exit;
    }

    $json = json_decode($res,true);

    // ✅ PAGOU
    if(isset($json["status_pagamento"]) && $json["status_pagamento"] == "CONCLUIDA"){

        // 🔥 LIBERA DIRETO (SEM SALVAR)
        if(!in_array($user_id,$VIP_IDS)){
            $VIP_IDS[] = (int)$user_id;
        }

        tg("editMessageCaption",[
            "chat_id"=>$chat,
            "message_id"=>$msg,
            "caption"=>"✅ <b>PAGAMENTO CONFIRMADO!</b>\n\n👑 VIP liberado 🚀",
            "parse_mode"=>"HTML"
        ]);

    } else {

        tg("answerCallbackQuery",[
            "callback_query_id"=>$callback["id"],
            "text"=>"⏳ Ainda não foi pago",
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
        if($tipo == "cpf4") consultaCpf4($chat,$cpf);
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