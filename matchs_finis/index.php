<?php
// on identifie la page du site
session_start();
$_GET['page']="matchsfinis";
?>
<?php
    try
        {
            $bdd = new PDO('mysql:host=localhost;dbname=badminton;charset=utf8','root','');
            
        }
        catch (Exception $e)
        {
            die('Erreur : ' . $e->getMessage());
        }
    ?>

<!DOCTYPE>
<html>
	<head>
		<link rel="stylesheet" href="..\style.css"/>
		<title>Matchs finis</title>
		<!-- On inclue la parie du head général à toutes les pages -->
		<?php include('..\head.php'); ?>
	</head>

	<body>

	 <!-- On inclue l'entête du site -->
	 <?php include('..\header.php'); ?>
	
		 <!-- La page du site -->
		<div class='page'>
		
 			<h1>Matchs finis </h1>
 		    
	
			
			<?php
$reqTour=$bdd->query("SELECT * FROM tour ORDER BY id");

				$i=0;
				while($donneesTour = $reqTour -> fetch())
				{
					$tour[$i]=$donneesTour['libelle'];
					$i++;
				}
				

				$idTour=0;
				// on récupère tout les tours commencé, 
				$reqMatchsFinis=$bdd->query("SELECT IDMATCH, NUMEROTERRAIN, idTour, HOUR(HEUREDBT) AS hD , MINUTE(HEUREDBT) AS minD, HOUR(HEUREFIN) AS hF, MINUTE(HEUREFIN) AS minF, sec_to_time(time_to_sec(HEUREFIN)-time_to_sec(HEUREDBT)) AS duree 
											 FROM matchs 
											 WHERE HEUREFIN!=0
											 ORDER BY IDMATCH DESC

												");

				// la variable t compte le nombre de passage dans la boucle
				$t=0;
				while($donneesMatchs = $reqMatchsFinis -> fetch())
				{
					// on récupère les informations des deux joueurs du match à afficher (nom, prenom, école, ville, classement départemental)
					$reqJoueur=$bdd->prepare('SELECT J.NOM AS nomJoueur, PRENOM, E.NOM AS ecole, V.NOM AS ville, CLASSEMENTDPT, J.ID AS IDJOUEUR, POINTAGE
												FROM rencontre R, joueur J, etablissement E, ville V
												WHERE J.ID=R.IDJOUEUR
												AND J.ID_SCOLARISER=E.ID
												AND E.ID_LOCALISER=V.ID
												AND IDMATCH=?
                                                GROUP BY IDJOUEUR
                                                ORDER BY IDJOUEUR');
					$reqJoueur->execute(array($donneesMatchs['IDMATCH']));

					$i=0;
					// on récupère les informations du joueur dans le tableau joueurs
					while($donneesJoueur=$reqJoueur -> fetch())
					{
						// on vérifie si le joueur n'est pas absent
						if($donneesJoueur['POINTAGE']==0)
							$abs=', <span style="color: blue"> absent !!</span>'; // si il l'est, on précise qu'il est absent
						else
							$abs="";
						$joueurs[$i]=array($donneesJoueur['IDJOUEUR'], $donneesJoueur['nomJoueur'] . ' ' . $donneesJoueur['PRENOM'] . ", " . $donneesJoueur['ecole'] . " à " . $donneesJoueur['ville'] . ", " . $donneesJoueur['CLASSEMENTDPT'] . $abs);
						$i++;
					}
					$reqJoueur->closeCursor();

					for ($i=0; $i<2; $i++) // pour les joueurs
						{
							
							for($j=0; $j<3; $j++) // pour les sets
							{
								$ScoreMatch=$bdd->prepare('SELECT SCORE
															FROM rencontre R, matchs M
															WHERE R.IDMATCH=M.IDMATCH
					                                        AND R.IDMATCH = ?
															AND IDSET= ?
															AND IDJOUEUR = ?
															');

								$ScoreMatch->execute(array($donneesMatchs['IDMATCH'], ($j+1), $joueurs[$i][0]));

								while($donneesScoresMatch= $ScoreMatch -> fetch())
								{
									$scoreSet[$i][$j]=$donneesScoresMatch['SCORE'];
									
								}
								$ScoreMatch->closeCursor();
							}
						}

			 // on regarde qui a gagné pour chaque set
					  $setGagné[0]=0;
			          $setGagné[1]=0;

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




					// si on arrive sur un nouveau tour(une autre partie du tournoi)
					if($idTour!=$donneesMatchs['idTour'])
					{
						// et si ce n'est pas le premier tour, alors on ferme le tableau précédent
						if($idTour!=0)
						{
							echo "</table>";
						}
						// on change la nouvelle valeur du tour
						$idTour=$donneesMatchs['idTour'];
						// on affiche le tour
						echo "<h2 id='" . ($donneesMatchs['idTour']) .  "'>" . $tour[($idTour-1)] . "</h2>";
						// puis on ouvre un nouveau tableau
						echo "<table class='matchsEnCours'>";
							// on affiche les titres des colonnes du tableau
							echo "<th>N°</th>";
							echo "<th>terrain</th>";
							echo "<th>joueur 1</th>";
							echo "<th>VS</th>";
							echo "<th>joueur 2</th>";
							echo "<th class='set'>set 1</th>";
							echo "<th class='set'>set 2</th>";
							echo "<th class='set'>set 3</th>";
							echo "<th class='score'>score</th>";
							echo "<th class='heureD'>heure début</th>";
							echo "<th class='heureF'>heure fin</th>";
							echo "<th class=heureF>Durée</th>";
							
						echo "</tr>";
					}

					// on affiche les données consernant le match de la ligne à afficher
					echo "<tr>";
						
							echo "<td>" . $donneesMatchs['IDMATCH'] . "</td>";
							echo "<td>" . $donneesMatchs['NUMEROTERRAIN'] . "</td>";
							echo "<td>" . $joueurs[0][1] . "</td>";
							echo "<td>VS</td>";
							echo "<td>" . $joueurs[1][1] . "</td>";
							echo "<td>" . $scoreSet[0][0] . " / " . $scoreSet[1][0] . "</td>";
							echo "<td>" . $scoreSet[0][1] . " / " . $scoreSet[1][1] . "</td>";
							echo "<td>" . $scoreSet[0][2] . " / " . $scoreSet[1][2] . "</td>";
							echo "<td>".  $setGagné[0] . " / " . $setGagné[1] . "</td>";
							echo "<td>" . $donneesMatchs['hD'] . 'H'. $donneesMatchs['minD'] . "</td>";
							echo "<td>". $donneesMatchs['hF'] . 'H' . $donneesMatchs['minF'] . "</td>";
							$duree = explode(  ":" , $donneesMatchs['duree']);
							echo"<td>".  $duree[0] . "H". $duree[1] ."</td>";
														
						echo "</form>";
					echo "</tr>";

					$t++;
				}

				if($t>0) // si il y a plus d'un enregistrement
				{
					echo "</table>"; // alors on ferme le tableau précédent
				}
				else // sinon on affiche un message pour indiqué l'absance de match en cours
				{
					echo "<p class='messageOrange'>Aucun match finis</p>";
				}

				?>
		</div>
	</body>	
</html>