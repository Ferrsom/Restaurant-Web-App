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

$sqlItems = "
SELECT k.kID, k.produkt, k.preis, COALESCE(bp.menge, 0) AS menge
FROM karte k
LEFT JOIN bestellposition bp ON bp.kID = k.kID AND bp.bID = ?
ORDER BY k.kID
";
$cmd = $verbindung->prepare($sqlItems);
$cmd->execute([$bID]);
$items = $cmd->fetchAll();
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Bestellung bearbeiten</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color:#f5efe6; }
    .menu-item { background:#fff8ee; border-radius:10px; padding:14px; margin-bottom:12px; }
    .menu-img { width:110px; height:80px; object-fit:cover; border-radius:8px; background:#efe3d2; }
    .price { font-weight:600; }
  </style>
</head>
<body>
<div class="container mt-5">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h2 class="mb-0">✏️ Bestellung bearbeiten</h2>
    <a href="bestellungen.php" class="btn btn-secondary">Zurück</a>
  </div>

  <div class="card mb-4" style="background:#fff8ee;">
    <div class="card-body">
      <div class="row g-2">
        <div class="col-md-4"><strong>Bestellnr.:</strong> #<?php echo (int)$header['bID']; ?></div>
        <div class="col-md-4"><strong>Tisch:</strong> Tisch <?php echo htmlspecialchars($header['tisch_nummer']); ?></div>
        <div class="col-md-4"><strong>Zeitpunkt:</strong> <?php echo htmlspecialchars($header['zeitpunkt']); ?></div>
      </div>
      <div class="text-muted mt-2">Menge auf 0 setzen = Position entfernen.</div>
    </div>
  </div>

  <form method="POST" action="bestellungsabfragen/aktualisieren.php">
    <input type="hidden" name="bID" value="<?php echo (int)$bID; ?>">

    <?php foreach ($items as $it):
      $kID = (int)$it['kID'];
      $preis = number_format((float)$it['preis'], 2, ',', '.');
      $menge = (int)$it['menge'];
    ?>

      <div class="row align-items-center menu-item">
        <div class="col-3 col-md-2">
          <img class="menu-img" src="bilder/<?php echo $kID; ?>.png" alt="Gericht <?php echo $kID; ?>">
        </div>

        <div class="col-6 col-md-6">
          <div class="fw-bold"><?php echo htmlspecialchars($it['produkt']); ?></div>
        </div>

        <div class="col-3 col-md-2 text-md-end">
          <div class="price"><?php echo $preis; ?> €</div>
        </div>

        <div class="col-12 col-md-2 mt-2 mt-md-0">
          <input type="number" name="menge[<?php echo $kID; ?>]" class="form-control" min="0" value="<?php echo $menge; ?>">
        </div>
      </div>
    <?php endforeach; ?>

    <div class="d-flex justify-content-between mt-4">
      <a href="bestellungen.php" class="btn btn-secondary">Abbrechen</a>
      <button type="submit" name="aktualisieren" class="btn btn-success">Änderungen speichern</button>
    </div>
  </form>
</div>
</body>
</html>
