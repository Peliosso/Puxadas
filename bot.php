<?php
error_reporting(0);

/* ================= CONFIG ================= */

$TOKEN = "8241553232:AAGvxGZhHWJkAzKxQ-RsE-Efvy-e4q2XI4U";
$API   = "https://api.telegram.org/bot{$TOKEN}";
$START_PHOTO = "https://conventional-magenta-fxkyikrbqe.edgeone.app/E8D6A8B8-36F3-4AE0-8493-E2C66DF18EF3.png9CB5-E627CBA570D7.png";

$PIX_VALOR = "25,00";
$PIX_CHAVE = "sua-chave-pix@exemplo.com";
$PIX_NOME  = "SEARCH PANEL";

/* ================= UPDATE ================= */

$update = json_decode(file_get_contents("php://input"), true);
$message  = $update["message"] ?? null;
$callback = $update["callback_query"] ?? null;

/* ================= API ================= */

function tg($method, $data) {
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

function menuPrincipal($chat, $nome = "usuário", $edit = false, $msg = null){
    global $START_PHOTO;

    $text =
"<b>🚀 • Astro Search</b>

Olá!</b>
Eu sou o Astro Search, o melhor sistema de consultas do Telegram.

<i>👇 • Escolha uma opção abaixo:</i>";

    $kb = [
        "inline_keyboard"=>[
            [["text"=>"📂 CONSULTAS","callback_data"=>"catalogo_1"]],
            [["text"=>"⭐ PLANOS","callback_data"=>"planos"]],
            [["text"=>"👤 MINHA CONTA","callback_data"=>"conta"]],
            [["text"=>"🛠 SUPORTE","callback_data"=>"suporte"]],
        ]
    ];

    if ($edit) {
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

/* ================= CATÁLOGO ================= */

function catalogo1($chat,$msg){
$text =
"<b>🔎 • CONSULTAS DISPONÍVEIS — 1/3</b>

<b>👩🏼 • IDENTIFICAÇÃO</b>
• CPF
• CPF (Base Secundária)
• RG
• CNH
• Número de Segurança CNH
• Nome Completo
• Nomes Abreviados
• Data de Nascimento

<b>☎️ • CONTATO</b>
• Telefone Móvel
• Telefone Móvel 2
• Telefone Fixo
• E-mail
• CEP";

$kb = [
 "inline_keyboard"=>[
   [["text"=>"➡️ Próxima","callback_data"=>"catalogo_2"]],
   [["text"=>"🔒 Ativar Plano","callback_data"=>"planos"]],
   [["text"=>"⬅️ Voltar","callback_data"=>"voltar_menu"]],
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
"<b>CONSULTAS DISPONÍVEIS — 2/3</b>

<b>🚛 • VEÍCULOS</b>
• Placa (Dados completos)
• RENAVAM
• Frota Veicular
• Vistoria
• Radar Veicular

<b>💸 • FINANCEIRO</b>
• Score de Crédito
• Histórico Financeiro
• Dívidas
• Comprovantes PIX
• IRPF";

$kb = [
 "inline_keyboard"=>[
   [["text"=>"⬅️ Anterior","callback_data"=>"catalogo_1"],["text"=>"➡️ Próxima","callback_data"=>"catalogo_3"]],
   [["text"=>"🔒 Ativar Plano","callback_data"=>"planos"]],
   [["text"=>"⬅️ Voltar","callback_data"=>"voltar_menu"]],
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

function catalogo3($chat,$msg){
$text =
"<b>CONSULTAS DISPONÍVEIS — 3/3</b>

<b>⚖️ • GOVERNAMENTAL</b>
• Receita Federal
• INSS
• RAIS
• Vacinação
• Processos Judiciais
• Mandados
• Boletins de Ocorrência

<b>🧿 • AVANÇADO</b>
• Cruzamento de Dados
• Relacionamentos
• Presença Visual Associada";

$kb = [
 "inline_keyboard"=>[
   [["text"=>"⬅️ Anterior","callback_data"=>"catalogo_2"]],
   [["text"=>"🔒 Ativar Plano","callback_data"=>"planos"]],
   [["text"=>"⬅️ Voltar","callback_data"=>"voltar_menu"]],
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
    menuPrincipal($message["chat"]["id"]);
    exit;
}

/* ================= CALLBACKS ================= */

if($callback){
 answer($callback["id"]);

 $chat = $callback["message"]["chat"]["id"];
 $msg  = $callback["message"]["message_id"];

 switch($callback["data"]){
    case "catalogo_1": catalogo1($chat,$msg); break;
    case "catalogo_2": catalogo2($chat,$msg); break;
    case "catalogo_3": catalogo3($chat,$msg); break;

    case "planos":
        tg("editMessageCaption",[
          "chat_id"=>$chat,
          "message_id"=>$msg,
          "caption"=>"<b>PLANO VITALÍCIO</b>\n\nValor único: R$ {$GLOBALS['PIX_VALOR']}\n\nAcesso total ao catálogo\nUso ilimitado\n\nPIX:\n{$GLOBALS['PIX_CHAVE']}\n{$GLOBALS['PIX_NOME']}",
          "parse_mode"=>"HTML",
          "reply_markup"=>json_encode([
            "inline_keyboard"=>[
              [["text"=>"⬅️ Voltar","callback_data"=>"voltar_menu"]]
            ]
          ])
        ]);
    break;

    case "voltar_menu":
        menuPrincipal($chat,true,$msg);
    break;
 }
 exit;
}

echo "OK";