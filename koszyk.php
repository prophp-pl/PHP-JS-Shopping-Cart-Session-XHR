<?php
header("Content-Type: application/json;charset=utf-8");
session_start();

function generuj_blad (string $komunikat, int $status = 500):string {
    if (headers_sent()) {
        throw new Exception('Nagłówki zostały już wysłane!');
    }

    http_response_code($status);
    return json_encode([
        'wiadomosc' => $komunikat
    ]);
}

function czy_poprawna_ilosc(int $id, int $ilosc = 1):bool {
    $produkty = require_once 'produkty.php';
    $produkty = array_column($produkty, 'magazyn', 'id');

    if (array_key_exists($id, $produkty) && $produkty[$id] >= $ilosc) {
        return true;
    }
    return false;

}

function generuj_podsumowanie_html():string {
    if (!isset($_SESSION['koszyk']) || empty($_SESSION['koszyk'])) {
        return '<p>Brak produktów w koszyku</p>';
    }

    $produkty = require_once 'produkty.php';
    $produkty = array_filter($produkty, function($produkt) {
        return array_key_exists($produkt['id'], $_SESSION['koszyk']);
    });

    ob_start();
    echo '<dl id="produkty_podsumowanie">';
    foreach ($produkty as $produkt) {
        echo '<dt>' . $produkt['nazwa'] . '(' . $_SESSION['koszyk'][$produkt['id']] . ')</dt>';
        echo '<dd><button data-id="' . $produkt['id'] . '">Usuń z koszyka</button></dd>';
    }
    echo '</dl>';
    echo '<button id="wyczysc_koszyk">Wyczyść zawartość koszyka</button>';

    $out = ob_get_contents();
    ob_end_clean();
    return $out;
}

$akcja = $_POST['akcja'] ?? null;

if ($akcja === 'dodaj') {
    $id =  (int) ($_POST['id'] ?? 0);
    if ($id < 1) {
        echo generuj_blad('Nieprawidłowe id produktu');
        return;
    }

    $ilosc = (isset($_SESSION['koszyk']) && array_key_exists($id, $_SESSION['koszyk'])) ? $_SESSION['koszyk'][$id] : 0;
    $ilosc++;
    if (!czy_poprawna_ilosc($id, $ilosc)) {
        echo generuj_blad('Nie można dodać kolejnego produktu. Ilość przekracza stan magazynowy');
        return;    
    }
    $_SESSION['koszyk'][$id] = $ilosc;
    
    echo json_encode([
        'wiadomosc' => 'Produkt został dodany do koszyka',
        'koszyk' => $_SESSION['koszyk']
    ]);
    return;
} elseif ($akcja === 'wyzeruj') {
    $_SESSION['koszyk'] = [];
    echo json_encode([
        'wiadomosc' => 'Koszyk został wyczyszczony',
        'koszyk' => $_SESSION['koszyk']
    ]);
    return;
} elseif ($akcja === 'pokaz') {
    header("Content-Type: text/html; charset=UTF-8");
    echo generuj_podsumowanie_html();
    return;
} elseif ($akcja === 'usun') {
    if (!isset($_SESSION['koszyk']) || empty($_SESSION['koszyk'])) {
        echo generuj_blad('Koszyk nie został odnaleziony');
        return;
    }
    $id =  (int) ($_POST['id'] ?? 0);
    if ($id < 1) {
        echo generuj_blad('Nieprawidłowe id produktu');
        return;
    }
    unset($_SESSION['koszyk'][$id]);
    echo json_encode([
        'wiadomosc' => 'Produkt został usunięty',
        'koszyk' => $_SESSION['koszyk']
    ]);
    return;
} else {
    echo generuj_blad('Nieprawidłowa akcja');
}