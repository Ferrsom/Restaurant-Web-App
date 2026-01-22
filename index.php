<?php
include "db/dbverbindung.php";
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Restaurant – Start</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color:#f5efe6; }
    .card{ background:#fff8ee; border:none; box-shadow:0 4px 8px rgba(0,0,0,.1); }
    .btn-menu{ background:#c9a66b; color:#fff; }
    .btn-menu:hover{ background:#b8945a; }
  </style>
</head>
<body>
  <div class="container text-center mt-5">
    <h1 class="mb-4">🍽 Restaurant Bestellsystem</h1>

    <div class="row justify-content-center g-4">
      <div class="col-md-4">
        <div class="card p-4">
          <h4>Neue Bestellung</h4>
          <a href="karte.php" class="btn btn-menu mt-3">Zur Karte</a>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card p-4">
          <h4>Bestellungen</h4>
          <a href="bestellungen.php" class="btn btn-menu mt-3">Alle Bestellungen</a>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
