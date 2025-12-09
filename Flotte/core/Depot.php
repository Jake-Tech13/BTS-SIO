<?php
class Depot {
    private ?int $id;
    private string $nom;
    private string $nomContact;
    private string $adresse;
    private string $ville;
    private string $telephone;
    private float $latitude;
    private float $longitude;
    
    // Getters
    public function getId(): ?int {
        return $this->id;
    }
    public function getNom(): string {
        return $this->nom;
    }
    public function getNomContact(): string {
        return $this->nomContact;
    }
    public function getAdresse(): string {
        return $this->adresse;
    }
    public function getVille(): string {
        return $this->ville;
    }
    public function getTelephone(): string {
        return $this->telephone;
    }
    public function getLatitude(): float {
        return $this->latitude;
    }
    public function getLongitude(): float {
        return $this->longitude;
    }
    
    // Setters
    public function setId(?int $id): void {
        $this->id = $id;
    }
    public function setNom(string $nom): void {
        $this->nom = $nom;
    }
    public function setNomContact(string $nomContact): void {
        $this->nomContact = $nomContact;
    }
    public function setAdresse(string $adresse): void {
        $this->adresse = $adresse;
    }
    public function setVille(string $ville): void {
        $this->ville = $ville;
    }
    public function setTelephone(string $telephone): void {
        $this->telephone = $telephone;
    }
    public function setLatitude(float $latitude): void {
        $this->latitude = $latitude;
    }
    public function setLongitude(float $longitude): void {
        $this->longitude = $longitude;
    }
    
    // Constructeur
    public function __construct(string $nom, string $nomContact, string $adresse, string $ville, string $telephone, float $latitude, float $longitude) {
        $this->id = null;
        $this->nom = $nom;
        $this->nomContact = $nomContact;
        $this->adresse = $adresse;
        $this->ville = $ville;
        $this->telephone = $telephone;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }
    
    // Méthodes
    
    /**
     * Affiche les informations du dépôt
     */
    public function afficherInfos(): string {
        return "Dépôt: {$this->nom} - Contact: {$this->nomContact} - {$this->telephone}";
    }
    
    /**
     * Retourne l'adresse complète du dépôt
     */
    public function getAdresseComplete(): string {
        return "{$this->adresse}, {$this->ville}";
    }
    
    /**
     * Retourne les coordonnées GPS
     */
    public function getCoordonnees(): array {
        return [
            'latitude' => $this->latitude,
            'longitude' => $this->longitude
        ];
    }
    
    /**
     * Calcule la distance entre deux dépôts (approximation)
     */
    public function calculerDistance(Depot $autrDepot): float {
        $lat1 = deg2rad($this->latitude);
        $lon1 = deg2rad($this->longitude);
        $lat2 = deg2rad($autrDepot->getLatitude());
        $lon2 = deg2rad($autrDepot->getLongitude());
        
        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;
        
        $a = sin($dlat / 2) * sin($dlat / 2) +
             cos($lat1) * cos($lat2) * sin($dlon / 2) * sin($dlon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        
        // Rayon de la terre en km
        $rayon = 6371;
        return $rayon * $c;
    }
    
    /**
     * Retourne les informations en tableau
     */
    public function toArray(): array {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'nomContact' => $this->nomContact,
            'adresse' => $this->adresse,
            'ville' => $this->ville,
            'telephone' => $this->telephone,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude
        ];
    }
    
    /**
     * Vérifie si le numéro de téléphone est valide
     */
    public function validerTelephone(): bool {
        return !empty($this->telephone) && preg_match('/^[0-9\s\-\+\.]+$/', $this->telephone);
    }
    
    /**
     * Compare deux dépôts
     */
    public function equals(Depot $autre): bool {
        return $this->id === $autre->id;
    }
    
    // Méthodes de communication avec d'autres entités
    
    /**
     * Retourne les informations complètes du dépôt
     */
    public function afficherDetailsComplets(): string {
        return "Dépôt: {$this->nom}, Contact: {$this->nomContact}, " .
               "{$this->getAdresseComplete()}, Tel: {$this->telephone}, " .
               "GPS: ({$this->latitude}, {$this->longitude})";
    }
    
    /**
     * Vérifie si le dépôt peut recevoir une livraison
     */
    public function peutRecevoirLivraison(Livraison $livraison): bool {
        // Un dépôt peut toujours recevoir une livraison
        return $livraison->getIdDestinationDepot() == $this->id;
    }
    
    /**
     * Retourne le nombre de véhicules présents dans ce dépôt
     * Note: Nécessite l'accès à VehiculeDAO
     */
    public function obtenirNombreVehicules(): string {
        return "Utilisez VehiculeDAO::getVehiculesByDepot({$this->id}) pour compter les véhicules";
    }
    
    /**
     * Retourne les véhicules actuellement dans ce dépôt
     * Note: Nécessite l'accès à VehiculeDAO
     */
    public function obtenirVehiculesPresents(): string {
        return "Utilisez VehiculeDAO::getVehiculesByDepot({$this->id}) pour récupérer la liste";
    }
    
    /**
     * Retourne les informations pour un trajet départ/arrivée
     */
    public function obtenirInfosTrajet(): array {
        return [
            'depotId' => $this->id,
            'nom' => $this->nom,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'adresse' => $this->getAdresseComplete(),
            'contact' => $this->nomContact,
            'telephone' => $this->telephone
        ];
    }
    
    /**
     * Vérifie si le dépôt est accessible (basé sur la localisation)
     */
    public function estAccessible(): bool {
        return !empty($this->latitude) && !empty($this->longitude) && 
               $this->latitude >= -90 && $this->latitude <= 90 &&
               $this->longitude >= -180 && $this->longitude <= 180;
    }
}