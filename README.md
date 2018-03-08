# Sistema di Gestione del Museo
Progetto per UDA del Secondo Quadrimestre 2018 5IA

# Ruoli

Nome               | Ruolo
------------------ | -------------
Valerio Bucci      | Addetto alle pubbliche relazioni
Andrea Chierchia   | Osservatore
Mattia Maglie      | Moderatore
Andrea Segala      | Gestore del Progetto
Lorenzo Trovato    | Gestore del Progetto


# Sistema informatico per la gestione della biglietteria online
### Entità

* acquistare biglietti (biglietto base & biglietto evento)
  * eventi (esposizioni tematiche)
  * visite
* identificazione delle esposizioni
  * codice identificativo
  * titolo
  * tariffa ordinaria
  * data di inizio e di fine (solo per biglietto evento)
* categorie visitatori
  * codice
  * descrizione
  * tipo documento da esibire
  * percentuale di sconto
* accessori
  * codice
  * descrizione
  * prezzo unitario
* biglietti
  * codice
  * data di validità

### Deliverable
- Documentazione
  - Business Model Canvas
  - Deliverable e Milestone
  - Precedenze
  - Work Breakdown Structure
  - Diagramma di Gantt
  - Schema E/R DataBase
  - Schema Logico Relazionale
  - Query richieste
- Prodotto
  - Applicazione Web 
  
### Milestone
- Amministrazione
  * Gestione visite
  * Aggiunta e Modifica prezzi
  * Ricavato vendite
  - Elenco esposizioni
    * Aggiunta
    * Modifica
    * Eliminazione
  - Ricavato vendite
  - Sconti
    * Aggiunta
    * Modifica
    * Rimozione
- Pubblico
  * Elenco esposizioni
  * Acquisto biglietti
  * Applicazione sconti
  * Acquisto Servizi/Prodotti accessori
- Autenticazione
  * Registrazione
  * Accesso

# PRECEDENZE

1. Autenticazione
    * Registrazione
    * Accesso
    
1. Pubblico
    * Elenco esposizioni
    * Acquisto biglietti
    * Applicazione sconti
    * Acquisto Servizi/Prodotti accessori

1. Amministrazione
    * Gestione visite
    * Aggiunta e Modifica prezzi
    * Elenco esposizioni
        * Aggiunta
        * Modifica
        * Eliminazione
    * Ricavato vendite
    * Sconti
        * Aggiunta
        * Modifica
        * Rimozione
  
# Work Breakdown Structure
https://drive.google.com/file/d/1zMDxxkBvm8cISVMXwFJA7TRQWP3KXHQa/view?usp=sharing
- Progetto
  - [x] Deliverable e Milestone
  - [x] WBS
  - [x] Diagramma di Gantt
  - [x] Identificazione delle precedenze
  - [x] Business Model Canvas
  - [ ] Analisi dei rischi
- Base dei dati
  - [ ] Schema concettuale E/R
  - [ ] Schema logico relazionale
  - [ ] Implementazione della base di dati
    - Creazione Tabelle
    - Inserimento dati di prova
  - [ ] Interrogazioni richieste
    - Titoli e date di esposizioni tematiche di un determinato anno
    - Numero biglietti emessi per una determinata esposizione
    - Ricavato della vendita dei biglietti di una determinata esposizione
- Web Application
  - Storyboard
  - Pagine
    - Amministrazione
      - Gestione visite
        - [ ] Aggiunta e Modifica prezzi
        - [ ] Ricavato vendite
      - Elenco esposizioni
        - [ ] Aggiunta
        - [ ] Modifica
        - [ ] Eliminazione
        - [ ] Ricavato vendite
      - Sconti
        - [ ] Aggiunta
        - [ ] Modifica
        - [ ] Rimozione
    - Pubblico
      - [ ] Elenco esposizioni
      - [ ] Acquisto biglietti
      - [ ] Applicazione sconti
      - [ ] Acquisto Servizi/Prodotti accessori
    - Autenticazione
      - [ ] Registrazione
      - [ ] Accesso  
