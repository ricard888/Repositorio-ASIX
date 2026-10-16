<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Dades Ricard</title>
    <link rel="stylesheet" href="fitxerestils51.css">
</head>
<body>

<a href="index51.php">Inici</a>
<a href="about51.php">About</a>
<a href="dades51.php">Dades</a>
<a href="joc51.php">Joc</a> |
<a href="control51.php">Control</a> |
<a href="dates51.php">Dates</a>

<h1>Dades del servidor i funcions</h1>

<?php
// Variables superglobals del servidor
$ip = $_SERVER['SERVER_ADDR'];
$navegador = $_SERVER['HTTP_USER_AGENT'];
$fitxer = $_SERVER['PHP_SELF'];

echo "<p>IP del servidor: " . $ip . "</p>";
echo "<p>Navegador: " . $navegador . "</p>";
echo "<p>Nom del fitxer: " . $fitxer . "</p>";

// Variables globals
$alumne = "Ricard";
echo "<p>Alumne usant GLOBALS: " . $GLOBALS['alumne'] . "</p>";

// Variables per provar les funcions
$text = "Hola";
$buida = "";

// Comprovacions
if (isset($text)) {
    echo "<p>La variable \$text existeix.</p>";
}

if (empty($buida)) {
    echo "<p>La variable \$buida està buida.</p>";
}

echo "<p>El tipus de dades de \$text és: " . gettype($text) . "</p>";
?>

</body>
</html>
