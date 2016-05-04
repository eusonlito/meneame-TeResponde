<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Menéame Responde</title>

        <style>
        * {
            margin: 0;
            padding: 0;
        }

        html, body {
            height: 100%;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        .content {
            width: 50%;
            text-align: center;
        }
        </style>
    </head>

    <body>
        <div class="container">
            <div class="content">
                <a href="<?= route('site.index'); ?>"><img src="<?= url('img/404.png'); ?>" /></a>
            </div>
        </div>
    </body>
</html>
