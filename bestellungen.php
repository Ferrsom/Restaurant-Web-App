<?php
include "db/dbverbindung.php";

$sql = "
SELECT b.bID, b.zeitpunkt, t.tisch_nummer, COALESCE(SUM(bp.menge * bp.einzelpreis), 0) AS gesamt
FROM bestellung b
JOIN tisch t ON t.tID = b.tID
LEFT JOIN bestellposition bp ON bp.bID = b.bID
GROUP BY b.bID, b.zeitpunkt, t.tisch_nummer
ORDER BY b.bID DESC
";

$bestellungen = $verbindung->query($sql)->fetchAll();

$msg = $_GET['msg'] ?? '';
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Bestellungen</title>
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
    <h2 class="mb-0">📋 Bestellungen</h2>
    <a href="index.php" class="btn btn-secondary">Zurück</a>
  </div>

  <?php if ($msg === 'deleted'): ?>
    <div class="alert alert-success">Bestellung wurde gelöscht.</div>
  <?php elseif ($msg === 'created'): ?>
    <div class="alert alert-success">Bestellung wurde erstellt.</div>
  <?php elseif ($msg === 'updated'): ?>
    <div class="alert alert-success">Bestellung wurde aktualisiert.</div>
  <?php endif; ?>

  <div class="card p-3">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead class="table-light">
          <tr>
            <th>Bestellnr.</th>
            <th>Tisch</th>
            <th>Zeitpunkt</th>
            <th class="text-end">Gesamt</th>
            <th class="text-end">Aktionen</th>
          </tr>
        </thead>
        <tbody>

          <?php foreach ($bestellungen as $b):
            $gesamt = number_format((float)$b['gesamt'], 2, ',', '.');
          ?>
            <tr>
              <td>#<?php echo (int)$b['bID']; ?></td>
              <td>Tisch <?php echo htmlspecialchars($b['tisch_nummer']); ?></td>
              <td><?php echo htmlspecialchars($b['zeitpunkt']); ?></td>
              <td class="text-end"><?php echo $gesamt; ?> €</td>
              <td class="text-end">
                <a href="bestellung_bearbeiten.php?bID=<?php echo (int)$b['bID']; ?>" class="btn btn-sm btn-warning">Edit</a>
                <a href="bestellungsabfragen/loeschen.php?bID=<?php echo (int)$b['bID']; ?>"
                   class="btn btn-sm btn-danger"
                   onclick="return confirm('Bestellung wirklich löschen?');">Löschen</a>
                <a href="rechnung.php?bID=<?php echo (int)$b['bID']; ?>" class="btn btn-sm btn-success">Rechnung</a>
              </td>
            </tr>
          <?php endforeach;
          ?>

          <?php if (count($bestellungen) === 0): ?>
            <tr><td colspan="5" class="text-center text-muted">Keine Bestellungen vorhanden.</td></tr>
          <?php endif;?>

        </tbody>
      </table>
    </div>
  </div>

</div>
</body>
</html>
