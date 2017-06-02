<?php 


$donnees=preg_split("/[\s]+/", rtrim($_POST['l']));

echo $donnees[0] . "</br>";
echo $donnees[1] . "</br>";
echo $donnees[2] . "</br>";
echo $donnees[3] . "</br>";
echo $donnees[4] . "</br>";
echo $donnees[5] . "</br>";
echo $donnees[6] . "</br>";

echo "<br />-------------</br>";
for($i=0;$i<=32;$i++) //joueurs
{
	for($j=0;$j<=6;$j++)
	{
		$nb=$i*6+$j;
		//$tab[$i][$j]=$donnees[$nb];
		echo $donnees[$nb];
		echo " ";
	}
	echo "<br/>";
}

echo "<br />-------------</br>";
/*
for($i=0; $i<32; $i++)
{
	for($j=0;$j<6;$j++)
	{
		echo $tab[$i][$j] . " ";
	}
	echo "<br/>";

}
/*
for($i=0;$i<32;$i++)
{
//"INSERT INTO joueur(ID_DEPARTEMENT,ID_SCOLARISER,NOM,PRENOM,NUMEROLICENCE,CLASSEMENTDPT)
//	VALUES($variables incrémentés )" Requete à réaliser
}
?>*/
