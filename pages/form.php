<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = $_FILES['image']['name'];
    $target_dir = "uploads/";

    // Assurez-vous que le répertoire existe
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $target_file = $target_dir . basename($image);

    // Connexion à la base de données
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "nina_bd";
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Téléchargement de l'image
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        echo "The file " . htmlspecialchars(basename($image)) . " has been uploaded.";
    } else {
        die("Sorry, there was an error uploading your file.");
    }

    // Insertion dans la table appropriée
    $sql = "";
    $page_path = "";

    switch ($title) {
        case 'bijoux':
            $sql = "INSERT INTO bijoux (title, description, price, image_path) VALUES ('$title', '$description', '$price', '$target_file')";
            $page_path = "bijoux.html";
            break;
        case 'perruques':
            $sql = "INSERT INTO perruque (title, description, price, image_path) VALUES ('$title', '$description', '$price', '$target_file')";
            $page_path = "perruques.html";
            break;
        case 'pijamas':
            $sql = "INSERT INTO pijamas (title, description, price, image_path) VALUES ('$title', '$description', '$price', '$target_file')";
            $page_path = "pijamas.html";
            break;
        case 'pantoufles':
            $sql = "INSERT INTO pantoufles (title, description, price, image_path) VALUES ('$title', '$description', '$price', '$target_file')";
            $page_path = "pantoufles.html";
            break;
        case 'sousvetements':
            $sql = "INSERT INTO sous_vetements (title, description, price, image_path) VALUES ('$title', '$description', '$price', '$target_file')";
            $page_path = "souvetement.html";
            break;
        default:
            die("Erreur: le titre spécifié ne correspond à aucune table.");
    }

    if (!empty($sql)) {
        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully";

            // Assurez-vous que la page existe
            if (!file_exists($page_path)) {
                die("La page $page_path n'existe pas.");
            }

            // Créer le contenu de la publication
            $publicationContent = "
                <div class=\"col\">
                    <img src='$target_file' alt='$title' width='300'>
                    <div class=\"card-body\">
                        <p class=\"card-text\">$description</p>
                        <div class=\"d-flex justify-content-between align-items-center\">
                            <div class=\"btn-group\">
                            <button type=\"button\" class=\"btn btn-sm btn-outline-secondary\" onclick=\"window.location.href='https://wa.me/237653709282';\"><i class=\"fa-brands fa-whatsapp fa-lg\" style=\"color: #63E6BE;\"></i> Commander</button>
                                <button type=\"button\" class=\"btn btn-sm btn-outline-secondary\">Liker</button>
                            </div>
                            <small class=\"text-muted\">prix: $price fcfa</small>
                        </div>
                    </div>
                </div>";

            // Lire le contenu existant de la page
            $pageContent = file_get_contents($page_path);

            // Insérer le nouveau contenu de la publication avant la balise de fermeture </div> de la classe container
            $pageContent = str_replace("</div><!-- End of container class -->", $publicationContent . "\n</div><!-- End of container class -->", $pageContent);

            // Écrire le contenu mis à jour dans la page
            file_put_contents($page_path, $pageContent);

            // Rediriger vers la page appropriée
            header("Location: $page_path");
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    $conn->close();
}
?>
