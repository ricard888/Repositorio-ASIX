# Librerias
import sqlite3
from datetime import datetime

# Nombre Base de Datos
NOMBRE_BD = "monitor_red.db"

# Inicializar BD
def inicializar_bd():
    # Crear BD i tabla si no existen
    conexion = sqlite3.connect(NOMBRE_BD)
    cursor = conexion.cursor()

    # Crear tabla con columnas
    cursor.execute ("""
        CREATE TABLE IF NOT EXISTS historial (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nombre TEXT NOT NULL,
            tipo TEXT NOT NULL,
            destino TEXT NOT NULL,
            estado TEXT NOT NULL,
            latencia REAL,
            fecha_hora TEXT NOT NULL)     
    """)

    conexion.commit()
    conexion.close()

# Insertar registros en la Base de Datos
def guardar_resultado(nombre, tipo, destino, estado, latencia):
    conexion = sqlite3.connect(NOMBRE_BD)
    cursor = conexion.cursor()

    fecha_actual = datetime.now().strftime("%Y-%m-%d %H:%M:%S")

    cursor.execute ("""
       INSERT INTO historial (nombre, tipo, destino, estado, latencia, fecha_hora) 
            VALUES (?, ?, ?, ?, ?, ?)            
    """, (nombre, tipo, destino, estado, latencia, fecha_actual))

    conexion.commit()
    conexion.close()