# 🔧 Pràctica: Encaminament Dinàmic amb RIPv2

[![Packet Tracer](https://img.shields.io/badge/Cisco-Packet%20Tracer-005EA6?style=flat-square&logo=cisco&logoColor=white)](https://www.cisco.com/)
[![Protocol](https://img.shields.io/badge/Routing-RIPv2-🟢%20Active?style=flat-square)](https://en.wikipedia.org/wiki/Routing_Information_Protocol)

## 📝 Descripció
Simulació d'una xarxa multi-router en **Cisco Packet Tracer** utilitzant el protocol d'encaminament dinàmic **RIPv2**. L'objectiu és interconnectar subxarxes de forma automàtica mitjançant l'intercanvi de taules de rutes, aplicant polítiques de seguretat amb interfícies passives.

---

## 🗺️ Topologia de la Xarxa

L'arquitectura utilitza 4 Routers interconnectats:

* **Subxarxa LAN Esquerra:** `172.16.16.0/24` (PC0, PC1)
* **Enllaç R0 ↔ R1:** `172.16.17.0/24`
* **Subxarxa Server0:** `172.16.18.0/24`
* **Segment Central Compartit (Switch0):** `172.16.19.0/24` (PC4, Router1, Router2)
* **Subxarxa Server2:** `172.16.20.0/24`
* **Enllaç R2 ↔ R3:** `172.16.21.0/24`
* **Subxarxa LAN Dreta:** `172.16.22.0/24` (PC2, PC3)

---

## ⚙️ Configuració Core de RIPv2 (CLI)

Per a aconseguir la convergència dinàmica sense rutes estàtiques (`ip route`), s'apliquen els següents comandaments base:

* **`version 2` & `no auto-summary`:** Permet el suport de xarxes sense classe (VLSM).
* **`passive-interface`:** S'activa **només** a les interfícies que connecten cap a PCs o Servidors per seguretat (evita l'enviament de rutes a usuaris finals), deixant lliures els enllaços entre routers.

### Exemple de comandaments (Router 1)
```bash
Router1(config)# router rip
Router1(config-router)# version 2
Router1(config-router)# no auto-summary
Router1(config-router)# network 172.16.17.0
Router1(config-router)# network 172.16.18.0
Router1(config-router)# network 172.16.19.0
Router1(config-router)# passive-interface fa1/0
