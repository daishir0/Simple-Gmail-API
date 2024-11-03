# Simple Gmail API

## Overview
Simple Gmail API is a lightweight PHP script that provides a secure REST API endpoint for sending emails through Gmail. It uses PHPMailer for reliable email delivery and includes features like API key authentication, JSON request handling, and comprehensive error logging.

Key Features:
- Single file implementation for easy deployment
- Secure API key authentication
- JSON request format support
- Detailed error logging
- UTF-8 encoding support
- Flexible message formatting
- Request validation and sanitization

## Installation

1. Clone the repository
```bash
git clone https://github.com/daishir0/simple-gmail-api
cd simple-gmail-api
```

2. Install dependencies using Composer
```bash
composer require phpmailer/phpmailer
```

3. Configure the script
- Copy `config.example.php` to `config.php`
- Edit `config.php` and set the following constants:
  - `API_KEY`: Your chosen API key
  - `GMAIL_USER`: Your Gmail address
  - `GMAIL_APP_PASSWORD`: Your Gmail app password
  - `GMAIL_FROM`: Sender email address

## Usage

1. Send an email using curl:
```bash
curl -X POST \
  -H "Content-Type: application/json" \
  -H "X-Api-Key: your_api_key" \
  -d '{
    "to": "recipient@example.com",
    "subject": "Test Email",
    "message": "Hello, World!",
    "additional_info": {
      "section1": "Additional details here",
      "section2": "More information"
    }
  }' \
  http://your-server/simple-gmail-api.php
```

2. Response format:
```json
{
  "status": "success",
  "message": "Email sent successfully"
}
```

## Notes
- Enable "Less secure app access" or generate an App Password in your Google Account settings
- The script only accepts POST requests with JSON data
- All communications are logged in `mail.log`
- Set appropriate file permissions for the log file
- Consider implementing rate limiting for production use
- Single file implementation makes it easy to deploy and maintain

## License
This project is licensed under the MIT License - see the LICENSE file for details.

---

# Simple Gmail API

## 概要
Simple Gmail APIは、GmailでメールをRESTful API経由で送信するためのシンプルなPHPスクリプトです。PHPMailerを使用して信頼性の高いメール配信を実現し、APIキー認証、JSONリクエスト処理、包括的なエラーログ機能を備えています。

主な機能：
- 単一ファイルでの実装で簡単にデプロイ可能
- セキュアなAPIキー認証
- JSON形式のリクエストサポート
- 詳細なエラーログ機能
- UTF-8エンコーディングサポート
- 柔軟なメッセージフォーマット
- リクエストの検証とサニタイズ

## インストール方法

1. リポジトリをクローン
```bash
git clone https://github.com/daishir0/simple-gmail-api
cd simple-gmail-api
```

2. Composerで依存関係をインストール
```bash
composer require phpmailer/phpmailer
```

3. スクリプトの設定
- `config.example.php`を`config.php`にコピー
- `config.php`を編集して以下の定数を設定：
  - `API_KEY`: 任意のAPIキー
  - `GMAIL_USER`: Gmailアドレス
  - `GMAIL_APP_PASSWORD`: Gmailアプリパスワード
  - `GMAIL_FROM`: 送信元メールアドレス

## 使い方

1. curlを使用してメールを送信：
```bash
curl -X POST \
  -H "Content-Type: application/json" \
  -H "X-Api-Key: your_api_key" \
  -d '{
    "to": "recipient@example.com",
    "subject": "テストメール",
    "message": "こんにちは",
    "additional_info": {
      "section1": "追加情報1",
      "section2": "追加情報2"
    }
  }' \
  http://your-server/simple-gmail-api.php
```

2. レスポンス形式：
```json
{
  "status": "success",
  "message": "Email sent successfully"
}
```

## 注意点
- Googleアカウントで「安全性の低いアプリの許可」を有効にするか、アプリパスワードを生成する必要があります
- スクリプトはJSONデータを含むPOSTリクエストのみを受け付けます
- すべての通信は`mail.log`に記録されます
- ログファイルの適切なファイルパーミッションを設定してください
- 本番環境での使用時はレート制限の実装を検討してください
- 単一ファイルでの実装により、デプロイとメンテナンスが容易です

## ライセンス
このプロジェクトはMITライセンスの下でライセンスされています。詳細はLICENSEファイルを参照してください。
