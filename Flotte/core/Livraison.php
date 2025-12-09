<?php
class Livraison {
    private ?int $id;
    private string $reference;
    private int $idClient;
    private int $idMarchandise;
    private int $idDestinationDepot;
    private float $poidsKg;
    private float $volumeM3;
    private string $statut;
    private string $dateCreation;
    private string $datePrelevementPrevue;
    private string $dateLivraisonPrevue;
    
    // Getters
    public function getId(): ?int {
        return $this->id;
    }
    public function getReference(): string {
        return $this->reference;
    }
    public function getIdClient(): int {
        return $this->idClient;
    }
    public function getIdMarchandise(): int {
        return $this->idMarchandise;
    }
    public function getIdDestinationDepot(): int {
        return $this->idDestinationDepot;
    }
    public function getPoidsKg(): float {
        return $this->poidsKg;
    }
    public function getVolumeM3(): float {
        return $this->volumeM3;
    }
    public function getStatut(): string {
        return $this->statut;
    }
    public function getDateCreation(): string {
        return $this->dateCreation;
    }
    public function getDatePrelevementPrevue(): string {
        return $this->datePrelevementPrevue;
    }
    public function getDateLivraisonPrevue(): string {
        return $this->dateLivraisonPrevue;
    }
    
    // Setters
    public function setId(?int $id): void {
        $this->id = $id;
    }
    public function setReference(string $reference): void {
        $this->reference = $reference;
    }
    public function setIdClient(int $idClient): void {
        $this->idClient = $idClient;
    }
    public function setIdMarchandise(int $idMarchandise): void {
        $this->idMarchandise = $idMarchandise;
    }
    public function setIdDestinationDepot(int $idDestinationDepot): void {
        $this->idDestinationDepot = $idDestinationDepot;
    }
    public function setPoidsKg(float $poidsKg): void {
        $this->poidsKg = $poidsKg;
    }
    public function setVolumeM3(float $volumeM3): void {
        $this->volumeM3 = $volumeM3;
    }
    public function setStatut(string $statut): void {
        $this->statut = $statut;
    }
    public function setDateCreation(string $dateCreation): void {
        $this->dateCreation = $dateCreation;
    }
    public function setDatePrelevementPrevue(string $datePrelevementPrevue): void {
        $this->datePrelevementPrevue = $datePrelevementPrevue;
    }
    public function setDateLivraisonPrevue(string $dateLivraisonPrevue): void {
        $this->dateLivraisonPrevue = $dateLivraisonPrevue;
    }
    
    // Constructeur
    public function __construct(int $idClient, int $idMarchandise, int $idDestinationDepot, float $poidsKg, float $volumeM3, string $statut) {
        $this->id = null;
        $this->idClient = $idClient;
        $this->idMarchandise = $idMarchandise;
        $this->idDestinationDepot = $idDestinationDepot;
        $this->poidsKg = $poidsKg;
        $this->volumeM3 = $volumeM3;
        $this->statut = $statut;
        $this->dateCreation = date('Y-m-d H:i:s');
        $this->datePrelevementPrevue = "";
        $this->dateLivraisonPrevue = "";
    }

    // Méthodes
    
    /**
     * Affiche les informations de la livraison
     */
    public function afficherInfos(): string {
        return "Livraison: {$this->reference} - Client: {$this->idClient} - Poids: {$this->poidsKg}kg - Statut: {$this->statut}";
    }
    
    /**
     * Marque la livraison comme en cours
     */
    public function marquerEnCours(): void {
        if ($this->statut === 'prevue') {
            $this->statut = 'en_cours';
        }
    }
    
    /**
     * Marque la livraison comme livrée
     */
    public function marquerLivree(): void {
        if ($this->statut === 'en_cours') {
            $this->statut = 'livree';
        }
    }
    
    /**
     * Annule la livraison
     */
    public function annuler(): void {
        if ($this->statut !== 'livree') {
            $this->statut = 'annulee';
        }
    }
    
    /**
     * Vérifie si la livraison est complète
     */
    public function estComplete(): bool {
        return !empty($this->reference) && 
               !empty($this->idClient) && 
               !empty($this->idMarchandise) &&
               !empty($this->idDestinationDepot) &&
               $this->poidsKg > 0 &&
               $this->volumeM3 > 0;
    }
    
    /**
     * Retourne les informations en tableau
     */
    public function toArray(): array {
        return [
            'id' => $this->id,
            'reference' => $this->reference,
            'idClient' => $this->idClient,
            'idMarchandise' => $this->idMarchandise,
            'idDestinationDepot' => $this->idDestinationDepot,
            'poidsKg' => $this->poidsKg,
            'volumeM3' => $this->volumeM3,
            'statut' => $this->statut,
            'dateCreation' => $this->dateCreation,
            'datePrelevementPrevue' => $this->datePrelevementPrevue,
            'dateLivraisonPrevue' => $this->dateLivraisonPrevue
        ];
    }
    
    /**
     * Calcule la densité (poids/volume)
     */
    public function calculerDensite(): float {
        if ($this->volumeM3 === 0) {
            return 0;
        }
        return $this->poidsKg / $this->volumeM3;
    }
    
    /**
     * Compare deux livraisons
     */
    public function equals(Livraison $autre): bool {
        return $this->id === $autre->id;
    }
    
    // Méthodes de communication avec d'autres entités
    
    /**
     * Affiche les détails complets de la livraison
     */
    public function afficherDetailsComplets(): string {
        return "Livraison {$this->reference}: " .
               "Client #{$this->idClient} | " .
               "Marchandise #{$this->idMarchandise} | " .
               "Dépôt destination #{$this->idDestinationDepot} | " .
               "Poids: {$this->poidsKg}kg, Volume: {$this->volumeM3}m³ | " .
               "Statut: {$this->statut}";
    }
    
    /**
     * Retourne les informations de facturation pour cette livraison
     * @return array Contenant les détails pour créer une facture
     */
    public function obtenirInfosFacturation(): array {
        return [
            'idLivraison' => $this->id,
            'reference' => $this->reference,
            'poids' => $this->poidsKg,
            'volume' => $this->volumeM3,
            'montantHt' => $this->calculerMontantEstime(),
            'statut' => 'emise'
        ];
    }
    
    /**
     * Calcule un montant estimé pour la livraison (basé sur poids et volume)
     */
    private function calculerMontantEstime(): float {
        // Tarif: 0.5€ par kg + 10€ par m³
        return ($this->poidsKg * 0.5) + ($this->volumeM3 * 10);
    }
    
    /**
     * Vérifie si la livraison peut être assignée à un véhicule
     */
    public function peutEtreAssigneeAVehicule(Vehicule $vehicule): bool {
        return $vehicule->peutCharger($this->poidsKg, $this->volumeM3) && 
               $vehicule->estDisponible();
    }
    
    /**
     * Retourne un message indiquant si c'est un client ou un dépôt de destination
     */
    public function obtenirDescriptionLieux(): string {
        return "Livraison du Client #{$this->idClient} vers Dépôt #{$this->idDestinationDepot}";
    }
}