<?php
// Gmail送信の設定
define('API_KEY', 'apikey');  // APIキー
define('GMAIL_USER', 'test@gmail.com');  // Gmailアカウント
define('GMAIL_APP_PASSWORD', 'aaaa bbbb cccc dddd');  // Gmailアプリパスワード
define('GMAIL_FROM', 'test@gmail.com');  // 送信元メールアドレス

// エラー表示（デバッグ時のみ有効にする）
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Composerのオートロードを読み込み
require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// POSTリクエストのみを許可
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(['error' => 'Method Not Allowed']));
}

// APIキーの検証
$headers = getallheaders();
if (!isset($headers['X-Api-Key']) || $headers['X-Api-Key'] !== API_KEY) {
    http_response_code(401);
    die(json_encode(['error' => 'Unauthorized']));
}

// JSON形式のPOSTデータを取得
$raw_data = file_get_contents("php://input");
$post_data = json_decode($raw_data, true);

// データがJSONとしてデコードされていない場合、エラーレスポンスを返す
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    die(json_encode(['error' => 'Invalid JSON']));
}

// デバッグ用に受け取ったデータをログ出力
logError("Received JSON POST data: " . print_r($post_data, true));

// ログ記録用の関数
function logError($message) {
    $logFile = __DIR__ . '/mail.log';
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
}

// コンテンツ構築関数（改行処理付き）
function buildContent($data) {
    $content_parts = [];

    // "message"フィールドを最初に追加
    if (isset($data['message'])) {
        $content_parts[] = $data['message'];
    }

    // 追加情報がある場合、各セクションを順に処理
    if (isset($data['additional_info']) && is_array($data['additional_info'])) {
        foreach ($data['additional_info'] as $section => $text) {
            $content_parts[] = strtoupper($section) . ":\n" . $text;
        }
    }

    // コンテンツを結合し、改行をLF形式に統一
    $content = implode("\n\n", $content_parts);
    $content = str_replace(["\r\n", "\r"], "\n", $content);

    logError("Final content: " . $content);  // 最終コンテンツをログに出力
    return trim($content);
}

try {
    // 必須のフィールドを取得
    $to = filter_var($post_data['to'] ?? null, FILTER_VALIDATE_EMAIL);
    $subject = $post_data['subject'] ?? '';
    $content = buildContent($post_data);
    
    // 入力検証
    if (!$to || !$subject || empty($content)) {
        http_response_code(400);
        die(json_encode(['error' => 'Invalid Parameters']));
    }

    // メール送信の準備
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->SMTPDebug = 0;
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->SMTPAuth = true;
    
    // Gmail認証情報の設定
    $mail->Username = GMAIL_USER;
    $mail->Password = GMAIL_APP_PASSWORD;
    
    // メール内容の設定（プレーンテキストのみ）
    $mail->setFrom(GMAIL_FROM);
    $mail->addAddress($to);
    $mail->CharSet = 'UTF-8';
    $mail->Subject = $subject;
    $mail->Body = $content;
    
    // メール送信
    $mail->send();
    
    // 成功レスポンス
    http_response_code(200);
    echo json_encode([
        'status' => 'success',
        'message' => 'Email sent successfully',
        'debug' => [
            'content' => $content
        ]
    ], JSON_UNESCAPED_UNICODE);
    logError("Email sent successfully to: $to");
    
} catch (Exception $e) {
    // エラーレスポンス
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
    logError("Mail error: " . $e->getMessage());
}
