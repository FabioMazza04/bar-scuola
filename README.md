# Bar Scuola — Web App per la Gestione del Bar Scolastico

Applicazione web sviluppata come progetto scolastico interdisciplinare (GPOI, Informatica, TPSI) per la gestione del bar interno di un istituto scolastico. Il sistema supporta due profili utente: **gestore del bar** e **studenti/personale scolastico**.

---

## Funzionalità

### Lato Gestore
- Dashboard con autenticazione riservata al ruolo `gestore`
- CRUD completo di **categorie**, **prodotti** e **offerte**
- Gestione quantità prodotti: quantità di default giornaliera e quantità attuale in tempo reale
- Reset scorte automatico al valore di default
- Gestione **offerte e sconti** con date di validità e opzione riservata ai possessori di tessera
- Monitoraggio **ordini in tempo reale** con aggiornamento automatico ogni 15 secondi
- Cambio stato ordine: `in preparazione` → `pronto` → `consegnato`
- Filtro prodotti per nome, ID e categoria

### Lato Studente / Personale
- Registrazione con email istituzionale (`@itcglalilei.edu.it`) e selezione di classe, sezione e indirizzo
- Login con reindirizzamento automatico in base al ruolo
- Catalogo prodotti con prezzi aggiornati in tempo reale (ogni 30 secondi)
- Prezzi scontati visibili quando è attiva un'offerta
- **Carrello** con possibilità di aggiungere più prodotti e conferma ordine
- **Tessera elettronica** con credito scalare — controllo saldo prima dell'acquisto
- **Ricarica tessera** con importi preimpostati (5€, 10€, 15€, 20€, 50€)

---

## Tecnologie utilizzate

| Layer | Tecnologie |
|---|---|
| Frontend | HTML5, CSS3, JavaScript (vanilla) |
| Backend | PHP 8 |
| Database | MySQL / MariaDB |
| Server | Apache (XAMPP) |
| Sicurezza | Prepared statements, password_hash / password_verify |

---

## Struttura del progetto

```
BAR/
  progettoBar/
    progettoBar/
      backend/
        connect.php          # Connessione al database
        index.php            # Router REST API
        api/
          categorie/         # CRUD categorie
          prodotti/          # CRUD prodotti
          offerte/           # CRUD offerte
          ordine/            # Creazione e gestione ordini
          tessera/           # Lettura credito e ricarica
          login.php          # Autenticazione
          registrazione.php          # Registrazione studenti
          registrazione_personale.php # Registrazione personale
      frontend/
        home.html            # Pagina di login
        dashboard.html       # Dashboard gestore
        studente.html        # Catalogo e carrello studente
        registrazione.html           # Form registrazione studenti
        registrazione_personale.html # Form registrazione personale
        index.js             # Logica frontend (fetch API, DOM)
        style.css            # Stili globali
  database.sql               # Script SQL per la creazione del database
```

---

## Requisiti

- [XAMPP](https://www.apachefriends.org/) (o equivalente con Apache + PHP + MySQL)
- Browser moderno (Chrome, Firefox, Edge)

---

## Come avviare il progetto in locale

### 1. Clona il repository
```bash
git clone https://github.com/tuousername/bar-scuola.git
```

### 2. Copia la cartella in htdocs
Sposta la cartella `BAR` dentro la directory `htdocs` di XAMPP:
```
C:/xampp/htdocs/BAR
```

### 3. Avvia XAMPP
Avvia i moduli **Apache** e **MySQL** dal pannello di controllo XAMPP.

### 4. Crea il database
- Apri il browser e vai su `http://localhost/phpmyadmin`
- Crea un nuovo database chiamato `bar`
- Importa il file `database.sql` dalla root del progetto

### 5. Configura la connessione
Apri `BAR/progettoBar/progettoBar/backend/connect.php` e verifica le credenziali:
```php
$host     = "localhost";
$username = "root";
$password = "";
$database = "bar";
```
Con XAMPP di default non serve modificare nulla.

### 6. Registra il primo utente gestore
Usa Postman o un client HTTP per fare una POST a:
```
http://localhost/BAR/progettoBar/progettoBar/backend/index.php/registrazione-personale
```
Body JSON:
```json
{
    "nome": "Mario",
    "cognome": "Rossi",
    "email": "mario@itcglalilei.edu.it",
    "password": "1234",
    "ruolo": "gestore"
}
```

### 7. Apri l'applicazione
```
http://localhost/BAR/progettoBar/progettoBar/frontend/home.html
```

---

## API Endpoints

| Metodo | Endpoint | Descrizione |
|--------|----------|-------------|
| GET/POST/PUT/DELETE | `/index.php/categorie` | CRUD categorie |
| GET/POST/PUT/DELETE | `/index.php/prodotti` | CRUD prodotti |
| GET/POST/PUT/DELETE | `/index.php/offerte` | CRUD offerte |
| POST/GET/PUT | `/index.php/ordine` | Gestione ordini |
| GET | `/index.php/tessera` | Lettura credito |
| POST | `/index.php/ricarica` | Ricarica tessera |
| POST | `/index.php/login` | Autenticazione |
| POST | `/index.php/registrazione` | Registrazione studenti |
| POST | `/index.php/registrazione-personale` | Registrazione personale |

---

## Note di sicurezza

- Tutte le query SQL usano **prepared statements** per prevenire SQL injection
- Le password sono hashate con `password_hash()` (bcrypt)
- L'accesso alla dashboard è limitato agli utenti con ruolo `gestore`
- La registrazione studenti richiede un'email con dominio istituzionale

---

## Autore

Progetto sviluppato come elaborato scolastico — Anno scolastico 2025/2026
