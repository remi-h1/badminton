<!-- Matchs en cours -->

<?php
// on identifie la page du site
session_start();
$_GET['page']="matchsEnAttente";
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
				<h1>Matchs en attente</h1>
				<?php
				
				// test erreur
				if(isset($_SESSION['erreur']) AND $_SESSION['erreur']==100)
				{
					$_SESSION['erreur']=false;
					echo "<p class='alert'>Vous devez remplir convenablement tous les champs</p>";
				}


				$reqTour=$bdd->query("SELECT * FROM tour ORDER BY id");

				$i=0;
				while($donneesTour = $reqTour -> fetch())
				{
					$tour[$i]=$donneesTour['libelle'];
					$i++;
				}
				for($j=0; $j<count($tour); $j++)
					echo "<a href='#" . ($j+1) . "'>" . $tour[$j] . " </a> - ";

				$idTour=0;
				$reqMatchsEnCours=$bdd->query("SELECT IDMATCH, idTour
												FROM matchs
												WHERE HEUREDBT='0'
												");

				while($donneesMatchs = $reqMatchsEnCours -> fetch())
				{
					$reqJoueur=$bdd->prepare('SELECT J.NOM AS nomJoueur, PRENOM, E.NOM AS ecole, V.NOM AS ville, CLASSEMENTDPT
												FROM rencontre R, joueur J, etablissement E, ville V
												WHERE J.ID=R.IDJOUEUR
												AND J.ID_SCOLARISER=E.ID
												AND E.ID_LOCALISER=V.ID
												AND IDMATCH=?
                                                GROUP BY R.IDJOUEUR
                                                ORDER BY R.IDJOUEUR');
					$reqJoueur->execute(array($donneesMatchs['IDMATCH']));

					$i=0;
					while($donneesJoueur=$reqJoueur -> fetch())
					{
						$joueurs[$i]=$donneesJoueur['nomJoueur'] . ' ' . $donneesJoueur['PRENOM'] . ", " . $donneesJoueur['ecole'] . " " . $donneesJoueur['ville'] . ", " . $donneesJoueur['CLASSEMENTDPT'];
						$i++;
					}
					$reqJoueur->closeCursor();

					if($idTour!=$donneesMatchs['idTour'])
					{
						if($idTour!=0)
						{
							echo "</table>";
						}
						$idTour=$donneesMatchs['idTour'];
						echo "<h2 id='" . ($donneesMatchs['idTour']) .  "'>" . $tour[($idTour-1)] . "</h2>";
						echo "<table class='matchsEnCours'>";
							echo "<th>N°</th>";
							echo "<th>joueur 1</th>";
							echo "<th>VS</th>";
							echo "<th>joueur 2</th>";
							echo "<th>enregistrer</th>";
						echo "</tr>";
					}

					echo "<tr>";
						echo "<form action='#confirme.php' method='POST'>";
							echo "<td>" . $donneesMatchs['IDMATCH'] . "</td>";
							echo "<td>" . $joueurs[0] . "</td>";
							echo "<td>VS</td>";
							echo "<td>" . $joueurs[1] . "</td>";
							echo "<td><input type='submit' value='Valider' class='valider'></td>";
						echo "</form>";
					echo "</tr>";
					
					?>
					

					<?php


					$reqRencontre=$bdd->prepare("SELECT * FROM rencontre
												WHERE HEUREDBT!='NULL'
												ORDER BY id");
					while($donneesRencontre=$reqRencontre->fetch())
					{

					}
				}

				?>

		</div>

	</body>
</html>