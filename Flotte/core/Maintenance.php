<?php
class Maintenance {
    private ?int $id;
    private int $idVehicule;
    private string $dateIntervention;
    private string $typeIntervention;
    private string $description;
    private float $cout;
    private int $odometerKm;
    private int $kmProchayneEcheance;
    private string $dateProchayneEcheance;
    private string $statut;
    
    // Getters
    public function getId(): ?int {
        return $this->id;
    }
    public function getIdVehicule(): int {
        return $this->idVehicule;
    }
    public function getDateIntervention(): string {
        return $this->dateIntervention;
    }
    public function getTypeIntervention(): string {
        return $this->typeIntervention;
    }
    public function getDescription(): string {
        return $this->description;
    }
    public function getCout(): float {
        return $this->cout;
    }
    public function getOdometerKm(): int {
        return $this->odometerKm;
    }
    public function getKmProchayneEcheance(): int {
        return $this->kmProchayneEcheance;
    }
    public function getDateProchayneEcheance(): string {
        return $this->dateProchayneEcheance;
    }
    public function getStatut(): string {
        return $this->statut;
    }
    
    // Setters
    public function setId(?int $id): void {
        $this->id = $id;
    }
    public function setIdVehicule(int $idVehicule): void {
        $this->idVehicule = $idVehicule;
    }
    public function setDateIntervention(string $dateIntervention): void {
        $this->dateIntervention = $dateIntervention;
    }
    public function setTypeIntervention(string $typeIntervention): void {
        $this->typeIntervention = $typeIntervention;
    }
    public function setDescription(string $description): void {
        $this->description = $description;
    }
    public function setCout(float $cout): void {
        $this->cout = $cout;
    }
    public function setOdometerKm(int $odometerKm): void {
        $this->odometerKm = $odometerKm;
    }
    public function setKmProchayneEcheance(int $kmProchayneEcheance): void {
        $this->kmProchayneEcheance = $kmProchayneEcheance;
    }
    public function setDateProchayneEcheance(string $dateProchayneEcheance): void {
        $this->dateProchayneEcheance = $dateProchayneEcheance;
    }
    public function setStatut(string $statut): void {
        $this->statut = $statut;
    }
    
    // Constructeur
    public function __construct(int $idVehicule, string $dateIntervention, string $typeIntervention, string $description, float $cout, int $odometerKm, string $statut) {
        $this->id = null;
        $this->idVehicule = $idVehicule;
        $this->dateIntervention = $dateIntervention;
        $this->typeIntervention = $typeIntervention;
        $this->description = $description;
        $this->cout = $cout;
        $this->odometerKm = $odometerKm;
        $this->kmProchayneEcheance = 0;
        $this->dateProchayneEcheance = "";
        $this->statut = $statut;
    }
    
    // Méthodes
    
    /**
     * Affiche les informations de maintenance
     */
    public function afficherInfos(): string {
        return "Maintenance: Véhicule {$this->idVehicule} - Type: {$this->typeIntervention} - Coût: {$this->cout}€ - Statut: {$this->statut}";
    }
    
    /**
     * Marque la maintenance comme terminée
     */
    public function terminer(): void {
        if ($this->statut === 'prevue') {
            $this->statut = 'terminee';
        }
    }
    
    /**
     * Annule la maintenance
     */
    public function annuler(): void {
        if ($this->statut === 'prevue') {
            $this->statut = 'annulee';
        }
    }
    
    /**
     * Définit la prochaine échéance
     */
    public function definirProchayneEcheance(int $kmEcheance, string $dateEcheance): void {
        $this->kmProchayneEcheance = $kmEcheance;
        $this->dateProchayneEcheance = $dateEcheance;
    }
    
    /**
     * Vérifie si une maintenance est nécessaire
     */
    public function maintenanceNecessaire(int $kmActuel): bool {
        return !empty($this->kmProchayneEcheance) && $kmActuel >= $this->kmProchayneEcheance;
    }
    
    /**
     * Retourne les informations en tableau
     */
    public function toArray(): array {
        return [
            'id' => $this->id,
            'idVehicule' => $this->idVehicule,
            'dateIntervention' => $this->dateIntervention,
            'typeIntervention' => $this->typeIntervention,
            'description' => $this->description,
            'cout' => $this->cout,
            'odometerKm' => $this->odometerKm,
            'kmProchayneEcheance' => $this->kmProchayneEcheance,
            'dateProchayneEcheance' => $this->dateProchayneEcheance,
            'statut' => $this->statut
        ];
    }
    
    /**
     * Valide les données de la maintenance
     */
    public function validerDonnees(): bool {
        return !empty($this->idVehicule) && 
               !empty($this->typeIntervention) &&
               $this->cout >= 0 &&
               $this->odometerKm > 0;
    }
    
    /**
     * Compare deux maintenances
     */
    public function equals(Maintenance $autre): bool {
        return $this->id === $autre->id;
    }
    
    // Méthodes de communication avec d'autres entités
    
    /**
     * Retourne les détails complets de la maintenance
     */
    public function afficherDetailsComplets(): string {
        return "Maintenance Véhicule #{$this->idVehicule}: {$this->typeIntervention}, " .
               "Date: {$this->dateIntervention}, Coût: {$this->cout}€, " .
               "Odomètre: {$this->odometerKm}km, Statut: {$this->statut}";
    }
    
    /**
     * Retourne les informations du véhicule concerné
     * Note: Nécessite l'accès à VehiculeDAO
     */
    public function obtenirInfosVehicule(): string {
        return "Utilisez VehiculeDAO::getVehiculeById({$this->idVehicule})";
    }
    
    /**
     * Retourne un coût estimé pour une intervention
     */
    public static function estimerCout(string $typeIntervention): float {
        $couts = [
            'entretien' => 150.0,
            'reparation_moteur' => 500.0,
            'reparation_carrosserie' => 300.0,
            'remplacement_pneus' => 200.0,
            'vidange' => 75.0,
            'revision' => 200.0,
            'reparation_freins' => 250.0,
            'autre' => 100.0
        ];
        return $couts[$typeIntervention] ?? 100.0;
    }
    
    /**
     * Retourne l'historique de maintenance du véhicule
     * Note: Nécessite l'accès à MaintenanceDAO
     */
    public function obtenirHistoriqueVehicule(): string {
        return "Utilisez MaintenanceDAO::getMaintenancesByVehicule({$this->idVehicule})";
    }
    
    /**
     * Retourne les maintenances programmées
     * Note: Nécessite l'accès à MaintenanceDAO
     */
    public function obtenirMaintenancesAVenir(): string {
        return "Utilisez MaintenanceDAO::getMaintenancesPrevues({$this->idVehicule})";
    }
    
    /**
     * Vérifie si la maintenance est urgente
     */
    public function estUrgente(): bool {
        return $this->statut === 'prevue' && 
               strtotime($this->dateIntervention) < time() + (7 * 24 * 60 * 60); // Dans 7 jours
    }
    
    /**
     * Retourne l'impact sur le budget
     */
    public function obtenirImpactBudget(): array {
        return [
            'type' => $this->typeIntervention,
            'cout' => $this->cout,
            'date' => $this->dateIntervention,
            'estUrgente' => $this->estUrgente(),
            'description' => $this->description
        ];
    }
}