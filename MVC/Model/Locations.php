<?php
class Locations {
    private int $ID_Location;
    private int $ID_Voiture;
    private int $ID_Utilisateur;
    private string $dateDebut;
    private string $dateFin;

    public function __construct(int $ID_Voiture, int $ID_Utilisateur, string $dateDebut, string $dateFin) {
        $this->ID_Voiture = $ID_Voiture;
        $this->ID_Utilisateur = $ID_Utilisateur;
        $this->dateDebut = $dateDebut;
        $this->dateFin = $dateFin;
    }

    // Getters
    public function getIdLocation(): int {
        return $this->ID_Location;
    }

    public function getIdVoiture(): int {
        return $this->ID_Voiture;
    }

    public function getIdUtilisateur(): int {
        return $this->ID_Utilisateur;
    }

    public function getDateDebut(): string {
        return $this->dateDebut;
    }

    public function getDateFin(): string {
        return $this->dateFin;
    }

    // Setters
    public function setIdLocation(int $ID_Location): void {
        $this->ID_Location = $ID_Location;
    }

    public function setIdVoiture(int $ID_Voiture): void {
        $this->ID_Voiture = $ID_Voiture;
    }

    public function setIdUtilisateur(int $ID_Utilisateur): void {
        $this->ID_Utilisateur = $ID_Utilisateur;
    }

    public function setDateDebut(string $dateDebut): void {
        $this->dateDebut = $dateDebut;
    }

    public function setDateFin(string $dateFin): void {
        $this->dateFin = $dateFin;
    }
}
?>