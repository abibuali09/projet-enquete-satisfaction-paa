<?php

    session_start();

    // Connexion à la base de donnée
    require("Config/connexion.php");

    if(isset($_SESSION['id-collecteur']))
    {
        $accueil_navlink_active = true;

        $id_collecteur = $_SESSION['id-collecteur'];

        # Récupération des données du collecteur
        $collecteur_info = mysqli_query($conn,"SELECT * FROM collecteur WHERE id_collecteur = '$id_collecteur'");
        if(mysqli_num_rows($collecteur_info) > 0)
        {
            $collecteur = mysqli_fetch_array($collecteur_info);

            $list_enquete = mysqli_query($conn,"SELECT * FROM enquete WHERE id_enquete = '{$collecteur['id_enquete']}' ");
            

        }

        
        
    }
    else
    {
        header("location:login.php");
    }

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link rel="stylesheet" href="/Projet-PAA/Boostrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/Projet-PAA/css/Sidebar.css?time=<?= time() ?>">
    <link rel="stylesheet" href="/Projet-PAA/css/accueil.css?time=<?= time() ?>">
    <link rel="stylesheet" href="/Projet-PAA/css/media-query.css?time=<?= time() ?>">
</head>
<body>

    <style>
        h1,h2,h3,h4,h5,h6,span,p{
            font-weight:500;
            margin:0;
            padding:0;
        }

    </style>

    <div id="app-wrapper">

        <!-- En tête -->
        <?php require("header.php"); ?>

        <!-- Sidebar (Bar latérale Gauche) -->
        <?php require("SideBar.php"); ?>

        

        <div class="user-img-container text-center pt-5 mb-2">
            <i class="fas fa-user-large text-white rounded-circle" style="font-size:35px;padding:25px;background:#00BCD4;border:2px solid black;"></i>
        </div>

        <div class="title-container text-center my-2">
            <h3 class="title-1">Collecteur : <span style="color:#00BCD4;">N°00<?= (isset($id_collecteur)) ? $id_collecteur : "1" ?></span></h3>
            <h3 class="title-2 mt-3">Bienvenue dans votre espace membre collecteur</h3>
        </div>

    </div>


    <script src="js/app.js"></script>

</body>
</html>