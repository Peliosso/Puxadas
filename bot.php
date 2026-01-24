<?php
/* ==========================
   CONFIGURAÇÃO
========================== */

$TOKEN = "8241553232:AAGvxGZhHWJkAzKxQ-RsE-Efvy-e4q2XI4U";
$API   = "https://api.telegram.org/bot$TOKEN";

/* CAMINHO DA IMAGEM LOCAL */
$START_PHOTO_PATH = __DIR__ . "/assets/A4643DEF-1337-4645-8000-D0A036889B18.png";

/* PIX */
$PIX_CHAVE = "sua-chave-pix@exemplo.com";
$PIX_NOME  = "SEARCH PANEL";
$PIX_VALOR = "25,00";

/* ==========================
   RECEBE UPDATE
========================== */

$update = json_decode(file_get_contents("php://input"), true);
$message  = $update["message"] ?? null;
$callback = $update["callback_query"] ?? null;

/* ==========================
   FUNÇÕES
========================== */

function sendMessage($chat_id, $text, $keyboard = null) {
    global $API;

    $data = [
        "chat_id" => $chat_id,
        "text" => $text,
        "parse_mode" => "HTML"
    ];

    if ($keyboard) {
        $data["reply_markup"] = json_encode($keyboard);
    }

    file_get_contents($API . "/sendMessage?" . http_build_query($data));
}

function sendPhotoLocal($chat_id, $photo_path, $caption, $keyboard = null) {
    global $API;

    $post_fields = [
        "chat_id" => $chat_id,
        "caption" => $caption,
        "parse_mode" => "HTML",
        "photo" => new CURLFile($photo_path)
    ];

    if ($keyboard) {
        $post_fields["reply_markup"] = json_encode($keyboard);
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $API . "/sendPhoto");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    curl_close($ch);
}

function answerCallback($id, $text) {
    global $API;
    file_get_contents($API . "/answerCallbackQuery?" . http_build_query([
        "callback_query_id" => $id,
        "text" => $text,
        "show_alert" => true
    ]));
}

/* ==========================
   /START e /MENU
========================== */

if ($message && isset($message["text"]) && in_array($message["text"], ["/start", "/menu"])) {

    $chat_id = $message["chat"]["id"];

    $caption =
"╔══════════════════════╗
 🔎 <b>SEARCH PANEL</b>
 Sistema Premium de Consultas
╚══════════════════════╝

⚡ O bot de buscas mais completo do Telegram
📸 Destaque em <b>presença visual e fotos públicas</b>
🔐 Plataforma automatizada

👇 Acesse o menu abaixo:";

    $keyboard = [
        "inline_keyboard" => [
            [["text" => "🔍 CONSULTAS", "callback_data" => "menu_consultas"]],
            [["text" => "⭐ PLANOS", "callback_data" => "menu_planos"]],
            [["text" => "👤 MINHA CONTA", "callback_data" => "menu_conta"]],
            [["text" => "🛠 SUPORTE", "callback_data" => "menu_suporte"]]
        ]
    ];

    sendPhotoLocal($chat_id, $START_PHOTO_PATH, $caption, $keyboard);
}

/* ==========================
   MENU CONSULTAS
========================== */

if ($callback && $callback["data"] == "menu_consultas") {

    $chat_id = $callback["message"]["chat"]["id"];

    $text =
"🔍 <b>CONSULTAS DISPONÍVEIS</b>

📸 <b>Presença Visual</b>
• Localização de fotos públicas
• Imagens associadas
• Presença visual online

👤 <b>Identificação</b>
• Busca por nome
• Registros associados

📞 <b>Contato</b>
• Telefone & vínculos
• Email & presença

🚗 <b>Veículos</b>
• Consulta veicular
• Registro por placa

🧠 <b>Cruzamentos</b>
• Pessoas relacionadas
• Mapa de conexões";

    $keyboard = [
        "inline_keyboard" => [
            [
                ["text" => "📸 Localizar Fotos", "callback_data" => "bloqueado"],
                ["text" => "👤 Busca por Nome", "callback_data" => "bloqueado"]
            ],
            [
                ["text" => "📞 Telefone", "callback_data" => "bloqueado"],
                ["text" => "🚗 Veículo", "callback_data" => "bloqueado"]
            ],
            [
                ["text" => "🧠 Cruzamento", "callback_data" => "bloqueado"]
            ],
            [
                ["text" => "⬅️ Voltar", "callback_data" => "voltar_menu"]
            ]
        ]
    ];

    sendMessage($chat_id, $text, $keyboard);
}

/* ==========================
   BLOQUEIO PREMIUM
========================== */

if ($callback && $callback["data"] == "bloqueado") {
    answerCallback(
        $callback["id"],
        "🔒 Recurso premium.\n\nAtive o plano para acesso total."
    );
}

/* ==========================
   PLANOS / PIX
========================== */

if ($callback && $callback["data"] == "menu_planos") {

    $chat_id = $callback["message"]["chat"]["id"];

    $text =
"⭐ <b>PLANO VITALÍCIO</b>

🔥 <b>R$ {$PIX_VALOR}</b> (pagamento único)

✔ Todas as consultas
✔ Presença visual / fotos públicas
✔ Uso ilimitado
✔ Acesso permanente

<b>PIX:</b>
{$PIX_CHAVE}
<b>Nome:</b> {$PIX_NOME}

Após o pagamento, envie o comprovante ao suporte.";

    $keyboard = [
        "inline_keyboard" => [
            [["text" => "💬 Enviar Comprovante", "callback_data" => "menu_suporte"]],
            [["text" => "⬅️ Voltar", "callback_data" => "voltar_menu"]]
        ]
    ];

    sendMessage($chat_id, $text, $keyboard);
}

/* ==========================
   MINHA CONTA
========================== */

if ($callback && $callback["data"] == "menu_conta") {

    $chat_id = $callback["message"]["chat"]["id"];

    sendMessage(
        $chat_id,
        "👤 <b>MINHA CONTA</b>\n\nPlano: Gratuito\nStatus: Ativo ✅\nConsultas: Bloqueadas\n\n🔓 Ative um plano para liberar tudo."
    );
}

/* ==========================
   SUPORTE
========================== */

if ($callback && $callback["data"] == "menu_suporte") {

    $chat_id = $callback["message"]["chat"]["id"];

    sendMessage(
        $chat_id,
        "🛠 <b>SUPORTE</b>\n\nEnvie seu comprovante PIX ou dúvidas por aqui."
    );
}

/* ==========================
   VOLTAR
========================== */

if ($callback && $callback["data"] == "voltar_menu") {
    sendMessage($callback["message"]["chat"]["id"], "Use /menu para voltar ao menu principal.");
}