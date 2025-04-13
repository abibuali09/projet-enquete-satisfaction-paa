

<nav class="sidebar" style="z-index:2;">

    <div class="logo-container py-2 text-center">
        <img src="Images/Logo-port-autonome-abidjan.png" alt="PAA logo" width="75" height="75" >
    </div>

    <div class="navlink-wrapper mt-3">
        
        <div class="navlink-container my-2">
            <ul>
                <li><a href="accueil.php" class="text-white text-decoration-none"  style="<?= (isset($accueil_navlink_active) && $accueil_navlink_active == true) ? "background:#03a9f4;" : NULL ?>"><i class="fa-solid fa-house me-2"></i>Accueil</a></li>

                <li><a href="profil.php" class="text-white text-decoration-none" style="<?= (isset($profil_navlink_active) && $profil_navlink_active == true) ? "background:#03a9f4;" : NULL ?>"><i class="fa-solid fa-user me-2"></i> Mon Profil</a></li>
                

                <?php if(isset($list_enquete) && mysqli_num_rows($list_enquete) > 0 && !empty($list_enquete)) : ?>

                    <li class="list-enquete <?= (isset($enquete_navlink_active)) ? "active" : NULL ?>" >
                    
                        <a href="#" class="enquete-navlink enquete-link text-white text-decoration-none" ><i class="fa-solid fa-book" style="margin-right:10px;font-size:20px;"></i> Enquêtes <i class="fas fa-angle-up arrow-top-bottom"></i> </a>

                        <div class="enquete-list-container">

                            <?php while($tab = mysqli_fetch_array($list_enquete)) : ?>

                                <p>
                                    <a href="enquete.php?nom-enquete=<?= $tab['nom_enquete'] ?>" class="text-white text-decoration-none" style="<?= (isset($_GET['nom-enquete']) && !empty($_GET['nom-enquete']) && $_GET['nom-enquete'] == $tab['nom_enquete']) ? "background:#03a9f4;" : NULL ?>">
                                    
                                        <?= $tab['nom_enquete'] ?>
                                
                                    </a>
                                </p>

                            <?php endwhile; ?>
                        </div>

                    </li>

                <?php endif; ?>

            </ul>
        </div>

        <div class="logout-container">
            <a href="logout.php" class="text-white text-decoration-none d-block w-100" style="background:#108ac1;font-size:17px;font-weight:500;padding:8px 5px;border-top:2px solid #dbdada;border-bottom:2px solid #dbdada;"><i class="fa-solid fa-right-from-bracket ms-1"></i> Déconnexion </a>
        </div>
    </div>

</nav>