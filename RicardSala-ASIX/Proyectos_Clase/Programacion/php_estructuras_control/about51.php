<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>About Ricard</title>
    <link rel="stylesheet" href="fitxerestils51.css">
</head>
<body>

<a href="index51.php">Inici</a>
<a href="about51.php">About</a>
<a href="dades51.php">Dades</a>
<a href="joc51.php">Joc</a> |
<a href="control51.php">Control</a> |
<a href="dates51.php">Dates</a>

<h1>Sobre aquesta activitat</h1>

<?php
// Constant
define("ACTIVITAT", "Pp5.1");

// Variables
$llenguatge = "PHP";
$anyIniciPHP = 1995;
$anyActual = 2026;

// Operador de resta (calcula els anys que té PHP)
$anysExistencia = $anyActual - $anyIniciPHP;

// Concatenació
echo "<p>Aquesta és la practica " . ACTIVITAT . " de Implantacio d'Aplicacions Web.</p>";
echo "<p>En aquesta activitat aprenem a fer servir i la sintaxi bàsica de " . $llenguatge . ".</p>";
echo "<p>El llenguatge " . $llenguatge . " es va crear fa " . $anysExistencia . " anys.</p>";
echo "<p>El objectiu es utilitzar variables, constants, operadors i concatenació per practicar i aprendre
el llenguatge " . $llenguatge . " </p>";
?>

</body>
</html>