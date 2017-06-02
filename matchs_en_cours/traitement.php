<?php
// auteur : Rémi Hillérteau
// description : cette page permet d'enregistrer la fin d'un match

session_start();

try
{
    $bdd = new PDO('mysql:host=localhost;dbname=badminton;charset=utf8', 'root', ''); // connexion à la base de données
}
catch(Exception $e)
{
    die('Erreur : '.$e->getMessage()); // En cas d'échec, il affiche un message d'erreur
}

// on vérifie que toutes les données ont été envoyés
if(!isset($_POST['set1_Joueur1']) OR (
empty($_POST['set1_Joueur1']) AND empty($_POST['set1_Joueur2']))
OR (empty($_POST['set2_Joueur1']) AND empty($_POST['set2_Joueur2'])) OR empty($_POST['idMATCH']))
{
	// si il manque des données, on redirige l'utilisateur sur la page matchs en cours avec un message d'erreur
	$_SESSION['erreur']=100;
	header("Location: http://localhost/badminton/matchs_en_cours/");
}
else
{
	// on récupères les deux identifiants (id) des deux joueurs dans le tablea $joueurs
	$reqJoueur=$bdd->prepare('SELECT J.ID AS idJ
								FROM rencontre R, joueur J
								WHERE J.ID=R.IDJOUEUR
								AND IDMATCH=?
                                GROUP BY IDJOUEUR
                                ORDER BY IDJOUEUR');
	$reqJoueur->execute(array($_POST['idMATCH']));

	$i=0;
	while($donneesJoueur=$reqJoueur -> fetch())
	{
		$joueurs[$i]=$donneesJoueur['idJ'];
		$i++;
	}

	// on renctre les résultats des matchs dans un tableau à 2 dimensions (pour la boucle qui suit)
	$scoreSet[0][0]= $_POST['set1_Joueur1'];
	$scoreSet[0][1]= $_POST['set2_Joueur1'];
	$scoreSet[0][2]= $_POST['set3_Joueur1'];
	$scoreSet[1][0]= $_POST['set1_Joueur2'];
	$scoreSet[1][1]= $_POST['set2_Joueur2'];
	$scoreSet[1][2]= $_POST['set3_Joueur2'];


	// on enregistre les scores des joueures dans la table rencontre
	for ($i=0; $i<2; $i++) // pour les joueurs
	{
		for($j=0; $j<3; $j++) // pour les scores
		{
			$modifierScoreMatch=$bdd->prepare('UPDATE rencontre R, matchs M
										SET SCORE= ?
										WHERE R.IDMATCH=M.IDMATCH
                                        AND R.IDMATCH = ?
										AND IDSET= ?
										AND IDJOUEUR = ?');

			$modifierScoreMatch->execute(array($scoreSet[$i] [$j], $_POST['idMATCH'], ($j+1), $joueurs[$i]));
		}
	}

	// on enregistre l'heure de fin du match dans la table matchs
	$heureFin=$_POST['heureFin'] . ":" . $_POST['minuteFin'] . ":00"; // contaténation de l'heure
	$ajoutHeureFin=$bdd->prepare('UPDATE matchs
							SET HEUREFIN = ?
							WHERE IDMATCH = ?');
	$ajoutHeureFin->execute(array($heureFin, $_POST['idMATCH']));

	// On note le terrain en "disponible" dans la table terrain
	$terrainDispo=$bdd->prepare('UPDATE terrain
							SET DISPONIBLE= "O"
							WHERE NUMEROTERRAIN = ?');
	$terrainDispo->execute(array($_POST['terrain']));


	// ********** on ajoute les joueurs dans les prochains matchs si on est sur un match inférieur ou égale à 88 ***********

	// on détermine dans un premier temps, le gagnant et le perdant du match

	$setGagné[0]=0;
	$setGagné[1]=0;
	// on regarde qui a gagné pour chaque set
	for($j=0; $j<3; $j++)
	{	
		// si les scores sont égales à 0, alors le set n'a pas été joué (les deux premiers ont été gagné par le méme joueur) ou alors le math n'a pas encore été joué
		if($scoreSet[1][$j]==0 AND $scoreSet[0][$j]==0)
		{

		}
		// si le score du joueur 1 est inférieur à celui du joueur 2,
		elseif($scoreSet[0][$j]<$scoreSet[1][$j])
			$setGagné[1]++; // alors le joueur 2 a gagné le set
		else // dans le cas contraire
			$setGagné[0]++; // le joueur 1 a gagné le set
	}

	if($setGagné[0]>$setGagné[1])
		$vainqueurPerdant=array($joueurs[0], $joueurs[1]);
	else
		$vainqueurPerdant=array($joueurs[1], $joueurs[0]);

	// pour chaque match : on a le vainqueur qui va jouer un autre match, et un perdant qui va jouer un autre match
	include('envoyerVersAutresMatchs.php');
	
	if($_POST['idMATCH']<=88)
	{
		// on créer dans la table rencontre, les rencontres des deux joueurs de ce match dans leurs prochains match
		for ($i=0; $i<2; $i++) // pour les joueurs
		{
			for($j=0; $j<3; $j++) // pour les scores
			{
				$rencontreSuivent=$bdd->prepare('INSERT INTO rencontre(IDJOUEUR, IDMATCH, IDSET)
												VALUES(?, ?, ?)
												');
				$rencontreSuivent->execute(array( $vainqueurPerdant[$i], $aMVaiPer[($_POST['idMATCH']-1)][$i] , ($j+1)));
			}
		}
	}

	// on renvoie l'utilisateur sur la page matchs en cours avec un message
	$_SESSION['matchEnregister']=true;
	header("Location: http://localhost/badminton/matchs_en_cours/");

}
?>