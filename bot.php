<?php
error_reporting(0);

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

/* ================= UPDATE ================= */

$update   = json_decode(file_get_contents("php://input"), true);
$message  = $update["message"] ?? null;
$callback = $update["callback_query"] ?? null;

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
        "/rg"          => "1234567",
        "/cnh"         => "12345678900",
        "/telefone"    => "11999999999",
        "/email"       => "teste@email.com",
        "/placa"       => "ABC1D23",
        "/pix"         => "email@pix.com",
        "/renavam"     => "123456789",
        "/nascimento"  => "01012000",
        "/obito" => "11122233344",
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
                [["text"=>"🚀 ATIVAR VIP AGORA","url"=>"https://t.me/acharpessoass"]]
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
["text"=>"🛠 Suporte","url"=>"https://t.me/acharpessoass"]
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

/obito - 🆕
/parentes - 🆕
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
                    ["text"=>"🚀 Adquirir Bot","url"=>"https://t.me/acharpessoass"]
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
                    ["text"=>"🚀 Adquirir Bot","url"=>"https://t.me/acharpessoass"]
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
                    ["text"=>"🚀 Adquirir Bot","url"=>"https://t.me/acharpessoass"]
                ]
            ]
        ])
    ]);

    unlink($file);
}

function enviarFotoCPF($chat){
    
    $cpfFoto = "12345678900"; // CPF fixo
    $caminho = __DIR__."/fotos/".$cpfFoto.".jpg";

    if(!file_exists($caminho)){
        tg("sendMessage",[
            "chat_id"=>$chat,
            "text"=>"❌ Foto não encontrada no servidor."
        ]);
        return;
    }

    tg("sendPhoto",[
        "chat_id"=>$chat,
        "photo"=>new CURLFile($caminho),
        "caption"=>"📸 <b>FOTO LOCALIZADA</b>\n\nCPF: <code>{$cpfFoto}</code>\nCréditos: <b>Astro Search</b>",
        "parse_mode"=>"HTML",
        "reply_markup"=>json_encode([
            "inline_keyboard"=>[
                [
                    ["text"=>"🗑 Apagar","callback_data"=>"apagar_msg"]
                ]
            ]
        ])
    ]);
}

function bloquearObito($chat){
    global $PIX_CHAVE, $PIX_NOME;

    tg("sendMessage",[
        "chat_id"=>$chat,
        "text"=>
"🪦 <b>NOVO SISTEMA DISPONÍVEL!</b>

Agora você pode adicionar o
<b>óbito pela base nacional</b>.

Tenha acesso a:

✔ Integração CADSUS
✔ Data do registro
✔ Situação nas bases
✔ Protocolo oficial

💰 <b>LIBERAÇÃO:</b> R$ 50,00

🔑 <b>CHAVE PIX:</b>
<code>{$PIX_CHAVE}</code>
👤 <b>{$PIX_NOME}</b>

Após o pagamento envie o comprovante.",
        "parse_mode"=>"HTML",
        "reply_markup"=>json_encode([
            "inline_keyboard"=>[
                [["text"=>"📋 COPIAR CHAVE PIX","callback_data"=>"copiar_pix_obito"]],
                [["text"=>"✅ ENVIAR COMPROVANTE","url"=>"https://t.me/acharpessoass"]]
            ]
        ])
    ]);
}

function consultaObito($chat, $cpf){
    global $STICKER_LOADING;

    // 🎬 Sticker loading
    $sticker = tg("sendSticker",[
        "chat_id"=>$chat,
        "sticker"=>$STICKER_LOADING
    ]);

    $stickerData = json_decode($sticker, true);
    $stickerMsgId = $stickerData["result"]["message_id"] ?? null;

    // ⏳ Delay real
    for($i=0;$i<4;$i++){
        tg("sendChatAction",[
            "chat_id"=>$chat,
            "action"=>"typing"
        ]);
        sleep(1);
    }

    $cpf = preg_replace('/\D/','',$cpf);

    // =========================
    // 🔎 CONSULTA API CPF
    // =========================
    $url = "https://sara-api.xyz/api/consultas/cpf?cpf={$cpf}&apikey=bocadavk";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    $response = curl_exec($ch);
    curl_close($ch);

    $api = json_decode($response, true);

    // Pega apenas o body
    $d = $api["body"];

    $nome   = $d["name"];
    $cpfMask = $d["cpf_masked"];
    $sexo   = $d["gender"];
    $nasc   = $d["birth_date"];
    $status = $d["federal_status"];
    $renda  = $d["income"];

    // =========================
    // 🎲 Dados simulados sistema
    // =========================
    $cns = rand(100000000000000, 999999999999999);
    $protocolo = rand(100000000, 999999999);
    $lote = rand(1000, 9999);
    $dataConsulta = date("d/m/Y H:i:s");

    // 🗑 apaga sticker
    if($stickerMsgId){
        tg("deleteMessage",[
            "chat_id"=>$chat,
            "message_id"=>$stickerMsgId
        ]);
    }

    // =========================
    // 📄 CONTEÚDO TXT
    // =========================
    $txt =
"CADSUS • RETORNO DE PROCESSAMENTO
==================================

DADOS DO TITULAR

CPF: {$cpfMask}
Nome: {$nome}
Sexo: {$sexo}
Nascimento: {$nasc}
Situação Receita: {$status}
Renda Declarada: R$ {$renda}

----------------------------------

CNS: {$cns}
PROTOCOLO: {$protocolo}
LOTE: {$lote}

STATUS DO EVENTO
ÓBITO ADICIONADO NA BASE NACIONAL

----------------------------------

Data da consulta: {$dataConsulta}

Prazo de propagação sistêmica:
até 20 dias corridos

----------------------------------
Astro Search • DataSync Engine
";

    $file = tempnam(sys_get_temp_dir(), "obito_");
    file_put_contents($file, $txt);

    // =========================
    // 🪦 MENSAGEM RESUMIDA
    // =========================
    $legenda =
"🪦 <b>ÓBITO ADICIONADO</b>

👤 <b>{$nome}</b>
📄 CPF: <code>{$cpfMask}</code>
📅 {$nasc}
⚖ Receita: {$status}

📄 Relatório completo enviado em TXT.

<i>Astro Search • Sistema Nacional</i>";

    tg("sendDocument",[
        "chat_id"=>$chat,
        "document"=>new CURLFile($file, "text/plain", "obito_{$cpf}.txt"),
        "caption"=>$legenda,
        "parse_mode"=>"HTML",
        "reply_markup"=>json_encode([
            "inline_keyboard"=>[
                [["text"=>"🗑 Apagar","callback_data"=>"apagar_msg"]]
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
    $url = "https://sara-api.xyz/api/consultas/telefone?telefone={$telefone}&apikey=bocadavk";
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

    tg("sendDocument",[
        "chat_id"=>$chat,
        "document"=>new CURLFile($file, "text/plain", "telefone_{$telefone}.txt"),
        "caption"=>"📞 <b>Consulta de Telefone concluída</b>\n\nCréditos: <b>Astro Search</b>",
        "parse_mode"=>"HTML",
        "reply_markup"=>json_encode([
            "inline_keyboard"=>[
                [
                    ["text"=>"🗑 Apagar","callback_data"=>"apagar_msg"],
                    ["text"=>"🚀 Adquirir Bot","url"=>"https://t.me/acharpessoass"]
                ]
            ]
        ])
    ]);

    unlink($file);
}

function consultaNome($chat, $nome){
    global $STICKER_LOADING;

    // 🎬 Sticker loading
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

    // 🔥 API SARA
    $url = "https://sara-api.xyz/api/consultas/nome?nome={$nomeUrl}&apikey=bocadavk";
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
            "text"=>"❌ Nenhum resultado encontrado."
        ]);
        return;
    }

    $txt =
"CONSULTA POR NOME — ASTRO SEARCH
================================

Nome pesquisado: {$json["query"]}
Total encontrados: {$json["total_results"]}

================================
";

    foreach($json["body"] as $pessoa){

        $txt .= "
CPF: {$pessoa["cpf"]}
Nome: {$pessoa["name"]}
Nascimento: {$pessoa["birth_date"]}
Sexo: {$pessoa["gender"]}
Mãe: ".trim($pessoa["mother_name"])."
RG: ".($pessoa["rg"] ?: "Não informado")."

--------------------------------
Consulta via:
Astro Search
";
    }

    $file = tempnam(sys_get_temp_dir(), "nome_");
    file_put_contents($file, $txt);

    tg("sendDocument",[
        "chat_id"=>$chat,
        "document"=>new CURLFile($file, "text/plain", "nome_resultado.txt"),
        "caption"=>"👤 <b>Consulta por nome concluída</b>\n\nCréditos: <b>Astro Search</b>",
        "parse_mode"=>"HTML",
        "reply_markup"=>json_encode([
            "inline_keyboard"=>[
                [
                    ["text"=>"🗑 Apagar","callback_data"=>"apagar_msg"],
                    ["text"=>"🚀 Adquirir Bot","url"=>"https://t.me/acharpessoass"]
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
    $url = "https://sara-api.xyz/api/consultas/parentes?cpf={$cpf}&apikey=bocadavk";
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
                    ["text"=>"🚀 Adquirir Bot","url"=>"https://t.me/acharpessoass"]
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
    $url = "https://sara-api.xyz/api/consultas/cpf?cpf={$cpf}&apikey=bocadavk";
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
                    ["text"=>"🚀 Adquirir Bot","url"=>"https://t.me/acharpessoass"]
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

    // ===== COMANDOS GRÁTIS =====
    if($cmd === "/cnpj"){
        $arg ? consultaCNPJ($chat, $arg) : tutorial($chat, "/cnpj");
        exit;
    }

    if($cmd === "/ip"){
        $arg ? consultaIP($chat, $arg) : tutorial($chat, "/ip");
        exit;
    }
    
    if($cmd === "/obito"){

    if(!$arg){
        tutorial($chat, "/obito");
        exit;
    }

    if(!isVip($userId)){
        bloquearObito($chat);
        exit;
    }

    consultaObito($chat, $arg);
    exit;
}

    if($cmd === "/cep"){
        $arg ? consultaCEP($chat, $arg) : tutorial($chat, "/cep");
        exit;
    }

    // ===== COMANDOS VIP =====
$vipCmds = ["/cpf","/parentes","/nome","/rg","/cnh","/telefone","/email","/placa","/pix","/renavam","/nascimento","/foto"];
    if(in_array($cmd, $vipCmds)){

    // ❗ primeiro valida se enviou argumento
    if(!$arg){
        tutorial($chat, $cmd);
        exit;
    }

    // 🔒 depois verifica VIP
    if(!isVip($userId)){
        bloquearConsulta($chat);
        exit;
    }

        if($cmd === "/cpf"){
            $arg ? consultaCPF($chat, $arg) : tutorial($chat, "/cpf");
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

    if($userId != 7320236887){
        tg("sendMessage",[
            "chat_id"=>$chat,
            "text"=>"⛔ Apenas os VIPs podem usar este comando."
        ]);
        exit;
    }

    enviarFotoCPF($chat);
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
        
        case "copiar_pix_obito":

global $PIX_CHAVE;

answer($callback["id"]);

tg("editMessageText",[
    "chat_id"=>$chat,
    "message_id"=>$msg,
    "text"=>
"📋 <b>CHAVE PIX COPIADA!</b>

<code>{$PIX_CHAVE}</code>

Envie o comprovante para liberação do acesso 🪦",
    "parse_mode"=>"HTML",
    "reply_markup"=>json_encode([
        "inline_keyboard"=>[
            [["text"=>"✅ ENVIAR COMPROVANTE","url"=>"https://t.me/acharpessoass"]]
        ]
    ])
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
        [["text"=>"📩 Enviar Comprovante","url"=>"https://t.me/acharpessoass"]],
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
                [["text"=>"🚀 ENVIAR COMPROVANTE","url"=>"https://t.me/acharpessoass"]]
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

Api: https://sara-api.xyz/api/consultas/fotov2?cpf=52306267200&apikey=bocadavk

Retorno:

{
  "success": true,
  "cpf": "52306267200",
  "estado": "RO",
  "foto": "data:image/jpg;base64,/9j/4AAQSkZJRgABAQEBLAEsAAD/2wBDAAgGBgcGBQgHBwcJCQgKDBQNDAsLDBkSEw8UHRofHh0aHBwgJC4nICIsIxwcKDcpLDAxNDQ0Hyc5PTgyPC4zNDL/2wBDAQkJCQwLDBgNDRgyIRwhMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjL/wAARCAKAAeADASIAAhEBAxEB/8QAHwAAAQUBAQEBAQEAAAAAAAAAAAECAwQFBgcICQoL/8QAtRAAAgEDAwIEAwUFBAQAAAF9AQIDAAQRBRIhMUEGE1FhByJxFDKBkaEII0KxwRVS0fAkM2JyggkKFhcYGRolJicoKSo0NTY3ODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uHi4+Tl5ufo6erx8vP09fb3+Pn6/8QAHwEAAwEBAQEBAQEBAQAAAAAAAAECAwQFBgcICQoL/8QAtREAAgECBAQDBAcFBAQAAQJ3AAECAxEEBSExBhJBUQdhcRMiMoEIFEKRobHBCSMzUvAVYnLRChYkNOEl8RcYGRomJygpKjU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6goOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4uPk5ebn6Onq8vP09fb3+Pn6/9oADAMBAAIRAxEAPwD3+iiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKQsB1IH1oAKKZ50eQC4yenNRJfW0kxhWZfMBwVzQBZopNy5xkUBgTgEGgBaKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigBKKCcCs6+1a3sot0jj72Pl+bBoA0ScDNQtKgVmkKhO7HoK5nVPGVhbwmO3u4FuDkgSEjHPpjmuJ1X4kz+W0HyRjcR5kI3KR9D1/SgXodH4k1AfI9teHyw+H8mTP4j6d68+vNU1FbyaOS7bfu4kL/K3v0/WsPUvEMtxJuS4ZgActt27vw7ViXesXFw6rNITtXhu+PSkykjs7XxjrkA+e6cqo3Bixz19a6rTviTNdMkV1sBBGXTgj0JP/ANavIEucqRuPtTI7hFusq529TzSDlR9LWfjDSni/f30QcHGWbbu/pVu28VaRfZW1v4XkH8O6vm+DUCQAxPByMmpYZmjnLo2GPdTii7Hy6XufS9pqUN2p8qRQynBQipI7qJnOXxkkDnjPf8a+d7TXL2zlZjM5YjAJJyK1I/GuoE+W5RlBzhht756jHNO4uVnvRkVGBLDa386lrxW28cywRKfMdWJ3OshyvHQg9RXT6d8QIHkSNyJElGRucAqevWi5Op6JRXK/8JdapKjszLA3A3jv/vdK2YtWtriNXikUhgcc96Lhc0aKarBlBByDTC5+YAZx1HemMloqtHc/OEcY7ZqzQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFACMCV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",
  "criador": "Sara API",
  "tipo_consulta": "Geral V2"
}
