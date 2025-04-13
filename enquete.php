<?php

    session_start();

    // Connexion à la base de donnée
    require("Config/connexion.php");

    if(isset($_SESSION['id-collecteur']))
    {

        $id_collecteur = $_SESSION['id-collecteur'];


        # Récupération des données du collecteur
        $collecteur_info = mysqli_query($conn,"SELECT * FROM collecteur WHERE id_collecteur = '$id_collecteur'");
        if(mysqli_num_rows($collecteur_info) > 0)
        {
            $collecteur = mysqli_fetch_array($collecteur_info);

            $list_enquete = mysqli_query($conn,"SELECT * FROM enquete WHERE id_enquete = '{$collecteur['id_enquete']}' ");


            # Récupération de l'équipe du collecteur chargé de collecter l'enquête )
            $get_equipe_collecteur = mysqli_query($conn,"SELECT * FROM equipe_collecteur WHERE id_equipe_collecteur = '{$collecteur['id_equipe_collecteur']}' ");

            if(mysqli_num_rows($get_equipe_collecteur) > 0)
            {
                $equipe_collecteur = mysqli_fetch_array($get_equipe_collecteur);
            }

        }


        if(isset($_GET['nom-enquete']) && !empty($_GET['nom-enquete'])) 
        {
            // Récupération du nom et affichage du questionnaire de l'enquête spécifié dans l'URL
            $nom_enquete = mysqli_real_escape_string($conn,$_GET['nom-enquete']);

            // Sélection de l'enquête spécifié via URL dans la table (enquete)
            $get_enquete = mysqli_query($conn,"SELECT * FROM enquete WHERE nom_enquete = '{$nom_enquete}' ");

            if(mysqli_num_rows($get_enquete) > 0)
            {
                # Activation de l'onglet (enquete)
                $enquete_navlink_active = true;

                $enquete = mysqli_fetch_array($get_enquete); 

                # Affichage de la liste des enquêtes à effectuer par le collecteur (Sidebar)
                $list_enquete = mysqli_query($conn,"SELECT * FROM enquete WHERE id_enquete = '{$collecteur['id_enquete']}' ");


            }
            elseif(mysqli_num_rows($get_enquete) == 0)
            {
                header("location:accueil.php");
            }

        }
        elseif(isset($_GET['nom-enquete']) && empty($_GET['nom-enquete']) || !isset($_GET['nom-enquete']))
        {
            // Rédirection de l'utilisateur vers la page (accueil.php)
            header("location:accueil.php");
        }


        // Enregistrement des réponses renseigné par l'utilisateur
        if(isset($_POST['Enregistrer']))
        {

            $user_raison_social = (isset($_POST['user-raison-social'])) ? $_POST['user-raison-social'] : NULL;
            $user_fullname = (isset($_POST['user-fullname'])) ? $_POST['user-fullname'] : NULL;
            // $user_firstname = (isset($_POST['user-firstname'])) ? $_POST['user-firstname'] : NULL;
            $user_direction = (isset($_POST['user-direction'])) ? $_POST['user-direction'] : NULL;
            $user_categorie = (isset($_POST['user-categorie'])) ? $_POST['user-categorie'] : NULL;
            $user_fonction = (isset($_POST['user-fonction'])) ? $_POST['user-fonction'] : NULL;
            $user_matricule = (isset($_POST['user-matricule'])) ? $_POST['user-matricule'] : NULL;
            $user_telephone = (isset($_POST['user-telephone'])) ? $_POST['user-telephone'] : NULL;
            $user_adresse = (isset($_POST['user-adresse'])) ? $_POST['user-adresse'] : NULL;
            $user_domaine = (isset($_POST['user-domaine'])) ? $_POST['user-domaine'] : NULL;
            $user_web_email = (isset($_POST['user-web-email'])) ? $_POST['user-web-email'] : NULL;
            $user_group = (isset($_POST['user-groupe'])) ? $_POST['user-groupe'] : NULL;


            $id_question = $_POST['id-question'];
            $input_value = $_POST['input-value'];


            # ENREGISTREMENT DE LA COLLECTE DU COLLECTEUR
            
            $save_collect = mysqli_query($conn,"INSERT INTO collecter VALUES(NULL,'$id_collecteur','{$enquete['id_enquete']}','{$equipe_collecteur['nom_equipe_collecteur']}',NOW())");

            if($save_collect)
            {

                # PROCESSUS D'ENREGISTREMENT DES RÉPONSES FOURNIES PAR L'UTILISATEUR 
                $save_user_info = mysqli_query($conn,"INSERT INTO utilisateur VALUES(NULL,'$user_raison_social','$user_fullname','$user_telephone','$user_direction','$user_categorie','$user_fonction','$user_matricule','$user_adresse','$user_domaine','$user_web_email','$user_group')");
    
                if($save_user_info)
                {
                    # Récupération de l'ID (id_utilisateur) du dernier enregistrement effectué dans la table (utilisateur)
                    $last_id = mysqli_query($conn,"SELECT MAX(id_utilisateur) AS User_Id FROM utilisateur");
                    
                    if(mysqli_num_rows($last_id) > 0)
                    {
                        $user_id = mysqli_fetch_array($last_id);
    
                        for($i = 0 ; $i < sizeof($id_question); $i++)
                        {

                            if(!empty($input_value[$i]))
                            {

                                $input_value_anti_injection = mysqli_real_escape_string($conn,$input_value[$i]);

                                # Enregistrement de toutes les réponses fournir par l'utilisateur suite à la soumission du formulaire
                                $save_user_response = mysqli_query($conn,"INSERT INTO reponse VALUES(NULL,'{$id_question[$i]}','$input_value_anti_injection',NOW(),'{$user_id['User_Id']}','{$enquete['id_enquete']}')");

                            }

                        }
    
                        if($save_user_response)
                        {
                            $success_message = "Enregistrement réussi avec succès !";
                        }
                    }
                    
                }
                
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
    <title><?= (isset($enquete)) ? $enquete['nom_enquete'] : NULL ?></title>
    <link rel="stylesheet" href="/Projet-PAA/Boostrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/Projet-PAA/css/Sidebar.css?time=<?= time() ?>">
    <link rel="stylesheet" href="/Projet-PAA/css/accueil.css?time=<?= time() ?>">
    <link rel="stylesheet" href="/Projet-PAA/css/enquete.css?time=<?= time() ?>">
    <link rel="stylesheet" href="/Projet-PAA/css/media-query.css?time=<?= time() ?>">
</head>
<body>

    <style>

        h1,h2,h3,h4,h5,h6,span,p,label{
            font-weight:500;
            margin:0;
            padding:0;
        }

        div#app-wrapper{
            padding: 60px 0px;
        }

    </style>

    <div id="app-wrapper">


        <?php // <!-- En tête --> ?>
        <?php require("header.php"); ?>



            <?php // <!-- Sidebar (Bar latérale Gauche) --> ?>
            <?php require("SideBar.php"); ?>


        <?php if(isset($success_message)) : ?>

            <?php // <!-- Notification d'enregistrement --> ?>
            
            <div class="notification-container p-2 ps-2 pe-3 d-flex align-items-center justify-content-center">

                <div class="content-wrapper d-flex">
                    <div class="content mx-2">
                        <i class="fas fa-solid fa-check check bg-white text-success p-1 px-2 py-2 rounded-circle" style="height:30px;width:30px;"></i>
                    </div>
                    <div class="content align-self-center">
                        <span class="text-white" style="font-weight:500;"><?= $success_message ?> </span>
                    </div>
                </div>

                <span class="loading d-block rounded-1" style="height:3px;width:0%;background:#0000008a;position:absolute;bottom:0;left:0px;"></span>

            </div>

        <?php endif; ?>


        
        <?php if(isset($enquete) && !empty($enquete)) : ?>

           
            <div class="enquete-title-container pt-4 py-2 w-100" style="background:#00BCD4;">
                <h3 class="text-center text-white" style="font-size:22px;"><?= $enquete['nom_enquete'] ?></h3>
                <!-- <h3 class="text-center text-white" style="font-size:22px;"> Questionnaire <?= $enquete['nom_enquete'] ?></h3> -->
            </div>


            <?php
        
                $question_list = mysqli_query($conn,"SELECT * FROM question WHERE id_enquete = '{$enquete['id_enquete']}' ");

                $user_group = mysqli_query($conn,"SELECT * FROM groupe_utilisateur WHERE id_enquete = '{$enquete['id_enquete']}' ");

                $liste_direction = mysqli_query($conn,"SELECT * FROM direction");

            ?>


            <?php if(isset($question_list) && mysqli_num_rows($question_list) > 0 && !empty($question_list)) : ?>


                <section id="questionnaire-wrapper" class="px-2">

                    <div class="questionnaire-wrapper position-relative " style="max-width:700px;margin:50px auto;border-radius:5px;border:2px solid gray;padding:10px;box-shadow:0px 0px 5px 1px #03a9f4;">

                        <form action="" method="POST" id="questionnaire-form" class="py-1">
                            
                            <div class="inner-wrapper" style="text-align:left;">
                                    
                                <?php if(isset($user_group) && mysqli_num_rows($user_group) > 0 && !empty($user_group)) : ?>


                                    <?php if($enquete['statut_enquete'] == "Interne" || $enquete['statut_enquete'] == "interne") : ?>

                                        
                                        <p class="my-2 mb-1 alert alert-primary py-1 text-dark w-100" style="font-weight:500;max-width:660px;">
                                            Direction
                                        </p>
                                        
                                        <p class="py-2" style="max-width:428px;">
                                        
                                            <select name="user-direction" id="" style="font-size:17px;" class="form-control w-100">  

                                                <option value="">Sélectionner la Direction</option>
            
                                                <?php while($tab = mysqli_fetch_array($liste_direction)) : ?>
            
                                                    <option value="<?= $tab['id_direction'] ?>"><?= $tab['nom_direction'] ?></option>
                                                
                                                <?php endwhile ; ?>
            
                                            </select>

                                        </p>
                                        
                                        <p class="alert alert-primary py-1 my-1 text-dark w-100" style="max-width:660px;">Groupe utilisateur </p>
                                        
                                        <p class="py-2 pt-1" style="max-width:428px;">
                                            
                                            <select name="user-groupe" id="" style="font-size:17px;" class="form-control w-100" required>

                                                <option value="">Sélectionner le groupe</option>

                                                <?php while($tab = mysqli_fetch_array($user_group)) : ?>
                
                                                    <option value="<?= $tab['id_groupe_utilisateur'] ?>"><?= $tab['nom_groupe_utilisateur'] ?></option>
                                                    
                                                <?php endwhile ; ?>
                
                                            </select>
                                        </p>


                                    <?php elseif($enquete['statut_enquete'] == "Autre") : ?>

                                        <?php 

                                            $get_groupe_utilisateur = mysqli_query($conn,"SELECT * FROM groupe_utilisateur WHERE id_enquete = '{$enquete['id_enquete']}' ");

                                            if(mysqli_num_rows($get_groupe_utilisateur) > 0)
                                            {
                                                $group_utilisateur = mysqli_fetch_array($get_groupe_utilisateur);
                                            }


                                            # Affichage de la liste de toutes les Directions enregistrées dans la base de données
                                            $liste_direction = mysqli_query($conn,"SELECT * FROM direction");

                                        ?>


                                        <?php //<!-- Id groupe utilisateur table (groupe_utilisateur) --> ?>
                                        <input type="hidden" name="user-groupe" value="<?= $group_utilisateur['id_groupe_utilisateur'] ?>">

                                        <h3 class="my-3 text-decoration-underline text-center" style="font-weight:500;color:white;font-size:18px;background:#282b31;padding: 10px 0px;border-radius: 3px;box-shadow:0px 0px 5px 1px #03a9f4;">
                                            INFORMATIONS AGENT
                                        </h3>
                                        

                                        <p class="my-2 mb-1 alert alert-primary py-1 text-dark w-100" style="font-weight:500;max-width:660px;">
                                            Nom
                                        </p>

                                        <p class="py-2" style="max-width:428px;">
                                            <input type="text" name="user-name" placeholder="Entrez votre nom" class="form-control w-100" required>
                                        </p>

                                        <p class="my-2 mb-1 alert alert-primary py-1 text-dark w-100" style="font-weight:500;max-width:660px;">
                                            Prénom 
                                        </p>

                                        <p class="py-2" style="max-width:428px;">
                                            <input type="text" name="user-firstname" placeholder="Entrez votre prénom" class="form-control w-100" required>
                                        </p>

                                        <p class="my-2 mb-1 alert alert-primary py-1 text-dark w-100" style="font-weight:500;max-width:660px;">
                                            Direction
                                        </p>
                                        
                                        <p class="py-2" style="max-width:428px;">
                                        
                                            <select name="user-direction" id="" style="font-size:17px;" class="form-control w-100" required>  

                                                <option value="">Sélectionner la Direction</option>

                                                <?php if(mysqli_num_rows($liste_direction) > 0) : ?>
                
                                                    <?php while($tab = mysqli_fetch_array($liste_direction)) : ?>
                
                                                        <option value="<?= $tab['nom_direction'] ?>"><?= $tab['nom_direction'] ?></option>
                                                    
                                                    <?php endwhile ; ?>

                                                <?php endif; ?>
            
                                            </select>

                                        </p>

                                        <p class="my-2 mb-1 alert alert-primary py-1 text-dark w-100" style="font-weight:500;max-width:660px;">
                                            Fonction 
                                        </p>

                                        <p class="py-2" style="max-width:428px;">
                                            <input type="text" name="user-fonction" placeholder="Entrez votre fonction" class="form-control w-100" required>
                                        </p>

                                        <p class="my-2 mb-1 alert alert-primary py-1 text-dark w-100" style="font-weight:500;max-width:660px;">
                                            Matricule 
                                        </p>

                                        <p class="py-2" style="max-width:428px;">
                                            <input type="tel" maxlength="4" name="user-matricule"  placeholder="Entrez votre numéro de matricule" class="form-control w-100" required>
                                        </p>
                                        
                                        <p class="my-2 mb-1 alert alert-primary py-1 text-dark w-100" style="font-weight:500;max-width:660px;">
                                            Téléphone 
                                        </p>

                                        <p class="py-2" style="max-width:428px;">
                                            <input type="tel" maxlength="10" name="user-telephone" placeholder="Entrez votre numéro de téléphone" class="form-control w-100" required>
                                        </p>
                                        
                                        <p class="my-2 mb-1 alert alert-primary py-1 text-dark w-100" style="font-weight:500;max-width:660px;">
                                            Adresse email 
                                        </p>

                                        <p class="py-2" style="max-width:428px;">
                                            <input type="email" name="user-adresse" placeholder="Entrez votre Adresse email" class="form-control w-100">
                                        </p>


                                    <?php else : ?>

                                        <?php $tab = mysqli_fetch_array($user_group)  ?>

                                        <input type="hidden" name="user-groupe" class="form-control" value="<?= $tab['id_groupe_utilisateur'] ?>" required>

                                        <p class="my-2 alert alert-primary py-1 text-dark w-100" style="font-weight:500;max-width:660px;">
                                            Raison social
                                        </p>

                                        <p class="w-100 my-2" style="max-width:428px;">
                                            <input type="text" name="user-raison-social" style="height:35px;" class="form-control w-100">
                                        </p>

                                        <p class="my-2 alert alert-primary py-1 text-dark w-100" style="font-weight:500;max-width:660px;">
                                            Fonction de l’interviewé
                                        </p>

                                        <p class="w-100" style="max-width:428px;">
                                            <input type="text" name="user-fonction" style="height:35px;" class="form-control">
                                        </p>

                                    <?php endif; ?>


                                <?php endif; ?>

                
                                <?php while($tab = mysqli_fetch_array($question_list)) : ?>

                                    <?php 

                                        // Sélection et affichage des différents grands titre
                                        $title = mysqli_query($conn,"SELECT * FROM choix WHERE id_question = '{$tab['id_question']}' ");

                                        if(mysqli_num_rows($title) > 0)
                                        {
                                            $tableau = mysqli_fetch_array($title);
                                        }
                                        
                                    ?>      

                                    <?php if(isset($tableau) && !empty($tableau)) : ?>


                                        <?php if($tableau['type_choix'] == "Titre") : # GRAND TITRE ?>
                                                
                                            <h3 class="my-3 text-decoration-underline text-center" style="font-weight:500;color:white;font-size:18px;background:#282b31;padding: 10px 0px;border-radius: 3px;box-shadow: 0px 0px 3px 3px #00bcd4;">
                                                <?= $tab['libelle_question'] ?>
                                            </h3>

                                        <?php elseif($tableau['type_choix'] != "Titre") : # LISTE DES QUETIONS ?>
                                        
                                            <input type="hidden" name="id-question[]" value="<?= $tab['id_question'] ?>">
                                            
                                            <p class="my-2 alert alert-primary py-1 text-dark" style="font-weight:500;max-width:660px;line-height:25px;">
                                                <?= $tab['libelle_question'] ?>
                                            </p>
                                                
                                        <?php endif; ?>

                                    <?php endif; ?>

                                                    
                                    <?php 

                                        // Sélection et affichage des différentes réponses liées à chaque questions
                                        $response = mysqli_query($conn,"SELECT * FROM choix WHERE id_question = '{$tab['id_question']}'") 
                                    ?>


                                    <?php  while($tab2 = mysqli_fetch_array($response)) :   ?>

                                        <?php if($tab2['type_choix'] == "Input") : # CHOIX DE TYPE INPUT ?>

                                            <p style="margin-bottom:25px;"><input type="text" name="input-value[]" style="height:35px;width:100%;"></p>

                                        <?php elseif($tab2['type_choix'] == "Radio") : # CHOIX DE TYPE RADIO  ?>
                                            
                                            
                                            <label for="reponse-<?= $tab2['id_choix'] ?>" style="margin-right:20px;cursor:pointer;" class="user-select-none">


                                                <input type="radio" name="input-value[] rb-<?= $tab2['id_question'] ?>" id="reponse-<?= $tab2['id_choix'] ?>" value="<?= trim($tab2['libelle_choix']) ?>" class="form-check-input" required>
                                                
                                                <?= $tab2['libelle_choix'] ?>
                                                
                                            </label>

                                        <?php elseif($tab2['type_choix'] == "Checkbox") : # CHOIX DE TYPE RADIO  ?>
                                            
                                            
                                            <label for="reponse-<?= $tab2['id_choix'] ?>" style="margin-right:20px;cursor:pointer;" class="user-select-none">

                                                <input type="checkbox" name="input-value[]" id="reponse-<?= $tab2['id_choix'] ?>" value="<?= trim($tab2['libelle_choix']) ?>" class="form-check-input " >
                                                
                                                <?= $tab2['libelle_choix'] ?>
                                                
                                            </label>

                                        <?php elseif($tab2['type_choix'] == "Textarea") : # CHOIX DE TYPE TEXTAREA ?>
                                            
                                            <p style="margin-bottom: 20px;">

                                                <textarea name="input-value[]" id="" cols="30" rows="10"  style="max-height:80px;max-width:665px;width:100%;resize:none;font-size:17px;" class="form-control"></textarea>
                                            </p>
                                                
                                        <?php endif;?>

                                                    
                                    <?php endwhile; ?>


                                <?php endwhile; ?>
                                
    
                                <div class="btn-container text-center mt-4 mb-2">
                                    <button type="submit" name="Enregistrer" class="btn btn-success w-100 py-2" style="max-width:320px;">Enregistrer</button>
                                </div>

                            </div>
        
                        </form>

                    </div>

                </section>

            <?php else : ?>

                <h3 class="text-center text-danger my-4" style="font-size:20px;">Aucune question n'a été enregistrée !</h3>

            <?php endif; ?>


        <?php else : ?>

            <h3 class="text-center text-danger" style="font-size:20px;">Aucune question n'a été enregistrée !</h3>

        <?php endif; ?>


    </div>


    <script>

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

    <script src="js/app.js"></script>
    
</body>
</html>