<?php

    session_start();

    require("Config/connexion.php");

    // Importation de la bibliothèque de gestion d'envoi des mails
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';

    // Soumission du formulaire du fichier (forget-password.php) pour la réinitialisation du mot de passe de l'utilisateur
    if(isset($_POST['Confirmer']))
    {

        $_SESSION['user-email'] = $user_email = $_POST['user-email'];
    
        $request1 = mysqli_query($conn,"SELECT * FROM collecteur WHERE email_collecteur = '$user_email'");
        if(mysqli_num_rows($request1) > 0)
        { 
            $collecteur = mysqli_fetch_array($request1);
        }

        $request2 = mysqli_query($conn,"SELECT * FROM administrateur WHERE email_administrateur = '$user_email'");
    
        if(mysqli_num_rows($request2) > 0)
        {
            $administrateur = mysqli_fetch_array($request2);
        }
        
    
        $request3 = mysqli_query($conn,"SELECT * FROM responsable WHERE email_responsable = '$user_email'");
    
        if(mysqli_num_rows($request3) > 0)
        {
            $responsable = mysqli_fetch_array($request3);
        }
        

        if(empty($collecteur) && empty($administrateur) && empty($responsable))
        {
            // Récupération du message d'erreur dans une variable de SESSION
            $_SESSION['invalid-email'] = "Adresse electronique inexistante !";
            header("location:forget-password.php");
        }


        if(!empty($administrateur) && $administrateur['email_administrateur'] == $user_email)
        {
            // Génération du code de réinitialisation à 5 chiffres
            $random_code = rand(10000,99000);

            try 
            {
                # Configuration des paramètres du serveur SMPT
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'abibdev2000@gmail.com';
                $mail->Password = 'mtwfjjuienvruqql';
                $mail->Port = 587;     # (tls port)
                # $mail->Port = 465;  # (ssl port)
                # $mail->SMTPSecure = 'ssl';
                $mail->SMTPSecure = 'tls';  # (TLS) est plus sûr et sécurisé que (SSL)
                $mail->isHTML(true);

                # Configuration des informations de l'expéditeur et du destinataire 
                $mail->setFrom("abibdev2000@gmail.com","PAA (Port autonome d'Abidjan)");
                $mail->addAddress($user_email,"Administrateur");

                # Configuration du contenu du message à envoyé 
                $mail->Subject = ("Code de réinitialisation");
                $mail->CharSet = 'UTF-8';
                $mail->Body = "Votre code de réinitialisation est : ".$random_code;
                $mail->send();
    
                // Mise à jour du code de réinitialisation dans la table collecteur
                $save = mysqli_query($conn,"UPDATE administrateur SET code_reinitialisation_administrateur = '$random_code' WHERE email_administrateur = '$user_email' ");
                if($save)
                {
                    header("location:code-confirmation.php");
                }

            }
            catch(Exception $e)
            {
                // echo "Une erreur s'est produite ".$e->getMessage();
                $_SESSION['invalid-email']  = "Désolé, un problème s'est produit !";
                header("location:forget-password.php");
            }
        }

        if(!empty($collecteur) && $collecteur['email_collecteur'] == $user_email)
        {
            // Génération du code de réinitialisation à 5 chiffres
            $random_code = rand(10000,99000);

            try 
            {
                # Configuration des paramètres du serveur SMPT
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'abibdev2000@gmail.com';
                $mail->Password = 'mtwfjjuienvruqql';
                $mail->Port = 587;     # (tls port)
                # $mail->Port = 465;  # (ssl port)
                # $mail->SMTPSecure = 'ssl';
                $mail->SMTPSecure = 'tls';  # (TLS) est plus sûr et sécurisé que (SSL)
                $mail->isHTML(true);

                # Configuration des informations de l'expéditeur et du destinataire 
                $mail->setFrom("abibdev2000@gmail.com","PAA (Port autonome d'Abidjan)");
                $mail->addAddress($user_email,"Collecteur");

                # Configuration du contenu du message à envoyé 
                $mail->Subject = ("Code de réinitialisation");
                $mail->CharSet = 'UTF-8';
                $mail->Body = "Votre code de réinitialisation est : ".$random_code;
                $mail->send();
    
                // Mise à jour du code de réinitialisation dans la table collecteur
                $save = mysqli_query($conn,"UPDATE collecteur SET code_reinitialisation_collecteur = '$random_code' WHERE email_collecteur = '$user_email' ");
                if($save)
                {
                    header("location:code-confirmation.php");
                }

            }
            catch(Exception $e)
            {
                // echo "Une erreur s'est produite ".$e->getMessage();
                $_SESSION['invalid-email']  = "Désolé, un problème s'est produit !";
                header("location:forget-password.php");
            }
        }

        if(!empty($responsable) && $responsable['email_responsable'] == $user_email)
        {
            // Génération du code de réinitialisation à 5 chiffres
            $random_code = rand(10000,99000);

            try 
            {
                # Configuration des paramètres du serveur SMPT
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'abibdev2000@gmail.com';
                $mail->Password = 'mtwfjjuienvruqql';
                $mail->Port = 587;     # (tls port)
                # $mail->Port = 465;  # (ssl port)
                # $mail->SMTPSecure = 'ssl';
                $mail->SMTPSecure = 'tls';  # (TLS) est plus sûr et sécurisé que (SSL)
                $mail->isHTML(true);

                # Configuration des informations de l'expéditeur et du destinataire 
                $mail->setFrom("abibdev2000@gmail.com","PAA (Port autonome d'Abidjan)");
                $mail->addAddress($user_email,"Responsable");

                # Configuration du contenu du message à envoyé 
                $mail->Subject = ("Code de réinitialisation");
                $mail->CharSet = 'UTF-8';
                $mail->Body = "Votre code de réinitialisation est : ".$random_code;
                $mail->send();
    
                // Mise à jour du code de réinitialisation dans la table collecteur
                $save = mysqli_query($conn,"UPDATE responsable SET code_reinitialisation_responsable = '$random_code' WHERE email_responsable = '$user_email' ");
                if($save)
                {
                    header("location:code-confirmation.php");
                }

            }
            catch(Exception $e)
            {
                // echo "Une erreur s'est produite ".$e->getMessage();
                $_SESSION['invalid-email']  = "Désolé, un problème est survenu !";
                header("location:forget-password.php");
            }
        }


    }
    else
    {
        header("location:forget-password.php");
    }


?>
