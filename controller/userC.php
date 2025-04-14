<?php
require_once 'C:/xampp/htdocs/Projet-web-2a25/config/config.php';
require_once 'C:/xampp/htdocs/Projet-web-2a25/model/user.php';

class UserController {
    private $db;

    public function __construct() {
        $this->db = config::getConnexion();
    }

    public function addUser($firstname, $lastname, $email, $phone, $role, $password) {
        try {
            $query = $this->db->prepare(
                "INSERT INTO user (nom, prenom, mail, telephone, role, mot_de_passe) 
                 VALUES (?, ?, ?, ?, ?, ?)"
            );
            
            return $query->execute([
                $lastname,
                $firstname,
                $email,
                $phone,
                $role,
                $password
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getUserByEmailAndPassword($email, $password) {
        try {
            // Afficher les valeurs reçues pour débogage
            error_log("Email reçu: " . $email);
            error_log("Password reçu: " . $password);

            // Vérifier d'abord si l'utilisateur existe
            $checkQuery = $this->db->prepare("SELECT * FROM user WHERE mail = ?");
            $checkQuery->execute([$email]);
            $user = $checkQuery->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                error_log("Utilisateur trouvé avec cet email");
                error_log("Mot de passe en base: " . $user['mot_de_passe']);
                error_log("Mot de passe fourni: " . $password);
            } else {
                error_log("Aucun utilisateur trouvé avec cet email");
            }

            // Requête principale
            $query = $this->db->prepare(
                "SELECT nom as lastname, prenom as firstname, mail as email, 
                        telephone as phone, role 
                 FROM user 
                 WHERE mail = ? AND `mot de passe` = ?"
            );
            
            $query->execute([$email, $password]);
            $result = $query->fetch(PDO::FETCH_ASSOC);

            error_log("Résultat de la requête: " . print_r($result, true));
            return $result;

        } catch (PDOException $e) {
            error_log("Erreur PDO: " . $e->getMessage());
            return false;
        }
    }

    public function updateUser($email, $data) {
        try {
            $query = $this->db->prepare(
                "UPDATE user 
                 SET nom = ?, prenom = ?, telephone = ?, role = ? 
                 WHERE mail = ?"
            );
            error_log('Mise à jour avec les données: ' . print_r([
                $data['lastname'],
                $data['firstname'],
                $data['phone'],
                $data['role'],
                $email
            ], true));
            
            return $query->execute([
                $data['lastname'],
                $data['firstname'],
                $data['phone'],
                $data['role'],
                $email
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }
}

?>