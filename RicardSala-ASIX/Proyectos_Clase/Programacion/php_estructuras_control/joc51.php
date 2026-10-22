<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Joc - Ricard</title>
    <link rel="stylesheet" href="fitxerestils51.css">
</head>
<body>

<h1>Joc: Pedra, Paper, Tisora, Spock, Lagarto</h1>

<p>
    <a href="index51.php">Inici</a> |
    <a href="about51.php">About</a> |
    <a href="dades51.php">Dades</a> |
    <a href="joc51.php">Joc</a> |
    <a href="control51.php">Control</a> |
    <a href="dates51.php">Dates</a>
</p>

<?php
/** @var array $opcionsJoc */ // Per a que phpStorm pogui localitzar la variable
require "variables51.php";

echo "<h2>Resultat de les 5 tirades:</h2>";

for ($i = 1; $i <= 5; $i++) {
    $jugador1 = rand(0, 4);
    $jugador2 = rand(0, 4);

    $nomJ1 = $opcionsJoc[$jugador1];
    $nomJ2 = $opcionsJoc[$jugador2];

    $guanyador = "";

    if ($jugador1 == $jugador2) {
        $guanyador = "Empat! Ningú guanya.";
    }
    // Casos on guanya el Jugador 1
    elseif (
        ($jugador1 == 2 && $jugador2 == 1) || // Tisora corta Paper
        ($jugador1 == 1 && $jugador2 == 0) || // Paper envuelve Pedra
        ($jugador1 == 0 && $jugador2 == 4) || // Pedra aplasta Lagarto
        ($jugador1 == 4 && $jugador2 == 3) || // Lagarto envenena Spock
        ($jugador1 == 3 && $jugador2 == 2) || // Spock rompe Tisora
        ($jugador1 == 2 && $jugador2 == 4) || // Tisora decapita Lagarto
        ($jugador1 == 4 && $jugador2 == 1) || // Lagarto devora Paper
        ($jugador1 == 1 && $jugador2 == 3) || // Paper desautoriza Spock
        ($jugador1 == 3 && $jugador2 == 0) || // Spock vaporiza Pedra
        ($jugador1 == 0 && $jugador2 == 2)    // Pedra aplasta Tisora
    ) {
        $guanyador = "Guanya el Jugador 1!";
    }
    else {
        $guanyador = "Guanya el Jugador 2!";
    }

    echo "<p><b>Tirada $i:</b> Jugador 1 ($nomJ1) vs Jugador 2 ($nomJ2) &rarr; <i>$guanyador</i></p>";
}
?>

</body>
</html>