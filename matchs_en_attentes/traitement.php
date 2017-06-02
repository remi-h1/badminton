<?php
// auteur : Rémi Hillérteau
// description : cette page permet d'enregistrer le début d'un match dans la base de données

session_start();
 // si les données si dessous ne sont pas défini, alors renvoyer sur la page d'accueil
if(!isset($_POST['idMatch']) OR !isset($_POST['heureDeb']) OR !isset($_POST['minuteDeb']) OR !isset($_POST['numTerrain']))
{
	header("Location: http://localhost/badminton/matchs_en_attentes/");
}
else
{
	 try
        {
            $bdd = new PDO('mysql:host=localhost;dbname=badminton;charset=utf8', 'root', ''); // connexion à la base de données
        }
        catch(Exception $e)
        {
            die('Erreur : '.$e->getMessage()); // En cas d'échec, il affiche un message d'erreur
        }

    // On vérifie qu'il y a encore des terrains de disponible
    $terrain = $bdd -> query('SELECT COUNT(NUMEROTERRAIN) AS nb FROM terrain WHERE DISPONIBLE="O"');

    while($nombreTerrain=$terrain->fetch())
    {
    	if($nombreTerrain['nb']==0)
    	{
    		$_SESSION['terrainDispo']=false;
    		header("Location: http://localhost/badminton/matchs_en_attentes/");
    	}

    }


    // on fait on concaténation de l'heure
    $heure=$_POST['heureDeb'] . ":" . $_POST['minuteDeb'] . ":00";

    // on rentre le terrain utilisé et le match dans la table matchs
	$match=$bdd->prepare('UPDATE matchs
					SET NUMEROTERRAIN=?,
					HEUREDBT=?
					WHERE IDMATCH=?
					');
	$match->execute(array($_POST['numTerrain'], $heure, $_POST['idMatch']));

	// on rentre la disponibilité du terrain selectioné en "N" (non disponible)
	$terrain=$bdd->prepare('UPDATE terrain
					SET DISPONIBLE="N"
					WHERE NUMEROTERRAIN=?
					');
	$terrain->execute(array($_POST['numTerrain']));


	$_SESSION['matchCommencer']=true;
	header("Location: http://localhost/badminton/matchs_en_attentes/");

}