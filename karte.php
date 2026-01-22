<?php
include "db/dbverbindung.php";

$tische = $verbindung->query("SELECT tID, tisch_nummer FROM tisch ORDER BY tisch_nummer")->fetchAll();
$karte  = $verbindung->query("SELECT kID, produkt, preis FROM karte ORDER BY kID")->fetchAll();

$err = $_GET['err'] ?? '';
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Speisekarte</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color:#f5efe6; }
    .menu-item { background:#fff8ee; border-radius:10px; padding:14px; margin-bottom:12px; }
    .menu-img { width:110px; height:80px; object-fit:cover; border-radius:8px; background:#efe3d2; }
    .price { font-weight:600; }
    .small-muted { color:#6c757d; font-size:.9rem; }
  </style>
</head>
<body>
<div class="container mt-5">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h2 class="mb-0">📜 Speisekarte</h2>
    <a href="index.php" class="btn btn-secondary">Zurück</a>
  </div>

  <?php if ($err === 'empty'): ?>
    <div class="alert alert-warning">Bitte mindestens ein Gericht mit Menge &gt; 0 auswählen.</div>
  <?php elseif ($err === 'table'): ?>
    <div class="alert alert-warning">Bitte einen Tisch auswählen.</div>
  <?php endif; ?>

  <form method="POST" action="bestellungsabfragen/speichern.php">
    <div class="card mb-4" style="background:#fff8ee;">
      <div class="card-body">
        <div class="row g-3 align-items-end">
          <div class="col-md-4">
            <label class="form-label">Tisch auswählen</label>
            <select name="tID" class="form-select" required>
              <option value="" selected disabled>Bitte wählen…</option>
              
              <?php foreach ($tische as $t): ?>
                <option value="<?php echo (int)$t['tID']; ?>">
                  Tisch <?php echo htmlspecialchars($t['tisch_nummer']); ?>
                </option>
              <?php endforeach; ?>
              
            </select>
            <div class="small-muted mt-1">Die Bestellung wird diesem Tisch zugeordnet.</div>
          </div>
          <div class="col-md-8">
            <div class="alert alert-light mb-0" style="background:#f7f0e6;">
              Mengen eintragen (0 = nicht bestellen). Dann unten „Bestellung erstellen“.
            </div>
          </div>
        </div>
      </div>
    </div>

    <?php foreach ($karte as $item): 
      $kID = (int)$item['kID'];
      $preis = number_format((float)$item['preis'], 2, ',', '.');
    ?>
      <div class="row align-items-center menu-item">
        <div class="col-3 col-md-2">
          <img class="menu-img" src="bilder/<?php echo $kID; ?>.png" alt="Gericht <?php echo $kID; ?>">
        </div>

        <div class="col-6 col-md-6">
          <div class="fw-bold"><?php echo htmlspecialchars($item['produkt']); ?></div>
        </div>

        <div class="col-3 col-md-2 text-md-end">
          <div class="price"><?php echo $preis; ?> €</div>
        </div>

        <div class="col-12 col-md-2 mt-2 mt-md-0">
          <input type="number" name="menge[<?php echo $kID; ?>]" class="form-control" min="0" value="0">
        </div>
      </div>
    <?php endforeach; ?>

    <div class="d-flex justify-content-between mt-4">
      <a href="index.php" class="btn btn-secondary">Abbrechen</a>
      <button type="submit" name="speichern" class="btn btn-success">Bestellung erstellen</button>
    </div>
  </form>
</div>
</body>
</html>
