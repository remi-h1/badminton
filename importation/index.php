<html>
 <head>
<meta charset="utf-8">
<?php if(isset($_GET['page']) and $_GET['page']=="accueil") { echo "<link rel='stylesheet' href='style.css' /> "; } else { echo "<link rel='stylesheet' href='style.css' />"; } ?>
<meta name="description" content="application pour gérer les tournois générals de Badminton" />
<meta name="generator" content="Sublime Text 2" />
<meta name="author" content="Florentin M., Maxime Delcroix, Nicolas A., et Rémi H. en BTS SIO à Sainte Marie de Cholet">
</head>
<body>
	<center>
<h1>Importation</h1>
<form action="traitement.php" method="POST">
	<textarea name="l" rows="40" cols="85"></textarea>
	<input type="submit" value="valider"/>
</form>
<p>Copier coller les données des 32 joueurs du document Excel "type" dans le formulaire, comme indiqué dans l'exemple ci-dessous</p>
<img src="http://localhost/badminton/img/importation.png"/>
</center>
</body>
</html>