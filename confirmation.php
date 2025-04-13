<?php

    session_start();   

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
    <link rel="stylesheet" href="/Projet-PAA/css/confirmation.css?time=<?= time() ?>">
</head>
<body>

    <style>
        body{
            height:100vh;
        }
    </style>

    <main class="body-wrapper" style="padding:20px 20px 10px;background:url('/Projet-PAA/Images/0295.jpg') center/cover no-repeat;height:100%;"> 

        <div class="login-wrapper shadow rounded-1 py-4 px-2">
            <form action="/Projet-PAA/login.php" method="POST" class="login-form">

                <div class="title-container mx-auto mt-2">
                    <h3 class="text-center" style="color:#0DB11D;font-size:19px;">Félicitation votre mot de passe à été 
                        modifié avec succès !
                    </h3>
                </div>

                <div class="img-container text-center">
                    <img src="/Projet-PAA/Images/Checkmark.png" alt="check-icon" class="text-center">
                </div>

                <div class="login-btn-container py-1 mt-5 ">
                    <button type="submit" class="login-btn btn btn-success d-block mx-auto py-2 w-100" style="max-width:350px;">TERMINER</button>
                </div>

            </form>
        </div>

    </main>

</body>
</html>