# Lista de IPs que monitorizaremos
OBJETIVOS = [
    {"nombre": "Google Web", "tipo": "HTTP", "destino": "https://www.google.com"},
    {"nombre": "GitHub", "tipo": "HTTP", "destino": "https://github.com"},
    {"nombre": "DNS Local", "tipo": "PING", "destino": "192.168.18.1"},
    {"nombre": "DNS Google", "tipo": "PING", "destino": "8.8.8.8"}
]

# Intervalo tiempo entre chequeo ( en segundos )
INTERVALO_CHEQUEO = 30