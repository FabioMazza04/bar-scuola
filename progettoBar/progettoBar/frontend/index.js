const ENDPOINT_CATEGORIE = "http://localhost/BAR/progettoBar/progettoBar/backend/index.php/categorie";
const ENDPOINT_PRODOTTI = "http://localhost/BAR/progettoBar/progettoBar/backend/index.php/prodotti";
const ENDPOINT_LOGIN = "http://localhost/BAR/progettoBar/progettoBar/backend/index.php/login";
const ENDPOINT_REGISTRAZIONE = "http://localhost/BAR/progettoBar/progettoBar/backend/index.php/registrazione";
const ENDPOINT_REGISTRAZIONE_PERSONALE = "http://localhost/BAR/progettoBar/progettoBar/backend/index.php/registrazione-personale";
const ENDPOINT_TESSERA = "http://localhost/BAR/progettoBar/progettoBar/backend/index.php/tessera";
const ENDPOINT_ORDINE = "http://localhost/BAR/progettoBar/progettoBar/backend/index.php/ordine";
const ENDPOINT_RICARICA = "http://localhost/BAR/progettoBar/progettoBar/backend/index.php/ricarica";
const ENDPOINT_OFFERTE = "http://localhost/BAR/progettoBar/progettoBar/backend/index.php/offerte";



var carrello = [];
var prodottiCache = [];
var offerteCache = [];
var ordiniCache = [];


//ORDINI
function mostraOrdini(){
    var sezioneCategoria = document.getElementById("sezione-categorie");
    var sezioneProdotto = document.getElementById("sezione-prodotti");
    var sezioneOfferte = document.getElementById("sezione-offerte");
    var sezioneOrdini = document.getElementById("sezione-ordini");

    sezioneCategoria.style.display = "none";
    sezioneProdotto.style.display = "none";
    sezioneOfferte.style.display = "none";

    sezioneOrdini.style.display = "block";

    caricaOrdini();
}

function caricaOrdini(){
    fetch(ENDPOINT_ORDINE)
        .then(risposta => risposta.json())
        .then(dati => {
            var tbody = document.getElementById("tabella-ordini");
            tbody.innerHTML = "";
            ordiniCache = dati;
            dati.forEach(function(ordini){
                tbody.innerHTML += "<tr><td>" + ordini.id_ordine +
                                   "</td><td>" + ordini.nome + " " + ordini. cognome +
                                   "</td><td>" + ordini.data_ordine +
                                   "</td><td>" + ordini.ora_ordine +
                                   "</td><td>" + ordini.totale +
                                   "</td><td>" + ordini.prodotti_ordinati +
                                   "</td><td>" + ordini.stato_ordine +
                                   "</td><td>" + 
                                   "<select id='stato-" + ordini.id_ordine + "'>" +
                                   "<option value='in preparazione'" + (ordini.stato_ordine === 'in preparazione' ? ' selected' : '') + ">In preparazione</option>" +
                                   "<option value='pronto'" + (ordini.stato_ordine === 'pronto' ? ' selected' : '') + ">Pronto</option>" +
                                   "<option value='consegnato'" + (ordini.stato_ordine === 'consegnato' ? ' selected' : '') + ">Consegnato</option>" +
                                   "</select>" +
                                   " <button onclick=\"aggiornaOrdine(" + ordini.id_ordine + ")\">Aggiorna</button>" +
                                   "</td></tr>";
            })
        })
        .catch(errore => console.log(errore));
}

if(document.getElementById("tabella-ordini")){
    setInterval(caricaOrdini, 15000);
}

function aggiornaOrdine(id_ordine){
    var stato_ordine = document.getElementById("stato-" + id_ordine).value;
    fetch(ENDPOINT_ORDINE, {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({id_ordine: id_ordine, stato_ordine: stato_ordine})
    })
    .then(risposta => risposta.json())
    .then(dati => {
        showToast("Ordine aggiornato con successo!", "success");
        caricaOrdini();
    })
    .catch(errore => console.log(errore));
}

//OFFERTE
function mostraOfferte(){
    var sezioneCategoria = document.getElementById("sezione-categorie");
    var sezioneProdotto = document.getElementById("sezione-prodotti");
    var sezioneOfferte = document.getElementById("sezione-offerte");
    var sezioneOrdini = document.getElementById("sezione-ordini");

    sezioneCategoria.style.display = "none";
    sezioneProdotto.style.display = "none";
    sezioneOrdini.style.display = "none";

    sezioneOfferte.style.display = "block";

    caricaOfferte();
}

function caricaOfferte(){
    fetch(ENDPOINT_OFFERTE)
        .then(risposta => risposta.json())
        .then(dati => {
            var tbody = document.getElementById("tabella-offerte");
            tbody.innerHTML = "";
            offerteCache = dati;
            dati.forEach(function(offerte){
                tbody.innerHTML += "<tr><td>" + offerte.id_offerta + 
                                   "</td><td>" + offerte.nome_offerta +
                                   "</td><td>" + offerte.percentuale_sconto +
                                   "</td><td>" + offerte.data_inizio_offerta +
                                   "</td><td>" + offerte.data_fine_offerta +
                                   "</td><td>" + offerte.solo_tessera +
                                   "</td><td>" + offerte.nome_prodotto +
                                   "</td><td>" + "<button onclick=\"modificaOfferta(" + offerte.id_offerta + ")\">Modifica</button>" +" | "+ "<button onclick=\"eliminaOfferta(" + offerte.id_offerta + ")\">Elimina</button>" + "</td></tr>";
            });
            var selectProdotto = document.getElementById("prodotto-offerta");
            selectProdotto.innerHTML = "<option value=''>Nessun Prodotto</option>";
            prodottiCache.forEach(function(prodotti){
                selectProdotto.innerHTML += "<option value='" + prodotti.id_prodotto + "'>" + prodotti.nome_prodotto + "</option>";
            });
        })
        .catch(errore => console.log(errore));
}

function aggiungiOfferta(){
    var nomeOfferta = document.getElementById("nome-offerta").value;
    var sconto = document.getElementById("percentuale-sconto").value;
    var dataInizio = document.getElementById("data-inizio").value;
    var dataFine = document.getElementById("data-fine").value;
    var tessera = document.getElementById("tessera").checked;
    var idProdotto = document.getElementById("prodotto-offerta").value;

    var soloTessera = tessera ? 1 : 0; //converte true o false in 1 o 0 
    //console.log(nomeOfferta, sconto, dataInizio, dataFine, soloTessera, idProdotto);
    if(nomeOfferta === null || nomeOfferta === "" || sconto === null || sconto < 0 ||
       dataInizio === null || dataInizio === "" || dataFine === null || dataFine === "" || 
       idProdotto === null || idProdotto === "" ){

        showToast("Inserisci i dati richiesti!", "error");
        return;
    }

    fetch(ENDPOINT_OFFERTE, {
        method: "POST", 
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({nome_offerta: nomeOfferta, percentuale_sconto: sconto, data_inizio_offerta: dataInizio, data_fine_offerta: dataFine, solo_tessera: soloTessera, id_prodotto: idProdotto })
    })
    .then(risposta => risposta.json())
    .then(dati => {
        showToast(dati.message, "success");
        caricaOfferte();
    })
}

function eliminaOfferta(id_offerta){
    if(confirm("Sei sicuro di voler eliminare l'offerta?")){
        fetch(ENDPOINT_OFFERTE, {
            method: "DELETE",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({id_offerta: id_offerta})
        })
        .then(risposta => risposta.json())
        .then(dati => {
            showToast(dati.message, "success");
            caricaOfferte();
        })
        .catch(errore => console.log(errore))
    }else{
        return;
    }
}

function modificaOfferta(id_offerta){
    var offerta = offerteCache.find(function(o){
        return o.id_offerta == id_offerta;
    });
    var nuovoNome = prompt("Inserire il nuovo nome dell'offerta:", offerta.nome_offerta);
    var nuovoSconto = prompt("Inserire il nuovo sconto dell'offerta:", offerta.percentuale_sconto);
    var nuovoInizio = prompt("Inserire la nuova data di inizio dell'offerta:", offerta.data_inizio_offerta);
    var nuovaFine = prompt("Inserire la nuova data di fine dell'offerta:", offerta.data_fine_offerta);
    var nuovaTessera = confirm("Riservato solo ai possessori di tessera?") ? 1 : 0;

    if(nuovoNome === null || nuovoNome === "" || nuovoSconto === null || nuovoSconto < 0 ||
        nuovoInizio === null || nuovoInizio === "" || nuovaFine === null || nuovaFine === "" ){
        
        return;
    }

    fetch(ENDPOINT_OFFERTE, {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({id_offerta: id_offerta, nome_offerta: nuovoNome, percentuale_sconto: nuovoSconto, data_inizio_offerta: nuovoInizio, data_fine_offerta: nuovaFine, solo_tessera: nuovaTessera, id_prodotto: offerta.id_prodotto})
    })
    .then(risposta => risposta.json())
    .then(dati => {
        showToast("Offerta modificata con successo!", "success");
        caricaOfferte();
    })
    .catch(errore => console.log(errore));
}

//CATEGORIE
function mostraCategorie(){
    var sezioneCategoria = document.getElementById("sezione-categorie");
    var sezioneProdotto = document.getElementById("sezione-prodotti");
    var sezioneOfferte = document.getElementById("sezione-offerte");
    var sezioneOrdini = document.getElementById("sezione-ordini");

    
    sezioneProdotto.style.display = "none";
    sezioneOfferte.style.display = "none";
    sezioneOrdini.style.display = "none";

    sezioneCategoria.style.display = "block";

    caricaCategoria();
}

function mostraProdotti(){
    var sezioneCategoria = document.getElementById("sezione-categorie");
    var sezioneProdotto = document.getElementById("sezione-prodotti");
    var sezioneOfferte = document.getElementById("sezione-offerte");
    var sezioneOrdini = document.getElementById("sezione-ordini");

    sezioneCategoria.style.display = "none";
    sezioneOfferte.style.display = "none";
    sezioneOrdini.style.display = "none";

    sezioneProdotto.style.display = "block";

    caricaProdotto();
}

function caricaCategoria(){
    fetch(ENDPOINT_CATEGORIE)
        .then(risposta => risposta.json())  //converte la risposta http in json
        .then(dati => {                     //array di categorie
            var tbody = document.getElementById("tabella-categorie");
            tbody.innerHTML = ""; //svuota per evitare duplicati
            dati.forEach(function(categoria){
                tbody.innerHTML += "<tr><td>" + categoria.id_categoria + 
                                   "</td><td>" + categoria.nome_categoria + 
                                   "</td><td>" + "<button onclick=\"modificaCategoria(" + categoria.id_categoria + ")\">Modifica</button>" +" | "+ "<button onclick=\"eliminaCategoria(" + categoria.id_categoria + ")\">Elimina</button>" + "</td></tr>";
            });

            var select = document.getElementById("categoria-prodotto");
            var selectFiltro = document.getElementById("filtro-categoria");
            select.innerHTML = "<option value=''>Nessuna categoria</option>";
            dati.forEach(function(categoria){
                select.innerHTML += "<option value='" + categoria.id_categoria +"'>" + categoria.nome_categoria + "</option>";
            });
            if(selectFiltro){
                selectFiltro.innerHTML = "<option value=''>Tutte le categorie</option>";
                dati.forEach(function(categoria){
                    selectFiltro.innerHTML += "<option value='" + categoria.id_categoria + "'>" + categoria.nome_categoria + "</option>";
                });
            }
        })
        .catch(errore => console.log(errore));
}

function creaCategoria(){
    var nome_categoria = document.getElementById("nome-categoria").value;
    if(nome_categoria === ""){
        showToast("Inserisci un nome!", "warning");
        return;
    }

    fetch(ENDPOINT_CATEGORIE, {
            method: "POST", 
            headers: { "Content-Type": "application/json" }, 
            body: JSON.stringify({ nome_categoria: nome_categoria })
        })
        .then(risposta => risposta.json())
        .then(dati => {
            showToast(dati.message, "success");
            caricaCategoria();
        })
        .catch(errore => console.log(errore)); 
}

function eliminaCategoria(id_categoria){
    if(confirm("Sei sicuro di voler eliminare questa categoria?")){
        fetch(ENDPOINT_CATEGORIE, {
            method: "DELETE",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id_categoria: id_categoria })
        })
        .then(risposta => risposta.json())
        .then(dati => {
            showToast(dati.message, "success");
            caricaCategoria();
        })
        .catch(errore => console.log(errore));
    }else{
        return;
    }
}

function modificaCategoria(id_categoria){
    var nuovoNome = prompt("Inserire il nome della nuova categoria:");
    if(nuovoNome === null || nuovoNome === ""){
        return;
    }
    fetch(ENDPOINT_CATEGORIE, {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id_categoria: id_categoria, nome_categoria: nuovoNome })
    })
    .then(risposta => risposta.json())
    .then(dati => {
        showToast(dati.message, "success");
        caricaCategoria();
    })
    .catch(errore => console.log(errore));
}


//PRODOTTI
function caricaProdotto(){
    fetch(ENDPOINT_PRODOTTI)
        .then(risposta => risposta.json())
        .then(dati => {
            var tbody = document.getElementById("tabella-prodotti");
            tbody.innerHTML = "";
            prodottiCache = dati;
            dati.forEach(function(prodotti){
                tbody.innerHTML += "<tr><td>" + prodotti.id_prodotto +
                                   "</td><td>" + prodotti.nome_prodotto +
                                   "</td><td>" + prodotti.prezzo_prodotto +
                                   "</td><td>" + prodotti.nome_categoria +
                                   "</td><td>" + prodotti.qta_attuale +
                                   "</td><td>" + prodotti.qta_default +
                                   "</td><td>" + "<button onclick=\"modificaProdotto(" + prodotti.id_prodotto + ")\">Modifica</button>" + " | " + "<button onclick=\"eliminaProdotto(" + prodotti.id_prodotto + ")\">Elimina</button>" + " | " + "<button onclick=\"resetScorte(" + prodotti.id_prodotto + ")\">Reset</button>" + "</td></tr>";
            });
        })
        .catch(errore => console.log(errore));
}

if(document.getElementById("tabella-prodotti")){
    setInterval(caricaProdotto, 15000);
}

function creaProdotto(){
    var nomeProdotto = document.getElementById("nome-prodotto").value;
    var prezzoProdotto = document.getElementById("prezzo-prodotto").value;
    var id_categoria = document.getElementById("categoria-prodotto").value;
    var descrizione_prodotto = document.getElementById("descrizione-prodotto").value;
    var quantita_default = document.getElementById("qta-default-prodotto").value;
    var quantita_attuale = document.getElementById("qta-attuale-prodotto").value;

    if(nomeProdotto === "" || prezzoProdotto === "" || prezzoProdotto < 0 ||
        descrizione_prodotto === "" || quantita_default < 0 || quantita_attuale < 0){
        return;
    }

    fetch(ENDPOINT_PRODOTTI, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ nome_prodotto: nomeProdotto, descrizione_prodotto: descrizione_prodotto, prezzo_prodotto: prezzoProdotto, qta_default: quantita_default, qta_attuale: quantita_attuale, id_categoria: id_categoria })
    })
    .then(risposta => risposta.json())
    .then(dati => {
        showToast(dati.message, "success");
        caricaProdotto();
    })
    .catch(errore => console.log(errore));
}

function eliminaProdotto(id_prodotto){
    if(confirm("Sei sicuro di voler eliminare questo prodotto?")){
        fetch(ENDPOINT_PRODOTTI, {
            method: "DELETE",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id_prodotto: id_prodotto})
        })
        .then(risposta => risposta.json())
        .then(dati => {
            showToast(dati.message, "success");
            caricaProdotto();
        })
        .catch(errore => console.log(errore));
    }else{
        return;
    }
}

function modificaProdotto(id_prodotto){
    var prodotto = prodottiCache.find(function(p){
        return p.id_prodotto == id_prodotto;
    })
    var nuovoNome = prompt("Inserire il nome del nuovo prodotto:", prodotto.nome_prodotto);
    var nuovaDescrizione = prompt("Inserire la descrizione del nuovo prodotto:", prodotto.descrizione_prodotto);
    var nuovoPrezzo = prompt("Inserire il prezzo del nuovo prodotto:", prodotto.prezzo_prodotto);
    var nuovaQta_default = prompt("Inserire la quantita di default del nuovo prodotto:", prodotto.qta_default);

    if(nuovoNome === null || nuovoNome === "" || nuovoPrezzo === null || nuovoPrezzo < 0 ||
        nuovaDescrizione === null || nuovaDescrizione === "" || nuovaQta_default === null ||
        nuovaQta_default < 0 ) return;
    fetch(ENDPOINT_PRODOTTI, {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id_prodotto: id_prodotto, nome_prodotto: nuovoNome , descrizione_prodotto: nuovaDescrizione, prezzo_prodotto: nuovoPrezzo, qta_default: nuovaQta_default})
    })
    .then(risposta => risposta.json())
    .then(dati => {
        showToast(dati.message, "success");
        caricaProdotto();
    })
    .catch(errore => console.log(errore));
}

function resetScorte(id_prodotto){
    fetch(ENDPOINT_PRODOTTI, {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id_prodotto: id_prodotto})
    })
    .then(risposta => risposta.json())
    .then(dati => {
        showToast(dati.message, "success");
        caricaProdotto();
    })
    .catch(errore => console.log(errore));
}

function login(){
    var username = document.getElementById("username").value;
    var psw = document.getElementById("psw").value;

    if(username === null || username === "" || 
        psw === null || psw === ""){
            showToast("Username o password mancanti.", "error");
            return;
        }
    fetch(ENDPOINT_LOGIN, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ username: username, psw: psw })
    })
    .then(risposta => {
        return risposta.json();
    })
    .then(dati => {
        //console.log(dati);
        if(dati.tipo === "personale_scolastico" && dati.ruolo === "gestore"){

        }else if(dati.tipo === "studente" || dati.tipo === "personale_scolastico"){
            localStorage.setItem("id_profilo", dati.id_profilo);
            localStorage.setItem("id_tessera", dati.id_tessera);
            localStorage.setItem("tipo", dati.tipo);
            localStorage.setItem("nome", dati.nome);
            window.location.href = "studente.html";
        }else{
            showToast(dati.message, "success");
        }
    })
    .catch(errore => console.log(errore));
}

function logout(){
    localStorage.clear();
    window.location.href = "home.html";
}

function registra(){
    var nome = document.getElementById("nome").value;
    var cognome = document.getElementById("cognome").value;
    var email = document.getElementById("email").value;
    var password = document.getElementById("password").value;
    var classe = document.getElementById("classe").value;
    var sezione = document.getElementById("sezione").value;
    var indirizzo = document.getElementById("indirizzo").value;
    
    if(nome === null || nome === "" ||
       cognome === null || cognome === "" ||
       email === null || email === "" ||
       password === null || password === "" ||
       classe === null || classe === "" ||
       sezione === null || sezione === "" ||
       indirizzo === null || indirizzo === ""){
        showToast("Campi insufficenti.", "error");
        return;
       }

    fetch(ENDPOINT_REGISTRAZIONE, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({nome: nome, cognome: cognome, email: email, 
                              password: password, classe: classe, sezione: sezione, indirizzo: indirizzo})
    })
    .then(risposta => {
        if(risposta.ok){
            window.location.href = "home.html";
        }else{
            return risposta.json();
        }
    })
    .then(dati => {
        showToast(dati.message, "success");
    })
    .catch(errore => console.log(errore));
}

function caricaProdottiStudente(){
    fetch(ENDPOINT_PRODOTTI)
        .then(risposta => risposta.json())
        .then(dati => {
            var tbody = document.getElementById("tabella-prodotti-studente");
            tbody.innerHTML = "";

            dati.forEach(function(prodotti){
                var prezzoFinale = prodotti.prezzo_prodotto;
                if(prodotti.percentuale_sconto){
                    prezzoFinale = prodotti.prezzo_prodotto - (prodotti.prezzo_prodotto * prodotti.percentuale_sconto / 100);
                }
                tbody.innerHTML += "<tr><td>" + prodotti.nome_prodotto +
                                   "</td><td>" + prodotti.descrizione_prodotto +
                                   "</td><td>" + prezzoFinale +
                                   "</td><td>" + prodotti.nome_categoria +
                                   "</td><td>" + prodotti.qta_attuale +
                                   "</td><td>" + "<button onclick=\"aggiungiAlCarrello(" + prodotti.id_prodotto + ", '" + prodotti.nome_prodotto + "', " + prezzoFinale + ")\">Aggiungi al carrello</button></td></tr>";
                                   
            });
            caricaCredito();
        })
        .catch(errore => console.log(errore));       
}

if(document.getElementById("tabella-prodotti-studente")){
    window.onload = caricaProdottiStudente;
    setInterval(caricaProdottiStudente, 30000);
}

function caricaCredito(){
    var id_tessera = localStorage.getItem("id_tessera");
    var nome = localStorage.getItem("nome");
    fetch(ENDPOINT_TESSERA + "?id_tessera=" + id_tessera, {
        method: "GET",
        headers: { "Content-Type": "application/json" }
    })
    .then(risposta => risposta.json())
    .then(dati => {
        document.getElementById("credito-tessera").innerHTML = "Credito: " + dati.credito + " €";
        document.getElementById("nome-studente").innerHTML = nome;
    })
}

function aggiungiAlCarrello(id_prodotto, nome, prezzo){
    console.log("click", id_prodotto, nome, prezzo);
    var esistente = carrello.find(function(item){
        return item.id === id_prodotto;
    });
    if(esistente){
        esistente.quantita++;
    }else{
        carrello.push({id: id_prodotto, nome: nome, prezzo: prezzo, quantita: 1});
    }
    aggiornaCarrello();
}

function aggiornaCarrello(){
    var lista = document.getElementById("lista-carrello");
    var tot;
    lista.innerHTML = "";
    carrello.forEach(function(prodotti){
        lista.innerHTML += "<li>Nome: "+prodotti.nome+"</li><li>Quantità: "+prodotti.quantita+"</li><li>Prezzo Tot: "+prodotti.prezzo * prodotti.quantita+"</li>";
    });

    var totale = 0;
    carrello.forEach(function(item){
        totale += item.prezzo * item.quantita;
    });
    document.getElementById("totale-carrello").innerHTML = "Totale: " + totale + "€";
}

function svuotaCarrello(){
    carrello = [];
    aggiornaCarrello();
}

function confermaOrdine(){
    if(carrello.length === 0){
        showToast("Il carrello è vuoto!", "error");
        return;
    }
    var id_tessera = localStorage.getItem("id_tessera");
    var id_profilo = localStorage.getItem("id_profilo");
    
    fetch(ENDPOINT_ORDINE, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({id_tessera: id_tessera, id_profilo: id_profilo, carrello: carrello})
    })
    .then(risposta => {
        if(risposta.ok){
            svuotaCarrello();
            caricaCredito();
            caricaProdottiStudente();
            showToast("Ordine inviato!", "success");
        }else{
            return risposta.json().then(dati => showToast(dati.message, "error"));
        }
    })
    .catch(errore => console.log(errore));
}

function ricaricaTessera(){
    var importo_ricarica = document.getElementById("importo-ricarica").value;
    var id_tessera = localStorage.getItem("id_tessera");

    fetch(ENDPOINT_RICARICA, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({id_tessera: id_tessera, importo: importo_ricarica})
    })
    .then(risposta => {
        if(risposta.ok){
            caricaCredito();
            showToast("Ricarica effetuata!", "success");
        }else{
            return risposta.json();
        }
    })
    .catch(errore => console.log(errore));
}

function registraPersonale(){
    var nome = document.getElementById("nome").value;
    var cognome = document.getElementById("cognome").value;
    var email = document.getElementById("email").value;
    var password = document.getElementById("password").value;
    var ruolo = document.getElementById("ruolo").value;

    if(nome === null || nome === "" ||
       cognome === null || cognome === "" ||
       email === null || email === "" ||
       password === null || password === "" ||
       ruolo === null || ruolo === "" ){
        showToast("Campi insufficenti.", "error");
        return;
    }

    fetch(ENDPOINT_REGISTRAZIONE_PERSONALE, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ nome: nome, cognome: cognome, email: email, 
                              password: password, ruolo: ruolo })
    })
    .then(risposta => {
        if(risposta.ok){
            window.location.href = "home.html";
        }else{
            return risposta.json();
        }
    })
    .then(dati => {
        showToast(dati.message, "success");
    })
    .catch(errore => console.log(errore));
}

function filtraProdotti(){
    var filtro = document.getElementById("ricerca-prodotto").value.toLowerCase();
    var filtroCategoria = document.getElementById("filtro-categoria").value;
    var risultati = prodottiCache.filter(function(prodotto){
        var matchNome = prodotto.nome_prodotto.toLowerCase().includes(filtro) ||
                        prodotto.id_prodotto.toString().includes(filtro);
        var matchCategoria = filtroCategoria === "" || prodotto.id_categoria == filtroCategoria;
        return matchNome && matchCategoria;
    });
    
    var tbody = document.getElementById("tabella-prodotti");
    tbody.innerHTML = "";
    risultati.forEach(function(prodotti){
        tbody.innerHTML += "<tr><td>" + prodotti.id_prodotto +
                           "</td><td>" + prodotti.nome_prodotto +
                           "</td><td>" + prodotti.prezzo_prodotto +
                           "</td><td>" + prodotti.nome_categoria +
                           "</td><td>" + prodotti.qta_attuale +
                           "</td><td>" + prodotti.qta_default +
                           "</td><td>" + "<button onclick=\"modificaProdotto(" + prodotti.id_prodotto + ")\">Modifica</button>" + " | " + "<button onclick=\"eliminaProdotto(" + prodotti.id_prodotto + ")\">Elimina</button>" + " | " + "<button onclick=\"resetScorte(" + prodotti.id_prodotto + ")\">Reset</button>" + "</td></tr>";
    });
}


function showToast(msg, tipo){
    var container = document.getElementById("toast-container");
    var toast = document.createElement("div");
    toast.className = "toast toast-" + tipo;
    toast.innerText = msg;
    container.appendChild(toast);
    setTimeout(function(){
        toast.classList.add("toast-hide");
        setTimeout(function(){
            container.removeChild(toast);
        }, 400);
    }, 2500);
}

