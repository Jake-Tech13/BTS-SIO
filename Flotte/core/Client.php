<?php

class Client {
    private int $idClient;
    private string $nom;
    private string $prenom;
    private string $raisonSociale;
    private string $email;
    private string $telephone;
    private string $mdp;
    private string $codePostal;
    private string $ville;
    private string $adresse;
    private string $numTVA;
    private DateTime $dateCreation;
    
    // Getters
    public function getId(): int {
        return $this->idClient;
    }
    public function getNom(): string {
        return $this->nom;
    }
    public function getPrenom(): string {
        return $this->prenom;
    }
    public function getRaisonSociale(): string {
        return $this->raisonSociale;
    }
    public function getEmail(): string {
        return $this->email;
    }
    public function getTelephone(): string {
        return $this->telephone;
    }
    public function getMdp(): string {
        return $this->mdp;
    }
    public function getCodePostal(): string {
        return $this->codePostal;
    }
    public function getVille(): string {
        return $this->ville;
    }
    public function getAdresse(): string {
        return $this->adresse;
    }
    public function getNumTVA(): string {
        return $this->numTVA;
    }
    public function getDateCreation(): DateTime {
        return $this->dateCreation;
    }
    
    // Setters
    public function setID(int $id): void {
        $this->idClient = $id;
    }
    public function setNom(string $nom): void {
        $this->nom = $nom;
    }
    public function setPrenom(string $prenom): void {
        $this->prenom = $prenom;
    }
    public function setRaisonSociale(string $raisonSociale): void {
        $this->$raisonSociale = $raisonSociale;
    }
    public function setEmail(string $email): void {
        $this->email = $email;
    }
    public function setTelephone(string $telephone): void {
        $this->telephone = $telephone;
    }
    public function setMdp(string $mdp): void {
        $this->mdp = $mdp;
    }
    public function setCodePostal(string $code): void {
        $this->codePostal = $code;
    }
    public function setVille(string $ville): void {
        $this->ville = $ville;
    }
    public function setAdresse(string $adresse): void {
        $this->adresse = $adresse;
    }
    public function setNumTVA(string $num): void {
        $this->adresse = $num;
    }
    public function setDateCreation(DateTime $date): void {
        $this->dateCreation = $date;
    }
    // Constructeur
    
    public function __construct(int $id, string $nom, string $prenom, string $raisonSociale, string $email, string $telephone, string $mdp, string $codePostal, string $ville, string $adresse, string $numTVA, DateTime $date) {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->raisonSociale = $raisonSociale;
        $this->email = $email;
        $this->telephone = $telephone;
        $this->mdp = $mdp;
        $this->codePostal = $codePostal;
        $this->ville = $ville;
        $this->adresse = $adresse;
        $this->numTVA = $numTVA;
        $this->dateCreation = $date;
    }

    // Méthodes
    public function creerLivraison(array $listeCommandes): Commande {
        $livraison = new Livraison($this, $listeCommandes);
        return $livraison;
    }
    
    //public function
}