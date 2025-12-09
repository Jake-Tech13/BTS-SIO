<?php
class Facture {
    private ?int $id;
    private int $idLivraison;
    private float $montantHt;
    private float $tva;
    private string $statut;
    private string $dateEmission;
    private string $datePaiement;
    private string $referenceExterne;
    
    // Getters
    public function getId(): ?int {
        return $this->id;
    }
    public function getIdLivraison(): int {
        return $this->idLivraison;
    }
    public function getMontantHt(): float {
        return $this->montantHt;
    }
    public function getTva(): float {
        return $this->tva;
    }
    public function getStatut(): string {
        return $this->statut;
    }
    public function getDateEmission(): string {
        return $this->dateEmission;
    }
    public function getDatePaiement(): string {
        return $this->datePaiement;
    }
    public function getReferenceExterne(): string {
        return $this->referenceExterne;
    }
    
    // Setters
    public function setId(?int $id): void {
        $this->id = $id;
    }
    public function setIdLivraison(int $idLivraison): void {
        $this->idLivraison = $idLivraison;
    }
    public function setMontantHt(float $montantHt): void {
        $this->montantHt = $montantHt;
    }
    public function setTva(float $tva): void {
        $this->tva = $tva;
    }
    public function setStatut(string $statut): void {
        $this->statut = $statut;
    }
    public function setDateEmission(string $dateEmission): void {
        $this->dateEmission = $dateEmission;
    }
    public function setDatePaiement(string $datePaiement): void {
        $this->datePaiement = $datePaiement;
    }
    public function setReferenceExterne(string $referenceExterne): void {
        $this->referenceExterne = $referenceExterne;
    }
    
    // Constructeur
    public function __construct(int $idLivraison, float $montantHt, float $tva, string $statut, string $referenceExterne) {
        $this->id = null;
        $this->idLivraison = $idLivraison;
        $this->montantHt = $montantHt;
        $this->tva = $tva;
        $this->statut = $statut;
        $this->dateEmission = "";
        $this->datePaiement = "";
        $this->referenceExterne = $referenceExterne;
    }
    
    // Méthodes
    
    /**
     * Affiche les informations de la facture
     */
    public function afficherInfos(): string {
        return "Facture: {$this->referenceExterne} - Montant HT: {$this->montantHt}€ - Statut: {$this->statut}";
    }
    
    /**
     * Calcule le montant TTC
     */
    public function getMontantTTC(): float {
        $tauxTVA = $this->tva / 100;
        return $this->montantHt * (1 + $tauxTVA);
    }
    
    /**
     * Calcule le montant de la TVA
     */
    public function getMontantTVA(): float {
        $tauxTVA = $this->tva / 100;
        return $this->montantHt * $tauxTVA;
    }
    
    /**
     * Marque la facture comme payée
     */
    public function marquerCommePayee(): void {
        if ($this->statut === 'emise' || $this->statut === 'impayee') {
            $this->statut = 'payee';
            $this->datePaiement = date('Y-m-d H:i:s');
        }
    }
    
    /**
     * Vérifie si la facture est payée
     */
    public function estPayee(): bool {
        return $this->statut === 'payee';
    }
    
    /**
     * Change le statut de la facture
     */
    public function changerStatut(string $nouveauStatut): void {
        $statuts = ['emise', 'payee', 'impayee'];
        if (in_array($nouveauStatut, $statuts)) {
            $this->statut = $nouveauStatut;
        }
    }
    
    /**
     * Retourne les informations en tableau
     */
    public function toArray(): array {
        return [
            'id' => $this->id,
            'idLivraison' => $this->idLivraison,
            'montantHt' => $this->montantHt,
            'tva' => $this->tva,
            'montantTTC' => $this->getMontantTTC(),
            'statut' => $this->statut,
            'dateEmission' => $this->dateEmission,
            'datePaiement' => $this->datePaiement,
            'referenceExterne' => $this->referenceExterne
        ];
    }
    
    /**
     * Compare deux factures
     */
    public function equals(Facture $autre): bool {
        return $this->id === $autre->id;
    }
    
    // Méthodes de communication avec d'autres entités
    
    /**
     * Retourne un récapitulatif complet de la facture
     */
    public function afficherRecapitulatif(): string {
        $montantTTC = $this->getMontantTTC();
        $montantTVA = $this->getMontantTVA();
        return "Facture {$this->referenceExterne}: " .
               "HT={$this->montantHt}€, TVA({$this->tva}%)={$montantTVA}€, TTC={$montantTTC}€, Statut={$this->statut}";
    }
    
    /**
     * Crée une facture pour une livraison donnée
     */
    public static function creerPourLivraison(Livraison $livraison, string $referenceExterne, float $tauxTVA = 20.0): Facture {
        $montantHt = ($livraison->getPoidsKg() * 0.5) + ($livraison->getVolumeM3() * 10);
        $facture = new Facture(
            $livraison->getId() ?? 0,
            $montantHt,
            $tauxTVA,
            'emise',
            $referenceExterne
        );
        $facture->dateEmission = date('Y-m-d H:i:s');
        return $facture;
    }
    
    /**
     * Retourne le montant restant dû
     */
    public function getMontantDu(): float {
        if ($this->estPayee()) {
            return 0;
        }
        return $this->getMontantTTC();
    }
    
    /**
     * Retourne les jours restants avant expiration (exemple: 30 jours)
     */
    public function joursRestants(int $delaiJours = 30): int {
        $dateEmission = strtotime($this->dateEmission);
        $dateExpiration = $dateEmission + ($delaiJours * 24 * 60 * 60);
        $joursRestants = ceil(($dateExpiration - time()) / (24 * 60 * 60));
        return max(0, $joursRestants);
    }
    
    /**
     * Vérifie si la facture est impayée
     */
    public function estImpayee(): bool {
        return $this->statut === 'impayee';
    }
}