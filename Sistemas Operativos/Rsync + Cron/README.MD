# Pr4.3 - Copies de Seguretat amb Rsync

Practica de sistemes sobre copies de seguretat i sincronitzacio remota entre dues maquines virtuals Ubuntu mitjancant `rsync`, `SSH`, `Samba` i `cron`.

---

## Entorn del Laboratori

| Maquina | Sistema Operatiu | IP | Rol |
|--------|------------------|----|-----|
| Maquina 1 (SQL) | Ubuntu 18.04 LTS | 192.168.192.3 | Origen / Principal |
| Maquina 2 (Client) | Ubuntu 22.04 LTS | 192.168.192.5 | Desti / Secundaria |

Ambdues maquines estan connectades mitjancant una xarxa **Solo-Anfitrion** de VirtualBox (`192.168.192.0/24`).

---

## Contingut de la Practica

### 1. Estructura de Directoris i Permisos

Creacio de l'arbre de carpetes `Sapalomera` amb `mkdir` i `touch`, aplicant permisos especifics amb `chmod`:

```
Sapalomera/
├── Direccio/
│   ├── Documents.txt     (chmod 700)
│   └── Nomines.txt       (chmod 700)
├── Professors/
│   ├── Notes.pdf         (chmod 700)
│   ├── Shrek4.mp4        (chmod 755)
│   └── Valorant.exe      (chmod 777)
└── Alumnes/
    ├── ExamenUF4Sistemes.docx  (chmod 007)
    └── WhatsappWeb.html        (chmod 777)
```

---

### 2. Compartir Carpeta amb Samba

Installacio i configuracio de Samba per compartir la carpeta `Sapalomera` en xarxa. Verificacio mitjancant `smbclient`.

```bash
apt install samba -y
nano /etc/samba/smb.conf
systemctl restart smbd
smbclient -L localhost -N
```

---

### 3. Copia Remota amb rsync

Transferencia de la carpeta `Sapalomera` des de la Maquina 1 a la Maquina 2 amb mode verbose, recursiu i compressio:

```bash
rsync -vrz /home/ricard/Sapalomera ricard@192.168.192.5:/home/ricard/
```

> **Nota:** No es pot transferir a l'arrel `/` ja que Ubuntu 22.04 bloqueja el login SSH de root per seguretat. Transferencia justificada a `/home/ricard/`.

---

### 4. Comparacio i Preservacio de Permisos

Les carpetes van canviar de `755` a `775` a causa de la configuracio `umask` diferent entre les dues maquines. Solucio amb el parametre `-p`:

```bash
rsync -vrzp /home/ricard/Sapalomera ricard@192.168.192.5:/home/ricard/
```

> `rsync` es incremental: si no detecta canvis, no transfereix res i ho confirma amb el output.

---

### 5. Copia Encriptada amb `-e ssh`

Creacio de la carpeta `/SapaEncriptat` a la Maquina 2 i transferencia encriptada mitjancant tunel SSH:

```bash
sudo rsync -vrzp -e ssh /home/ricard/Sapalomera ricard@192.168.192.5:/SapaEncriptat
```

> El fitxer `ExamenUF4Sistemes.docx` amb permisos `007` dona error esperat: nomes "altres" tenen permisos, ni el propietari ni root poden llegir-lo.

---

### 6. Copia Remota Excloent Fitxers `.txt`

Transferencia amb parametres `-vazh` excloent tots els fitxers amb extensio `.txt`:

```bash
sudo rsync -vazh --exclude="*.txt" -e ssh /home/ricard/Sapalomera ricard@192.168.192.5:/NoTreballem
```

| Parametre | Significat |
|-----------|-----------|
| `-v` | Verbose |
| `-a` | Archive (preserva permisos, propietari, dates) |
| `-z` | Compressio de dades |
| `-h` | Format llegible (KB, MB) |
| `--exclude` | Exclusio de fitxers per patro |

---

### 7. Sincronitzacio Automatica amb Cron

#### Configuracio SSH sense contrasenya (necessari per al cron)

```bash
ssh-keygen -t rsa
ssh-copy-id ricard@192.168.192.5
```

#### Creacio del cron a la Maquina 1

```bash
crontab -e
```

Linia afegida al fitxer:

```
* * * * * rsync -avz /SapaSincronitzat/ ricard@192.168.192.5:/SapaSincronitzat/
```

Sincronitzacio cada minut de la carpeta `/SapaSincronitzat/` entre les dues maquines. Qualsevol fitxer creat a la Maquina 1 apareix automaticament a la Maquina 2 al cap d'un minut sense cap intervencio manual.

---

## Conceptes Clau Apresos

| Concepte | Descripcio |
|----------|-----------|
| `chmod` | Control de permisos per propietari, grup i altres |
| `umask` | Mascara que resta permisos al crear fitxers nous |
| `Samba` | Protocol per compartir fitxers en xarxa local |
| `rsync` | Eina de sincronitzacio incremental eficient |
| `-e ssh` | Encriptacio de la transferencia mitjancant tunel SSH |
| `ssh-keygen` | Generacio de parella de claus publica/privada |
| `crontab` | Planificador de tasques automatiques en Linux |

---

## Aplicacions en Entorns Reals

- Backups incrementals automatitzats de servidors
- Sincronitzacio de directoris entre nodes d'un cluster
- Replicacio de dades entre datacenters
- Desplegament d'arxius a servidors remots
- Mirroring de repositoris

---

## Autor

**Ricard Sala Nikitin**  
UF4 - Seguretat, Rendiment i Recursos
