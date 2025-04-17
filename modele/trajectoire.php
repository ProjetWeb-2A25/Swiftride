<?php

class trajectory
{
    private $ID_T;
    private $ville_D;
    private $ville_A;
    private $date_D;
    private $distance;
    private $statue;
    private $temps_est;

    
    public function __construct($ville_D, $ville_A, $distance, $statue, DateTime $temps_est)
    {
        $this->ville_D = $ville_D;
        $this->ville_A = $ville_A;
        $this->distance = $distance;
        $this->statue = $statue;
        $this->temps_est = $temps_est;
    }

    
    public function getID_T()
    {
        return $this->ID_T;
    }

    public function getVille_D()
    {
        return $this->ville_D;
    }

    public function getVille_A()
    {
        return $this->ville_A;
    }

    public function getDate_D()
{
    return $this->date_D instanceof DateTime ? $this->date_D->format('Y-m-d') : null;
}

    public function getDistance()
    {
        return $this->distance;
    }

    public function getStatue()
    {
        return $this->statue;
    }

    public function getTemps_est()
{
    return $this->temps_est instanceof DateTime ? $this->temps_est->format('H:i:s') : null;
}


    // Setters
    public function setID_T($ID_T)
    {
        $this->ID_T = $ID_T;
    }

    public function setVille_D($ville_D)
    {
        $this->ville_D = $ville_D;
    }

    public function setVille_A($ville_A)
    {
        $this->ville_A = $ville_A;
    }

    public function setDate_D(DateTime $date_D)
    {
        $this->date_D = $date_D;
    }

    public function setDistance($distance)
    {
        $this->distance = $distance;
    }

    public function setStatue($statue)
    {
        $this->statue = $statue;
    }

    public function setTemps_est(DateTime $temps_est)
    {
        $this->temps_est = $temps_est;
    }
}
