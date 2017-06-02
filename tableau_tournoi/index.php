<?php
// auteur : Rémi Hillériteau
// description : cette page permet d'afficher le tableau du tournoi avec le nom des joueurs pour chacun des matchs

// on identifie la page du site
session_start();
$_GET['page']="tableau_tournoi";
?>
<!doctype>
<html>
	<head>
		<title>Accueil</title>
		<!-- On inclue la parie du head général à toutes les pages -->
		<?php include('../head.php'); ?>
	</head>
	<body>

	<!-- On inclue l'entête du site -->
	<?php include('../header.php'); ?>

	<!-- La page du site -->
		<div class='page'>
				<h1>Tableau tournoi</h1>

				<?php

				// ************ On réalise un tableau $vainqueursPerdants[$i] avec $i, le numéro du match-1, avec pour chaque match le nom du vainqueur et celui du perdant
				// dans le cas ou l'un des deux ne sont pas encore définis, on met le vainqueur et/ou le perdant par défaut **************

				$rangJoueur=$bdd->query("SELECT J.NOM AS nomJoueur, PRENOM, E.NOM AS ecole, V.NOM AS ville, J.ID AS idJ, RANG, POINTAGE
										FROM  joueur J, etablissement E, ville V
										WHERE  J.ID_SCOLARISER=E.ID
										AND E.ID_LOCALISER=V.ID
										ORDER BY RANG
					 					");
				$k=0;
				while($donneesRangJoueur=$rangJoueur -> fetch())
				{
					if($donneesRangJoueur['POINTAGE']==0)
							$abs=', <span style="color: blue"> abs !!</span>';
						else
							$abs="";
					$joueursRang[$k]=$donneesRangJoueur['nomJoueur'] . ' ' . $donneesRangJoueur['PRENOM'] . ", " . $donneesRangJoueur['ecole'] . " " . $donneesRangJoueur['ville'] . $abs;
					$k++;
				}



				// **************  on récupère chaque match ****************
				$Matchs=$bdd->query("SELECT IDMATCH
									FROM matchs
									ORDER BY IDMATCH
									");

				while($match=$Matchs -> fetch())
				{
					// on vide les valeurs des deux joueurs, au cas ou on ne récupère pas deux joueurs pour ce match
					$joueurs[0]=0;
					$joueurs[1]=0;
					/// on récupère les informations des deux joueurs du match à afficher (nom, prenom, école, ville)
					$reqJoueur=$bdd->prepare('SELECT J.NOM AS nomJoueur, PRENOM, E.NOM AS ecole, V.NOM AS ville, J.ID AS idJ, POINTAGE
												FROM rencontre R, joueur J, etablissement E, ville V
												WHERE J.ID=R.IDJOUEUR
												AND J.ID_SCOLARISER=E.ID
												AND E.ID_LOCALISER=V.ID
												AND IDMATCH=?
                                                GROUP BY IDJOUEUR
                                                ORDER BY IDJOUEUR');
					$reqJoueur->execute(array($match['IDMATCH']));

					$i=0;
					// on récupère les informations du joueur dans le tableau joueurs
					while($donneesJoueur=$reqJoueur -> fetch())
					{
						// on vérifie si le joueur n'est pas absent
						if($donneesJoueur['POINTAGE']==0)
							$abs=', <span style="color: blue"> abs !!</span>'; // si il l'est, on précise qu'il est absent
						else
							$abs="";
						$joueurs[$i]=array($donneesJoueur['idJ'], $donneesJoueur['nomJoueur'] . ' ' . $donneesJoueur['PRENOM'] . ", " . $donneesJoueur['ecole'] . " " . $donneesJoueur['ville'] . $abs);
						$i++;
						
					}
					$reqJoueur->closeCursor();

					// si il n'y a pas de joueur, les scores sont nuls
					if($joueurs[0]==0 OR $joueurs[1]==0)
					{
						$scoreSet[0]=array(0,0,0);
						$scoreSet[1]=array(0,0,0);
					}
					else
					{
						// sinon on récupère les scores des joueures dans la table rencontre
						for ($i=0; $i<2; $i++) // pour les joueurs
						{
							for($j=0; $j<3; $j++) // pour les sets
							{
								$ScoreMatch=$bdd->prepare('SELECT SCORE
															FROM rencontre R
															WHERE IDMATCH = ?
															AND IDSET= ?
															AND IDJOUEUR = ?
															');

								$ScoreMatch->execute(array($match['IDMATCH'], ($j+1), $joueurs[$i][0]));

								while($donneesScoresMatch= $ScoreMatch -> fetch())
								{
									$scoreSet[$i][$j]=$donneesScoresMatch['SCORE'];
								}
								$ScoreMatch->closeCursor();
							}
						}
					}
					

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

					if($setGagné[0]==$setGagné[1])
						$vainqueursPerdants[($match['IDMATCH']-1)]=array(0, 0);
					elseif($setGagné[0]>$setGagné[1])
						$vainqueursPerdants[($match['IDMATCH']-1)]=array($joueurs[0][1], $joueurs[1][1]);
					else
						$vainqueursPerdants[($match['IDMATCH']-1)]=array($joueurs[1][1], $joueurs[0][1]);
				}
				$Matchs->closeCursor();

				 include('tableautournoi.php'); ?>
		</div>		

	</body>
</html>