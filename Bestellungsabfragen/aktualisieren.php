<?php
include "../db/dbverbindung.php";

if (!isset($_POST['aktualisieren'])) {
    header("location:../bestellungen.php");
    exit;
}

//bID in int
$bID = isset($_POST['bID']) ? (int)$_POST['bID'] : 0;

//bID checken
if ($bID <= 0) {
    header("location:../bestellungen.php");
    exit;
}

//Mengen-Array aus dem Formular holen
$mengeArr = $_POST['menge'] ?? [];

//Aus dem Array filtern die Positionen 
$auswahl = [];
foreach ($mengeArr as $kID => $m) {
    $kID = (int)$kID;
    $m = (int)$m;
    if ($kID > 0 && $m > 0) {
        $auswahl[$kID] = $m;
    }
}

try {
    //Transaktion starten
    // Dadurch DB-Änderungen "als Paket" wenn Fehler, zurückrollen
    $verbindung->beginTransaction();
    
    //Alte Bestellpositionen mit einzelpreis laden
    //damit sich der Preis bleibt, wenn später der Menüpreis in der karte sich ändert
    $sql = "SELECT kID, einzelpreis FROM bestellposition WHERE bID = ?";
    $cmd = $verbindung->prepare($sql);
    $cmd->execute([$bID]);
    $alt = $cmd->fetchAll();

    //Map bauen kID - einzelpreis
    $altPreis = [];
    foreach ($alt as $a) {
        $altPreis[(int)$a['kID']] = (float)$a['einzelpreis'];
    }

    //Für neue Positionen, die vorher nicht in der Bestellung waren, brauchen wir einen Preis
    //Dafür holen wir für die ausgewählten kIDs die aktuellen Preise aus der karte
    $preisMap = [];
    if (count($auswahl) > 0) {
        $ids = array_keys($auswahl);
        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        $sql = "SELECT kID, preis FROM karte WHERE kID IN ($placeholders)";
        $cmd = $verbindung->prepare($sql);
        $cmd->execute($ids);
        $preise = $cmd->fetchAll();

        foreach ($preise as $p) {
            $preisMap[(int)$p['kID']] = (float)$p['preis'];
        }
    }

    //Vereinfachte Update-Lösung
    //Erst alle Positionen dieser Bestellung löschen
    $sqlDel = "DELETE FROM bestellposition WHERE bID = ?";
    $cmdDel = $verbindung->prepare($sqlDel);
    $cmdDel->execute([$bID]);

    //Positionen aus dem Formular neu einfügen
    if (count($auswahl) > 0) {
        $sqlIns = "INSERT INTO bestellposition (bID, kID, menge, einzelpreis) VALUES (?,?,?,?)";
        $cmdIns = $verbindung->prepare($sqlIns);

        foreach ($auswahl as $kID => $m) {
            //Wenn Gericht vorher in der Bestellung war nehmen wir alten Preis sonst aktuellen Preis aus der karte
            $price = $altPreis[$kID] ?? ($preisMap[$kID] ?? null);
            if ($price === null) continue;

            $cmdIns->execute([$bID, $kID, $m, $price]);
        }
    }

    $verbindung->commit();

    header("location:../bestellungen.php?msg=updated");
    exit;

} catch (Exception $e) {
    $verbindung->rollBack();
    die("Fehler beim Aktualisieren: " . $e->getMessage());
}
?>
