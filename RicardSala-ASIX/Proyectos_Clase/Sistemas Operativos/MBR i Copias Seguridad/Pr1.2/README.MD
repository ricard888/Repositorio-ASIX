# ASIX2 - Pràctica MBR, RAID i Còpies de Seguretat (M0378)

Repositori amb la documentació i resums de la Pràctica 2 de Seguretat Informàtica. Inclou treballs amb MBR/GPT, RAID 10 en Linux amb ZFS, RAID 5 en Windows amb Storage Spaces i còpies de seguretat amb Déjà Dup.

---

## 📌 Què s'ha fet a la pràctica?

### 1. Manipulació de MBR i GPT
* **En Linux (SystemRescue):**
  * Còpia de seguretat del MBR i GPT amb l'ordre `dd` i `sgdisk`.
  * Simulació d'avaria sobreescrivint els primers sectors del disc (`custom_mbr.bin` / `/dev/zero`).
  * Recuperació de la taula de particions i dades tornant a escriure el backup.
* **En Windows (10/11):**
  * Lectura directa dels sectors del disc en hexadecimal fent servir **HxD**.
  * Corrupció manual de capçaleres i comprovació d'errors d'arrencada a la BIOS/sistema.

---

## ⚙️ 2. RAIDs i Resiliència

### ZFS RAID 10 (Ubuntu Server)
* Creació d'un pool `dadesRS` fent servir 4 discos de 10 GB en dos miralls (*mirrors*).
* Creació del dataset `dadesRS/practica2` i verificació de la capacitat lliure usable (~20 GB).
* Prova de fallada de disc amb `zpool offline`, comprovant que les dades segueixen accessibles.
* Canvi i resincronització de disc (*resilvering*) amb `zpool replace`.
* Ús de **Snapshots** (`zfs snapshot`), comprovació de canvis (`zfs diff`) i restauració d'estat anterior amb `zfs rollback`.

### Windows Storage Spaces (RAID 5)
* Creació d'un grup d'emmagatzematge amb paritat usant 3 discos de 10 GB.
* Anàlisi de capacitat teòrica vs. real (pèrdua d'espai per paritat, metadades i conversió decimal/binària a Windows).
* Prova de fallada treient un dels discos de la VM: verificació de lectura del fitxer en estat degradat.
* Diagnòstic de l'estat del volum amb la interfície gràfica i mitjançant PowerShell (`Get-VirtualDisk` i `Get-PhysicalDisk`).

---

## 📂 3. Còpies de Seguretat (Déjà Dup)
* Configuració de còpies locals a `~/backup` incloent `/home` i `/var`.
* Exclusió de directoris temporals o de sistema (`/var/log`, `/var/crash`, etc.) per evitar errors de permisos.
* Anàlisi del format de fitxers generat per Duplicity: volums comprimits i xifrats (`.gpg`), fitxers de manifest i signatures.

---

## 🛠️ Entorn i eines
* **Sistemes operatius:** Ubuntu Server, Ubuntu Desktop, Windows 10/11, SystemRescue.
* **Comandes i eines:** `dd`, `fdisk`, `gdisk`, `sgdisk`, `zpool`, `zfs`, PowerShell, HxD, Déjà Dup.