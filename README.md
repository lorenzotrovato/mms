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
  - [x] Analisi dei rischi
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
      
# Valutazione dei rischi

Pericolo                        | Probabilità   | Cause                                                                                                                                                                                                              | Conseguenze                                                                                                                        | Precauzioni
------------------------------- | ------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ---------------------------------------------------------------------------------------------------------------------------------- | ------------
Disturbi Visivi                 | Medio-Alta    | - Illuminazione inadatta <br> - Luce dirretta <br> - Presenza di colori estremi <br> - Posizione monitor inadeguata                                                                                                | - Bruciore <br> - Fastidio alla luce <br> - Visione annebbiata <br> - Stanchezza alla lettura                                      | - Pause frequenti <br> - Regolare l'illuminazione <br> - Eliminare riflessi <br> - Posizionare il monitor all'altezza degli occhi <br> - Posizionarsi ad una distanza corretta dal monitor
Disturbi muscolo-scheletrici    | Media         | - Posizione scorretta durante le ore lavorative <br> - Sedia e arredo non ergonomico <br> - Mantenere una posizione fissa per troppo tempo oppure movimenti ripetitivi <br> - Isolamento <br> - Fattori ambientali | - Sensazione di pesantezza <br> - Dolori <br> - Intorpidimento <br> - Rigidità generale                                            | - Postura corretta <br> - Regolare correttamente l'altezza della sedia <br> - Effettuare regolarmente pause
Fatica mentale e stress         | Alta          | - Utilizzo intenso dei videoterminali <br> - Cattiva organizzazione del lavoro                                                                                                                                     | - Mal di testa <br> - Instabilità <br> - Ansia <br> - Insonnia                                                                     | - Fare attività fisica <br> - Usare al meglio le pause <br> - Comportamenti corretti
Incendio                        | Bassa         | - Impianti difettosi <br> - Disattenzione nell’utilizzo di sostanze infiammabili e/o fiamme libere <br> - Inadeguata pulizia e manutenzione dell’ambiente di lavoro                                                | - Ustioni <br> - Soffocamento <br> - Intossicazione <br> - Ferite e fratture per riduzione della visibilità, cadute e/o crolli...  | - Non manipolare sostanze infiammabili in presenza di scintille <br> - Stoccare le sostanze infiammabili in luoghi adatti <br> - Non fumare in presenza di materiali infiammabili <br> - Controlli e manutenzioni regolari <br> - Pianificazione delle emergenze <br> - Installazione di impianti/attrezzature antincendio <br> - Opportuna distanza tra le aree a rischio incendio e persone o cose potenzialmente a rischio
Rischio Elettrico               | Media         | - Cattiva realizzazione/progettazione e manutenzione degli impianti elettrici <br> - Scorretto utilizzo di apparecchiature ad alimentazione elettrica                                                              | - Tetanizzazione <br> - Arresto respiratorio <br> - Fibrillazione ventricolare <br> - Ustioni                                      | - Isolamento delle parti elettriche in tensione attraverso schermi isolanti <br> - Messa a terra <br> - Protezione differenziale <br> - Utilizzo esclusivo di apparecchiature elettriche a doppio isolamento <br> - Utilizzo corretto delle apparecchiature

 
