# 🛡️ Monitorització de Seguretat DHCP i Detecció d'Amenaces

Un entorn complet de **monitorització de seguretat i detecció d'amenaces** per a serveis DHCP IPv4 (Kea DHCP) utilitzant **Prometheus**, **Grafana**, **Exporters personalitzats** i scripts de simulació d'atacs amb **Python (Scapy)**.

---

## 📸 Vista General del Sistema

| Component | Descripció |
| :--- | :--- |
| **Servidor DHCP** | Kea DHCP Server executant-se nativament en Linux (`172.24.66.10`) |
| **Colector de Mètriques** | `kea-exporter` exposant mètriques de *leases*, assignacions i estat del pool al port `:9547` |
| **Detector Rogue DHCP** | Script personalitzat en Python (`tcpdump` + `prometheus_client`) escoltant al port `:9101` |
| **Simulador d'Atacs** | Script de proves en Python (`scapy`) per avaluar atacs de denegació de servei (DHCP Starvation)[cite: 18, 19] |
| **Base de Dades / Alertes** | Prometheus funcionant en Docker (`:9090`) analitzant objectius i gestionant regles d'alerta |
| **Visualització** | Dashboard de Grafana (`:3000`) mostrant la salut de la xarxa i l'ocupació en temps real |

---

## 🚀 Què s'ha implementat?

### 1. Mètriques i Infraestructura
- **Servei systemd personalitzat (`kea-exporter.service`)**: Automatització de l'exporter de Kea DHCP per exposar mètriques en format estàndard de Prometheus.
- **Desplegament amb Docker**: Contenidors per a **Prometheus** i **Grafana** interconnectats mitjançant una xarxa interna.
- **Mapeig de Xarxa**: Configuració d'accés segur (reenviament de ports NAT) des de la màquina amfitriona cap a la màquina virtual (Ports 9090 per Prometheus i 3000 per Grafana)[cite: 8].

---

### 2. Detecció en Temps Real de Servidors Rogue (DHCP No Autoritzat)
- Creació d'un script en Python (`detect_rogue_dhcp.py`) que analitza el tràfic de capa 2/3 filtrant els missatges `BOOTP/DHCP Offer`.
- Si una resposta prové d'una IP diferent de la legítima (`172.24.66.10`), el detector activa la mètrica `dhcp_rogue_server_detected = 1` i genera un registre a `/var/log/dhcp-rogue.log`.

---

### 3. Regles d'Alerta a Prometheus (`alert-rules.yml`)
Es van configurar dues regles crítiques dins de Prometheus[cite: 16]:

```yaml
groups:
  - name: dhcp_alerts
    rules:
      # ⚠️ Alerta per esgotament del Pool d'adreces (Atac Starvation)
      - alert: DHCPPoolExhaustionWarning
        expr: (kea_dhcp4_addresses_assigned_total / kea_dhcp4_addresses_total) * 100 > 90
        for: 1m
        labels:
          severity: critical
        annotations:
          summary: "Pool DHCP a punt d'esgotar-se"
          description: "Ocupació per sobre del 90% durant més d'1 minut."

      # 🚨 Alerta per detecció de Servidor DHCP No Autoritzat (Rogue DHCP)
      - alert: RogueDHCPServerDetected
        expr: dhcp_rogue_server_detected == 1
        for: 0m
        labels:
          severity: critical
        annotations:
          summary: "Servidor DHCP no autoritzat detectat a la xarxa"