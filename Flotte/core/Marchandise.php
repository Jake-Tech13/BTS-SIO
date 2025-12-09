<?php
class Marchandise {
    private ?int $id;
    private string $nom;
    private string $numUn;
    private string $classeDanger;
    private string $etat;
    private string $consignesManipulation;
    private string $restrictionsTransport;
    private string $dateCreation;
    
    // Getters
    public function getId(): ?int {
        return $this->id;
    }
    public function getNom(): string {
        return $this->nom;
    }
    public function getNumUn(): string {
        return $this->numUn;
    }
    public function getClasseDanger(): string {
        return $this->classeDanger;
    }
    public function getEtat(): string {
        return $this->etat;
    }
    public function getConsignesManipulation(): string {
        return $this->consignesManipulation;
    }
    public function getRestrictionsTransport(): string {
        return $this->restrictionsTransport;
    }
    public function getDateCreation(): string {
        return $this->dateCreation;
    }
    
    // Setters
    public function setId(?int $id): void {
        $this->id = $id;
    }
    public function setNom(string $nom): void {
        $this->nom = $nom;
    }
    public function setNumUn(string $numUn): void {
        $this->numUn = $numUn;
    }
    public function setClasseDanger(string $classeDanger): void {
        $this->classeDanger = $classeDanger;
    }
    public function setEtat(string $etat): void {
        $this->etat = $etat;
    }
    public function setConsignesManipulation(string $consignesManipulation): void {
        $this->consignesManipulation = $consignesManipulation;
    }
    public function setRestrictionsTransport(string $restrictionsTransport): void {
        $this->restrictionsTransport = $restrictionsTransport;
    }
    public function setDateCreation(string $dateCreation): void {
        $this->dateCreation = $dateCreation;
    }
    
    // Constructeur
    public function __construct(string $nom, string $numUn, string $classeDanger, string $etat, string $consignesManipulation, string $restrictionsTransport) {
        $this->id = null;
        $this->nom = $nom;
        $this->numUn = $numUn;
        $this->classeDanger = $classeDanger;
        $this->etat = $etat;
        $this->consignesManipulation = $consignesManipulation;
        $this->restrictionsTransport = $restrictionsTransport;
        $this->dateCreation = date('Y-m-d H:i:s');
    }
    
    // Méthodes
    
    /**
     * Affiche les informations de la marchandise
     */
    public function afficherInfos(): string {
        return "Marchandise: {$this->nom} - Classe: {$this->classeDanger} - État: {$this->etat}";
    }
    
    /**
     * Vérifie si la marchandise est dangereuse
     */
    public function estDangereuse(): bool {
        return !empty($this->classeDanger) && $this->classeDanger !== 'non_classee';
    }
    
    /**
     * Vérifie si la marchandise nécessite des restrictions
     */
    public function aDesRestrictions(): bool {
        return !empty($this->restrictionsTransport);
    }
    
    /**
     * Change l'état de la marchandise
     */
    public function changerEtat(string $nouvelEtat): void {
        $etats = ['solide', 'liquide', 'gaz'];
        if (in_array($nouvelEtat, $etats)) {
            $this->etat = $nouvelEtat;
        }
    }
    
    /**
     * Retourne les informations en tableau
     */
    public function toArray(): array {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'numUn' => $this->numUn,
            'classeDanger' => $this->classeDanger,
            'etat' => $this->etat,
            'consignesManipulation' => $this->consignesManipulation,
            'restrictionsTransport' => $this->restrictionsTransport,
            'dateCreation' => $this->dateCreation
        ];
    }
    
    /**
     * Valide les données de la marchandise
     */
    public function validerDonnees(): bool {
        return !empty($this->nom) && 
               !empty($this->etat) &&
               in_array($this->etat, ['solide', 'liquide', 'gaz']);
    }
    
    /**
     * Compare deux marchandises
     */
    public function equals(Marchandise $autre): bool {
        return $this->id === $autre->id;
    }
    
    // Méthodes de communication avec d'autres entités
    
    /**
     * Retourne les détails complets de la marchandise
     */
    public function afficherDetailsComplets(): string {
        return "Marchandise: {$this->nom}, Classe: {$this->classeDanger}, " .
               "État: {$this->etat}, Num ONU: {$this->numUn}, " .
               "Créée le: {$this->dateCreation}";
    }
    
    /**
     * Retourne les consignes de manipulation
     */
    public function afficherConsignes(): string {
        if (empty($this->consignesManipulation)) {
            return "Aucune consigne spéciale";
        }
        return "Consignes: {$this->consignesManipulation}";
    }
    
    /**
     * Retourne les restrictions de transport
     */
    public function afficherRestrictions(): string {
        if (empty($this->restrictionsTransport)) {
            return "Aucune restriction";
        }
        return "Restrictions: {$this->restrictionsTransport}";
    }
    
    /**
     * Retourne les livraisons contenant cette marchandise
     * Note: Nécessite l'accès à LivraisonDAO
     */
    public function obtenirLivraisons(): string {
        return "Utilisez LivraisonDAO::getLivraisonsByMarchandise({$this->id})";
    }
    
    /**
     * Vérifie si la marchandise requiert un permis spécial
     */
    public function requiertPermisSpecial(): bool {
        return $this->estDangereuse() || !empty($this->restrictionsTransport);
    }
    
    /**
     * Retourne les informations pour l'étiquetage
     */
    public function obtenirInfosEtiquetage(): array {
        return [
            'nom' => $this->nom,
            'numUN' => $this->numUn,
            'classe' => $this->classeDanger,
            'etat' => $this->etat,
            'dangereux' => $this->estDangereuse(),
            'consignes' => $this->consignesManipulation,
            'restrictions' => $this->restrictionsTransport
        ];
    }
    
    /**
     * Vérifie si cette marchandise est compatible avec une autre
     */
    public function estCompatibleAvec(Marchandise $autre): bool {
        // Les marchandises dangereuses de classes différentes peuvent ne pas être compatibles
        if ($this->estDangereuse() && $autre->estDangereuse()) {
            return $this->classeDanger === $autre->classeDanger;
        }
        return true;
    }
}