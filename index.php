<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Przykładowy koszyk produktów</title>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="koszyk.js"></script>
    <style>
        dt:before {
            content: "";
            display: block;
        }
        dt, dd {
            display: inline;
        }

        .ukryj {
            display: none;
        }

        .zamknij {
            float: right;
            cursor: pointer;
        }

        #koszyk_podsumowanie {
            width: 50vw;
            position: fixed;
            top: 20%;
            left: calc(50% - 25vw);
            background-color: rgba(255, 255, 255, .9);
            border: 2px solid #ccc;
            padding: 10px;
        }
    </style>
</head>
<body>
    <main>
        <div id="wiadomosc"></div>
        <dl id="produkty">
        <?php
            $produkty = require_once 'produkty.php';

            foreach ($produkty as $produkt) {
                echo '<dt>', $produkt['nazwa'], '</dt>';
                echo '<dd><button data-id="' . $produkt['id'] . '">Dodaj do koszyka</button></dd>';
            }
        ?>
        </dl>

        <section id="koszyk">
            <a id="pokaz_koszyk" href="#">Pokaż zawartość koszyka</a>
            <div id="koszyk_podsumowanie" class="ukryj">
                <span class="zamknij">zamknij [x]</span>
                <div class="body"></div>
            </div>
        </section>

    </main>
</body>
</html>