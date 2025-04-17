<?php
class Vehicules {
    private int $ID_Voiture; // Assurez-vous que l'ID est cohérent avec la déclaration
    private string $marque;
    private string $modele;
    private string $type;
    private float $prixLocationJour;
    private string $statut;
    private int $ID_Proprietaire;

    public function __construct(string $marque, string $modele, string $type, float $prixLocationJour, string $statut, int $idProprietaire) {
        $this->marque = $marque;
        $this->modele = $modele;
        $this->type = $type;
        $this->prixLocationJour = $prixLocationJour;
        $this->statut = $statut;
        $this->ID_Proprietaire = $idProprietaire;
    }

    // Getters
    public function getIdVoiture(): int {
        return $this->ID_Voiture;  // Assurez-vous que vous utilisez le nom correct de la variable
    }

    public function getMarque(): string {
        return $this->marque;
    }

    public function getModele(): string {
        return $this->modele;
    }

    public function getType(): string {
        return $this->type;
    }

    public function getPrixLocationJour(): float {
        return $this->prixLocationJour;
    }

    public function getStatut(): string {
        return $this->statut;
    }

    public function getIdProprietaire(): int {
        return $this->ID_Proprietaire;
    }
    // Setters
    public function setId(int $ID_Voiture): void {
        $this->ID_Voiture = $ID_Voiture;  // Assurez-vous que vous utilisez le nom correct de la variable
    }

    public function setMarque(string $marque): void {
        $this->marque = $marque;
    }

    public function setModele(string $modele): void {
        $this->modele = $modele;
    }

    public function setType(string $type): void {
        $this->type = $type;
    }

    public function setPrixLocationJour(float $prix): void {
        $this->prixLocationJour = $prix;
    }

    public function setStatut(string $statut): void {
        $this->statut = $statut;
    }

    public function setIdProprietaire(int $id): void {
        $this->ID_Proprietaire = $id;
    }
}

?>