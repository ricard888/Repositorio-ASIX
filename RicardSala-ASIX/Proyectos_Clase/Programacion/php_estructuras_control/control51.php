<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Control de Rendiment - Ricard</title>
    <link rel="stylesheet" href="fitxerestils51.css">
</head>
<body>

<h1>Comparativa de Rendiment: WHILE vs FOR</h1>

<p>
    <a href="index51.php">Inici</a> |
    <a href="about51.php">About</a> |
    <a href="dades51.php">Dades</a> |
    <a href="joc51.php">Joc</a> |
    <a href="control51.php">Control</a> |
    <a href="dates51.php">Dates</a>
</p>

<?php
require "variables51.php";

// --- PROVA AMB BUCLE WHILE ---
$sumaTempsWhile = 0;
$minWhile = 999999;
$maxWhile = 0;

$pWhile = 0;
while ($pWhile < 77) {
    $inici = microtime(true);

    $k = 0;
    while ($k < 777) {
        $val = rand(0, 100);
        if ($val > 51) {
            // Comprobació
        }
        $k++;
    }

    $fi = microtime(true);
    $temps = ($fi - $inici) * 1000; // Temps en mil·lisegons

    $sumaTempsWhile += $temps;

    if ($temps < $minWhile) {
        $minWhile = $temps;
    }
    if ($temps > $maxWhile) {
        $maxWhile = $temps;
    }

    $pWhile++;
}

$mitjanaWhile = $sumaTempsWhile / 77;


// --- PROVA AMB BUCLE FOR ---
$sumaTempsFor = 0;
$minFor = 999999;
$maxFor = 0;

for ($pFor = 0; $pFor < 77; $pFor++) {
    $inici = microtime(true);

    for ($j = 0; $j < 777; $j++) {
        $val = rand(0, 100);
        if ($val > 51) {
            // Comprobació
        }
    }

    $fi = microtime(true);
    $temps = ($fi - $inici) * 1000;

    $sumaTempsFor += $temps;

    if ($temps < $minFor) {
        $minFor = $temps;
    }
    if ($temps > $maxFor) {
        $maxFor = $temps;
    }
}

$mitjanaFor = $sumaTempsFor / 77;

// --- MOSTRAR RESULTATS ---
echo "<h2>Resultats Estrutura WHILE</h2>";
echo "<p>Mínim: $minWhile ms | Màxim: $maxWhile ms | Mitjana: $mitjanaWhile ms</p>";

echo "<h2>Resultats Estrutura FOR</h2>";
echo "<p>Mínim: $minFor ms | Màxim: $maxFor ms | Mitjana: $mitjanaFor ms</p>";

echo "<h2>Conclusió</h2>";
if ($mitjanaWhile < $mitjanaFor) {
    echo "<p><b>L'estructura WHILE ha estat més ràpida en mitjana.</b></p>";
} elseif ($mitjanaFor < $mitjanaWhile) {
    echo "<p><b>L'estructura FOR ha estat més ràpida en mitjana.</b></p>";
} else {
    echo "<p><b>Totes dues estructures han tingut exactament el mateix rendiment.</b></p>";
}
?>

</body>
</html>