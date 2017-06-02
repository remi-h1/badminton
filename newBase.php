<?php
// auteur : Rémi Hillériteau
// description : cette page permet de recommencer un tournoi
 try
        {
            $bdd = new PDO('mysql:host=localhost;dbname=badminton;charset=utf8', 'root', ''); // connexion à la base de données
        }
        catch(Exception $e)
        {
            die('Erreur : '.$e->getMessage()); // En cas d'échec, il affiche un message d'erreur
        }

        // on vide les tables liées aux données d'un tournoi

       		// on désactive les clés étrangères pour faire les TRUNCATES
        	$clesEtrangeresDesactiver=$bdd->query(' SET FOREIGN_KEY_CHECKS=0');

	        $viderRencontre=$bdd->query("TRUNCATE rencontre");

	        $SuppTerrain=$bdd->query("DELETE FROM matchs");
	        $viderMatchs=$bdd->query("TRUNCATE matchs");

	        $SuppTerrain=$bdd->query("DELETE FROM terrain");
	        $viderTerrain=$bdd->query("TRUNCATE terrain");

	        $SuppJoueur=$bdd->query("DELETE FROM joueur");
	        $viderJoueur=$bdd->query("TRUNCATE joueur");

	        $SuppEtablissement=$bdd->query("DELETE FROM etablissement");
	        $viderEtablissement=$bdd->query("TRUNCATE etablissement");

	        $SuppVille=$bdd->query("DELETE FROM ville");
	        $viderVille=$bdd->query("TRUNCATE ville");

	        // on réactive les clés étrangères
	        $clesEtrangereActiver=$bdd->query(' SET FOREIGN_KEY_CHECKS=1');

	    // On remet des données dans la table
	        for($i=0; $i<$_POST["nbTerrain"]; $i++)
	        {
	        	$ajoutTerrain=$bdd->prepare("INSERT INTO terrain(NUMEROTERRAIN, DISPONIBLE)
	        								VALUES(?, 'O')");
	        	$ajoutTerrain->execute(array($i+1));
	        }
	        
	        include("tour.php");

	        for($i=0; $i<104; $i++)
	        {
	        	$ajoutMatch=$bdd->prepare("INSERT INTO matchs(IDMATCH, idTour, NUMEROTERRAIN, HEUREDBT, HEUREFIN)
	        								VALUES(?, ?, NULL, '00:00:00', '00:00:00')");
	        	$ajoutMatch->execute(array($i+1, $tour[$i]));
	        }

	//redirection vers la page d'importation
	        header("Location: http://localhost/badminton/importation/");
?>