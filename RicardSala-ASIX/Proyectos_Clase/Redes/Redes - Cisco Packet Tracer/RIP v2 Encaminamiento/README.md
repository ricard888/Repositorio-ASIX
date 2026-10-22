# 🔧 Pràctica: Configuració de Xarxa Dinàmica amb RIPv2

## 📝 Què és aquest projecte?
Aquest projecte és una pràctica de simulació en **Cisco Packet Tracer** on hem muntat una xarxa amb **4 routers interconnectats**. L'objectiu principal ha estat aconseguir que tots els ordinadors i servidors es puguin fer `ping` de punta a punta de forma automàtica usant el protocol **RIPv2**, sense haver de posar rutes estàtiques a mà.

---

## 🗺️ Mapa de la Xarxa (Topologia)

La xarxa està dividida en aquestes zones i subxarxes:

* **LAN Esquerra:** `172.16.16.0/24` (On estan el PC0 i el PC1)
* **Enllaç entre R0 i R1:** `172.16.17.0/24`
* **Zona Server0:** `172.16.18.0/24`
* **Tram Central (Switch0):** `172.16.19.0/24` (Punt on es creuen el Router1, Router2 i el PC4)
* **Zona Server2:** `172.16.20.0/24`
* **Enllaç entre R2 i R3:** `172.16.21.0/24`
* **LAN Dreta:** `172.16.22.0/24` (On estan el PC2 i el PC3)

---

## ⚙️ Comandes Clau Utilitzades (Lògica)

Perquè els routers comparteixin les seves "agendes de contactes" (taules d'encaminament), hem entrat a la CLI de cadascun i hem aplicat aquesta configuració base:

1. **`version 2` i `no auto-summary`:** Perquè el protocol funcioni amb xarxes modernes (VLSM) i els routers passin les IPs ben detallades sense ajuntar-les ni resumir-les de cop.
2. **`network [IP.0]`:** Aquí hem posat **només** les xarxes que cada router toca directament amb els seus propis cables.
3. **`passive-interface`:** Hem posat el "bozal" de seguretat a les interfícies que van directes a ordinadors o servidors. Així evitem que els PCs rebin brossa de dades de RIP que no necessiten i protegim la xarxa.

### Exemple real de configuració (Router 1)
```bash
Router1(config)# router rip
Router1(config-router)# version 2
Router1(config-router)# no auto-summary
Router1(config-router)# network 172.16.17.0
Router1(config-router)# network 172.16.18.0
Router1(config-router)# network 172.16.19.0
Router1(config-router)# passive-interface fa1/0
