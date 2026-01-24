<?php
error_reporting(0);

/* ================= CONFIG ================= */

$TOKEN = "8241553232:AAGvxGZhHWJkAzKxQ-RsE-Efvy-e4q2XI4U";
$API   = "https://api.telegram.org/bot{$TOKEN}";

/* IMAGEM VÁLIDA */
$START_PHOTO = "https://conventional-magenta-fxkyikrbqe.edgeone.app/E8D6A8B8-36F3-4AE0-8493-E2C66DF18EF3.png";

/* PIX */
$PIX_VALOR = "25,00";
$PIX_CHAVE = "70192823698";
$PIX_NOME  = "Isabelly";

/* ================= UPDATE ================= */

$update = json_decode(file_get_contents("php://input"), true);
$message  = $update["message"] ?? null;
$callback = $update["callback_query"] ?? null;

/* ================= API ================= */

function tg($method, $data){
    global $API;
    $ch = curl_init($API."/".$method);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $data
    ]);
    curl_exec($ch);
    curl_close($ch);
}

function answer($id){
    tg("answerCallbackQuery", ["callback_query_id"=>$id]);
}

/* ================= TUTORIAL / BLOQUEIO ================= */

function tutorial($chat, $cmd){
    $t = [
        "/cpf" => "📘 <b>Como usar /cpf</b>\n\nExemplo:\n<code>/cpf 00000000000</code>",
        "/nome" => "📘 <b>Como usar /nome</b>\n\nExemplo:\n<code>/nome João Silva</code>",
        "/rg" => "📘 <b>Como usar /rg</b>\n\nExemplo:\n<code>/rg 1234567</code>",
        "/cnh" => "📘 <b>Como usar /cnh</b>\n\nExemplo:\n<code>/cnh 123456789</code>",
        "/telefone" => "📘 <b>Como usar /telefone</b>\n\nExemplo:\n<code>/telefone 11999999999</code>",
        "/email" => "📘 <b>Como usar /email</b>\n\nExemplo:\n<code>/email teste@email.com</code>",
        "/placa" => "📘 <b>Como usar /placa</b>\n\nExemplo:\n<code>/placa ABC1D23</code>",
        "/pix" => "📘 <b>Como usar /pix</b>\n\nExemplo:\n<code>/pix chavepix</code>",
    ];

    tg("sendMessage",[
        "chat_id"=>$chat,
        "text"=>$t[$cmd] ?? "📘 Use o comando corretamente.",
        "parse_mode"=>"HTML"
    ]);
}

function bloquearConsulta($chat){
    tg("sendMessage",[
        "chat_id"=>$chat,
        "text"=>"🔒 <b>Consulta bloqueada</b>\n\nPara realizar consultas, adquira um plano.",
        "parse_mode"=>"HTML",
        "reply_markup"=>json_encode([
            "inline_keyboard"=>[
                [["text"=>"⭐ Adquirir Plano","callback_data"=>"planos"]]
            ]
        ])
    ]);
}

/* ================= MENU PRINCIPAL ================= */

function menuPrincipal($chat, $nome="usuário", $edit=false, $msg=null){
    global $START_PHOTO;

    $text =
"<b>🚀 • Astro Search</b>

Olá, <b>{$nome}</b>!
Eu sou o Astro Search, o sistema de consultas mais avançado do Telegram.

<i>👇 Escolha uma opção abaixo:</i>";

    $kb = [
        "inline_keyboard"=>[
            [["text"=>"📂 CONSULTAS","callback_data"=>"catalogo_1"]],
            [["text"=>"⭐ PLANOS","callback_data"=>"planos"]],
            [["text"=>"👤 MINHA CONTA","callback_data"=>"conta"]],
            [["text"=>"🛠 SUPORTE","callback_data"=>"suporte"]],
        ]
    ];

    if($edit){
        tg("editMessageCaption",[
            "chat_id"=>$chat,
            "message_id"=>$msg,
            "caption"=>$text,
            "parse_mode"=>"HTML",
            "reply_markup"=>json_encode($kb)
        ]);
    } else {
        tg("sendPhoto",[
            "chat_id"=>$chat,
            "photo"=>$START_PHOTO,
            "caption"=>$text,
            "parse_mode"=>"HTML",
            "reply_markup"=>json_encode($kb)
        ]);
    }
}

/* ================= CATÁLOGOS ================= */

function catalogo1($chat,$msg){
$text =
"🚀 • <b>CONSULTAS — 1/2</b>

🔱 • <b>VIPs</b>

<i>Clique em algumas das opções e veja como funciona:</i>

• /cpf
• /rg 
• /cnh  
• /nome   
• /telefone  
• /email
• /placa
• /pix";

$kb = [
 "inline_keyboard"=>[
   [["text"=>"➡️ Próxima","callback_data"=>"catalogo_2"]],
   [["text"=>"🔒 Ativar Plano","callback_data"=>"planos"]],
   [["text"=>"⬅️ Menu","callback_data"=>"voltar_menu"]],
 ]
];

tg("editMessageCaption",[
 "chat_id"=>$chat,
 "message_id"=>$msg,
 "caption"=>$text,
 "parse_mode"=>"HTML",
 "reply_markup"=>json_encode($kb)
]);
}

function catalogo2($chat,$msg){
$text =
"🚀 • <b>CONSULTAS — 2/2</b>

♻️ • <b>Grátis</b>

/cep
/cnpj
/ip";

$kb = [
 "inline_keyboard"=>[
   [["⬅️ Anterior","callback_data"=>"catalogo_1"]],
   [["🔒 Ativar Plano","callback_data"=>"planos"]],
   [["⬅️ Menu","callback_data"=>"voltar_menu"]],
 ]
];

tg("editMessageCaption",[
 "chat_id"=>$chat,
 "message_id"=>$msg,
 "caption"=>$text,
 "parse_mode"=>"HTML",
 "reply_markup"=>json_encode($kb)
]);
}

/* ================= START ================= */

if($message && in_array($message["text"],["/start","/menu"])){
    $nome = $message["from"]["first_name"] ?? "usuário";
    menuPrincipal($message["chat"]["id"], $nome);
    exit;
}

/* ================= COMANDOS (TUTORIAL x BLOQUEIO) ================= */

if($message && isset($message["text"]) && str_starts_with($message["text"], "/")){

    $chat = $message["chat"]["id"];
    $txt  = trim($message["text"]);

    $p = explode(" ", $txt, 2);
    $cmd  = strtolower($p[0]);
    $args = $p[1] ?? null;

    $vip = ["/cpf","/nome","/rg","/cnh","/telefone","/email","/placa","/pix"];

    if(in_array($cmd, $vip)){
        if(!$args){
            tutorial($chat, $cmd);
        } else {
            bloquearConsulta($chat);
        }
        exit;
    }
}

/* ================= CALLBACKS ================= */

if($callback){
 answer($callback["id"]);

 $chat = $callback["message"]["chat"]["id"];
 $msg  = $callback["message"]["message_id"];
 $nome = $callback["from"]["first_name"] ?? "usuário";

 switch($callback["data"]){
    case "catalogo_1": catalogo1($chat,$msg); break;
    case "catalogo_2": catalogo2($chat,$msg); break;

    case "planos":
        tg("editMessageCaption",[
          "chat_id"=>$chat,
          "message_id"=>$msg,
          "caption"=>"😱 • <b>PLANO VITALÍCIO</b>\n\nValor único: R$ {$PIX_VALOR}\n\nAcesso total\nUso ilimitado\n\nPIX:\n{$PIX_CHAVE}\n{$PIX_NOME}",
          "parse_mode"=>"HTML",
          "reply_markup"=>json_encode([
            "inline_keyboard"=>[
              [["⬅️ Menu","callback_data"=>"voltar_menu"]]
            ]
          ])
        ]);
    break;

    case "voltar_menu":
        menuPrincipal($chat,$nome,true,$msg);
    break;
 }
 exit;
}

echo "OK";