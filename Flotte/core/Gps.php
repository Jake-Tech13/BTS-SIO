<?php
class Gps {
    private ?int $id;
    private string $horodatage;
    private float $latitude;
    private float $longitude;
    private float $vitesseKmh;
    private float $cap;
    
    // Getters
    public function getId(): ?int {
        return $this->id;
    }
    public function getHorodatage(): string {
        return $this->horodatage;
    }
    public function getLatitude(): float {
        return $this->latitude;
    }
    public function getLongitude(): float {
        return $this->longitude;
    }
    public function getVitesseKmh(): float {
        return $this->vitesseKmh;
    }
    public function getCap(): float {
        return $this->cap;
    }
    
    // Setters
    public function setId(?int $id): void {
        $this->id = $id;
    }
    public function setHorodatage(string $horodatage): void {
        $this->horodatage = $horodatage;
    }
    public function setLatitude(float $latitude): void {
        $this->latitude = $latitude;
    }
    public function setLongitude(float $longitude): void {
        $this->longitude = $longitude;
    }
    public function setVitesseKmh(float $vitesseKmh): void {
        $this->vitesseKmh = $vitesseKmh;
    }
    public function setCap(float $cap): void {
        $this->cap = $cap;
    }
    
    // Constructeur
    public function __construct(string $horodatage, float $latitude, float $longitude, float $vitesseKmh, float $cap) {
        $this->id = null;
        $this->horodatage = $horodatage;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->vitesseKmh = $vitesseKmh;
        $this->cap = $cap;
    }
    
    // Méthodes
    
    /**
     * Affiche les informations GPS
     */
    public function afficherInfos(): string {
        return "Position: ({$this->latitude}, {$this->longitude}) - Vitesse: {$this->vitesseKmh}km/h - Cap: {$this->cap}°";
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
     * Calcule la distance entre deux positions (en km)
     */
    public function calculerDistance(Gps $autrePosition): float {
        $lat1 = deg2rad($this->latitude);
        $lon1 = deg2rad($this->longitude);
        $lat2 = deg2rad($autrePosition->getLatitude());
        $lon2 = deg2rad($autrePosition->getLongitude());
        
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
     * Vérifie si le véhicule est en mouvement
     */
    public function estEnMouvement(): bool {
        return $this->vitesseKmh > 0;
    }
    
    /**
     * Retourne les informations en tableau
     */
    public function toArray(): array {
        return [
            'id' => $this->id,
            'horodatage' => $this->horodatage,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'vitesseKmh' => $this->vitesseKmh,
            'cap' => $this->cap
        ];
    }
    
    /**
     * Valide les coordonnées GPS
     */
    public function validerCoordonnees(): bool {
        return $this->latitude >= -90 && $this->latitude <= 90 &&
               $this->longitude >= -180 && $this->longitude <= 180;
    }
    
    /**
     * Compare deux positions GPS
     */
    public function equals(Gps $autre): bool {
        return $this->id === $autre->id;
    }
    
    // Méthodes de communication avec d'autres entités
    
    /**
     * Retourne les détails complets de la position
     */
    public function afficherDetailsComplets(): string {
        $mouvement = $this->estEnMouvement() ? "en mouvement" : "arrêté";
        return "Position GPS: ({$this->latitude}, {$this->longitude}), " .
               "Horodatage: {$this->horodatage}, Vitesse: {$this->vitesseKmh}km/h, " .
               "Cap: {$this->cap}°, Statut: {$mouvement}";
    }
    
    /**
     * Retourne le trajet associé à cette position
     * Note: Nécessite l'accès à TrajetDAO
     */
    public function obtenirTrajet(): string {
        return "Utilisez TrajetDAO::getTrajetByGps({$this->id})";
    }
    
    /**
     * Retourne les points de passage entre deux positions
     */
    public static function obtenirEtapes(Gps $depart, Gps $arrivee): array {
        return [
            'depart' => $depart->getCoordonnees(),
            'arrivee' => $arrivee->getCoordonnees(),
            'distance' => $depart->calculerDistance($arrivee),
            'unites' => 'km'
        ];
    }
    
    /**
     * Vérifie si le véhicule se rapproche d'une destination
     */
    public function serapproche(Gps $destination, float $toleranceKm = 5.0): bool {
        $distance = $this->calculerDistance($destination);
        return $distance <= $toleranceKm;
    }
    
    /**
     * Retourne la direction en degrés
     */
    public function obtenirDirection(): string {
        $directions = ['N', 'NNE', 'NE', 'ENE', 'E', 'ESE', 'SE', 'SSE', 'S', 'SSO', 'SO', 'OSO', 'O', 'ONO', 'NO', 'NNO'];
        $index = round(($this->cap % 360) / 22.5);
        return $directions[$index % 16] ?? 'N';
    }
    
    /**
     * Retourne les informations de suivi en temps réel
     */
    public function obtenirInfosSuivi(): array {
        return [
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'horodatage' => $this->horodatage,
            'vitesse' => $this->vitesseKmh,
            'cap' => $this->cap,
            'direction' => $this->obtenirDirection(),
            'mouvement' => $this->estEnMouvement() ? 'en mouvement' : 'arrêté'
        ];
    }
    
    /**
     * Calcule l'ETA (Estimated Time of Arrival) vers une destination
     */
    public function calculerETA(Gps $destination): string {
        $distance = $this->calculerDistance($destination);
        if ($this->vitesseKmh <= 0) {
            return "Impossible à calculer (véhicule arrêté)";
        }
        $heures = $distance / $this->vitesseKmh;
        $temps = time() + ($heures * 3600);
        return date('H:i:s', $temps);
    }
}