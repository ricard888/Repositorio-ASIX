# Importamos la instancia "app" definida en el archivo app.py
from app import app

# Punto de entrada para arrancar el servidor localmente
if __name__ == "__main__":
    # debug=True > Recarga el server automaticamente cuando guardamos
    # los cambios
    print("Iniciando entorno de produccion en http://localhost:5000")
    app.run(debug=True, port=5000)