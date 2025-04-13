
<?php

    session_start();

    require("Config/connexion.php");


    if(isset($_SESSION['id-collecteur']))
    {
        header("location:accueil.php");
    }

    if(isset($_SESSION['id-administrateur']))
    {
        header("location:admin/accueil.php");
    }

    if(isset($_SESSION['id-responsable']))
    {
        header("location:responsable/accueil.php");
    }
        
    if(isset($_POST['Connexion']))
    {

        // Récupération du nom et du mot de passe de l'utilisateur
        $username = $_POST['username'];
        $user_password = $_POST['user-password'];


        // Vérification de l'existance du nom de l'utilisateur dans la base de donnée
        $request = mysqli_query($conn,"SELECT * FROM collecteur WHERE nom_utilisateur_collecteur = '$username'");
        
        if(mysqli_num_rows($request) > 0)
        {
            $collecteur = mysqli_fetch_array($request); 
        }

        $request2 = mysqli_query($conn,"SELECT * FROM administrateur WHERE nom_utilisateur_administrateur = '$username'");
        if(mysqli_num_rows($request2) > 0)
        {
            $administrateur = mysqli_fetch_array($request2); 
        }

        $request3 = mysqli_query($conn,"SELECT * FROM responsable WHERE nom_utilisateur_responsable = '$username'");
        if(mysqli_num_rows($request3) > 0)
        {
            $responsable = mysqli_fetch_array($request3); 
        }


        // Vérification de l'existance du nom de l'utilisateur (collecteur,administrateur,responsable)
        if(empty($administrateur) && empty($collecteur) && empty($responsable))
        {
            $username_error = $username;
            $message_error = "Nom d'utilisateur inexistant !";
        }


        // Vérification du mot de passe de l'utilisateur (collecteur,administrateur,responsable)
        if(!empty($collecteur['nom_utilisateur_collecteur']) && $username === $collecteur['nom_utilisateur_collecteur'] && !password_verify($user_password,$collecteur['password_collecteur']) || !empty($administrateur['nom_utilisateur_administrateur']) && $username === $administrateur['nom_utilisateur_administrateur'] && !password_verify($user_password,$administrateur['password_administrateur']) || !empty($responsable['nom_utilisateur_responsable']) && $username === $responsable['nom_utilisateur_responsable'] && !password_verify($user_password,$responsable['password_responsable']) )
        {
            $username_success = $username;
            $password_err = true;
            $message_error = "Mot de passe incorrect !";
        }


        // Rédirection du collecteur vers la page d'accueil suite à la validation de l'authentification
        if(!empty($collecteur['nom_utilisateur_collecteur']) && $username === $collecteur['nom_utilisateur_collecteur'] && password_verify($user_password,$collecteur['password_collecteur']))
        {

            // Récupération de l'identifiant du collecteur dans une variable de SESSION
            $_SESSION['id-collecteur'] = $collecteur['id_collecteur']; 

            // Message de validation
            $message_success = "Connexion réussie avec succès !";

            $username_success = $username;
            $password_success = $user_password;

            // Rédirection vers la page (Accueil.php)
            header("location:accueil.php");

        }

        // Rédirection de l'administrateur vers la page d'accueil suite à la validation de l'authentification
        if(!empty($administrateur['nom_utilisateur_administrateur']) && $username === $administrateur['nom_utilisateur_administrateur'] && password_verify($user_password,$administrateur['password_administrateur']))
        {

            // Récupération de l'identifiant de l'administrateur dans une variable de SESSION
            $_SESSION['id-administrateur'] = $administrateur['id_administrateur']; 

            // Message de validation
            $message_success = "Connexion réussie avec succès !";

            $username_success = $username;
            $password_success = $user_password;

            // Rédirection vers la page (Accueil.php)
            header("location:admin/accueil.php");

        }

        // Rédirection du responsable vers la page d'accueil suite à la validation de l'authentification
        if(!empty($responsable['nom_utilisateur_responsable']) && $username === $responsable['nom_utilisateur_responsable'] && password_verify($user_password,$responsable['password_responsable']))
        {

            // Récupération de l'identifiant du responsable dans une variable de SESSION
            $_SESSION['id-responsable'] = $responsable['id_responsable']; 

            // Message de validation
            $message_success = "Connexion réussie avec succès !";

            $username_success = $username;
            $password_success = $user_password;

            // Rédirection vers la page (Accueil.php)
            header("location:responsable/accueil.php");

        }

      

    }

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Port Autonome d'Abidjan</title>
    <link rel="stylesheet" href="/Projet-PAA/Boostrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/Projet-PAA/css/login.css?time=<?= time() ?>">
</head>
<body>

    <style>

        body{
            height:100vh;
            background:url('/Projet-PAA/Images/0295.jpg') center/cover no-repeat;
        }

        h1,h2,h3,h4,h5,h6,span,p{
            font-weight:500;
            margin:0;
            padding:0;
        }
        
    </style>

    <div class="app-wrapper" style="height:100%;">

        <main class="body-wrapper"> 

            <div class="login-wrapper shadow rounded-1 py-4 px-2">

                <form action="" method="POST" class="login-form position-relative">

                    <?php if(isset($message_success)) : ?>
                        <p class="verification-message" style="background:green;"><?= $message_success ?></p>
                    <?php endif; ?>

                    <?php if(isset($message_error)) : ?>
                        <p class="verification-message" style="background:red;"><?= $message_error ?></p>
                    <?php endif; ?>


                    <div class="logo-container text-center">
                        <img src="Images/Logo-port-autonome-abidjan.png" alt="" width="130" height="130">
                    </div>
                    <div class="title-container my-2 mb-4">
                        <h3 class="text-center" style="color:#0192cc;">Connexion</h3>
                    </div>

                    <div class="input-container py-2 mx-3 position-relative">

                        <input type="text" name="username" placeholder="Nom d'utilisateur" id="username" class="w-100 py-1 px-2 rounded-1 d-block mx-auto" value="<?php if(isset($username_success)) echo $username_success; if(isset($username_error)) echo $username_error;  ?>" style="<?= (isset($username_error)) ? "border:2px solid red;" : NULL ?> <?= (isset($password_err)) ? "border:2px solid rgb(0, 188, 212);" : NULL ?>"  required>

                        <img src="Images/Male User.png" alt="User Icon" height="35" class="user-icon">

                    </div>

                    <div class="input-container py-2 mx-3 position-relative">

                        <input type="password"  name="user-password" placeholder="Mot de passe" id="user-password" class="w-100 py-1 px-2 rounded-1 d-block mx-auto" style="<?= (isset($password_err)) ? "border:2px solid red;" : NULL ?>"  required>

                        <img src="Images/Lock.png" alt="User Icon" height="32" class="lock-icon" style="cursor:pointer;">


                    </div>

                    <div class="forget-password-container mx-3">
                        <a href="forget-password.php" class="d-block text-end text-danger" style="font-weight:500;text-decoration:underline;text-underline-position:under;">Mot de passe oublié !</a>
                    </div>

                    <div class="login-btn-container py-1 mt-2 ">
                        <button type="submit" name="Connexion" class="login-btn btn btn-primary d-block mx-auto py-2 w-100" style="max-width:350px;">Se connecter</button>
                    </div>
                </form>
            </div>

        </main>

    </div>

    <script src="js/login.js?time=<?= time() ?>"></script>

</body>
</html>