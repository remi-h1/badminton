<?php
// matchs en cours
// auteur : Rémi Hillériteau
// cette page permet de visualiser les matchs qui sont en train d'être jouer, et permet d'enregistrer les scores à la fin du match

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
				<?php
				
				// test erreur

				// Si le match n'a pas pu être enregistré, afficher un message
				if(isset($_SESSION['erreur']) AND $_SESSION['erreur']==100)
				{
					$_SESSION['erreur']=false;
					echo "<p class='alert'>Le match n'a pas été enregistré !! Vous devez remplir convenablement tous les champs d'un match</p>";
				}

				// si un match vient d'être enregistré, alors afficher un message
				if(isset($_SESSION['matchEnregister']) AND $_SESSION['matchEnregister']==true)
				{
					echo "<p class='message'>Le match a bien été enregistré</p>";
					$_SESSION['matchEnregister']=false;
				}

				// on récupère les informations liées aux tours (correspondants à différent niveau dans le déroulement du tournoi)
				$reqTour=$bdd->query("SELECT * FROM tour ORDER BY id");

				$i=0;
				while($donneesTour = $reqTour -> fetch())
				{
					$tour[$i]=$donneesTour['libelle'];
					$i++;
				}
				// le code si dessous permet d'afficher les liens vers les différents tours (voir si utilité)
				//for($j=0; $j<count($tour); $j++)
					//echo "<a href='#" . ($j+1) . "'>" . $tour[$j] . " </a> - ";

				$idTour=0;
				// on récupère tout les tours commencé, donc avec une heure de début défini, et une heure de fin NULL (=0)
				$reqMatchsEnCours=$bdd->query("SELECT IDMATCH, NUMEROTERRAIN, idTour, HOUR(HEUREDBT) AS h, MINUTE(HEUREDBT) AS min
												FROM matchs
												WHERE HEUREDBT!=0
												AND HEUREFIN=0
												");

				// la variable t compte le nombre de passage dans la boucle
				$t=0;
				while($donneesMatchs = $reqMatchsEnCours -> fetch())
				{
					// on récupère les informations des deux joueurs du match à afficher (nom, prenom, école, ville, classement départemental)
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
					// on récupère les informations du joueur dans le tableau joueurs
					while($donneesJoueur=$reqJoueur -> fetch())
					{
						$joueurs[$i]=$donneesJoueur['nomJoueur'] . ' ' . $donneesJoueur['PRENOM'] . ", " . $donneesJoueur['ecole'] . " à " . $donneesJoueur['ville'] . ", " . $donneesJoueur['CLASSEMENTDPT'];
						$i++;
					}
					$reqJoueur->closeCursor();

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
							echo "<tr>";
							// on affiche les titres des colonnes du tableau
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
					}

					// on affiche les données consernant le match de la ligne à afficher
					echo "<tr>";
						echo "<form action='confirme.php' method='post'>";
							echo "<td>" . $donneesMatchs['IDMATCH'] . "</td>";
							echo "<td>" . $donneesMatchs['NUMEROTERRAIN'] . "</td>";
							echo "<td>" . $joueurs[0] . "</td>";
							echo "<td>VS</td>";
							echo "<td>" . $joueurs[1] . "</td>";
							echo "<td><input type='text' name='set1_Joueur1' id='set1_Joueur1' maxlength='3' style='width: 25px;' > / <input type='text' name='set1_Joueur2' id='set1_Joueur2' maxlength='3' style='width: 25px;' > </td>";
							echo "<td><input type='text' name='set2_Joueur1' id='set2_Joueur1' maxlength='3' style='width: 25px;' > / <input type='text' name='set2_Joueur2' id='set2_Joueur2' maxlength='3' style='width: 25px;' > </td>";
							echo "<td><input type='text' name='set3_Joueur1' id='set3_Joueur1' maxlength='3' style='width: 25px;' > / <input type='text' name='set3_Joueur2' id='set3_Joueur2' maxlength='3' style='width: 25px;' > </td>";
							echo "<td>" . $donneesMatchs['h'] . 'H'. $donneesMatchs['min'] . "</td>";
							echo "<td><input type='text' name='heureFin' id='heureFin' maxlength='2' style='width: 25px;'' value='" . date('H') . "' > H <input type='text' name='minuteFin' id='minuteFin'  maxlength='2' style='width: 25px;'  value='" . date('i') . "' ></td>";
							echo "<input type='hidden' name='idMATCH' value='" . $donneesMatchs['IDMATCH'] . "'>";
							echo "<td><input type='submit' value='Valider' class='valider'></td>";
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
					echo "<p class='messageOrange'>Aucun match en cours</p>";
				}

				?>

		</div>

	</body>
</html>