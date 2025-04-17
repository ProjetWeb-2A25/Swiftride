<?php
include '../Controller/VehiculeC.php';
include '../Model/Vehicules.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nom'], $_POST['prenom'], $_POST['age'])) {
    $pc = new PersonneC();
    $p = new Personne($_POST['nom'], $_POST['prenom'], $_POST['age']);
    $pc->AjouterPersonne($p);
    header('Location:ListePersonne.php');
    exit(); // Bonne pratique pour éviter que le reste de la page s'affiche après redirection
}
?>

<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=<, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action ="Add.php" method ="POST"> 
        <input type="text" name ="nom" placeholder ="Entrer votre nom" required>
        <input type="text" name ="prenom" placeholder ="Entrer votre prenom" required>
        <input type="text" name ="age" placeholder ="Entrer votre age" required>
        <input type="submit" value="Save">
    </form>
</body>
</html>
