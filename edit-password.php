

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


    $user_email = (isset($_SESSION['user-email'])) ? $_SESSION['user-email'] : NULL ;
    $code_confirmation = (isset($_SESSION['code-confirmation'])) ? $_SESSION['code-confirmation'] : NULL ;


    if(isset($_POST['Confirmer']))
    {

        $new_password = $_POST['new-password'];
        $password_confirmation = $_POST['password-confirmation'];

        # Vérification de la conformité des mots de passe de réinitialisation
        if($new_password == $password_confirmation)
        {

            $hash_password = password_hash($new_password,PASSWORD_DEFAULT);


            $verify_admin = mysqli_query($conn,"SELECT * FROM administrateur WHERE code_reinitialisation_administrateur = '$code_confirmation' ");
            if(mysqli_num_rows($verify_admin))
            {
                $admin = mysqli_fetch_array($verify_admin);
            }

            $verify_collecteur = mysqli_query($conn,"SELECT * FROM collecteur WHERE code_reinitialisation_collecteur = '$code_confirmation' ");
            if(mysqli_num_rows($verify_collecteur))
            {
                $collecteur = mysqli_fetch_array($verify_collecteur);
            }

            if(isset($admin) && !empty($admin) && $admin['code_reinitialisation_administrateur'] == $code_confirmation)
            {
                
                // Mise à jour du mot de passe de l'utilisateur
                $save = mysqli_query($conn,"UPDATE administrateur SET password_administrateur = '$hash_password' WHERE email_administrateur = '$user_email'");
                if($save)
                {
                    header("location:confirmation.php");
                }
                
            }
            
            if(isset($collecteur) && !empty($collecteur) && $collecteur['code_reinitialisation_collecteur'] == $code_confirmation)
            {
                
                // Mise à jour du mot de passe de l'utilisateur
                $save = mysqli_query($conn,"UPDATE collecteur SET password_collecteur = '$hash_password' WHERE email_collecteur = '$user_email'");
                if($save)
                {
                    header("location:confirmation.php");
                }

            }
            
        }
        else
        {
            $message_error = "Mot de passe incompatible !";
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
    <link rel="stylesheet" href="Boostrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/edit-password.css?time=<?= time() ?>">
</head>
<body>

    <style>
        body{
            height:100vh;
        }
    </style>


    <main class="body-wrapper" style="padding:20px 20px 10px;background:url('/Projet-PAA/Images/0295.jpg') center/cover no-repeat;height:100%;"> 

        <div class="login-wrapper shadow rounded-1 py-4 px-2">
            <form action="#" method="POST" class="edit-password-form position-relative">

                <?php if(isset($message_error)) : ?>
                    <p class="verification-message" style="background:red;"><?= $message_error ?></p>
                <?php endif; ?>

                <div class="title-container mx-auto mt-2">
                    <h3 class="text-center" style="color:#0A89B0;font-size:19px;">Modification du mot de passe
                    </h3>
                </div>

                <div class="input-container mx-auto">
                    <input type="password" name="new-password" class="rounded-1 d-block mx-auto w-100" id="new-password" placeholder="Nouveau mot de passe" style="<?= (isset($message_error)) ? "border:2px solid red;" : NULL ?>" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required>
                </div>

                <div class="input-container mx-auto">
                    <input type="password" name="password-confirmation" class="rounded-1 d-block mx-auto w-100" id="password-confirmation" placeholder="Confirmer le mot de passe" style="<?= (isset($message_error)) ? "border:2px solid red;" : NULL ?>" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required >
                </div>

                <div class="confirmation-btn-container py-1 mt-5 ">
                    <button type="submit" name="Confirmer" class="login-btn btn btn-success d-block mx-auto py-2 w-100" style="max-width:350px;">Confirmer</button>
                </div>

                <div class="cancel-btn-container py-1 mt-1">
                    <button class="login-btn btn btn-primary d-block mx-auto py-2 w-100" style="max-width:350px;"><a href="code-confirmation.php" class="text-decoration-none text-white d-block w-100">Annuler</a></button>
                </div>

            </form>
        </div>

    </main>

    <script src="js/login.js?time=<?= time() ?>"></script>

</body>
</html>