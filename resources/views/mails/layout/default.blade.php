<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            font-size: 14px;
            color: #333;
        }

        .content {
            background-color: #efefef;
            border-radius: 10px;
            padding: 20px;
        }

        .header {
            text-align: center;
            font-size: 24px;
            margin-bottom: 30px;
        }

        .footer {
            text-align: center;
            font-size: 14px;
            margin-top: 30px;
            font-size: 12px;
            font-weight: bold;
        }

        @media only screen and (max-width: 600px) {
            .container {
                padding: 10px;
            }
            .content {
                padding: 10px;
            }
            .header,
            .footer {
                font-size: 18px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <strong>{{env('MAIL_FROM_NAME')}}</strong>
        </div>

        <div class="content">
            @yield('content')
            <br />
            <br />
            <br />
            <br />
            <br />
            <b>Lotogram Destek Ekibi</b>
        </div>

        <div class="footer">
            © {{date('Y') - 2}} {{config('app.name')}} Tüm hakları saklıdır.
        </div>
    </div>
</body>

</html>
