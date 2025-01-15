<?php
if (!empty($_GET['q'])) {
    switch ($_GET['q']) {
        case 'info':
            phpinfo();
            exit;
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RunaServer</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400" rel="stylesheet" type="text/css">
    <link rel="shortcut icon" href="https://i.imgur.com/GjU0Lpj.png" type="image/png">
    <style>
        *, :before *, :after * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-weight: 100;
            font-family: 'Roboto', sans-serif;
            font-size: 18px;
        }

        header, main, aside, footer{
            padding: 1rem;
            margin: auto;
        }

        header {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
        }

        .header-item {
            margin: 0;
            padding: 1rem;
        }

        .header-logo {
            height: 100px;
        }

        h1 {
            font-size: 4rem;
        }

        main {
            display: flex;
            /*background-color: #f5f5f5;*/
            background-color: #12007e;
            color: white;
        }

        main .info {

        }

        main .dirs {

        }

        nav {
            width: 100%;
        }

        ul {
            list-style: none;
            padding: 0;
            margin: auto;
        }

        a {
            color: #4fb7ff;
            font-weight: 900;
            text-decoration: none;
        }

        a:hover {
            color: red;
            font-weight: 900;
            transition: 300ms;
        }

        main strong {
            color: #d4d1d1;
        }

        nav a {
            display: block;
            margin: 1rem 0;
        }

        nav a:after {
            content: '→';
            margin-left: 0.5rem;
        }

        .alert {
            color: red;
            font-weight: 900;
        }
        code {
            margin-left: 2rem;
        }
        .txt-right {
            text-align: right;
        }

        footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
        }

        @media (min-width: 650px) {
            h1 {
                font-size: 6rem;
            }
        }
    </style>
</head>

<body>
    <header>
        <img class="header-item header-logo" src="https://i.imgur.com/GjU0Lpj.png" alt="Offline">
        <h1 class="header-item" title="RunaServer">RunaServer</h1>
        <div class="header-item">
            <svg xmlns="http://www.w3.org/2000/svg" aria-label="Docker" role="img" viewBox="0 0 512 512" class="header-logo" fill="#000000">
                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                <g id="SVGRepo_iconCarrier">
                    <rect width="512" height="512" rx="15%" fill="#e9e9e9"></rect>
                    <path stroke="#066da5" stroke-width="38" d="M296 226h42m-92 0h42m-91 0h42m-91 0h41m-91 0h42m8-46h41m8 0h42m7 0h42m-42-46h42"></path>
                    <path fill="#066da5" d="m472 228s-18-17-55-11c-4-29-35-46-35-46s-29 35-8 74c-6 3-16 7-31 7H68c-5 19-5 145 133 145 99 0 173-46 208-130 52 4 63-39 63-39"></path>
                </g>
            </svg>
        </div>
    </header>
    <main>
        <aside class="info">
            <p>
                <strong>Server Software:</strong> <?php print($_SERVER['SERVER_SOFTWARE']); ?>
            </p>
            <p>
                <strong>PHP version:</strong> <?php print PHP_VERSION; ?> <span><a title="phpinfo()" href="/?q=info"> phpinfo</a></span>
            </p>
            <p>
                <strong>Document Root (Docker):</strong> <?php print($_SERVER['DOCUMENT_ROOT']); ?>
            </p>
            <p>
                <strong>Original Root:</strong> ~/runaserver/html
            </p>
            <p>
                <?php $adminer_link = 'http://' . $_SERVER['HTTP_HOST'] . '/filemanager/adminer.php'; ?>
                <strong>Gestor BD:</strong>  <a href="<?= $adminer_link . '?server=mysql&username=root'; ?>" target="_blank"><?= $adminer_link; ?></a>
                <br>
                <small>Default credentials: root:12345678</small>
            </p>
            
        </aside>
        <aside class="dirs">
            <?php
            $dirList = glob('*', GLOB_ONLYDIR);
            if (!empty($dirList)) :
            ?>
                <nav>
                    <ul>
                        <?php
                        $host = $_SERVER['HTTP_HOST'];
                        foreach ($dirList as $key => $value) :
                            $link = 'http://' . $host . '/' . $value;
                        ?>
                            <a href="<?= $link; ?>" target="_blank"><?= $link; ?></a>
                        <?php
                        endforeach;
                        ?>
                    </ul>
                </nav>
            <?php
            else :
            ?>
                <p class="alert">There are no directories, create your first project now</p>
            <?php
            endif;
            ?>
        </aside>
    </main>
    <main>
        <aside>
            <table>
                <tr>
                    <td class="txt-right"> <strong>Run server : </strong> </td>
                    <td> <code>~/runaserver/server start</code> </td>
                </tr>
                <tr>
                    <td class="txt-right"> <strong>Stop server : </strong> </td>
                    <td> <code>~/runaserver/server stop</code> </td>
                </tr>
                <tr>
                    <td class="txt-right"> <strong>Restart server : </strong> </td>
                    <td> <code>~/runaserver/server restart</code> </td>
                </tr>
                <tr>
                    <td class="txt-right"> <strong>Replace Config? : </strong> </td>
                    <td> <code>~/runaserver/server rebuild</code> </td>
                </tr>
                <tr>
                    <td class="txt-right"> <strong>Composer command (Example) : </strong> </td>
                    <td> <code>~/runaserver/server run composer install</code> </td>
                </tr>
                <tr>
                    <td class="txt-right"> <strong>Artisan command (Example) : </strong> </td>
                    <td> <code>~/runaserver/server run php artisan key:generate</code> </td>
                </tr>
            </table>
        </aside>
    </main>
    <footer>
        <p>
            Con el poder de <a title="Docker Based" href="https://docker.com">Docker</a>
        </p>
    </footer>
</body>
</html>