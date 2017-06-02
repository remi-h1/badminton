<html>
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
    <head>
<meta charset="utf-8">
<?php if(isset($_GET['page']) and $_GET['page']=="accueil") { echo "<link rel='stylesheet' href='style.css' /> "; } else { echo "<link rel='stylesheet' href='style.css' />"; } ?>
<meta name="description" content="application pour gérer les tournois générals de Badminton" />
<meta name="generator" content="Sublime Text 2" />
<meta name="author" content="Florentin M., Maxime Delcroix, Nicolas A., et Rémi H. en BTS SIO à Sainte Marie de Cholet">
</head>
<body>
<?php 


$z=str_replace("\n", "\t", $_POST['l']);
$donnees=explode("\t", $z);



for($i=0;$i<32;$i++) //joueurs
{
	for($j=0;$j<7;$j++)
	{
		$nb=$i*7+$j;
		$tab[$i][$j]=$donnees[$nb];
		
	}
	
	
}
echo "<br />-------------</br>";
for($i=0;$i<32;$i++) //joueurs
{
	for($j=0;$j<7;$j++)
	{
		//$tab[$i][$j]=$donnees[$nb];
		echo $tab[$i][$j];
		echo "-*-";
	}
	echo "<br/>";
}

for($i=0; $i<32; $i++) //Ville
{
	
	$reqVille=$bdd->prepare("SELECT COUNT(NOM) AS nb FROM ville WHERE NOM=?");
	$reqVille->execute(array($tab[$i][4]));


	while ($donneesVille=$reqVille->fetch())	
		{	
					
			if($donneesVille['nb']==0)
			{
				$requeteVille=$bdd->prepare("INSERT INTO  ville(ID_SITUER,NOM) VALUES(?,?)");
				$requeteVille->execute(array($tab[$i][5], $tab[$i][4]));
			}
			
			$requeteIdVille=$bdd->prepare("SELECT ID FROM ville WHERE NOM=?");
			$requeteIdVille->execute(array($tab[$i][4]));
			while ($donneesIdVille=$requeteIdVille->fetch())
			{	
				$tab[$i][7]= $donneesIdVille['ID'];
			}
		}
	

}


for($i=0; $i<32; $i++) // Etablissement
{
	
	$reqEtablissement=$bdd->prepare("SELECT COUNT(NOM) AS nb FROM etablissement WHERE NOM=?");
	$reqEtablissement->execute(array($tab[$i][3]));


	while ($donneesEtablissement=$reqEtablissement->fetch())	
	{	

		if($donneesEtablissement['nb']==0)

		{
			$requeteIdEtablissement=$bdd->prepare("INSERT INTO  etablissement(ID_LOCALISER,NOM) VALUES(?,?)");
			$requeteIdEtablissement->execute(array($tab[$i][7], $tab[$i][3]));
		}
		$SelectIdEtablissement=$bdd->prepare("SELECT ID FROM etablissement WHERE NOM=?");
		$SelectIdEtablissement->execute(array($tab[$i][3]));
		while ($donneesIdEtablissement=$SelectIdEtablissement->fetch())
		{	
			$tab[$i][8]= $donneesIdEtablissement['ID'];
		}
	}
}

for($i=0; $i<32; $i++) //Joueur
{
	echo "test";
	$requeteJoueur=$bdd->prepare("INSERT INTO joueur(ID_DEPARTEMENT, ID_SCOLARISER, NUMEROLICENCE,NOM,PRENOM,CLASSEMENTDPT)
										VALUES(?,?,?,?,?,?)");
	$requeteJoueur->execute(array($tab[$i][5],$tab[$i][8],$tab[$i][0],$tab[$i][1],$tab[$i][2],$tab[$i][6]));	

	
}

header("Location: http://localhost/badminton/pointage/");
?>
</body>
</html>