<?php
// on identifie la page du site
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
				// !isset($_POST['set1_joueur1']) OR empty($_POST['set1_joueur1']) OR empty($_POST['set2_joueur1']) OR empty($_POST['set3_joueur1'])
				// OR empty($_POST['set1_joueur2']) OR empty($_POST['set2_joueur2']) OR 
				if(empty($_POST['set3_joueur2']))
				{
					$_SESSION['erreur']=100;
					header("Location: http://localhost/bad/matchs_en_cours/");
				}
				else
				{
					echo "<tr>";
						echo "<form action='confirme.php' method='POST'>";
							echo "<td>" . $donneesMatchs['IDMATCH'] . "</td>";
							echo "<td>" . $donneesMatchs['NUMEROTERRAIN'] . "</td>";
							echo "<td>" . $joueurs[0] . "</td>";
							echo "<td>VS</td>";
							echo "<td>" . $joueurs[1] . "</td>";
							echo "<td><input type='text' name='set1_Joueur1' id='set1_Joueur1' maxlength='2' style='width: 25px;' > / <input type='text' name='set1_Joueur2' id='set1_Joueur2' maxlenght='2' style='width: 25px;' > </td>";
							echo "<td><input type='text' name='set2_Joueur1' id='set2_Joueur1' maxlength='2' style='width: 25px;' > / <input type='text' name='set2_Joueur2' id='set2_Joueur2' maxlenght='2' style='width: 25px;' > </td>";
							echo "<td><input type='text' name='set3_Joueur1' id='set3_Joueur1' maxlength='2' style='width: 25px;' > / <input type='text' name='set3_Joueur2' id='set3_Joueur2' maxlenght='2' style='width: 25px;' > </td>";
							echo "<td>" . $donneesMatchs['h'] . 'H'. $donneesMatchs['min'] . "</td>";
							echo "<td><input type='text' name='heureFin' id='heureFin' maxlength='2' style='width: 25px;'' value='" . date('H') . "' > H <input type='text' name='minuteFin' id='minuteFin'  maxlenght='2' style='width: 25px;'  value='" . date('i') . "' ></td>";
							echo "<td><input type='submit' value='Valider' class='valider'></td>";
						echo "</form>";
					echo "</tr>";
				}







				
				?>

		</div>
	</body>
</html>