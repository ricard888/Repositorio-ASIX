<?php
// Variables per al joc (0: Pedra, 1: Paper, 2: Tisora, 3: Spock, 4: Lagarto)
$opcionsJoc = array("Pedra", "Paper", "Tisora", "Spock", "Lagarto");

// Variables per a la pàgina de dates
// Data de naixement (Any, Mes, Dia, Hora, Minut, Segon) -> mktime(h, i, s, m, d, Y)
$dataNaixementTimestamp = mktime(0, 0, 0, 3, 31, 2006); // Modificat amb la meva data real

// Data de final de curs > Exemple: 20 de juny ( comptant recuperacions )
$dataFiCursTimestamp = mktime(23, 59, 59, 6, 20, 2027);
?>
