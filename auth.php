<?php
session_start();

// Funkcija za preverjanje prijave
function preveriPrijavo() {
    if (!isset($_SESSION['id_vloga'])) {
        header("Location: index.html"); // Preusmeri na prijavno stran, če uporabnik ni prijavljen
        exit();
    }
}

// Funkcija za preverjanje določene vloge
// 1 - administrator, 2 - učitelj, 3 - učenec
function preveriVlogo($zahtevanaVloga) {
    preveriPrijavo(); // Preverimo, ali je uporabnik prijavljen
    if ($_SESSION['id_vloga'] != $zahtevanaVloga) {
        header("Location: index.html"); // Če vloga ni ustrezna, preusmeri na prijavno stran
        exit();
    }
}
?>
