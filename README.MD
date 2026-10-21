# Servidor web basico con Python y Flask

Proyecto backend sencillo desarrollado para aprender el funcionamiento de un servidor web, el manejo de rutas HTTP y la respuesta en formato JSON.

---

## Estructura del proyecto

* **app.py**: Contiene la logica principal del servidor. Define la aplicacion Flask, la lista de datos en memoria y los endpoints (rutas) para recibir peticiones GET y POST.
* **main.py**: Es el punto de entrada ejecutable. Separa el arranque del servidor de la logica interna y se encarga de iniciar la aplicacion en el puerto 5000.
* **test.py**: Script de prueba local en Python que utiliza la libreria requests para enviar peticiones POST al servidor sin necesidad de usar la terminal.

---

## Instalacion y configuracion del entorno

1. Instalacion de paquetes necesarios:
   python -m pip install flask requests

2. Verificacion de la instalacion:
   python -c "import flask, requests; print('Tot correcte')"

3. Seleccion del interprete:
   En VS Code es necesario seleccionar la version correcta del interprete de Python (Python 3.11 64-bit) usando el comando `Python: Select Interpreter` para evitar conflictos de librerias.

---

## Como ejecutar la aplicacion

Para arrancar el servidor web local, ejecuta en la terminal:

```bash
python main.py