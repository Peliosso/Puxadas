<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

/* ==========================
   CONFIG
========================== */

$TOKEN = "8241553232:AAGvxGZhHWJkAzKxQ-RsE-Efvy-e4q2XI4U";
$API   = "https://api.telegram.org/bot{$TOKEN}";
$START_PHOTO = "https://i.imgur.com/SEU_LINK.jpg";

/* PIX */
$PIX_VALOR = "25,00";
$PIX_CHAVE = "70192823698";
$PIX_NOME  = "Isabelly";

/* ==========================
   UPDATE
========================== */

$update = json_decode(file_get_contents("php://input"), true);
$message  = $update["message"] ?? null;
$callback = $update["callback_query"] ?? null;

/* ==========================
   API HELPER
========================== */

function api($method, $data = []) {
    global $API;
    $ch = curl_init($API . "/" . $method);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $data
    ]);
    curl_exec($ch);
    curl_close($ch);
}

function answer($id) {
    api("answerCallbackQuery", ["callback_query_id" => $id]);
}

/* ==========================
   MENUS
========================== */

function mainMenu($chat_id, $edit = false, $msg_id = null) {
    $text =
"╔════════════════╗
🔎 <b>SEARCH PANEL</b>
Sistema Premium de Consultas
╚═════════════════╝

⚡ Plataforma privada
📸 Forte presença visual
🔐 Acesso controlado por plano

👇 Escolha uma opção:";

    $kb = [
        "inline_keyboard" => [
            [["text"=>"🔍 CONSULTAS","callback_data"=>"consultas"]],
            [["text"=>"⭐ PLANOS","callback_data"=>"planos"]],
            [["text"=>"👤 MINHA CONTA","callback_data"=>"conta"]],
            [["text"=>"🛠 SUPORTE","callback_data"=>"suporte"]],
        ]
    ];

    if ($edit) {
        api("editMessageText", [
            "chat_id"=>$chat_id,
            "message_id"=>$msg_id,
            "text"=>$text,
            "parse_mode"=>"HTML",
            "reply_markup"=>json_encode($kb)
        ]);
    } else {
        api("sendPhoto", [
            "chat_id"=>$chat_id,
            "photo"=>$GLOBALS["START_PHOTO"],
            "caption"=>$text,
            "parse_mode"=>"HTML",
            "reply_markup"=>json_encode($kb)
        ]);
    }
}

function consultasMenu($chat_id, $msg_id) {

    $text =
"<b>CATÁLOGO DE CONSULTAS DISPONÍVEIS</b>

━━━━━━━━━━━━━━━━━━━━
<b>IDENTIFICAÇÃO CIVIL</b>
━━━━━━━━━━━━━━━━━━━━
• CPF
• CPF (Base Alternativa)
• RG
• CNH
• Número de Segurança da CNH
• Nome Completo
• Nomes Abreviados
• Data de Nascimento
• Vínculos Familiares
• Vizinhos Registrados

━━━━━━━━━━━━━━━━━━━━
<b>CONTATO & LOCALIZAÇÃO</b>
━━━━━━━━━━━━━━━━━━━━
• Telefone Móvel
• Telefone Móvel (Base Secundária)
• Telefone Fixo
• Endereço Completo
• CEP
• E-mail
• IP e Presença Digital

━━━━━━━━━━━━━━━━━━━━
<b>VEÍCULOS</b>
━━━━━━━━━━━━━━━━━━━━
• Consulta por Placa (Dados Completos)
• RENAVAM
• Frota Veicular
• Vistoria Veicular
• Radar e Registros de Circulação

━━━━━━━━━━━━━━━━━━━━
<b>FINANCEIRO & CRÉDITO</b>
━━━━━━━━━━━━━━━━━━━━
• Score de Crédito
• Histórico de Crédito
• Dívidas e Pendências
• Comprovantes via PIX
• IRPF (Base Declaratória)

━━━━━━━━━━━━━━━━━━━━
<b>GOVERNAMENTAL & REGISTROS</b>
━━━━━━━━━━━━━━━━━━━━
• CNPJ
• Receita Federal
• INSS
• RAIS (Histórico Profissional)
• Vacinação COVID
• Boletim de Ocorrência
• Mandados e Restrições
• Processos Judiciais

━━━━━━━━━━━━━━━━━━━━
<b>BASES AVANÇADAS</b>
━━━━━━━━━━━━━━━━━━━━
• Cruzamento de Dados
• Relacionamentos Diretos
• Análise de Vínculos
• Presença Visual Associada

━━━━━━━━━━━━━━━━━━━━
<b>ACESSO RESTRITO</b>
━━━━━━━━━━━━━━━━━━━━
Todas as consultas acima são liberadas
exclusivamente para usuários com plano ativo.";

    $keyboard = [
        "inline_keyboard" => [
            [
                ["text" => "ADQUIRIR PLANO", "callback_data" => "planos"]
            ],
            [
                ["text" => "VOLTAR AO MENU", "callback_data" => "voltar"]
            ]
        ]
    ];

    api("editMessageText", [
        "chat_id" => $chat_id,
        "message_id" => $msg_id,
        "text" => $text,
        "parse_mode" => "HTML",
        "reply_markup" => json_encode($keyboard)
    ]);
}

/* ==========================
   START
========================== */

if ($message && in_array($message["text"], ["/start","/menu"])) {
    mainMenu($message["chat"]["id"]);
    http_response_code(200); exit;
}

/* ==========================
   CALLBACKS
========================== */

if ($callback) {

    $chat_id = $callback["message"]["chat"]["id"];
    $msg_id  = $callback["message"]["message_id"];
    answer($callback["id"]);

    switch ($callback["data"]) {

        case "consultas":
            consultasMenu($chat_id, $msg_id);
            break;

        case "planos":
            api("editMessageText", [
                "chat_id"=>$chat_id,
                "message_id"=>$msg_id,
                "parse_mode"=>"HTML",
                "text"=>"⭐ <b>PLANO VITALÍCIO</b>\n\n💰 R$ {$PIX_VALOR}\n\n✔ Acesso total\n✔ Uso ilimitado\n✔ Pagamento único\n\n<b>PIX:</b>\n{$PIX_CHAVE}\n<b>Nome:</b> {$PIX_NOME}",
                "reply_markup"=>json_encode([
                    "inline_keyboard"=>[
                        [["text"=>"🛠 SUPORTE","callback_data"=>"suporte"]],
                        [["text"=>"⬅️ VOLTAR","callback_data"=>"voltar"]]
                    ]
                ])
            ]);
            break;

        case "conta":
            api("editMessageText", [
                "chat_id"=>$chat_id,
                "message_id"=>$msg_id,
                "parse_mode"=>"HTML",
                "text"=>"👤 <b>MINHA CONTA</b>\n\nPlano: Gratuito\nStatus: Ativo\nAcesso: Bloqueado",
                "reply_markup"=>json_encode([
                    "inline_keyboard"=>[
                        [["text"=>"⬅️ VOLTAR","callback_data"=>"voltar"]]
                    ]
                ])
            ]);
            break;

        case "suporte":
            api("editMessageText", [
                "chat_id"=>$chat_id,
                "message_id"=>$msg_id,
                "parse_mode"=>"HTML",
                "text"=>"🛠 <b>SUPORTE</b>\n\nEnvie seu comprovante ou dúvida aqui.",
                "reply_markup"=>json_encode([
                    "inline_keyboard"=>[
                        [["text"=>"⬅️ VOLTAR","callback_data"=>"voltar"]]
                    ]
                ])
            ]);
            break;

        case "voltar":
            mainMenu($chat_id, true, $msg_id);
            break;
    }

    http_response_code(200);
    exit;
}

http_response_code(200);
echo "OK"; tu