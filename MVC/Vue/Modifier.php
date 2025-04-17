<?php
// On inclut les fichiers nécessaires
include '../Controller/PersonneC.php';
include '../Model/Personne.php';

// On crée une instance du contrôleur
$pc = new PersonneC();

// Variable pour stocker la personne à modifier (si trouvée)
$person = null;

// === 1. Cas où l'utilisateur cherche une personne par ID ===
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['chercher_id'])) {
    $id = $_POST['chercher_id'];
    $liste = $pc->listePersonne();

    // On parcourt la liste pour trouver la personne avec cet ID
    foreach ($liste as $p) {
        if ($p['id'] == $id) {
            $person = $p;
            break;
        }
    }
}

// === 2. Cas où l'utilisateur a modifié une personne ===
if ($_SERVER["REQUEST_METHOD"] == "POST" 
    && isset($_POST['id'], $_POST['nom'], $_POST['prenom'], $_POST['age']) 
    && !isset($_POST['chercher_id'])) {
    
    // On crée un nouvel objet Personne avec les valeurs modifiées
    $pModifiee = new Personne($_POST['nom'], $_POST['prenom'], $_POST['age']);
    
    // On utilise le contrôleur pour appliquer la modification
    $pc->ModifierPersonne($_POST['id'], $pModifiee);
    
    // On redirige vers la liste après modification
    header('Location:ListePersonne.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier une personne</title>
</head>
<body>

    <h1>Modifier une personne</h1>

    <!-- Formulaire pour rechercher une personne par ID -->
    <form method="POST" action="Modifier.php">
        <label for="chercher_id">Entrez l'ID de la personne à modifier :</label>
        <input type="number" name="chercher_id" required>
        <input type="submit" value="Chercher">
    </form>

    <br>

    <!-- Si une personne a été trouvée avec l'ID fourni -->
    <?php if ($person) { ?>
        <form method="POST" action="Modifier.php">
            <!-- Champ caché pour garder l'ID de la personne -->
            <input type="hidden" name="id" value="<?= $person['id']; ?>">

            <!-- Champs pré-remplis avec les anciennes valeurs -->
            <label>Nom :</label>
            <input type="text" name="nom" value="<?= $person['nom']; ?>" required><br>

            <label>Prénom :</label>
            <input type="text" name="prenom" value="<?= $person['prenom']; ?>" required><br>

            <label>Âge :</label>
            <input type="number" name="age" value="<?= $person['age']; ?>" required><br>

            <input type="submit" value="Mettre à jour">
        </form>

    <!-- Si un ID a été soumis mais aucune personne trouvée -->
    <?php } elseif (isset($_POST['chercher_id'])) { ?>
        <p style="color:red;">Aucune personne trouvée avec cet ID.</p>
    <?php } ?>

    <br><br>
    <!-- Lien pour retourner à la liste -->
    <a href="ListePersonne.php">⬅️ Retour à la liste des personnes</a>

</body>
</html>
