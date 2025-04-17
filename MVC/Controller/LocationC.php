<?php
include_once("C:/xampp/htdocs/Projet 2A25/config.php");


class LocationC {
    // List all locations
    public function listeLocations() {
        $db = config::getConnexion();
        try {
            $liste = $db->query('SELECT * FROM locations');
            return $liste;
        } catch (Exception $e) {
            throw new Exception('Erreur lors de la récupération des locations : ' . $e->getMessage());
        }
    }

    // Delete locations associated with a vehicle
    public function SupprimerLocationsParVehicule($idVehicule) {
        $db = config::getConnexion();
        try {
            $req = $db->prepare('DELETE FROM locations WHERE ID_Voiture = :id');
            $req->execute(['id' => $idVehicule]);
        } catch (Exception $e) {
            throw new Exception('Erreur lors de la suppression des locations : ' . $e->getMessage());
        }
    }

    // Add a new location
    public function AjouterLocation($location) {
        $db = config::getConnexion();
        try {
            $req = $db->prepare('INSERT INTO locations (ID_Voiture, ID_Utilisateur, Date_Debut, Date_Fin)
                                 VALUES (:ID_Voiture, :ID_Utilisateur, :Date_Debut, :Date_Fin)');
            $req->execute([
                'ID_Voiture' => $location->getIdVoiture(),
                'ID_Utilisateur' => $location->getIdUtilisateur(),
                'Date_Debut' => $location->getDateDebut(),
                'Date_Fin' => $location->getDateFin()
            ]);
        } catch (Exception $e) {
            throw new Exception('Erreur lors de l\'insertion de la location : ' . $e->getMessage());
        }
    }

    // Update location details
    public function ModifierLocation($id, $ID_Voiture, $ID_Utilisateur, $Date_Debut, $Date_Fin) {
        $db = config::getConnexion();
        try {
            $req = $db->prepare('UPDATE locations 
                                 SET ID_Voiture = :ID_Voiture, 
                                     ID_Utilisateur = :ID_Utilisateur, 
                                     Date_Debut = :Date_Debut, 
                                     Date_Fin = :Date_Fin
                                 WHERE ID_Location = :ID_Location');
            $req->execute([
                'ID_Location' => $id,
                'ID_Voiture' => $ID_Voiture,
                'ID_Utilisateur' => $ID_Utilisateur,
                'Date_Debut' => $Date_Debut,
                'Date_Fin' => $Date_Fin
            ]);
        } catch (Exception $e) {
            throw new Exception('Erreur lors de la modification de la location : ' . $e->getMessage());
        }
    }
}
?>