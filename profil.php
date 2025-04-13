
<?php

    session_start();

    // Connexion à la base de donnée
    require("Config/connexion.php");

    if(isset($_SESSION['id-collecteur']))
    {
        # Activation du lien de navigation ou l'onglet (Profil)
        $profil_navlink_active = true;

        $id_collecteur = $_SESSION['id-collecteur'];

        # Récupération des données du collecteur
        $collecteur_info = mysqli_query($conn,"SELECT * FROM collecteur WHERE id_collecteur = '$id_collecteur'");
        if(mysqli_num_rows($collecteur_info) > 0)
        {
            $collecteur = mysqli_fetch_array($collecteur_info);

            $list_enquete = mysqli_query($conn,"SELECT * FROM enquete WHERE id_enquete = '{$collecteur['id_enquete']}' ");

        }

        if(isset($_POST['Modifier-Info']))
        {

            $collecteur_name = mysqli_real_escape_string($conn,$_POST['collecteur-name']) ;
            $collecteur_firstname = mysqli_real_escape_string($conn,$_POST['collecteur-firstname']) ;
            $collecteur_email = mysqli_real_escape_string($conn,$_POST['collecteur-email']) ;
            $collecteur_username = mysqli_real_escape_string($conn,$_POST['collecteur-username']);

            $update = mysqli_query($conn,"UPDATE collecteur SET nom_collecteur = '$collecteur_name' , prenom_collecteur = '$collecteur_firstname' , email_collecteur = '$collecteur_email' , nom_utilisateur_collecteur = '$collecteur_username' WHERE id_collecteur = '$id_collecteur' ");

            if($update)
            {
                $_SESSION['success-notification'] = "Modification réussie avec succès !";

                $collecteur_info = mysqli_query($conn,"SELECT * FROM collecteur WHERE id_collecteur = '$id_collecteur'");

                if(mysqli_num_rows($collecteur_info) > 0)
                {
                    $collecteur = mysqli_fetch_array($collecteur_info);
                }

            }

        }

        if(isset($_POST['Modifier-Password']))
        {

            $collecteur_password = mysqli_real_escape_string($conn,$_POST['collecteur-password']);
            $collecteur_new_password = mysqli_real_escape_string($conn,$_POST['collecteur-new-password']);
            $collecteur_password_confirmation = mysqli_real_escape_string($conn,$_POST['collecteur-password-confirmation']);


            if($collecteur_new_password == $collecteur_password_confirmation && password_verify($collecteur_password,$collecteur['password_collecteur']))
            {

                $password_hash = password_hash($collecteur_new_password,PASSWORD_DEFAULT);

                $update = mysqli_query($conn,"UPDATE collecteur SET password_collecteur = '$password_hash' WHERE id_collecteur = '$id_collecteur' ");
    
                if($update)
                {
                    $_SESSION['success-notification'] = "Modification réussie avec succès !";

                    $collecteur_info = mysqli_query($conn,"SELECT * FROM collecteur WHERE id_collecteur = '$id_collecteur'");
                    
                    if(mysqli_num_rows($collecteur_info) > 0)
                    {
                        $collecteur = mysqli_fetch_array($collecteur_info);
                    }
                }

            }
            else if(!password_verify($collecteur_password,$collecteur['password_collecteur']))
            {
                $_SESSION['error-notification'] = "Ancien mot de passe incorrect !";
            }
            else if(password_verify($collecteur_password,$collecteur['password_collecteur']) && $collecteur_new_password != $collecteur_password_confirmation)
            {
                $_SESSION['error-notification'] = "Mot de passe incompatible !";
            }

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
    <title>Mon Profil</title>
    <link rel="stylesheet" href="Boostrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/Sidebar.css?time=<?= time() ?>">
    <link rel="stylesheet" href="css/accueil.css?time=<?= time() ?>">
    <link rel="stylesheet" href="css/profil.css?time=<?= time() ?>">
    <link rel="stylesheet" href="css/media-query.css?time=<?= time() ?>">
</head>
<body>

    <style>
        h1,h2,h3,h4,h5,h6,span,p,label{
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


        <div class="enquete-title-container pt-4 py-2 w-100" style="background:#00BCD4;">
            <h3 class="text-center text-white" style="font-size:22px;">Mon profil</h3>
        </div>

        <div class="user-img-container text-center pt-4 mb-3">
            <i class="fas fa-user-large text-white rounded-circle" style="font-size:35px;padding:25px;background:#00BCD4;border:2px solid black;"></i>
        </div>


        <?php if(isset($_SESSION['success-notification'])) : ?>

            <?php // <!-- Message de Notification --> ?>
            <div class="notification-container p-2 ps-2 pe-3 d-flex align-items-center justify-content-center">

                <div class="content-wrapper d-flex">
                    <div class="content mx-2">
                        <i class="fas fa-solid fa-check check bg-white text-success p-1 px-2 py-2 rounded-circle" style="height:30px;width:30px;"></i>
                    </div>
                    <div class="content align-self-center">
                        <span class="text-white" style="font-weight:500;"><?= $_SESSION['success-notification'] ?></span>
                    </div>
                </div>

                <span class="loading d-block rounded-1" style="height:3px;width:0%;background:#0000008a;position:absolute;bottom:0;left:0px;"></span>

            </div>

        <?php endif; unset($_SESSION['success-notification']);  ?>


        <?php if(isset($_SESSION['error-notification'])) : ?>

            <?php // <!-- Message de Notification --> ?>
            <div class="notification-container p-2 ps-2 pe-3 d-flex align-items-center justify-content-center bg-danger">

                <div class="content-wrapper d-flex">
                    <div class="content mx-2">
                        <i class="fas fa-solid fa-check check bg-white text-danger p-1 px-2 py-2 rounded-circle" style="height:30px;width:30px;"></i>
                    </div>
                    <div class="content align-self-center">
                        <span class="text-white" style="font-weight:500;"><?= $_SESSION['error-notification'] ?></span>
                    </div>
                </div>

                <span class="loading d-block rounded-1" style="height:3px;width:0%;background:#0000008a;position:absolute;bottom:0;left:0px;"></span>

            </div>

        <?php endif; unset($_SESSION['error-notification']);  ?>
        
        
        <div class="collecteur-profil-wrapper">

            <form action="" method="POST" class="w-100 d-flex flex-column justify-content-between">

                <div class="wrapper">

                    <div class="input-wrapper flex-column">
                        <div class="input-container me-3">
                            <label for="nom">Nom</label>
                            <p><input type="text" id="nom" name="collecteur-name" placeholder="Nom"  value="<?= (isset($collecteur)) ? $collecteur['nom_collecteur'] : NULL ?>" pattern="[A-Za-z]{1,255}" required></p>
                        </div>
                        <div class="input-container">
                            <label for="prenom">Prénom</label>
                            <p><input type="text" id="prenom" name="collecteur-firstname" placeholder="Prénom" value="<?= (isset($collecteur)) ? $collecteur['prenom_collecteur'] : NULL ?>" pattern="[A-Za-z ]{1,255}" required></p>
                        </div>
                    </div>
    
                    <div class="input-wrapper flex-column">
                        <div class="input-container">
                            <label for="username">Nom d'utilisateur</label>
                            <p><input type="text" id="username" name="collecteur-username" placeholder="exemple123" value="<?= (isset($collecteur)) ? $collecteur['nom_utilisateur_collecteur'] : NULL ?>" pattern="[A-Za-z]{1,255}.[0-9]{1,5}" required></p>
                        </div>
                        <div class="input-container me-3">
                            <label for="email">Adresse electronique</label>
                            <p><input type="email" id="email" name="collecteur-email" placeholder="exemple@mail.com" value="<?= (isset($collecteur)) ? $collecteur['email_collecteur'] : NULL ?>" pattern="^([0-9]{10})|([A-Za-z0-9._%\+\-]+@[a-z0-9.\-]+\.[a-z]{2,3})$" required></p>
                        </div>
                    </div>

                </div>

                <div class="btn-container text-center my-3 mx-auto">
                    <button type="submit" name="Modifier-Info" class="btn btn-success w-100 py-2">Modifier</button>
                </div>

            </form>

            <form action="" method="POST" class="w-100 d-flex flex-column justify-content-between">

                <div class="wrapper">

                    <div class="input-wrapper flex-column">
                        <div class="input-container me-3">
                            <label for="old-password">Mot de passe</label>
                            <p><input type="password" id="old-password" name="collecteur-password" placeholder="Mot de passe" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required></p>
                        </div>
                        <div class="input-container">
                            <label for="new-password">Nouveau mot de passe</label>
                            <p><input type="password" id="new-password" name="collecteur-new-password" placeholder="Nouveau mot de passe" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required></p>
                        </div>
                    </div>
                    
                    <div class="input-wrapper flex-column">
                        <div class="input-container me-3">
                            <label for="password-confirmation">Confirmer le mot de passe</label>
                            <p><input type="password" id="password-confirmation" name="collecteur-password-confirmation" placeholder="Confirmer le mot de passe" class="" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required></p>
                        </div>
                        <div class="input-container">
                        </div>
                    </div>

                </div>

                <div class="btn-container text-center my-3 mx-auto">
                    <button type="submit" name="Modifier-Password" class="btn btn-success w-100 py-2">Modifier</button>
                </div>

            </form>

        </div>

    </div>


    <script>

        // Activation du message de notification
        if(document.querySelector("div.notification-container"))
        {
            
            setTimeout(() => {
    
                document.querySelector("div.notification-container").classList.add("active")
                
                let counter = 100 
    
                let handler = setInterval(() => {
    
                    counter--;
                    document.querySelector("span.loading").style.width = `${counter}%`;
                    if(counter == 0)
                    {
                        clearInterval(handler)
                        document.querySelector("div.notification-container").classList.remove("active")
                    }
                    
                }, 35);
    
            }, 100);

        }

    </script>
    
    <script src="js/app.js?time=<?= time() ?>"></script>
    
</body>
</html>