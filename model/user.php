<?php

class User
{
    private int $id;
    private string $nom;
    private string $prenom;
    private string $mail;
    private string $telephone;
    private string $role;
    private string $motDePasse;

    // Constructeur optionnel
    public function __construct($nom = "", $prenom = "", $mail = "", $telephone = "", $role = "", $motDePasse = "")
    {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->mail = $mail;
        $this->telephone = $telephone;
        $this->role = $role;
        $this->motDePasse = $motDePasse;
    }

    // Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function getMail(): string
    {
        return $this->mail;
    }

    public function getTelephone(): string
    {
        return $this->telephone;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getMotDePasse(): string
    {
        return $this->motDePasse;
    }

    // Setters
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }

    public function setMail(string $mail): void
    {
        $this->mail = $mail;
    }

    public function setTelephone(string $telephone): void
    {
        $this->telephone = $telephone;
    }

    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    public function setMotDePasse(string $motDePasse): void
    {
        $this->motDePasse = $motDePasse;
    }
}
