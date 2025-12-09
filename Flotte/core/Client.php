<?php

class Client {
    private ?int $id;
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
    private string $dateCreation;
    
    // Getters
    public function getId(): ?int {
        return $this->id;
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
    public function getDateCreation(): string {
        return $this->dateCreation;
    }
    
    // Setters
    public function setID(?int $id): void {
        $this->id = $id;
    }
    public function setNom(string $nom): void {
        $this->nom = $nom;
    }
    public function setPrenom(string $prenom): void {
        $this->prenom = $prenom;
    }
    public function setRaisonSociale(string $raisonSociale): void {
        $this->raisonSociale = $raisonSociale;
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
        $this->numTVA = $num;
    }
    public function setDateCreation(string $date): void {
        $this->dateCreation = $date;
    }

    // Constructeur    
    public function __construct(?int $id = null, string $nom, string $prenom, string $raisonSociale, string $email, string $telephone, string $mdp, string $codePostal, string $ville, string $adresse, string $numTVA, ?string $dateCreation = null) {
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
        $this->dateCreation = $dateCreation ?? date('Y-m-d');
    }

    // Méthodes
    
    /**
     * Affiche les informations du client
     */
    public function afficherInfos(): string {
        return "Client: {$this->prenom} {$this->nom} ({$this->raisonSociale}) - {$this->email} - {$this->telephone}";
    }
    
    /**
     * Valide l'email du client
     */
    public function validerEmail(): bool {
        return filter_var($this->email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    /**
     * Valide le numéro TVA
     */
    public function validerNumTVA(): bool {
        return !empty($this->numTVA) && strlen($this->numTVA) >= 5;
    }
    
    /**
     * Valide le code postal
     */
    public function validerCodePostal(): bool {
        return !empty($this->codePostal) && preg_match('/^\d{5}$/', $this->codePostal);
    }
    
    /**
     * Valide tous les champs obligatoires
     */
    public function validerDonnees(): bool {
        return !empty($this->nom) && 
               !empty($this->prenom) && 
               !empty($this->raisonSociale) &&
               $this->validerEmail() &&
               !empty($this->telephone) &&
               !empty($this->mdp) &&
               $this->validerCodePostal() &&
               !empty($this->ville) &&
               !empty($this->adresse) &&
               $this->validerNumTVA();
    }
    
    /**
     * Retourne les informations du client en tableau
     */
    public function toArray(): array {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'raisonSociale' => $this->raisonSociale,
            'email' => $this->email,
            'telephone' => $this->telephone,
            'codePostal' => $this->codePostal,
            'ville' => $this->ville,
            'adresse' => $this->adresse,
            'numTVA' => $this->numTVA,
            'dateCreation' => $this->dateCreation
        ];
    }
    
    /**
     * Compare deux clients
     */
    public function equals(Client $autre): bool {
        return $this->id === $autre->id;
    }
    
    /**
     * Retourne le nom complet du client
     */
    public function getNomComplet(): string {
        return "{$this->prenom} {$this->nom}";
    }
    
    /**
     * Retourne l'adresse complète
     */
    public function getAdresseComplete(): string {
        return "{$this->adresse}, {$this->codePostal} {$this->ville}";
    }
    
    // Méthodes de communication avec d'autres entités
    
    /**
     * Crée une nouvelle livraison pour ce client
     * @return Livraison La livraison créée
     */
    public function creerLivraison(int $idMarchandise, int $idDestinationDepot, float $poidsKg, float $volumeM3): Livraison {
        return new Livraison(
            $this->id ?? 0,
            $idMarchandise,
            $idDestinationDepot,
            $poidsKg,
            $volumeM3,
            'prevue'
        );
    }
    
    /**
     * Retourne le nombre de livraisons prévues pour ce client
     * Note: Cette méthode nécessite l'accès au DAO
     */
    public function obtenirNombreLivraisonsActives(): string {
        return "Utilisez LivraisonDAO::getLivraisonsByClient({$this->id}) pour récupérer les livraisons";
    }
    
    /**
     * Retourne les adresses de facturation du client
     * Note: Utile pour les factures liées aux livraisons
     */
    public function obtenirAdresseFacturation(): string {
        return "Client: {$this->getNomComplet()}, {$this->getAdresseComplete()}, TVA: {$this->numTVA}";
    }
}