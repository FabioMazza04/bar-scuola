# Progetto Bar - Backend API (PHP + MySQL)

Backend REST in PHP per la gestione di categorie e prodotti per il progetto Bar.

## Stato Attuale

- Router API attivo in `backend/index.php`
- Endpoint `categorie` disponibile in lettura (`GET`)
- Endpoint `categorie` create/update/delete presenti ma ancora da implementare
- Modulo `prodotti` predisposto ma non ancora implementato
- Frontend presente (`frontend/home.html`, `frontend/index.js`) ma attualmente vuoto

## Struttura Progetto

```text
progettoBar/
  backend/
    connect.php
    index.php
    api/
      categorie/
        create.php
        delete.php
        read.php
        update.php
      prodotti/
  frontend/
    home.html
    index.js
```

## Requisiti

- XAMPP o MAMP (Apache + MySQL)
- PHP 8+
- MySQL/MariaDB

## Configurazione Database

Nel file `backend/connect.php` la connessione e' configurata cosi (da verificare in base all'utenza usata):

- host: `localhost`
- username: `root`
- password: `root`
- database: `bar`

Assicurati che il database `bar` esista e contenga almeno le tabelle `categorie` e `prodotti`.


## Avvio Progetto (XAMPP o MAMP)

1. Avvia Apache e MySQL da XAMPP o MAMP.
2. Metti il progetto in `htdocs`.
3. Verifica che il path pubblico sia raggiungibile dal browser.

URL base tipico con XAMPP o MAMP:

- `http://localhost/progettoBar/backend/index.php`

## Endpoint API

### Categorie

- `GET /progettoBar/backend/index.php/categorie` -> elenco categorie
- `POST /progettoBar/backend/index.php/categorie` -> placeholder
- `PUT /progettoBar/backend/index.php/categorie` -> placeholder
- `DELETE /progettoBar/backend/index.php/categorie` -> placeholder

### Prodotti

- `GET /progettoBar/backend/index.php/prodotti` -> placeholder
- `POST /progettoBar/backend/index.php/prodotti` -> placeholder
- `PUT /progettoBar/backend/index.php/prodotti` -> placeholder
- `DELETE /progettoBar/backend/index.php/prodotti` -> placeholder

## Test Rapido

1 - Apri il browser e naviga su:
`http://localhost/progettoBar/backend/index.php/categorie`

2 - Con Postman invia una GET a:

`http://localhost/progettoBar/backend/index.php/categorie`

3 - Con `curl`:

```bash
curl -X GET "http://localhost/progettoBar/backend/index.php/categorie"
```

## Note Tecniche

- Il router gestisce CORS e preflight `OPTIONS`.
- Le risposte sono in JSON.
- In caso di endpoint non trovato: HTTP `404` con messaggio `Risorsa non trovata`.
