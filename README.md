# 🚀 Repositorio Personal - Ricard Sala

¡Hola! 👋 Soy **Ricard Sala**, estudiante de **ASIR (Administración de Sistemas Informáticos en Red)**. Me apasiona la automatización de tareas, el despliegue de infraestructuras, la gestión de redes y la ciberseguridad. 

Busco mi primera oportunidad laboral o prácticas en entornos Cloud, Administración de Sistemas (Linux/Windows) o DevOps, donde pueda aportar mis conocimientos técnicos y seguir aprendiendo de entornos de producción reales.

---

## 🛠️ Tecnologías y Competencias
* **Sistemas Operativos:** Linux (Ubuntu/Debian/Rocky), Windows Server.
* **Redes y Enrutamiento:** Protocolos de enrutamiento dinámico (RIPv2, OSPF), VLANs, VPNs.
* **Automatización y Scripts:** Python, Bash, automatización de tareas y bots.
* **Servicios de Red:** DNS, DHCP, Apache/Nginx, SSH, FTP, SAMBA.

---

## 📁 Proyectos Destacados

### 1. 🤖 Bot de Telegram
* **Descripción:** Desarrollo de un bot automatizado en Python para Telegram enfocado en [menciona brevemente qué hace el bot, por ejemplo: monitorización de sistemas, alertas o automatización].
* **Tecnologías:** Python, API de Telegram, [librerías usadas].

### 2. 🌐 Configuración RIP-v2 (Comandes)
* **Descripción:** Implementación y análisis de un escenario de red utilizando el protocolo de enrutamiento dinámico RIPv2 mediante comandos avanzados de red.
* **Tecnologías:** Cisco IOS / Quagga, Routing, Redes IPv4.

### 3. 🎓 Proyecto Integrado ASIX (Pau & Ricard)
* **Descripción:** Proyecto final integrador del ciclo donde diseñamos y desplegamos una infraestructura completa que incluye [ej: servidor web, bases de datos, seguridad perimetral...].
* **Tecnologías:** Bash scripting, administración de servidores, documentación técnica.

---

## 📫 Contacto y Enlaces
* 💼 **LinkedIn:** [Enlace a tu perfil de LinkedIn]
* 📧 **Email:** [Tu correo de contacto para empresas]
* 📍 **Ubicación:** Cataluña, España

# [cite_start]Pr4.1 - Còpies de seguretat de volums AD [cite: 2]
[cite_start]**Assignatura:** UF4: Seguretat, rendiment i recursos [cite: 1]  
[cite_start]**Tipus:** Pràctiques de classe [cite: 3]  
**Entorn:** Windows Server 2019 (Controlador de Domini)

---

## [cite_start]1. Còpies de seguretat programades vs. Manuals [cite: 7]

### Procediment programat via Interfície Gràfica (GUI)
1. Obrim l'administrador del servidor i cliquem a **Windows Server Backup** (`wbadmin.msc`).
2. Al panell d'accions de la dreta, seleccionem **Backup Schedule Wizard** (Assistent de programació).
3. Triem la configuració desitjada (*Full Server* o *Custom* per especificar volums o el *System State*).
4. Indiquem l'hora o intervals diaris en què s'executarà automàticament.
5. Seleccionem el destí. L'assistent programat requereix preferentment un **disc dedicat exclusiu** per a les còpies, el qual serà formatat i ocultat de l'explorador de fitxers.

![Còpia Programada](screenshots/programada_gui.png) ### Diferències amb la Còpia Manual (*Backup Once*)
* **Gestió del destí:** La còpia manual permet emmagatzemar el backup en qualsevol volum local amb lletra assignada o en una carpeta compartida de xarxa sense necessitat de formatar o dedicar el disc sencer.
* **Rotació i automatització:** La programada és autònoma i gestiona l'espai eliminant automàticament els backups més antics quan el disc s'omple. La manual és un esdeveniment únic que requereix intervenció de l'administrador i pot sobreescriure dades prèvies si es desa a la mateixa ruta.

---

## [cite_start]2. Restauració d'unitat i Mode DSRM [cite: 8]

[cite_start]Per restaurar elements crítics d'Active Directory, cal arrencar el sistema en **Mode de Restauració de Serveis de Directori (DSRM)** per evitar que els fitxers de la base de dades estiguin bloquejats[cite: 8].

### Passos del procediment:
1. Executem `msconfig`, anem a la pestanya **Boot**, marquem **Safe boot** i seleccionem **Active Directory repair**. Reiniciem el servidor.
2. [cite_start]Iniciem sessió localment utilitzant l'usuari **.\Administrador** i la contrasenya específica de DSRM (configurada durant la promoció del DC)[cite: 8].
3. [cite_start]Obrim *Windows Server Backup*, triem **Recover** [cite: 8] [cite_start]i seguim l'assistent per restaurar la unitat o fitxers desitjats (com la unitat `C:`)[cite: 8].
4. Desmarquem el mode segur a `msconfig` i tornem a reiniciar normalment.

![Mode DSRM](screenshots/dsrm_config.png) ---

## [cite_start]3. Còpia de seguretat del System State per CMD i Programació [cite: 9, 10]

### [cite_start]Còpia Manual instantània (CMD / PowerShell) [cite: 9]
[cite_start]Executem la següent comanda com a Administrador per fer un backup de l'estat del sistema immediat[cite: 9]:
```powershell
wbadmin start systemstatebackup -backupTarget:E: -quiet
