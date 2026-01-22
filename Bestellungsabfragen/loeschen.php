<?php
include "../db/dbverbindung.php";

$bID = isset($_GET['bID']) ? (int)$_GET['bID'] : 0;

if ($bID <= 0) {
    header("location:../bestellungen.php");
    exit;
}

$sql = "DELETE FROM bestellung WHERE bID = ?";
$cmd = $verbindung->prepare($sql);
$cmd->execute([$bID]);

header("location:../bestellungen.php?msg=deleted");
exit;
?>
