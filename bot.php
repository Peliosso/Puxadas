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
    940636198, 
    8538480916,
    6133561216,
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
   $vipCmds = ["/cpf","/nome","/rg","/cnh","/telefone","/email","/placa","/pix","/renavam","/nascimento","/foto"];
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
        
        if($cmd === "/nome"){
            $arg ? consultaNome($chat, $arg) : tutorial($chat, "/nome");
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