<?php
// auteur : Rémi Hillérteau
// description : cette page permet de passer un match lorsqu'il y a un joueur d'absent, et de l'enregistrer dans le base de données

session_start();
 // si les données si dessous ne sont pas défini, alors renvoyer sur la page d'accueil
if(!isset($_POST['idMatch']) OR empty($_POST['idMatch']))
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

    // on fait on concaténation de l'heure
    $heure=date('H'). ":" . date('i') . ":00";

    // on rentre complète le match dans la table matchs
	$match=$bdd->prepare('UPDATE matchs
					SET	HEUREDBT=?,
					HEUREFIN=?
					WHERE IDMATCH=?
					');
	$match->execute(array($heure, $heure, $_POST['idMatch']));

	// on récupères les deux identifiants (id) des deux joueurs dans le tablea $joueurs
	$reqJoueur=$bdd->prepare('SELECT J.ID AS idJ, POINTAGE
								FROM rencontre R, joueur J
								WHERE J.ID=R.IDJOUEUR
								AND IDMATCH=?
                                GROUP BY IDJOUEUR
                                ORDER BY IDJOUEUR');
	$reqJoueur->execute(array($_POST['idMatch']));

	$i=0;
	while($donneesJoueur=$reqJoueur -> fetch())
	{
		$joueurs[$i]=array($donneesJoueur['idJ'], $donneesJoueur['POINTAGE']);
		$i++;
	}

	// on renctre les résultats des matchs dans un tableau à 2 dimensions (pour la boucle qui suit)
	if($joueurs[0][1]==0 AND $joueurs[1][1]==1) // si le joueur 1 est absent
	{
		// on fait gagné le joueur 2
		$scoreSet[0][0]=0;
		$scoreSet[0][1]=0;
		$scoreSet[0][2]=0;
		$scoreSet[1][0]=1;
		$scoreSet[1][1]=1;
		$scoreSet[1][2]=1;
	}
	elseif($joueurs[1][1]==0 AND $joueurs[0][1]==1) // si le joueur 2 est absent
	{
		// on fait gagné le joueur 1
		$scoreSet[0][0]=1;
		$scoreSet[0][1]=1;
		$scoreSet[0][2]=1;
		$scoreSet[1][0]=0;
		$scoreSet[1][1]=0;
		$scoreSet[1][2]=0;
	}
	else // si les deux sont absents, le vainqueur est déterminé aléatoirement
	{
		if(rand(0, 100)%2==0)
		{
			$scoreSet[0][0]=0;
			$scoreSet[0][1]=0;
			$scoreSet[0][2]=0;
			$scoreSet[1][0]=1;
			$scoreSet[1][1]=1;
			$scoreSet[1][2]=1;
		}
		else
		{
			$scoreSet[0][0]=1;
			$scoreSet[0][1]=1;
			$scoreSet[0][2]=1;
			$scoreSet[1][0]=0;
			$scoreSet[1][1]=0;
			$scoreSet[1][2]=0;
		}
	}
	


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

			$modifierScoreMatch->execute(array($scoreSet[$i] [$j], $_POST['idMatch'], ($j+1), $joueurs[$i][0]));
		}
	}


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
		$vainqueurPerdant=array($joueurs[0][0], $joueurs[1][0]);
	else
		$vainqueurPerdant=array($joueurs[1][0], $joueurs[0][0]);

	// pour chaque match : on a le vainqueur qui va jouer un autre match, et un perdant qui va jouer un autre match
	include('../matchs_en_cours/envoyerVersAutresMatchs.php');
	
	if($_POST['idMatch']<=88)
	{
		// on créer dans la table rencontre, les rencontres des deux joueurs de ce match dans leurs prochains match
		for ($i=0; $i<2; $i++) // pour les joueurs
		{
			for($j=0; $j<3; $j++) // pour les scores
			{
				$rencontreSuivent=$bdd->prepare('INSERT INTO rencontre(IDJOUEUR, IDMATCH, IDSET)
												VALUES(?, ?, ?)
												');
				$rencontreSuivent->execute(array( $vainqueurPerdant[$i], $aMVaiPer[($_POST['idMatch']-1)][$i] , ($j+1)));
			}
		}
	}


	$_SESSION['matchPasser']=true;
	header("Location: http://localhost/badminton/matchs_en_attentes/");

}