<?php
// auteur : Rémi Hillériteau
// cette page permet de confirmer l'enregistrement des scores d'un match

session_start();
$_GET['page']="matchsencours";
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
				<h1>Matchs en cours</h1>
				<H2>Confirmé le match</h2>
				<?php
				// pour que le match sois enregistré il faut au moins que les deux premiers set sois joués
				// en test dans un premier temps si une des donénes est envoyé, si une est envoyé, le formulaire à été envoyé et toutes les données ont été envoyés
				// puis on test que toutes les données minimals ont été complétés
				if(!isset($_POST['set1_Joueur1']) OR (
				empty($_POST['set1_Joueur1']) AND empty($_POST['set1_Joueur2']))
				OR (empty($_POST['set2_Joueur1']) AND empty($_POST['set2_Joueur2'])))
				{
					$_SESSION['erreur']=100;
					header("Location: http://localhost/badminton/matchs_en_cours/");
				}
				else
				{
					// ********************   On ne fait pas de vérification de données *************************

					// on récupère depuis la base de données les informations liées au match à enregistrers
					$reqMatchsEnCours=$bdd->prepare("SELECT IDMATCH, NUMEROTERRAIN, idTour, HOUR(HEUREDBT) AS h, MINUTE(HEUREDBT) AS min
													FROM matchs
													WHERE IDMATCH=?");
					$reqMatchsEnCours->execute(array($_POST['idMATCH']));

					while($donneesMatchs = $reqMatchsEnCours -> fetch())
					{
						// on récupère les informations des deux joueurs jouant le matchs (nom, prénom, école, ville, classement départemental)
						$reqJoueur=$bdd->prepare('SELECT J.NOM AS nomJoueur, PRENOM, E.NOM AS ecole, V.NOM AS ville, CLASSEMENTDPT
													FROM rencontre R, joueur J, etablissement E, ville V
													WHERE J.ID=R.IDJOUEUR
													AND J.ID_SCOLARISER=E.ID
													AND E.ID_LOCALISER=V.ID
													AND IDMATCH=?
	                                                GROUP BY IDJOUEUR
	                                                ORDER BY IDJOUEUR');
						$reqJoueur->execute(array($donneesMatchs['IDMATCH']));

						$i=0;
						while($donneesJoueur=$reqJoueur -> fetch())
						{
							// Les informations des deux joueurs sont mis dans le tableau joueurs
							$joueurs[$i]=$donneesJoueur['nomJoueur'] . ' ' . $donneesJoueur['PRENOM'] . ", " . $donneesJoueur['ecole'] . " à " . $donneesJoueur['ville'] . ", " . $donneesJoueur['CLASSEMENTDPT'];
							$i++;
						}
						$reqJoueur->closeCursor();

						// on affiche le tableau récapitulant les informations du match

						// on affiche les titres du tableau
						echo "</table>";
						$idTour=$donneesMatchs['idTour'];
						echo "<table class='matchsEnCours'>";
							echo "<th>N°</th>";
							echo "<th>terrain</th>";
							echo "<th>joueur 1</th>";
							echo "<th>VS</th>";
							echo "<th>joueur 2</th>";
							echo "<th class='set'>set 1</th>";
							echo "<th class='set'>set 2</th>";
							echo "<th class='set'>set 3</th>";
							echo "<th>heure début</th>";
							echo "<th class='set'>heure fin</th>";
							echo "<th>enregistrer</th>";
						echo "</tr>";

						// on affiche la ligne correspondant au match à enregistrer
						echo "<tr>";
							echo "<form action='traitement.php' method='post'>";
								echo "<td>" . $donneesMatchs['IDMATCH'] . "</td>";
								echo "<td>" . $donneesMatchs['NUMEROTERRAIN'] . "</td>";
								echo "<td>" . $joueurs[0] . "</td>";
								echo "<td>VS</td>";
								echo "<td>" . $joueurs[1] . "</td>";
								echo "<td><input type='text' name='set1_Joueur1' value='" . $_POST['set1_Joueur1'] . "' id='set1_Joueur1' maxlength='2' style='width: 25px;' > / <input type='text'  value='" . $_POST['set1_Joueur2'] . "' name='set1_Joueur2' id='set1_Joueur2' maxlenght='2' style='width: 25px;' > </td>";
								echo "<td><input type='text' name='set2_Joueur1' value='" . $_POST['set2_Joueur1'] . "' id='set2_Joueur1' maxlength='2' style='width: 25px;' > / <input type='text'  value='" . $_POST['set2_Joueur2'] . "' name='set2_Joueur2' id='set2_Joueur2' maxlenght='2' style='width: 25px;' > </td>";
								echo "<td><input type='text' name='set3_Joueur1' value='" . $_POST['set3_Joueur1'] . "' id='set3_Joueur1' maxlength='2' style='width: 25px;' > / <input type='text'  value='" . $_POST['set3_Joueur2'] . "' name='set3_Joueur2' id='set3_Joueur2' maxlenght='2' style='width: 25px;' > </td>";
								echo "<td>" . $donneesMatchs['h'] . 'H'. $donneesMatchs['min'] . "</td>";
								echo "<td><input type='text' name='heureFin' id='heureFin' maxlength='2' style='width: 25px;'' value='" . $_POST['heureFin'] . "' > H <input type='text' name='minuteFin' id='minuteFin'  maxlenght='2' style='width: 25px;'  value='" . $_POST['minuteFin'] . "' ></td>";
								echo "<input type='hidden' name='idMATCH' value='" . $donneesMatchs['IDMATCH'] . "'>";
								echo "<input type='hidden' name='terrain' value='" . $donneesMatchs['NUMEROTERRAIN'] . "'>";
								echo "<td><input type='submit' value='confirmer' class='valider'></td>";
							echo "</form>";
						echo "</tr>";
					}
					$reqMatchsEnCours->closeCursor();

				}
				
				?>

		</div>
	</body>
</html>