<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>CV Ricard</title>
    <link rel="stylesheet" href="fitxerestils51.css">
</head>
<body>

<a href="index51.php">Inici</a>
<a href="about51.php">About</a>
<a href="dades51.php">Dades</a>
<a href="joc51.php">Joc</a> |
<a href="control51.php">Control</a> |
<a href="dates51.php">Dates</a>

<h1>Curriculum Vitae</h1>

<?php
// Constant
const NOM = "Ricard";

// Variables i operador de resta
$anyNaixement = 2002;
$anyActual = 2026;
$edat = $anyActual - $anyNaixement;

// Funció de text
$estudis = "desenvolupament web";
$estudisMajuscules = strtoupper($estudis);

// Concatenació
echo "<p>El meu nom és " . NOM . ".</p>";
echo "<p>Tinc " . $edat . " anys.</p>";
echo "<p>Estudio " . $estudisMajuscules . ".</p>";
?>

</body>
</html>
