<?php
include_once("C:/xampp/htdocs/Projet 2A25/config.php");


class VehiculeC {
    // List all vehicles
    public function listeVehicules() {
        $db = config::getConnexion(); 
        try {
            $liste = $db->query('SELECT * FROM vehicules');
            return $liste;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    // Delete a vehicle
    public function SupprimerVehicule($id) {
        $db = config::getConnexion();
        try {
            // Supprimer d'abord les locations associées
            $lc = new LocationC();
            $lc->SupprimerLocationsParVehicule($id);
    
            // Ensuite, supprimer le véhicule
            $req = $db->prepare('DELETE FROM vehicules WHERE ID_voiture = :ID_voiture');
            $req->execute(['ID_voiture' => $id]);
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    // Add a new vehicle
    public function AjouterVehicule($vehicule) {
        $db = config::getConnexion();
        try {
            $req = $db->prepare('INSERT INTO vehicules (marque, modele, type, Prix_Location_Jour, statut, ID_Proprietaire) 
                                 VALUES (:marque, :modele, :type, :Prix_Location_Jour, :statut, :ID_Proprietaire)');
            $req->execute([
                'marque' => $vehicule->getMarque(),
                'modele' => $vehicule->getModele(),
                'type' => $vehicule->getType(),
                'Prix_Location_Jour' => $vehicule->getPrixLocationJour(),
                'statut' => $vehicule->getStatut(),
                'ID_Proprietaire' => $vehicule->getIdProprietaire() // Notez la majuscule
            ]);
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }


    // Update vehicle details
    public function ModifierVehicule($id, $vehicule) {
        $db = config::getConnexion(); 
        try {
            $req = $db->prepare('UPDATE vehicules 
                                 SET marque = :marque, modele = :modele, type = :type, Prix_Location_Jour = :Prix_Location_Jour, 
                                     statut = :statut, ID_Proprietaire = :ID_Proprietaire 
                                 WHERE id_Voiture = :id');
            $req->execute([
                'id' => $id,
                'marque' => $vehicule->getMarque(),
                'modele' => $vehicule->getModele(),
                'type' => $vehicule->getType(),
                'Prix_Location_Jour' => $vehicule->getPrixLocationJour(),
                'statut' => $vehicule->getStatut(),
                'ID_Proprietaire' => $vehicule->getIdProprietaire()
            ]);
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }
}
?>
