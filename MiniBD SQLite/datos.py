# Libreria sqlite
import sqlite3

# Crear conexion con la base de datos ( crear si no existe )
conexion = sqlite3.connect('datos.db')
cursor = conexion.cursor()

# Crear tabla
cursor.execute('''
CREATE TABLE IF NOT EXISTS
usuarios (
id INTEGER PRIMARY KEY AUTOINCREMENT,
nombre TEXT NOT NULL,
edad INTEGER NOT NULL
)
''')

# Insertar datos
cursor.execute("INSERT INTO usuarios (nombre, edad) VALUES ('Ricard', 20)")
conexion.commit() # !Guardamos los cambios!

# Leer datos
cursor.execute("SELECT * FROM usuarios")
usuarios = cursor.fetchall()

# Recorrer cada usuario de la base de datos i  mostrarlo en pantalla
for i in usuarios:
    print(f"Id: {i[0]}, Nombre: {i[1]}, Edad: {i[2]}")

# Cerrar conexion
conexion.close()