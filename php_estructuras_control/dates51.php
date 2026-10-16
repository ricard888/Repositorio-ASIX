<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Dates Ricard</title>
    <link rel="stylesheet" href="fitxerestils51.css">
</head>
<body>

<a href="index51.php">Inici</a>
<a href="about51.php">About</a>
<a href="dades51.php">Dades</a>
<a href="joc51.php">Joc</a>
<a href="control51.php">Control</a>
<a href="dates51.php">Dates</a>

<h1>Càlculs de Temps i Dates</h1>

<?php
/**
 * Indiquem a PhpStorm que aquestes variables venen del fitxer variables51.php
 * @var int $dataNaixementTimestamp
 * @var int $dataFiCursTimestamp
 */
require "variables51.php";

$ara = time(); // Timestamp actual en segons

// --- 1. DES DE LA DATA DE NAIXEMENT FINS ARA ---
$segonsPasats = $ara - $dataNaixementTimestamp;

$diesPasats = $segonsPasats / 86400;      // 86400s = 1 dia
$horesPasades = $segonsPasats / 3600;     // 3600s = 1 hora
$minutsPasats = $segonsPasats / 60;       // 60s = 1 minut

echo "<h2>Temps des del meu naixement (" . date("d/m/Y", $dataNaixementTimestamp) . "):</h2>";
echo "<p><b>Total de dies:</b> " . number_format($diesPasats, 0, ',', '.') . " dies</p>";
echo "<p><b>Total d'hores:</b> " . number_format($horesPasades, 0, ',', '.') . " hores</p>";
echo "<p><b>Total de minuts:</b> " . number_format($minutsPasats, 0, ',', '.') . " minuts</p>";
echo "<p><b>Total de segons:</b> " . number_format($segonsPasats, 0, ',', '.') . " segons</p>";


// --- 2. DES DE ARA FINS AL FINAL DE CURS ---
$segonsRestants = $dataFiCursTimestamp - $ara;

$diesRestants = $segonsRestants / 86400;
$horesRestants = $segonsRestants / 3600;
$minutsRestants = $segonsRestants / 60;

echo "<h2>Temps restant per acabar el curs (" . date("d/m/Y", $dataFiCursTimestamp) . "):</h2>";
echo "<p><b>Total de dies:</b> " . number_format($diesRestants, 0, ',', '.') . " dies</p>";
echo "<p><b>Total d'hores:</b> " . number_format($horesRestants, 0, ',', '.') . " hores</p>";
echo "<p><b>Total de minuts:</b> " . number_format($minutsRestants, 0, ',', '.') . " minuts</p>";
echo "<p><b>Total de segons:</b> " . number_format($segonsRestants, 0, ',', '.') . " segons</p>";
?>

</body>
</html>