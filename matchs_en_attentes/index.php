<!-- Matchs en cours
auteur : Rémi Hillériteau
description : cette page permet de visualiser les matchs non commencés, et de pouvoir les débuter
 -->

<?php
session_start();
$_GET['page']="matchsenattente";
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
				<h1>Matchs en attentes</h1>
				<?php
				// si un match n'a pas pu commencer, car il n'y a plus de terrain de disponible
				if(isset($_SESSION['terrainDispo']) AND $_SESSION['terrainDispo']==false)
				{
					echo "<p class='alert'>Plus de terrain disponible</p>";
					$_SESSION['terrainDispo']=true;
					$_SESSION['matchCommencer']=false;
				}	
				// si un match vient d'être commencé, afficher un message
				elseif(isset($_SESSION['matchCommencer']) AND $_SESSION['matchCommencer']==true)
				{
					echo "<p class='message'>Le match a bien commencé</p>";
					$_SESSION['matchCommencer']=false;
				}
				// si un match vient d'être passé, afficher un message
				if(isset($_SESSION['matchPasser']) AND $_SESSION['matchPasser']==true)
				{
					echo "<p class='message'>Le match a bien été passé</p>";
					$_SESSION['matchPasser']=false;
				}
				


				$reqTour=$bdd->query("SELECT * FROM tour ORDER BY id");

				$i=0;
				while($donneesTour = $reqTour -> fetch())
				{
					$tour[$i]=$donneesTour['libelle'];
					$i++;
				}

				$idTour=0;
				$reqMatchsEnAttentes=$bdd->query("SELECT IDMATCH, idTour
												FROM matchs
												WHERE HEUREDBT='0'
												");

				// la variable t compte le nombre de passage dans la boucle
				$t=0;
				while($donneesMatchs = $reqMatchsEnAttentes -> fetch()) // pour chaque match nom commencé
				{
					// on récupère les informations des deux joueurs du match à afficher (nom, prenom, école, ville)
					$reqJoueur=$bdd->prepare('SELECT J.NOM AS nomJoueur, PRENOM, E.NOM AS ecole, V.NOM AS ville, CLASSEMENTDPT, POINTAGE, J.ID AS idJ
												FROM rencontre R, joueur J, etablissement E, ville V
												WHERE J.ID=R.IDJOUEUR
												AND J.ID_SCOLARISER=E.ID
												AND E.ID_LOCALISER=V.ID
												AND IDMATCH=?
                                                GROUP BY IDJOUEUR
                                                ORDER BY IDJOUEUR');
					$reqJoueur->execute(array($donneesMatchs['IDMATCH']));

					$i=0; // $i permet d'identifier le joueur 1 et le joueur 2
					$joueurs[0]=0; // on vide le tableau joueurs
					$joueurs[1]=0;
					// on récupère les informations du joueur dans le tableau joueurs
					while($donneesJoueur=$reqJoueur -> fetch())
					{
						$joueurs[$i]=$donneesJoueur['nomJoueur'] . ' ' . $donneesJoueur['PRENOM'] . ", " . $donneesJoueur['ecole'] . " à " . $donneesJoueur['ville'] . ", " . $donneesJoueur['CLASSEMENTDPT'];
						$pointage[$i]=$donneesJoueur['POINTAGE']; // pour le test d'absence du joueur
						$i++;
					}
					$reqJoueur->closeCursor();

					if(!isset($joueurs[0]) OR empty($joueurs[0]) AND empty($joueurs[1])OR !isset($joueurs[1])) // si les deux joueurs ne sont pas défini
					{
						include('matchsDefaut.php');
						$joueurs[0]=$joueursMatchDefaut[($donneesMatchs['IDMATCH']-1)][0];
						$joueurs[1]=$joueursMatchDefaut[($donneesMatchs['IDMATCH']-1)][1];
						$pointage[0]=1; // si les joueurs sont pas défini, il n'y a pas d'absence
						$pointage[1]=1;
						$enAttente=true;
					}
					elseif(!isset($joueurs[1]) OR empty($joueurs[1])) // si seul l'un des joueurs ' n'est pas défini
					{
						include('matchsDefaut.php');
						// on récupère le joueur dans une autre variable
						$j=$joueurs[0];
						$p=$pointage[0];

						// on regarde si les matchs précédents sont finis
						for($e=0; $e<2; $e++)
						{
							$req=$bdd->prepare('SELECT HEUREFIN
												FROM matchs
												WHERE IDMATCH=?');
							$req->execute(array($joueursMatchDefaut[($donneesMatchs['IDMATCH']-1)][(2+$e)]));

							while($donnees=$req -> fetch())
							{
								if($donnees['HEUREFIN']!=0) // match fini
								{
									$joueurs[$e]=$j;
									$pointage[$e]=$p;
								}
								else // match en cour, le joueur n'est pas encore défini
								{
									$joueurs[$e]=$joueursMatchDefaut[($donneesMatchs['IDMATCH']-1)][$e];
									$pointage[$e]=1;
								}
							}
						}

						// il manque un joueur, donc le match est en attente
						$enAttente=true;

					}
					// test d'absence
					if($pointage[0]==0 OR $pointage[1]==0) // si le joueur 1 ou 2 n'est pas pointer
						$match_passer=true; // alors on passe le match en denant la victoire à celui qui est là
					else
						$match_passer=false;

					// affichage
					if($idTour!=$donneesMatchs['idTour']) // si le tour n'est pas le même que le précédent
					{	
						// on ferme le précédent tableau 
						if($idTour!=0)
						{
							echo "</table>";
						}
						$idTour=$donneesMatchs['idTour'];

						// nouveau titre
						echo "<h2 id='" . ($donneesMatchs['idTour']) .  "'>" . $tour[($idTour-1)] . "</h2>";
						
						// nouveau tableau
						echo "<table class='matchsEnCours'>";
							echo "<th>N°</th>";
							echo "<th>joueur 1</th>";
							echo "<th>VS</th>";
							echo "<th>joueur 2</th>";
							echo "<th>Commencer</th>";
						echo "</tr>";
					}

					// les données d'un match
					echo "<tr>";
						echo "<form action='";
						if($match_passer==true) { echo "passer.php"; } else { echo "confirme.php"; }
						echo "' method='POST'>";
							echo "<td>" . $donneesMatchs['IDMATCH'] . "</td>";
							echo "<td>" . $joueurs[0];
								if($pointage[0]==0) // si le joueur 1 est absent
									echo " <span style='color: blue'>absent !!</span>";
							 echo "</td>";
							echo "<td>VS</td>";
							echo "<td>" . $joueurs[1];
								if($pointage[1]==0) // si le joueur 2 est absent
									echo " <span style='color: blue'>absent !!</span>";
							 echo "</td>";
							if(isset($enAttente) AND $enAttente==true) // le match est en attente
							{
								echo "<td class='enAttente' style='width: 100px;'>En attente</td>";
								$enAttente=false;
							}
							elseif($match_passer==true) // un ou deux joueur(s) est/sont absent(s), on passe le match
							{
								echo "<td style='width: 100px;'><input type='submit' value='Passer' class='passer'></td>";
								$match_passer=true;
							}
							else // le match peut être joué
								echo "<td style='width: 100px;'><input type='submit' value='Commencer' class='valider'></td>";
							echo "<input type='hidden' name='idMatch' value='" . $donneesMatchs['IDMATCH'] . "' />";
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
					echo "<p class='messageOrange'>Aucun match en attente</p>";
				}

				?>

		</div>

	</body>
</html>