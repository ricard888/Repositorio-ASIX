🛡️ Monitorització de Seguretat DHCP i Detecció d'Amenaces

Projecte complet de monitorització de seguretat, anàlisi de mètriques i detecció d'amenaces en temps real per a serveis DHCP IPv4 (Kea DHCP). Aquest entorn utilitza Prometheus, Grafana, Exporters personalitzats en Python i scripts d'auditoria de xarxa (Scapy).

📚 Explicació de Conceptes Clau

Abans de veure com està configurat el projecte, és important entendre com es connecten totes les peces i quin paper té cada tecnologia:

1. Què és un Servidor DHCP?

El servidor DHCP (Dynamic Host Configuration Protocol) és l'encarregat d'assignar automàticament adreces IP, màscares de xarxa i la porta d'enllaç (gateway) als dispositius que es connecten a la xarxa. Sense un servidor DHCP, cada equip s'hauria de configurar manualment.

2. Què és un "Exporter" i per què el necessitem?

Prometheus no sap com llegir directament la informació interna dels programes (com Kea DHCP o les alertes de xarxa). Un Exporter és un petit servei intermediari que actua com a "traductor": agafa les dades internes del servei, les converteix al format estàndard que entén Prometheus i les publica en una pàgina web interna (un punt d'accés o endpoint /metrics).

kea-exporter: Llegeix la configuració i l'estat de Kea DHCP a través de la seva API HTTP (port 8000) i publica mètriques d'IPs totals, IPs ocupades, etc., al port 9547.

detect_rogue_dhcp.py: És el nostre exporter personalitzat. Escolta el tràfic de xarxa i exposa la mètrica dhcp_rogue_server_detected al port 9101.

3. Com funcionen Prometheus i Grafana?

Prometheus: És una base de dades especialitzada en sèries temporals. Cada pocs segons va a visitar els endpoints dels Exporters (scraping), recull les mètriques numèriques i avalua les regles d'alerta. Si un valor supera un límit (per exemple, si el pool d'IPs supera el 90%), Prometheus canvia l'estat de l'alerta a FIRING.

Grafana: És la interfície gràfica de visualització. Es connecta a Prometheus (Data Source), llegeix aquestes dades temporals i les transforma en panells visuals interactius (gràfiques de línies, velocímetres, indicadors d'estat).

🏗️ Arquitectura del Sistema

  [ Clients de la Xarxa / Atacant ]
                 │
                 ▼ (Tràfic DHCP - Capa 2/3)
┌─────────────────────────────────────────────────────────┐
│                    kea-server                           │
│  IP: 172.24.66.10  │ Interfície: enp0s8                 │
│                                                         │
│  ┌─────────────────┐       ┌─────────────────────────┐  │
│  │   Kea DHCP      │       │ detect_rogue_dhcp.py    │  │
│  │ (Port :8000 API)│       │ (tcpdump + exporter)    │  │
│  └────────┬────────┘       └────────────┬────────────┘  │
│           │                             │               │
│  ┌────────▼────────┐                    │               │
│  │  kea-exporter   │                    │               │
│  │   (Port :9547)  │                    │               │
│  └────────┬────────┘                    │               │
└───────────┼─────────────────────────────┼───────────────┘
            │ /metrics                    │ /metrics
            ▼                             ▼
┌─────────────────────────────────────────────────────────┐
│                  Contenidors Docker                     │
│                                                         │
│  ┌───────────────────────────────────────────────────┐  │
│  │                    Prometheus                     │  │
│  │            (Port :9090 / alert-rules)             │  │
│  └────────────────────────┬──────────────────────────┘  │
│                           │                             │
│                           ▼                             │
│  ┌───────────────────────────────────────────────────┐  │
│  │                     Grafana                       │  │
│  │            (Port :3000 / Dashboards)              │  │
│  └───────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────┘


🛠️ Components i Fitxers del Projecte

1. docker-compose.yml

Defineix i gestiona l'execució dels servidors de monitorització dins de contenidors aïllats Docker.

prometheus-ricardsala: Executa Prometheus v2.55.1, carrega el fitxer de configuració general i el fitxer de regles d'alerta. Exposa el port 9090.

grafana-ricardsala: Executa Grafana v11.3.0/v11.4.0, manté la informació persistent en un volum de dades (grafana-data) i exposa el port 3000.

monitor-network: Xarxa bridge de Docker que permet que Grafana es comuniqui directament amb Prometheus mitjançant el nom del contenidor (http://prometheus:9090).

2. kea-exporter.service

Fitxer de unitat de systemd que fa que l'exporter de Kea s'executi automàticament en segon pla com un servei del sistema operatiu Linux.

Es connecta a l'API local de Kea (http://172.24.66.10:8000).

Garanteix que si el servei cau, es reintenti la seva execució automàticament (Restart=always).

3. detect_rogue_dhcp.py

Script personalitzat en Python dissenyat per detectar la presència de servidors DHCP no autoritzats (Rogue DHCP).

Com funciona?: Utilitza l'eina tcpdump en segon pla per analitzar en temps real els paquets que passen per la interfície enp0s8 al port UDP 67 o 68.

Com detecta la amenaça?: Quan rep una resposta DHCP (Offer), extreu la IP del servidor que l'ha enviat. Si aquesta IP no coincideix amb la del nostre servidor legítim (172.24.66.10), activa la mètrica dhcp_rogue_server_detected.set(1) i escriu un registre a /var/log/dhcp-rogue.log.

Publicació de dades: Inicia un servidor HTTP al port 9101 perquè Prometheus pugui llegir l'estat del detector.

4. prometheus/alert-rules.yml

Conté les regles lògiques d'avís que Prometheus avalua de forma contínua:

DHCPPoolExhaustionWarning: Es dispara si l'ocupació del pool supera el 90% ((kea_dhcp4_addresses_assigned_total / kea_dhcp4_addresses_total) * 100 > 90).

RogueDHCPServerDetected: Es dispara immediatament (for: 0m) si la mètrica del detector passa a ser igual a 1 (dhcp_rogue_server_detected == 1).

⚔️ Escenaris d'Atac Simulats

1. Atac de Denegació de Servei: DHCP Starvation (starvation_test.py)

Què és un atac Starvation?: Un atacant genera milers de sol·licituds DHCP demanant una adreça IP, cada vegada utilitzant una adreça MAC inventada o aleatòria. El servidor DHCP legítim exhaureix tot el seu rang d'IPs assignant-ne a dispositius inexistents, deixant sense servei als usuaris reals de la xarxa.

Com el simulem?: Amb l'eina Scapy en Python, generem un bucle de 2.000 peticions DHCP DISCOVER amb MACs falses creades al moment (02:XX:XX:XX:XX:XX).

Com es detecta?:

En el dashboard de Grafana, la gràfica d'ocupació del pool puja ràpidament cap al 100%.

En Prometheus (http://localhost:9090/alerts), la regla DHCPPoolExhaustionWarning passa d'estat Inactive a FIRING.

2. Atac de Servidor DHCP No Autoritzat: Rogue DHCP (rogue_offer_sim.py)

Què és un Rogue DHCP?: Un atacant (o un usuari que connecta un router no autoritzat a la xarxa) comença a respondre a les peticions d'IP dels clients. Si el servidor fals respon més ràpid que el servidor legítim, pot assignar als equips una porta d'enllaç o uns servidors DNS maliciosos per realitzar un atac Man-In-The-Middle (MITM).

Com el simulem?: Executem el script rogue_offer_sim.py que construeix un paquet de resposta DHCP Offer simulant provenir d'una IP atacant (172.24.66.99).

Com es detecta?:

El detector detect_rogue_dhcp.py captura el paquet en capa 2/3.

S'escriu la línia d'alerta a /var/log/dhcp-rogue.log.

La mètrica dhcp_rogue_server_detected canvia de 0.0 a 1.0.

L'alerta RogueDHCPServerDetected es posa en estat FIRING a la interfície de Prometheus.

📝 Preguntes Finals i Reflexió Teòrica

1. Per què cal utilitzar un llindar (90%) en lloc d'alertar quan l'ocupació arriba al 100%?

Si s'espera a enviar l'alerta quan el pool està al 100%, l'atac de denegació de servei ja s'ha consumat i els usuaris legítims ja no poden obtenir configuració de xarxa. Establir el límit al 90% dóna un marge de temps essencial a l'administrador per analitzar el tràfic, ampliar la subxarxa o blocar l'atac abans que caigui el servei.

2. Quina limitació té el nostre detector de Rogue DHCP (basat en tcpdump) en comparació amb DHCP Snooping en un switch?

El nostre detector és passiu: només pot veure els paquets que arriben a la interfície de xarxa del servidor i avisar, però no pot evitar que el paquet arribi als clients.
En canvi, DHCP Snooping és una mesura de seguretat activa que s'executa als switches de la xarxa (Capa 2). Permet definir quins ports són d'un servidor DHCP de confiança i quins no; si detecta una resposta DHCP en un port no autoritzat, el switch bloqueja el port immediatament i evita que l'oferta maliciosa arribi a la xarxa.

3. Si haguéssim de desplegar monitorització en producció, quins altres atacs valdria la pena monitoritzar?

Option Injection: Detectar peticions o respostes DHCP amb opcions inusuals o modificades per evitar alteracions de paràmetres de xarxa (DNS falsos, rutes no autoritzades).

Relay Attacks: Controlar el volum anormal de tràfic enviat a través d'agents Relay per detectar desviació de tràfic entre subxarxes.

Fingerprinting: Monitoritzar els patrons de peticions per identificar escanejos i catalogació de sistemes operatius a la xarxa.

4. Quines accions immediates de contenció prendríeu si es dispara l'alerta RogueDHCPServerDetected?

Identificació: Extreure la MAC i la IP de l'atacant a partir del fitxer de registre /var/log/dhcp-rogue.log.

Localització: Consultar la taula CAM del switch per saber a quin port físic està connectat el dispositiu no autoritzat.

Contenció: Aïllar l'equip deshabilitant administrativament el port del switch o desconnectant el cable de xarxa.

Verificació: Validar que el servidor Kea legítim torna a respondre sense interferències i reinstaurar les configuracions correctes als clients afectats.

🚀 Instruccions per a la Recreació del Projecte

1. Iniciar els Exporters i el Detector

# Iniciar el servei del kea-exporter
sudo systemctl start kea-exporter

# Iniciar el detector Rogue DHCP en segon pla
sudo su
python3 /usr/local/bin/detect_rogue_dhcp.py &


2. Desplegar la infraestructura de monitorització

cd dhcp-monitoring-ricardsala
docker compose up -d


3. Verificar endpoints de mètriques

Mètriques Kea Exporter: curl http://172.24.66.10:9547/metrics

Mètriques Rogue Detector: curl http://172.24.66.10:9101/metrics

Interfície de Prometheus: http://localhost:9090

Interfície de Grafana: http://localhost:3000 (Usuari: admin, Contrasenya configurada a docker-compose.yml)

4. Executar les proves d'atac

# Prova 1: DHCP Starvation
python3 starvation_test.py

# Prova 2: Rogue DHCP Offer
python3 rogue_offer_sim.py
