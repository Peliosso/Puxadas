<?php
error_reporting(0);

/* ================= CONFIG ================= */

$TOKEN = "8241553232:AAGvxGZhHWJkAzKxQ-RsE-Efvy-e4q2XI4U";
$API   = "https://api.telegram.org/bot{$TOKEN}";

/* IMAGEM */
$START_PHOTO = "https://conventional-magenta-fxkyikrbqe.edgeone.app/E8D6A8B8-36F3-4AE0-8493-E2C66DF18EF3.png";

/* PIX */
$PIX_VALOR = "25,00";
$PIX_CHAVE = "70192823698";
$PIX_NOME  = "Isabelly";

/* ================= UPDATE ================= */

$update   = json_decode(file_get_contents("php://input"), true);
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

function tutorial($chat,$cmd){
    $map = [
        "/cpf"=>"<b>/cpf</b>\nExemplo:\n<code>/cpf 00000000000</code>",
        "/nome"=>"<b>/nome</b>\nExemplo:\n<code>/nome João Silva</code>",
        "/rg"=>"<b>/rg</b>\nExemplo:\n<code>/rg 1234567</code>",
        "/cnh"=>"<b>/cnh</b>\nExemplo:\n<code>/cnh 123456789</code>",
        "/telefone"=>"<b>/telefone</b>\nExemplo:\n<code>/telefone 11999999999</code>",
        "/email"=>"<b>/email</b>\nExemplo:\n<code>/email teste@email.com</code>",
        "/placa"=>"<b>/placa</b>\nExemplo:\n<code>/placa ABC1D23</code>",
        "/pix"=>"<b>/pix</b>\nExemplo:\n<code>/pix chavepix</code>",
    ];

    tg("sendMessage",[
        "chat_id"=>$chat,
        "text"=>"📘 <b>Como usar</b>\n\n".($map[$cmd] ?? "Use corretamente."),
        "parse_mode"=>"HTML"
    ]);
}

function bloquearConsulta($chat){
    tg("sendMessage",[
        "chat_id"=>$chat,
        "text"=>"🔒 <b>Consulta bloqueada</b>\n\nAdquira um plano para realizar consultas.",
        "parse_mode"=>"HTML",
        "reply_markup"=>json_encode([
            "inline_keyboard"=>[
                [["text"=>"⭐ Ver Planos","callback_data"=>"planos"]]
            ]
        ])
    ]);
}

/* ================= MENU ================= */

function menuPrincipal($chat,$nome,$edit=false,$msg=null){
    global $START_PHOTO;

    $text =
"<b>🚀 • Astro Search</b>

Olá, <b>{$nome}</b>!
Escolha uma opção abaixo:";

    $kb = [
        "inline_keyboard"=>[
            [
                ["text"=>"📂 Consultas","callback_data"=>"catalogo_1"],
                ["text"=>"👤 Minha conta","callback_data"=>"conta"]
            ],
            [
                ["text"=>"⭐ Planos","callback_data"=>"planos"],
                ["text"=>"🛠 Suporte","url"=>"https://t.me/silenciante"]
            ]
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
    tg("editMessageCaption",[
        "chat_id"=>$chat,
        "message_id"=>$msg,
        "caption"=>
"🚀 <b>CONSULTAS — 1/2</b>

🔱 <b>VIP</b>

/cpf
/foto
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

function catalogo3($chat,$msg){
    tg("editMessageCaption",[
        "chat_id"=>$chat,
        "message_id"=>$msg,
        "caption"=>
"🚀 <b>CONSULTAS — 2/2</b>

♻️ <b>Grátis</b>

/cep
/cnpj
/ip",
        "parse_mode"=>"HTML",
        "reply_markup"=>json_encode([
            "inline_keyboard"=>[
                [["text"=>"⬅️ Anterior","callback_data"=>"catalogo_2"]],
                [["text"=>"⬅️ Menu","callback_data"=>"voltar_menu"]],
            ]
        ])
    ]);
}

/* ================= START ================= */

if($message && in_array($message["text"],["/start","/menu"])){
    menuPrincipal(
        $message["chat"]["id"],
        $message["from"]["first_name"] ?? "usuário"
    );
    exit;
}

/* ================= COMANDOS ================= */

if($message && isset($message["text"]) && str_starts_with($message["text"], "/")){
    $chat = $message["chat"]["id"];
    $p = explode(" ", trim($message["text"]), 2);
    $cmd = strtolower($p[0]);
    $arg = $p[1] ?? null;

    $vip = ["/cpf","/nome","/rg","/cnh","/telefone","/email","/placa","/pix"];

    if(in_array($cmd,$vip)){
        $arg ? bloquearConsulta($chat) : tutorial($chat,$cmd);
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

    switch($callback["data"]){
        case "catalogo_1": catalogo1($chat,$msg); break;
        case "catalogo_2": catalogo2($chat,$msg); break;
        case "catalogo_3": catalogo3($chat,$msg); break;

        case "planos":
            tg("editMessageCaption",[
                "chat_id"=>$chat,
                "message_id"=>$msg,
                "caption"=>"⭐ <b>PLANO VITALÍCIO</b>\n\nValor: R$ {$PIX_VALOR}\n\nPIX:\n{$PIX_CHAVE}\n{$PIX_NOME}",
                "parse_mode"=>"HTML",
                "reply_markup"=>json_encode([
                    "inline_keyboard"=>[
                        [["text"=>"⬅️ Menu","callback_data"=>"voltar_menu"]]
                    ]
                ])
            ]);
        break;

        case "conta":
            tg("editMessageCaption",[
                "chat_id"=>$chat,
                "message_id"=>$msg,
                "caption"=>
"👤 <b>MINHA CONTA</b>

🆔 ID: <code>{$id}</code>
👤 Nome: <b>{$nome}</b>
⭐ Plano: <b>Grátis</b>",
                "parse_mode"=>"HTML",
                "reply_markup"=>json_encode([
                    "inline_keyboard"=>[
                        [["text"=>"⬅️ Menu","callback_data"=>"voltar_menu"]]
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