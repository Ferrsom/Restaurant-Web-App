<?php
include "db/dbverbindung.php";

$bID = isset($_GET['bID']) ? (int)$_GET['bID'] : 0;

if ($bID <= 0) {
    header("location:bestellungen.php");
    exit;
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
    header("location:bestellungen.php");
    exit;
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

$gesamt = 0.0;

foreach ($pos as $p) {
    $gesamt += (float)$p['summe'];
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Rechnung</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color:#f5efe6; }
    .card { background:#fff8ee; }
  </style>
</head>
<body>
<div class="container mt-5">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h2 class="mb-0">💶 Rechnung</h2>
    <a href="bestellungen.php" class="btn btn-secondary">Zurück</a>
  </div>

  <div class="card p-3 mb-3">
    <div class="row g-2">
      <div class="col-md-4"><strong>Bestellnr.:</strong> #<?php echo (int)$header['bID']; ?></div>
      <div class="col-md-4"><strong>Tisch:</strong> Tisch <?php echo htmlspecialchars($header['tisch_nummer']); ?></div>
      <div class="col-md-4"><strong>Zeitpunkt:</strong> <?php echo htmlspecialchars($header['zeitpunkt']); ?></div>
    </div>
  </div>

  <div class="card p-4">
    <div class="table-responsive">
      <table class="table">
        <thead class="table-light">
          <tr>
            <th>Gericht</th>
            <th class="text-end">Menge</th>
            <th class="text-end">Einzelpreis</th>
            <th class="text-end">Summe</th>
          </tr>
        </thead>
        <tbody>

          <?php if (count($pos) === 0): ?>
            <tr><td colspan="4" class="text-center text-muted">Keine Positionen vorhanden.</td></tr>
          <?php endif; ?>

          <?php foreach ($pos as $p):
            $einzel = number_format((float)$p['einzelpreis'], 2, ',', '.');
            $summe  = number_format((float)$p['summe'], 2, ',', '.');
          ?>
            <tr>
              <td><?php echo htmlspecialchars($p['produkt']); ?></td>
              <td class="text-end"><?php echo (int)$p['menge']; ?></td>
              <td class="text-end"><?php echo $einzel; ?> €</td>
              <td class="text-end"><?php echo $summe; ?> €</td>
            </tr>
          <?php endforeach; ?>

        </tbody>
      </table>
    </div>

    <hr>
    <h4 class="text-end">Gesamt: <?php echo number_format($gesamt, 2, ',', '.'); ?> €</h4>

    <div class="d-flex justify-content-end mt-3">
      <a class="btn btn-outline-secondary"
      href="rechnungsabfragen/exportieren.php?bID=<?php echo (int)$header['bID']; ?>">Drucken</a>
    </div>

  </div>
</div>
</body>
</html>
