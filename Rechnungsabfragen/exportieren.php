<?php
include "../db/dbverbindung.php";

$bID = isset($_GET['bID']) ? (int)$_GET['bID'] : 0;

if ($bID <= 0) {
    die("Ungültige Bestellung");
}

$sqlHeader = "
SELECT b.bID, b.zeitpunkt, t.tisch_nummer
FROM bestellung b
JOIN tisch t ON t.tID = b.tID
WHERE b.bID = ?
";
$cmd = $verbindung->prepare($sqlHeader);
$cmd->execute([$bID]);
$header = $cmd->fetch();

if (!$header) {
    die("Bestellung nicht gefunden");
}

$sqlPos = "
SELECT k.produkt, bp.menge, bp.einzelpreis, (bp.menge * bp.einzelpreis) AS summe
FROM bestellposition bp
JOIN karte k ON k.kID = bp.kID
WHERE bp.bID = ?
ORDER BY k.kID
";
$cmd = $verbindung->prepare($sqlPos);
$cmd->execute([$bID]);
$pos = $cmd->fetchAll();

$gesamt = 0;
foreach ($pos as $p) {
    $gesamt += (float)$p['summe'];
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Rechnung #<?php echo $bID; ?></title>
<style>
  body {
    font-family: Arial, sans-serif;
    font-size: 12pt;
    margin: 40px;
  }
  h2 { margin-bottom: 5px; }
  .meta { margin-bottom: 20px; }
  table {
    width: 100%;
    border-collapse: collapse;
  }
  th, td {
    border-bottom: 1px solid #000;
    padding: 6px 0;
  }
  th { text-align: left; }
  .right { text-align: right; }
  .total {
    margin-top: 20px;
    font-size: 14pt;
    font-weight: bold;
    text-align: right;
  }
</style>
</head>
<body>

<h2>Rechnung</h2>
<div class="meta">
  Bestellung #: <?php echo (int)$header['bID']; ?><br>
  Tisch: <?php echo htmlspecialchars($header['tisch_nummer']); ?><br>
  Datum: <?php echo htmlspecialchars($header['zeitpunkt']); ?>
</div>

<table>
  <thead>
    <tr>
      <th>Gericht</th>
      <th class="right">Menge</th>
      <th class="right">Preis</th>
      <th class="right">Summe</th>
    </tr>
  </thead>
  <tbody>

    <?php foreach ($pos as $p): ?>
    <tr>
      <td><?php echo htmlspecialchars($p['produkt']); ?></td>
      <td class="right"><?php echo (int)$p['menge']; ?></td>
      <td class="right"><?php echo number_format($p['einzelpreis'], 2, ',', '.'); ?> €</td>
      <td class="right"><?php echo number_format($p['summe'], 2, ',', '.'); ?> €</td>
    </tr>
    <?php endforeach; ?>

  </tbody>
</table>

<div class="total">
  Gesamt: <?php echo number_format($gesamt, 2, ',', '.'); ?> €
</div>

<script>
  window.print();
</script>

</body>
</html>
