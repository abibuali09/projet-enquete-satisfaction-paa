<?php

    session_start();

    require("Config/connexion.php");


    if(isset($_POST['Confirmer']))
    {

        $code_confirmation = $_POST['code-confirmation'];

        $request1 = mysqli_query($conn,"SELECT * FROM collecteur WHERE code_reinitialisation_collecteur = '$code_confirmation'");
        
        if(mysqli_num_rows($request1) > 0)
        {
            $collecteur = mysqli_fetch_array($request1);
        }

        $request2 = mysqli_query($conn,"SELECT * FROM administrateur WHERE code_reinitialisation_administrateur = '$code_confirmation'");

        if(mysqli_num_rows($request2) > 0)
        {
            $administrateur = mysqli_fetch_array($request2);
        }
        
        if(empty($administrateur) && empty($collecteur))
        {
            $message_error = "Code de confirmation erroné"; 
        }

        if(!empty($administrateur) && $administrateur['code_reinitialisation_administrateur'] == $code_confirmation || !empty($collecteur) && $collecteur['code_reinitialisation_collecteur'] == $code_confirmation)
        {
            $_SESSION['code-confirmation'] = $code_confirmation;
            header("location:edit-password.php");
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
    <link rel="stylesheet" href="css/forget-password.css?time=<?= time() ?>">
</head>
<body>
    
    <style>
        body{
            height:100vh;
        }
    </style>

    <main class="body-wrapper" style="padding:20px 20px 10px;background:url('/Projet-PAA/Images/0295.jpg') center/cover no-repeat;height:100%;"> 

        <div class="login-wrapper shadow rounded-1 py-4 px-2">
            <form action="#" method="POST" class="code-confirmation-form position-relative">

            
                <!-- <p class="verification-message" style="background:red;">Adresse electronique inexistante !</p> -->

                <?php if(isset($message_error)) : ?>
                    <p class="verification-message" style="background:red;"><?= $message_error ?></p>
                <?php endif; ?>

                <div class="title-container mx-auto mt-2">
                    <h3 class="text-center" style="color:#0A89B0;font-size:19px;">Entrez le code de réinitialisation
                    </h3>
                </div>

                <div class="input-container mx-auto">
                    <input type="text" name="code-confirmation" class="rounded-1 d-block mx-auto w-100 text-center" id="code-confirmation" placeholder="Code de confirmation" pattern="^[0-9]{5}$" maxlength="5" style="<?= (isset($message_error)) ? "border:2px solid red;" : NULL ?>">
                </div>

                <div class="confirmation-btn-container py-1 mt-5 ">
                    <button type="submit" name="Confirmer" class="login-btn btn btn-success d-block mx-auto py-2 w-100" style="max-width:350px;">Confirmer</button>
                </div>

                <div class="cancel-btn-container py-1 mt-1 ">
                    <button class="login-btn btn btn-primary d-block mx-auto py-2 w-100" style="max-width:350px;"><a href="forget-password.php" class="text-decoration-none text-white d-block w-100">Annuler</a></button>
                </div>

            </form>
        </div>

    </main>

    <script src="js/login.js?time=<?= time() ?>"></script>

</body>
</html>