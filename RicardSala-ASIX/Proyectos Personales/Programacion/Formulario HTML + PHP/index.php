<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Formulario Entorno</title>
</head>
<body>

    <h2>Escribe tu nombre</h2>
    <form method="POST">
        <input type="text"
        name="usuario"
        placeholder="Pon tu nombre de usuario">
        <button type="submit">Enviar</button>

    </form>

    <?php
            if ( $_SERVER
            ['REQUEST_METHOD'] == 'POST')
            {
                $name = $_POST['usuario'];

                echo "Bienvenido" . $name;
            }
    ?>
    
</body>
</html>