# ADPropi — Mòdul PowerShell per a l'Institut XYZ

**Pràctica MP0374 · RA7 · Active Directory**

## Objectiu

En aquesta pràctica he configurat l'Active Directory des de zero, amb el domini `XYZ.net`, i he creat el mòdul `ADPropi.psm1`. La idea és poder consultar i gestionar usuaris amb les meves pròpies comandes de PowerShell, sense haver de fer-ho tot manualment.

## Què he configurat?

- He instal·lat el rol **AD DS** i creat el domini `XYZ.net`.
- He organitzat els alumnes i professors en **OU** i grups, com `ESO1A`, `ASIX2A` i `Mates`.
- He creat i provat els **7 cmdlets** de la pràctica.
- He preparat fitxers CSV per processar alumnes i professors.
- He comprovat que el professor `pmas` es crea a la OU correcta.

> **Alguns conceptes:** l'Active Directory és on es gestionen els usuaris i grups del domini. Una **OU** és com una carpeta que serveix per tenir els objectes ordenats. Un **cmdlet** és una comanda de PowerShell que fa una tasca concreta. Un fitxer **CSV** permet guardar dades en files i columnes per carregar diversos usuaris de cop.

## Els 7 cmdlets

| Cmdlet | Què fa? |
|---|---|
| `Get-UsuariState` | Comprova si un compte està actiu, desactivat o no existeix (`Enabled`, `Disabled` o `$null`). |
| `Get-Usuaris` | Mostra els usuaris que formen part del grup indicat. |
| `Get-Alumnes` | Mostra les dades dels alumnes d'un grup i les ordena pels cognoms i el nom. Per defecte, consulta `gAlumnes`. |
| `New-Alumne` | Crea un alumne a la OU de la seva classe i l'afegeix al grup corresponent. |
| `New-Profe` | Crea un professor a la OU corresponent, genera una contrasenya i prepara l'enviament de les dades per correu. |
| `Create-Alumnes` | Llegeix un CSV: si l'alumne ja està actiu no fa res, si està desactivat l'activa i, si no existeix, el crea. |
| `Create-Professors` | Fa una comprovació semblant amb el CSV del professorat i crea els comptes que falten. |

`Get-UsuariState` és important perquè els dos cmdlets `Create-*` el fan servir abans de decidir què fer amb cada compte.

## Carregar i provar el mòdul

He desat el mòdul a la carpeta de mòduls de Windows PowerShell. Per carregar-lo o tornar-lo a carregar després de modificar-lo, faig servir:

```powershell
Import-Module "C:\Program Files\WindowsPowerShell\Modules\ADPropi\ADPropi.psm1" -Force
```

`-Force` força la recàrrega del mòdul perquè PowerShell agafi els canvis que he fet.

Exemples d'ús:

```powershell
Get-UsuariState -NomCompte "pmas"
Get-Usuaris -Grup "gMates"
Get-Alumnes
Create-Alumnes -RutaCSV "C:\alumnes.csv"
Create-Professors -RutaCSV "C:\professors.csv"
```

## Resultat

He comprovat que els cmdlets estan carregats i que el professor `pmas` s'ha creat a la ruta correcta:

```text
OU=Mates,OU=Professors,DC=XYZ,DC=net
```

Així, amb el mòdul puc consultar, crear i revisar comptes d'alumnes i professors, també a partir de fitxers CSV.
