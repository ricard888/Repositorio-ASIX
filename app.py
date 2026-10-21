# Importamos Flask para crear el server. jsonify para
# responder en formato JSON y request para leer la
# información que nos envia el usuario
from flask import Flask, jsonify, request

# Creamos la instancia principal de la aplicacion Flask
# __name__ le dice a Flask en que archivo/modulo se està ejecutando
app = Flask(__name__)

# Simulamos una BD usando una lista de Python en memoria
notas = ["Comprar ropa", "Crear un servidor web"]

# Ruta principal ( GET por defecto ). Devuelve un mensaje de bienvenida
@app.route("/")
def home():
    return "<h1>Servidor Activo</h1><p>Consulta la API en /notas</p>"

# Ruta para obtener la lista completa de notas guardadas ( GET )
@app.route("/notas", methods=["GET"])
def obtener_notas():
    # Convertimas la lista Python a formato JSON para enviarlo al cliente
    return jsonify({"notas": notas, "total": len(notas)})

# Ruta para recibit una nueva nota y guardarla ( POST )
@app.route("/notas", methods=["POST"])
def agregar_nota():
     # Extraemos los datos en formato JSON que vienen en la peticion
     datos = request.get_json()

     # Comprobamos si se ha enviado la clave "nota" dentro del JSON
     if datos and "nota" in datos:
            nueva_nota = datos["nota"]
            notas.append(nueva_nota) # Guardamos la nueva nota en la lista
            return jsonify({"mensaje": "La nota se ha guardado con éxito!!", "nota": nueva_nota})

     # Si faltan datos, devolvemos un codigo de error ( 400 > Bad Request )
     return jsonify({"error: Te falta incluir el campo 'nota'"}), 400
