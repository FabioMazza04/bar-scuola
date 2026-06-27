CREATE DATABASE IF NOT EXISTS bar;

CREATE TABLE IF NOT EXISTS ricariche_tessera(
    id_ricarica INT(4) NOT NULL AUTO_INCREMENT,
    importo DECIMAL(10,2),
    data_ricarica DATE,
    ora_ricarica TIME,
    metodo_pagamento ENUM('contanti', 'carta', 'satispay'),
    PRIMARY KEY(id_ricarica)
);

CREATE TABLE IF NOT EXISTS tessera(
    id_tessera INT(4) NOT NULL AUTO_INCREMENT,
    credito DECIMAL(10,2),
    id_profilo INT,
    PRIMARY KEY(id_tessera)
);

CREATE TABLE IF NOT EXISTS ordini(
    id_ordine INT(4) NOT NULL AUTO_INCREMENT,
    data_ordine DATE,
    ora_ordine TIME,
    stato_ordine VARCHAR(255),
    totale DECIMAL(10,2),
    id_profilo INT,
    PRIMARY KEY(id_ordine)
);

CREATE TABLE IF NOT EXISTS ordini_tessera(
    id_ordine_tessera INT(4) NOT NULL AUTO_INCREMENT,
    id_ordine INT,
    id_tessera INT,
    PRIMARY KEY (id_ordine_tessera),
    FOREIGN KEY (id_ordine) REFERENCES ordini (id_ordine),
    FOREIGN KEY (id_tessera) REFERENCES tessera (id_tessera)
);

CREATE TABLE IF NOT EXISTS profili(
    id_profilo INT(4) NOT NULL AUTO_INCREMENT,
    nome VARCHAR(255) NOT NULL,
    cognome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    tipo ENUM('studente', 'personale_scolastico'),
    cellulare VARCHAR(15) NOT NULL,
    password VARCHAR(255) NOT NULL,
    id_tessera INT,
    PRIMARY KEY(id_profilo),
    FOREIGN KEY(id_tessera) REFERENCES tessera (id_tessera)
);

CREATE TABLE IF NOT EXISTS personale_scolastico(
    id_personale INT(4) NOT NULL AUTO_INCREMENT,
    ruolo ENUM('docente','ata','gestore'),
    id_profilo INT,
    PRIMARY KEY(id_personale),
    FOREIGN KEY(id_profilo) REFERENCES profili(id_profilo)
);

CREATE TABLE IF NOT EXISTS studenti(
    id_stud INT(4) NOT NULL AUTO_INCREMENT,
    classe INT(1) NOT NULL,
    sezione VARCHAR(1) NOT NULL,
    indirizzo VARCHAR(4) NOT NULL,
    id_profilo INT,
    PRIMARY KEY(id_stud),
    FOREIGN KEY(id_profilo) REFERENCES profili(id_profilo)
);

CREATE TABLE IF NOT EXISTS tessera_riacariche_tessera(
    id_registrazione_ricarica INT(4) NOT NULL AUTO_INCREMENT,
    id_tessera INT,
    id_ricarica INT,
    PRIMARY KEY (id_registrazione_ricarica),
    FOREIGN KEY (id_tessera) REFERENCES tessera (id_tessera),
    FOREIGN KEY (id_ricarica) REFERENCES ricariche_tessera (id_ricarica)
);

CREATE TABLE IF NOT EXISTS dettaglio_ordine(
    id_dettaglio INT(4) NOT NULL AUTO_INCREMENT,
    qta INT(4),
    prezzo DECIMAL(10,2),
    sconto_applicato INT(3),
    PRIMARY KEY (id_dettaglio)
);

CREATE TABLE IF NOT EXISTS ordini_dettaglio_ordine(
    id_ordini_dettaglio_ordine INT(4) NOT NULL AUTO_INCREMENT,
    id_dettaglio INT,
    id_ordine INT,
    PRIMARY KEY(id_ordini_dettaglio_ordine),
    FOREIGN KEY(id_dettaglio) REFERENCES dettaglio_ordine(id_dettaglio),
    FOREIGN KEY(id_ordine) REFERENCES ordini(id_ordine)
);

CREATE TABLE IF NOT EXISTS categorie(
    id_categoria INT(4) NOT NULL AUTO_INCREMENT,
    nome_categoria VARCHAR(255) NOT NULL,
    PRIMARY KEY(id_categoria)
);

CREATE TABLE IF NOT EXISTS prodotti(
    id_prodotto INT(4) NOT NULL AUTO_INCREMENT,
    nome_prodotto VARCHAR(255),
    descrizione_prodotto VARCHAR(255),
    prezzo_prodotto DECIMAL(10,2),
    stato_prodotto VARCHAR(255),
    qta_default INT(4),
    qta_attuale INT(4),
    id_categoria INT(4),
    PRIMARY KEY(id_prodotto),
    FOREIGN KEY(id_categoria) REFERENCES categorie(id_categoria)
);

CREATE TABLE IF NOT EXISTS offerte(
    id_offerta INT(4) NOT NULL AUTO_INCREMENT,
    nome_offerta VARCHAR(255),
    percentuale_sconto INT(3),
    data_inizio_offerta DATE,
    data_fine_offerta DATE,
    solo_tessera BOOLEAN,
    id_prodotto INT,
    PRIMARY KEY(id_offerta),
    FOREIGN KEY(id_prodotto) REFERENCES prodotti(id_prodotto)
);

CREATE TABLE IF NOT EXISTS prodotti_dettaglio_ordine(
    id_prodotti_dettaglio INT(4) NOT NULL AUTO_INCREMENT,
    id_dettaglio INT,
    id_prodotto INT,
    PRIMARY KEY (id_prodotti_dettaglio),
    FOREIGN KEY (id_dettaglio) REFERENCES dettaglio_ordine (id_dettaglio),
    FOREIGN KEY (id_prodotto) REFERENCES prodotti (id_prodotto)
);