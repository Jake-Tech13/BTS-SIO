<?php
class Chauffeur {
    private ?int $id;
    private string $nom;
    private string $prenom;
    private string $telephone;
    private string $numeroPermis;
    private string $dateEmbauche;
    private string $statut;
    private string $certifications;
    
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
    public function getTelephone(): string {
        return $this->telephone;
    }
    public function getNumeroPermis(): string {
        return $this->numeroPermis;
    }
    public function getDateEmbauche(): string {
        return $this->dateEmbauche;
    }
    public function getStatut(): string {
        return $this->statut;
    }
    public function getCertifications(): string {
        return $this->certifications;
    }
    
    // Setters
    public function setId(?int $id): void {
        $this->id = $id;
    }
    public function setNom(string $nom): void {
        $this->nom = $nom;
    }
    public function setPrenom(string $prenom): void {
        $this->prenom = $prenom;
    }
    public function setTelephone(string $telephone): void {
        $this->telephone = $telephone;
    }
    public function setNumeroPermis(string $numeroPermis): void {
        $this->numeroPermis = $numeroPermis;
    }
    public function setDateEmbauche(string $dateEmbauche): void {
        $this->dateEmbauche = $dateEmbauche;
    }
    public function setStatut(string $statut): void {
        $this->statut = $statut;
    }
    public function setCertifications(string $certifications): void {
        $this->certifications = $certifications;
    }
    
    // Constructeur
    public function __construct(string $nom, string $prenom, string $telephone, string $numeroPermis, string $dateEmbauche, string $statut, string $certifications) {
        $this->id = null;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->telephone = $telephone;
        $this->numeroPermis = $numeroPermis;
        $this->dateEmbauche = $dateEmbauche;
        $this->statut = $statut;
        $this->certifications = $certifications;
    }
    
    // Méthodes
    
    /**
     * Affiche les informations du chauffeur
     */
    public function afficherInfos(): string {
        return "Chauffeur: {$this->prenom} {$this->nom} - Permis: {$this->numeroPermis} - Statut: {$this->statut}";
    }
    
    /**
     * Retourne le nom complet du chauffeur
     */
    public function getNomComplet(): string {
        return "{$this->prenom} {$this->nom}";
    }
    
    /**
     * Vérifie si le chauffeur est actif
     */
    public function estActif(): bool {
        return $this->statut === 'actif';
    }
    
    /**
     * Change le statut du chauffeur
     */
    public function changerStatut(string $nouveauStatut): void {
        if (in_array($nouveauStatut, ['actif', 'inactif'])) {
            $this->statut = $nouveauStatut;
        }
    }
    
    /**
     * Retourne les informations en tableau
     */
    public function toArray(): array {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'telephone' => $this->telephone,
            'numeroPermis' => $this->numeroPermis,
            'dateEmbauche' => $this->dateEmbauche,
            'statut' => $this->statut,
            'certifications' => $this->certifications
        ];
    }
    
    /**
     * Vérifie si le numéro de permis est valide
     */
    public function validerPermis(): bool {
        return !empty($this->numeroPermis) && strlen($this->numeroPermis) >= 8;
    }
    
    /**
     * Compare deux chauffeurs
     */
    public function equals(Chauffeur $autre): bool {
        return $this->id === $autre->id;
    }
    
    // Méthodes de communication avec d'autres entités
    
    /**
     * Retourne les détails complets du chauffeur
     */
    public function afficherDetailsComplets(): string {
        return "Chauffeur: {$this->getNomComplet()}, Permis: {$this->numeroPermis}, " .
               "Embauche: {$this->dateEmbauche}, Statut: {$this->statut}, " .
               "Tel: {$this->telephone}";
    }
    
    /**
     * Retourne les trajets assignés à ce chauffeur
     * Note: Nécessite l'accès à TrajetDAO
     */
    public function obtenirTrajetsAssignes(): string {
        return "Utilisez TrajetDAO::getTrajetsByChauffeur({$this->id})";
    }
    
    /**
     * Retourne les trajets en cours
     * Note: Nécessite l'accès à TrajetDAO
     */
    public function obtenirTrajetEnCours(): string {
        return "Utilisez TrajetDAO::getTrajetEnCoursByChauffeur({$this->id})";
    }
    
    /**
     * Vérifie si le chauffeur peut être assigné à un trajet
     */
    public function peutEtreAssigneAuTrajet(): bool {
        return $this->estActif() && $this->validerPermis();
    }
    
    /**
     * Retourne les certifications du chauffeur
     */
    public function afficherCertifications(): string {
        if (empty($this->certifications)) {
            return "Aucune certification spéciale";
        }
        return "Certifications: {$this->certifications}";
    }
    
    /**
     * Retourne les informations pour contacter le chauffeur
     */
    public function obtenirInfosContact(): array {
        return [
            'nom' => $this->getNomComplet(),
            'telephone' => $this->telephone,
            'statut' => $this->statut,
            'certifications' => $this->certifications
        ];
    }
}