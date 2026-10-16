# Pràctica Pp5.2: Introducció a PHP - Estructures de Control i Dates

**Autor:** Ricard Sala Nikitin  
**Mòdul:** M0376 - Implantació d'Aplicacions Web  

---

## 📄 Descripció dels fitxers

Aquesta pràctica es divideix en diferents fitxers PHP i un fitxer de full d'estils CSS que treballen de manera conjunta:

* **`variables51.php`**: Fitxer centralitzat de configuració on es defineixen les variables globals del projecte, l'array amb les opcions del joc (Pedra, Paper, Tisora, Spock, Lagarto) i les marques de temps (*timestamps*) utilitzades en la gestió de dates amb la funció `mktime()`.
* **`joc51.php`**: Script encarregat de simular la lògica del joc. Executa 5 rondes automàtiques per tirada generant valors aleatoris (`rand()`) i determina el guanyador o l'empat utilitzant una estructura condicional `if / elseif / else`.
* **`control51.php`**: Fitxer encarregat de realitzar proves de rendiment entre estructures repetitives. Executa bucles `WHILE` i `FOR` generant 777 números aleatoris durant 77 repeticions i calcula el temps d'execució en mil·lisegons utilitzant `microtime(true)`.
* **`dates51.php`**: Mòdul destinat al treball i càlcul amb dates. Calcula en temps real el temps viscut (dies, hores, minuts i segons) des de la data de naixement, així com el compte enrere fins a la data de finalització del curs acadèmic.
* **`fitxerestils51.css`**: Full d'estils en CSS pur (Vanilla CSS) encarregat de donar format visual, estructura i disseny a totes les pàgines del projecte sense dependre de llibreries externes.

---

## 🛠️ Solució d'errors a l'IDE (PhpStorm)

En incloure el fitxer `variables51.php` mitjançant l'instrucció `require`, PhpStorm marcava com a *Undefined variable* les variables declarades en aquell fitxer exterior.

Per resoldre aquesta advertència visual sense canviar l'estructura del codi, s'han afegit blocs d'anotacions PHPDoc utilitzant l'etiqueta `@var` abans de fer ús de les variables en els fitxers secundaris:

```php
/** 
 * @var array $opcionsJoc 
 * @var int $dataNaixementTimestamp 
 */
require "variables51.php";
