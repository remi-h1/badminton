<?php
// auteur : Rémi Hillériteau
// cette page permet de confirmer le début du match, en rentrant un terrain et une heure
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
				<h1>Débuter un match</h1>
				<?php
				if(empty($_POST['idMatch'])) // si le match n'est pas défini, alors renvoyer sur la page d'accueil
				{
					header("Location: http://localhost/badminton/matchs_en_attentes/");
				}
				else
				{
					$reqMatchsEnCours=$bdd->prepare("SELECT IDMATCH, idTour
												FROM matchs
												WHERE HEUREDBT='0'
												AND IDMATCH=?
												");
					$reqMatchsEnCours->execute(array($_POST['idMatch']));

					while($donneesMatchs = $reqMatchsEnCours -> fetch())
					{
						// on récupère les informations des deux joueurs du match à afficher (nom, prenom, école, ville)
						$reqJoueur=$bdd->prepare('SELECT J.NOM AS nomJoueur, PRENOM, E.NOM AS ecole, V.NOM AS ville, CLASSEMENTDPT
													FROM rencontre R, joueur J, etablissement E, ville V
													WHERE J.ID=R.IDJOUEUR
													AND J.ID_SCOLARISER=E.ID
													AND E.ID_LOCALISER=V.ID
													AND IDMATCH=?
	                                                GROUP BY IDJOUEUR
	                                                ORDER BY IDJOUEUR');
						$reqJoueur->execute(array($_POST['idMatch']));

						$i=0; // $i permet d'identifier le joueur 1 et le joueur 2
						// on récupère les informations du joueur dans le tableau joueurs
						while($donneesJoueur=$reqJoueur -> fetch())
						{
							// on concatène les information des joueur dans un tableau joueurs
							$joueurs[$i]=$donneesJoueur['nomJoueur'] . ' ' . $donneesJoueur['PRENOM'] . ", " . $donneesJoueur['ecole'] . " à " . $donneesJoueur['ville'] . ", " . $donneesJoueur['CLASSEMENTDPT'];
							$i++;
						}
						$reqJoueur->closeCursor();

						// titre tableau
						echo "<table class='matchsEnCours'>";
							echo "<tr>";
								echo "<table class='matchsEnCours'>";
								echo "<th>N°</th>";
								echo "<th>terrain</th>";
								echo "<th>joueur 1</th>";
								echo "<th>VS</th>";
								echo "<th>joueur 2</th>";
								echo "<th>heure début</th>";
								echo "<th>enregistrer</th>";
							echo "</tr>";
							// contenu
							echo "<tr>";
								echo "<form action='traitement.php' method='POST'>";
									echo "<td>" . $donneesMatchs['IDMATCH'] . "</td>";
									echo "<td>";
										echo "<select name='numTerrain'>";
											// on récupère tous les terrains disponibles
											$terrain = $bdd -> query('SELECT NUMEROTERRAIN FROM terrain WHERE DISPONIBLE="O"');
											$i=0;
											// on affiche toutes les classes
											while($donnees=$terrain->fetch())
											{
												$i++;
												echo "<option value='" . $donnees['NUMEROTERRAIN'] . "'>" . $donnees['NUMEROTERRAIN'] . "</option>";
											}
											$terrain->closeCursor();

											if($i==0)
											{
												// si il n'y a aunune classe dans la base, afficher qu'il y en a pas
												echo "<option value='false'>aucun terrain disponible</option>";
											}
										echo "</select>";
									echo "</td>";
									echo "<td>" . $joueurs[0] . "</td>";
									echo "<td>VS</td>";
									echo "<td>" . $joueurs[1] . "</td>";
									echo "<td><input type='text' name='heureDeb' id='heureDeb' maxlength='2' style='width: 25px;'' value='" . date('H') . "' > H <input type='text' name='minuteDeb' id='minuteDeb'  maxlength='2' style='width: 25px;'  value='" . date('i') . "' ></td>";
									echo "<input type='hidden' name='idMatch' value='" . $_POST['idMatch'] . "' />";
									echo "<td><input type='submit' value='Valider' class='valider'></td>";
								echo "</form>";
							echo "</tr>";
						echo "</table";
					}
				}

				
				?>



		</div>
	</body>
</html>