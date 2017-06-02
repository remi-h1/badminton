<?php
// page d'accueil
// auteur : Rémi Hillériteau
// cette page permet de démarrer un nouveau tournoi
session_start();
$_GET['page']="accueil";
?>
<!doctype>
<html>
	<head>
		<title>Accueil</title>
		<!-- On inclue la parie du head général à toutes les pages -->
		<?php include('head.php'); ?>
	</head>
	<body>

	<!-- On inclue l'entête du site -->
	<?php include('header.php'); ?>

	<!-- La page du site -->
	<div class='page'>
			<h1>Débuter un nouveau tournoi</h1>
			<p class="alert" style="text-align: left;">Attention !! Le tournoi précédent sera supprimé !!</p>
			<form action="newBase.php" method="POST">
				<label for="nbTerrain">Nombre de terrains : </label>
				<input type="number" name="nbTerrain" id="nbTerrain" autofocus/>
				<input type="submit" value="Valider" />
			</form>
		</div>

	</body>
</html>