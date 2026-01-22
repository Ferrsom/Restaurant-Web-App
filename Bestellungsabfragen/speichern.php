<?php
include "../db/dbverbindung.php";

if (!isset($_POST['speichern'])) {
    header("location:../karte.php");
    exit;
}

//tID aus dem Formular holen und in int umwandeln
$tID = isset($_POST['tID']) ? (int)$_POST['tID'] : 0;

//checken
if ($tID <= 0) {
    header("location:../karte.php?err=table");
    exit;
}

//Mengen-Array aus dem Formular holen
$mengeArr = $_POST['menge'] ?? [];

//Aus dem Array filtern wir die Positionen heraus, die wirklich bestellt werden sollen
//Bei Menge 0 nicht bestellen
$auswahl = [];
foreach ($mengeArr as $kID => $m) {
    $kID = (int)$kID;
    $m   = (int)$m;
    if ($kID > 0 && $m > 0) {
        $auswahl[$kID] = $m;
    }
}

//Wenn keine Position, Fehlermeldung
if (count($auswahl) === 0) {
    header("location:../karte.php?err=empty");
    exit;
}

try {

    //Transaktion starten. Wenn ein Fehler passiert, wird alles zurückgerollt
    //Bestellung und Positionen sollen nur gemeinsam gespeichert werden
    $verbindung->beginTransaction();

    $cmd = $verbindung->prepare("INSERT INTO bestellung (tID) VALUES (?)");
    $cmd->execute([$tID]);
    $bID = (int)$verbindung->lastInsertId();

    //Preise für Gerichte holen. Damit speichern wir einen aktuellen Preis
    $ids = array_keys($auswahl);
    $ph  = implode(',', array_fill(0, count($ids), '?'));

    $cmd = $verbindung->prepare("SELECT kID, preis FROM karte WHERE kID IN ($ph)");
    $cmd->execute($ids);


    //Map bauen. kID - preis
    $preisMap = [];
    foreach ($cmd->fetchAll() as $row) {
        $preisMap[(int)$row['kID']] = (float)$row['preis'];
    }


    //Bestellposition speichern. Einzelpreis ist ein Snapshot, Preise bleiben stabil, auch wenn spätere Änderung der Karte
    $cmdIns = $verbindung->prepare(
        "INSERT INTO bestellposition (bID, kID, menge, einzelpreis) VALUES (?,?,?,?)"
    );

    foreach ($auswahl as $kID => $m) {
        if (!isset($preisMap[$kID])) continue;
        $cmdIns->execute([$bID, $kID, $m, $preisMap[$kID]]);
    }

    $verbindung->commit();

    header("location:../bestellungen.php?msg=created");
    exit;

} catch (Exception $e) {
    $verbindung->rollBack();
    die("Fehler beim Speichern: " . $e->getMessage());
}
?>
