<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Connexion à la base de données
    $conn = new mysqli('localhost', 'root', '', 'nina_bd');

    // Vérifier la connexion
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    // Récupérer les valeurs du formulaire
    $nom = $_POST['nom'];
    $password = $_POST['password'];

    // Vérifier si l'utilisateur est l'administrateur
    if ($nom === 'fofou6 nina' && $password === 'nina12') {
        $_SESSION['user'] = $nom;
        header('Location: admin.php');
        exit();
    }

    // Préparer et exécuter la requête pour vérifier les informations de connexion
    $stmt = $conn->prepare("SELECT * FROM user WHERE nom = ? AND password = ?");
    $stmt->bind_param("ss", $nom, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Les informations de connexion sont correctes, créer une session pour l'utilisateur
        $_SESSION['user'] = $nom;

        // Redirection vers la page de bienvenue
        header('Location: bienvenue.php');
        exit();
    } else {
        // Les informations de connexion sont incorrectes, afficher le message d'erreur
        echo '<div id="modalChoice">
                <div class="modal-dialog" role="document">
                    <div class="modal-content rounded-4 shadow">
                        <div class="modal-body p-4 text-center">
                            <h5 class="mb-0">Erreur de connexion</h5>
                            <p class="mb-0">Nom ou mot de passe incorrect.</p>
                        </div>
                        <div class="modal-footer flex-nowrap p-0">
                            <button type="button" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0 border-right" onclick="closeModal();"><strong>Ok</strong></button>
                        </div>
                    </div>
                </div>
              </div>
              <style>
                  #modalChoice {
                      display: block;
                      position: fixed;
                      z-index: 1050;
                      left: 0;
                      top: 0;
                      width: 100%;
                      height: 100%;
                      overflow: hidden;
                      background-color: rgba(0,0,0,0.5);
                  }
              </style>
              <script>
                  function closeModal() {
                      document.getElementById("modalChoice").style.display = "none";
                      window.location.href = "inscription.html";
                  }
              </script>';
    }

    // Fermer la connexion
    $stmt->close();
    $conn->close();
}
?>
