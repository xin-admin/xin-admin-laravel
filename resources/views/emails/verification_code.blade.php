<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>验证码邮件</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header img {
            width: 100px;
            height: 100px;
        }
        .logo {
            max-width: 150px;
        }
        .code-container {
            background-color: #f5f5f5;
            padding: 15px;
            text-align: center;
            margin: 20px 0;
            border-radius: 5px;
        }
        .verification-code {
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 2px;
            color: #2d3748;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #718096;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="header">
    <img src="https://file.xinadmin.cn/file/favicons.ico" alt="Logo" class="logo">
    <h1>您的验证码</h1>
</div>

<p>您好！</p>

<p>您正在尝试进行身份验证，请使用以下验证码完成操作：</p>

<div class="code-container">
    <div class="verification-code">{{ $code }}</div>
</div>

<p>此验证码将在 {{ $expireMinutes }} 分钟后失效，请尽快使用。</p>

<p>如果您没有请求此验证码，请忽略此邮件。</p>

<div class="footer">
    <p>© {{ date('Y') }} {{ config('app.name') }}. 版权所有.</p>
</div>
</body>
</html>