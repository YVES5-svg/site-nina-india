<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Vérifier que tous les champs sont remplis
    if (empty($_POST['nom']) || empty($_POST['mail']) || empty($_POST['password']) || empty($_POST['confirmPassword'])) {
        echo 'Tous les champs sont obligatoires.';
        exit();
    }

    // Vérifier que les mots de passe correspondent
    if ($_POST['password'] !== $_POST['confirmPassword']) {
        echo 'Les mots de passe ne correspondent pas.';
        exit();
    }

    // Connexion à la base de données
    $conn = new mysqli('localhost', 'root', '', 'nina_bd');

    // Vérifier la connexion
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    // Récupérer les valeurs du formulaire
    $nom = $_POST['nom'];
    $mail = $_POST['mail'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hacher le mot de passe

    // Vérifier si l'e-mail est déjà utilisé
    $stmt = $conn->prepare("SELECT id FROM user WHERE mail = ?");
    $stmt->bind_param("s", $mail);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo 'Cet e-mail est déjà utilisé.';
        exit();
    }

    // Préparer et lier pour l'insertion
    $stmt = $conn->prepare("INSERT INTO user (nom, mail, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nom, $mail, $password);

    // Exécuter la requête
    if ($stmt->execute()) {
        // Créer une session pour l'utilisateur
        $_SESSION['user'] = $nom;

        // Redirection vers la page de connexion
        header('Location: inscription.html');
        exit();
    } else {
        echo 'Erreur: ' . $stmt->error;
    }

    // Fermer la connexion
    $stmt->close();
    $conn->close();
}
?>
