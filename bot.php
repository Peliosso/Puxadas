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
$STICKER_LOADING = "CAACAgIAAxkBAAEQUkBpdQ4VdCPwAybo7q4AAVMxYnM6HzYAAhYMAAL5LuBLduZ5vHwXjSs4BA";

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
        "/cpf"=>"<b>/cpf</b>\nExemplo:\n<code>/cpf 00000000000</code>",
        "/nome"=>"<b>/nome</b>\nExemplo:\n<code>/nome João Silva</code>",
        "/rg"=>"<b>/rg</b>\nExemplo:\n<code>/rg 1234567</code>",
        "/cnh"=>"<b>/cnh</b>\nExemplo:\n<code>/cnh 123456789</code>",
        "/telefone"=>"<b>/telefone</b>\nExemplo:\n<code>/telefone 11999999999</code>",
        "/email"=>"<b>/email</b>\nExemplo:\n<code>/email teste@email.com</code>",
        "/placa"=>"<b>/placa</b>\nExemplo:\n<code>/placa ABC1D23</code>",
        "/pix"=>"<b>/pix</b>\nExemplo:\n<code>/pix chavepix</code>",
        "/cep"=>"<b>/cep</b>\nExemplo:\n<code>/cep 01001000</code>",
        "/cnpj"=>"<b>/cnpj</b>\nExemplo:\n<code>/cnpj 00000000000100</code>",
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

function catalogo2($chat,$msg){
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
                [["text"=>"⬅️ Anterior","callback_data"=>"catalogo_1"]],
                [["text"=>"⬅️ Menu","callback_data"=>"voltar_menu"]],
            ]
        ])
    ]);
}

function consultaCNPJ($chat, $cnpj){
    global $STICKER_LOADING;

    // Sticker carregando (NÃO some depois)
    tg("sendSticker",[
        "chat_id"=>$chat,
        "sticker"=>$STICKER_LOADING
    ]);

    // Limpa CNPJ
    $cnpj = preg_replace('/\D/','',$cnpj);

    if(strlen($cnpj) !== 14){
        tg("sendMessage",[
            "chat_id"=>$chat,
            "text"=>"❌ CNPJ inválido.\nUse: <code>/cnpj 00000000000100</code>",
            "parse_mode"=>"HTML"
        ]);
        return;
    }

    // Consulta BrasilAPI
    $resp = @file_get_contents("https://brasilapi.com.br/api/cnpj/v1/{$cnpj}");
    $data = json_decode($resp, true);

    if(!$data || isset($data["message"])){
        tg("sendMessage",[
            "chat_id"=>$chat,
            "text"=>"❌ CNPJ não encontrado."
        ]);
        return;
    }

    // TXT formatado
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
Créditos: Astro Search
";

    // Cria arquivo
    $file = tempnam(sys_get_temp_dir(), "cnpj_");
    file_put_contents($file, $txt);

    // Envia TXT
    tg("sendDocument",[
        "chat_id"=>$chat,
        "document"=>new CURLFile($file, "text/plain", "cnpj_{$cnpj}.txt"),
        "caption"=>"🏢 <b>Consulta de CNPJ concluída</b>\n\nCréditos: <b>Astro Search</b>",
        "parse_mode"=>"HTML",
        "reply_markup"=>json_encode([
            "inline_keyboard"=>[
                [
                    ["text"=>"🗑 Apagar","callback_data"=>"apagar_msg"],
                    ["text"=>"🚀 Adquirir Bot","url"=>"https://t.me/silenciante"]
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
                    ["text"=>"🚀 Adquirir Bot","url"=>"https://t.me/silenciante"]
                ]
            ]
        ])
    ]);

    unlink($file);
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

if($cmd === "/cnpj"){
    $arg ? consultaCNPJ($chat, $arg) : tutorial($chat, "/cnpj");
    exit;
}

if($cmd === "/cep"){
    $arg ? consultaCEP($chat, $arg) : tutorial($chat, "/cep");
    exit;
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
        
        case "apagar_msg":
    tg("deleteMessage",[
        "chat_id"=>$chat,
        "message_id"=>$msg
    ]);
break;

        case "planos":

    $hasPhoto = isset($callback["message"]["photo"]);

    $method = $hasPhoto ? "editMessageCaption" : "editMessageText";

    $data = [
        "chat_id" => $chat,
        "message_id" => $msg,
        "parse_mode" => "HTML",
        "reply_markup" => json_encode([
            "inline_keyboard" => [
                [["text"=>"⬅️ Menu","callback_data"=>"voltar_menu"]]
            ]
        ])
    ];

    if($hasPhoto){
        $data["caption"] =
"⭐ <b>PLANO VITALÍCIO</b>

Valor: R$ {$PIX_VALOR}

PIX:
{$PIX_CHAVE}
{$PIX_NOME}";
    } else {
        $data["text"] =
"⭐ <b>PLANO VITALÍCIO</b>

Valor: R$ {$PIX_VALOR}

PIX:
{$PIX_CHAVE}
{$PIX_NOME}";
    }

    tg($method, $data);
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