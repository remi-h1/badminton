<?php
// page affichage du classement des joueurs
// auteur : Rémi Hillérteau
// description : cette page permet d'afficher le classement des joueurs

session_start();
$_GET['page']="rang";
?>
<!doctype>
<html>
	<head>
		<title>Classement des joueurs</title>
		<!-- On inclue la parie du head général à toutes les pages -->
		<?php include('head.php'); ?>
	</head>
	<body>

	<!-- On inclue l'entête du site -->
	<?php include('header.php'); ?>

	<!-- La page du site -->
		<div class='page'>
			<h1>Classement des joueurs</h1>
			<p><a href="http://localhost/badminton/classementRang.php">Recommencer le classement</a> | <a href="http://localhost/badminton/matchs_en_attentes/">Commencer le tournoi</a></p>

			<table class='classement'>
				<tr>
					<th>rang</th>
					<th>joueur</th>
				</tr>

			<?php
			// on récupère tous les joueurs trié par rang
				$reqJoueur=$bdd->query('SELECT J.NOM AS nomJoueur, PRENOM, E.NOM AS ecole, V.NOM AS ville, CLASSEMENTDPT, POINTAGE, RANG, ID_DEPARTEMENT
										FROM joueur J, etablissement E, ville V
										WHERE J.ID_SCOLARISER=E.ID
										AND E.ID_LOCALISER=V.ID
			                            ORDER BY RANG');

					while($donneesJoueur=$reqJoueur -> fetch())
					{
						// on vérifie si le joueur n'est pas absent
						if($donneesJoueur['POINTAGE']==0)
							$abs=', <span style="color: blue"> absent !!</span>'; // si il l'est, on précise qu'il est absent
						else
							$abs="";
						$joueurs=array($donneesJoueur['RANG'], $donneesJoueur['nomJoueur'] . ' ' . $donneesJoueur['PRENOM'] . ", " . $donneesJoueur['ecole'] . ", " . $donneesJoueur['ville'] . " " . $donneesJoueur["ID_DEPARTEMENT"] . ", " . $donneesJoueur['CLASSEMENTDPT'] . $abs);

						// on affiche les joueurs dans des lignes d'un tableau
						echo "<tr>";
							echo "<td>" . $joueurs[0] . "</td>";
							echo "<td>" . $joueurs[1] . "</td>";
						echo "</tr>";

					}
					$reqJoueur->closeCursor();


				
					?>
				</table>
					
<!-- on affiche les 16 permiers matchs, pour visualiser les premières rencontres -->

				<h1>Les premiers matchs</h1>
				<table class="matchsEnCours">
					<tr>
						<th>N°</th>
						<th>joueur 1</th>
						<th>VS</th>
						<th>joueur 2</th>
					</th>
				<?php 
				$reqMatch=$bdd->query("SELECT IDMATCH
										FROM matchs
										WHERE HEUREDBT='0'
										AND IDMATCH<=16
										");

				while($donneesMatchs = $reqMatch -> fetch()) // pour chaque match nom commencé
				{
					// on récupère les informations des deux joueurs du match à afficher (nom, prenom, école, ville)
					$reqJoueur=$bdd->prepare('SELECT J.NOM AS nomJoueur, PRENOM, E.NOM AS ecole, V.NOM AS ville, CLASSEMENTDPT, POINTAGE, ID_DEPARTEMENT
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
						// on vérifie si le joueur n'est pas absent
						if($donneesJoueur['POINTAGE']==0)
							$abs=', <span style="color: blue"> absent !!</span>'; // si il l'est, on précise qu'il est absent
						else
							$abs="";
						$joueurs[$i]=$donneesJoueur['nomJoueur'] . ' ' . $donneesJoueur['PRENOM'] . ", " . $donneesJoueur['ecole'] . ", " . $donneesJoueur['ville'] . " " . $donneesJoueur["ID_DEPARTEMENT"] . ", " . $donneesJoueur['CLASSEMENTDPT'] . $abs;
						$i++;
					}
					$reqJoueur->closeCursor();

					echo "<tr>";
						echo "<td>" . $donneesMatchs['IDMATCH'] . "</td>";
						echo "<td>" . $joueurs[0];
						echo "</td>";
						echo "<td>VS</td>";
						echo "<td>" . $joueurs[1];
						echo "</td>";
						echo "</tr>";

				}
				
					?>
					</table>
		</div>

	</body>
</html>