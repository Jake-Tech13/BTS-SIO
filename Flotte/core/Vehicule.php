<?php
class Vehicule {
    private ?int $id;
    private string $immatriculation;
    private string $codeVin;
    private string $modele;
    private int $annee;
    private float $capaciteKg;
    private float $capaciteM3;
    private string $statut;
    private ?int $idDepotActuel;
    
    // Getters
    public function getId(): ?int {
        return $this->id;
    }
    public function getImmatriculation(): string {
        return $this->immatriculation;
    }
    public function getCodeVin(): string {
        return $this->codeVin;
    }
    public function getModele(): string {
        return $this->modele;
    }
    public function getAnnee(): int {
        return $this->annee;
    }
    public function getCapaciteKg(): float {
        return $this->capaciteKg;
    }
    public function getCapaciteM3(): float {
        return $this->capaciteM3;
    }
    public function getStatut(): string {
        return $this->statut;
    }
    public function getIdDepotActuel(): ?int {
        return $this->idDepotActuel;
    }
    
    // Setters
    public function setId(?int $id): void {
        $this->id = $id;
    }
    public function setImmatriculation(string $immatriculation): void {
        $this->immatriculation = $immatriculation;
    }
    public function setCodeVin(string $codeVin): void {
        $this->codeVin = $codeVin;
    }
    public function setModele(string $modele): void {
        $this->modele = $modele;
    }
    public function setAnnee(int $annee): void {
        $this->annee = $annee;
    }
    public function setCapaciteKg(float $capaciteKg): void {
        $this->capaciteKg = $capaciteKg;
    }
    public function setCapaciteM3(float $capaciteM3): void {
        $this->capaciteM3 = $capaciteM3;
    }
    public function setStatut(string $statut): void {
        $this->statut = $statut;
    }
    public function setIdDepotActuel(?int $idDepotActuel): void {
        $this->idDepotActuel = $idDepotActuel;
    }
    
    // Constructeur
    public function __construct(string $immatriculation, string $codeVin, string $modele, int $annee, float $capaciteKg, float $capaciteM3, string $statut, ?int $idDepotActuel) {
        $this->id = null;
        $this->immatriculation = $immatriculation;
        $this->codeVin = $codeVin;
        $this->modele = $modele;
        $this->annee = $annee;
        $this->capaciteKg = $capaciteKg;
        $this->capaciteM3 = $capaciteM3;
        $this->statut = $statut;
        $this->idDepotActuel = $idDepotActuel;
    }
    
    // Méthodes
    
    /**
     * Affiche les informations du véhicule
     */
    public function afficherInfos(): string {
        return "Véhicule: {$this->immatriculation} ({$this->modele} {$this->annee}) - Statut: {$this->statut}";
    }
    
    /**
     * Vérifie si le véhicule est disponible
     */
    public function estDisponible(): bool {
        return in_array($this->statut, ['disponible', 'en_service']);
    }
    
    /**
     * Change le statut du véhicule
     */
    public function changerStatut(string $nouveauStatut): void {
        $statuts = ['disponible', 'indisponible', 'en_service', 'hors_service', 'en_entretien'];
        if (in_array($nouveauStatut, $statuts)) {
            $this->statut = $nouveauStatut;
        }
    }
    
    /**
     * Vérifie si le véhicule peut porter une charge donnée
     */
    public function peutCharger(float $poidsKg, float $volumeM3): bool {
        return $poidsKg <= $this->capaciteKg && $volumeM3 <= $this->capaciteM3;
    }
    
    /**
     * Calcule le taux d'utilisation en poids
     */
    public function tauxUtilisationPoids(float $poidsKg): float {
        return ($poidsKg / $this->capaciteKg) * 100;
    }
    
    /**
     * Calcule le taux d'utilisation en volume
     */
    public function tauxUtilisationVolume(float $volumeM3): float {
        return ($volumeM3 / $this->capaciteM3) * 100;
    }
    
    /**
     * Retourne les informations en tableau
     */
    public function toArray(): array {
        return [
            'id' => $this->id,
            'immatriculation' => $this->immatriculation,
            'codeVin' => $this->codeVin,
            'modele' => $this->modele,
            'annee' => $this->annee,
            'capaciteKg' => $this->capaciteKg,
            'capaciteM3' => $this->capaciteM3,
            'statut' => $this->statut,
            'idDepotActuel' => $this->idDepotActuel
        ];
    }
    
    /**
     * Vérifie si l'immatriculation est valide
     */
    public function validerImmatriculation(): bool {
        return !empty($this->immatriculation) && strlen($this->immatriculation) >= 6;
    }
    
    /**
     * Compare deux véhicules
     */
    public function equals(Vehicule $autre): bool {
        return $this->id === $autre->id;
    }
    
    // Méthodes de communication avec d'autres entités
    
    /**
     * Retourne les détails complets du véhicule
     */
    public function afficherDetailsComplets(): string {
        return "Véhicule: {$this->immatriculation} ({$this->modele} {$this->annee}), " .
               "VIN: {$this->codeVin}, Capacités: {$this->capaciteKg}kg / {$this->capaciteM3}m³, " .
               "Statut: {$this->statut}, Dépôt: {$this->idDepotActuel}";
    }
    
    /**
     * Retourne le nom du dépôt actuel
     * Note: Nécessite l'accès à DepotDAO
     */
    public function obtenirNomDepotActuel(): string {
        if (empty($this->idDepotActuel)) {
            return "Aucun dépôt assigné";
        }
        return "Utilisez DepotDAO::getDepotById({$this->idDepotActuel}) pour récupérer le nom";
    }
    
    /**
     * Assigne le véhicule à un dépôt
     */
    public function assignerDepot(Depot $depot): void {
        $this->idDepotActuel = $depot->getId();
    }
    
    /**
     * Retourne les informations de maintenance prévue
     * Note: Nécessite l'accès à MaintenanceDAO
     */
    public function obtenirMaintenancesPrevues(): string {
        return "Utilisez MaintenanceDAO::getMaintenancesByVehicule({$this->id}, 'prevue')";
    }
    
    /**
     * Retourne les trajets récents du véhicule
     * Note: Nécessite l'accès à TrajetDAO
     */
    public function obtenirTrajetsRecents(int $nombre = 10): string {
        return "Utilisez TrajetDAO::getTrajetsByVehicule({$this->id}, LIMIT {$nombre})";
    }
    
    /**
     * Retourne les chauffeurs affectés à ce véhicule
     * Note: Nécessite l'accès à TrajetDAO
     */
    public function obtenirChauffeursAffectes(): string {
        return "Utilisez TrajetDAO::getChauffeursByVehicule({$this->id})";
    }
    
    /**
     * Vérifie si le véhicule peut effectuer un trajet
     */
    public function peutEffectuerTrajet(): bool {
        return $this->estDisponible() && !empty($this->idDepotActuel);
    }
}