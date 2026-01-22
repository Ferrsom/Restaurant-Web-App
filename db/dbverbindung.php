<?php
$host = "localhost";
$benutzer = "root";
$db = "bestellungssystem";
$pass = "";

try {
    $verbindung = new PDO("mysql:host=$host;dbname=$db", $benutzer, $pass,);
} catch (PDOException $e) {
    die("Verbindung fehler: ".$e->getMessage());
}
?>
