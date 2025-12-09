<?php
class Trajet {
    private ?int $id;
    private int $idVehicule;
    private int $idChauffeur;
    private int $idGps;
    private string $heureDepartPrevue;
    private string $heureDepartReelle;
    private string $heureArrivePrevue;
    private string $heureArriveReelle;
    private string $statut;
    private string $notes;
    
    // Getters
    public function getId(): ?int {
        return $this->id;
    }
    public function getIdVehicule(): int {
        return $this->idVehicule;
    }
    public function getIdChauffeur(): int {
        return $this->idChauffeur;
    }
    public function getIdGps(): int {
        return $this->idGps;
    }
    public function getHeureDepartPrevue(): string {
        return $this->heureDepartPrevue;
    }
    public function getHeureDepartReelle(): string {
        return $this->heureDepartReelle;
    }
    public function getHeureArrivePrevue(): string {
        return $this->heureArrivePrevue;
    }
    public function getHeureArriveReelle(): string {
        return $this->heureArriveReelle;
    }
    public function getStatut(): string {
        return $this->statut;
    }
    public function getNotes(): string {
        return $this->notes;
    }
    
    // Setters
    public function setId(?int $id): void {
        $this->id = $id;
    }
    public function setIdVehicule(int $idVehicule): void {
        $this->idVehicule = $idVehicule;
    }
    public function setIdChauffeur(int $idChauffeur): void {
        $this->idChauffeur = $idChauffeur;
    }
    public function setIdGps(int $idGps): void {
        $this->idGps = $idGps;
    }
    public function setHeureDepartPrevue(string $heureDepartPrevue): void {
        $this->heureDepartPrevue = $heureDepartPrevue;
    }
    public function setHeureDepartReelle(string $heureDepartReelle): void {
        $this->heureDepartReelle = $heureDepartReelle;
    }
    public function setHeureArrivePrevue(string $heureArrivePrevue): void {
        $this->heureArrivePrevue = $heureArrivePrevue;
    }
    public function setHeureArriveReelle(string $heureArriveReelle): void {
        $this->heureArriveReelle = $heureArriveReelle;
    }
    public function setStatut(string $statut): void {
        $this->statut = $statut;
    }
    public function setNotes(string $notes): void {
        $this->notes = $notes;
    }
    
    // Constructeur
    public function __construct(int $idVehicule, int $idChauffeur, int $idGps, string $heureDepartPrevue, string $heureArrivePrevue, string $statut) {
        $this->id = null;
        $this->idVehicule = $idVehicule;
        $this->idChauffeur = $idChauffeur;
        $this->idGps = $idGps;
        $this->heureDepartPrevue = $heureDepartPrevue;
        $this->heureDepartReelle = "";
        $this->heureArrivePrevue = $heureArrivePrevue;
        $this->heureArriveReelle = "";
        $this->statut = $statut;
        $this->notes = "";
    }
    
    // Méthodes
    
    /**
     * Affiche les informations du trajet
     */
    public function afficherInfos(): string {
        return "Trajet: Véhicule {$this->idVehicule} - Chauffeur {$this->idChauffeur} - Statut: {$this->statut}";
    }
    
    /**
     * Marque le trajet comme démarré
     */
    public function demarrerTrajet(): void {
        if ($this->statut === 'prevu') {
            $this->statut = 'en_cours';
            $this->heureDepartReelle = date('Y-m-d H:i:s');
        }
    }
    
    /**
     * Marque le trajet comme terminé
     */
    public function terminerTrajet(): void {
        if ($this->statut === 'en_cours') {
            $this->statut = 'termine';
            $this->heureArriveReelle = date('Y-m-d H:i:s');
        }
    }
    
    /**
     * Annule le trajet
     */
    public function annuler(): void {
        if ($this->statut !== 'termine') {
            $this->statut = 'annule';
        }
    }
    
    /**
     * Calcule la durée prévue du trajet (en heures)
     */
    public function dureePrerue(): float {
        $debut = strtotime($this->heureDepartPrevue);
        $fin = strtotime($this->heureArrivePrevue);
        return ($fin - $debut) / 3600;
    }
    
    /**
     * Calcule la durée réelle du trajet (en heures)
     */
    public function dureeReelle(): float {
        if (empty($this->heureDepartReelle) || empty($this->heureArriveReelle)) {
            return 0;
        }
        $debut = strtotime($this->heureDepartReelle);
        $fin = strtotime($this->heureArriveReelle);
        return ($fin - $debut) / 3600;
    }
    
    /**
     * Retourne les informations en tableau
     */
    public function toArray(): array {
        return [
            'id' => $this->id,
            'idVehicule' => $this->idVehicule,
            'idChauffeur' => $this->idChauffeur,
            'idGps' => $this->idGps,
            'heureDepartPrevue' => $this->heureDepartPrevue,
            'heureDepartReelle' => $this->heureDepartReelle,
            'heureArrivePrevue' => $this->heureArrivePrevue,
            'heureArriveReelle' => $this->heureArriveReelle,
            'statut' => $this->statut,
            'notes' => $this->notes
        ];
    }
    
    /**
     * Compare deux trajets
     */
    public function equals(Trajet $autre): bool {
        return $this->id === $autre->id;
    }
    
    // Méthodes de communication avec d'autres entités
    
    /**
     * Retourne les détails complets du trajet
     */
    public function afficherDetailsComplets(): string {
        return "Trajet: Véhicule #{$this->idVehicule} | Chauffeur #{$this->idChauffeur} | " .
               "Départ: {$this->heureDepartPrevue} → {$this->heureDepartReelle} | " .
               "Arrivée: {$this->heureArrivePrevue} → {$this->heureArriveReelle} | " .
               "Statut: {$this->statut}";
    }
    
    /**
     * Retourne les informations du véhicule utilisé
     * Note: Nécessite l'accès à VehiculeDAO
     */
    public function obtenirInfosVehicule(): string {
        return "Utilisez VehiculeDAO::getVehiculeById({$this->idVehicule})";
    }
    
    /**
     * Retourne les informations du chauffeur assigné
     * Note: Nécessite l'accès à ChauffeurDAO
     */
    public function obtenirInfosChauffeur(): string {
        return "Utilisez ChauffeurDAO::getChauffeurById({$this->idChauffeur})";
    }
    
    /**
     * Retourne les données GPS du trajet
     * Note: Nécessite l'accès à GpsDAO
     */
    public function obtenirPosistionGPS(): string {
        return "Utilisez GpsDAO::getGpsById({$this->idGps})";
    }
    
    /**
     * Retourne le résumé du trajet pour affichage
     */
    public function obtenirResume(): array {
        return [
            'id' => $this->id,
            'vehicule' => $this->idVehicule,
            'chauffeur' => $this->idChauffeur,
            'dateDepart' => $this->heureDepartPrevue,
            'dateArrivee' => $this->heureArrivePrevue,
            'statut' => $this->statut,
            'dureePrevue' => $this->dureePrerue(),
            'dureeReelle' => $this->dureeReelle()
        ];
    }
    
    /**
     * Assigne un véhicule et un chauffeur au trajet
     */
    public function assignerRessources(Vehicule $vehicule, Chauffeur $chauffeur): bool {
        if (!$chauffeur->peutEtreAssigneAuTrajet() || !$vehicule->peutEffectuerTrajet()) {
            return false;
        }
        $this->idVehicule = $vehicule->getId() ?? 0;
        $this->idChauffeur = $chauffeur->getId() ?? 0;
        return true;
    }
    
    /**
     * Retourne si le trajet est retardé
     */
    public function estRetarde(): bool {
        if (empty($this->heureDepartReelle)) {
            $maintenant = new DateTime();
            $depart = new DateTime($this->heureDepartPrevue);
            return $maintenant > $depart;
        }
        return false;
    }
}