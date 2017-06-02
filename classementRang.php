<?php
// page classement des joueurs
// auteur : Rémi Hillériteau
// description : cette page permet d'établir le classement des joueurs, et de créer les 16 premiers matchs

session_start();
//connexion à la base de données en PDO
try
{
    // On se connecte à MySQL
    $bdd = new PDO('mysql:host=localhost;dbname=badminton;charset=utf8', 'root', '');
}
catch(Exception $e)
{
    // En cas d'erreur, on affiche un message et on arrête tout
     die('Erreur : '.$e->getMessage());
}

// on vérifie que le tournoi n'a pas encore commencé
$matchCommencer=$bdd->query('SELECT COUNT(IDMATCH) AS nb
							FROM matchs
							WHERE HEUREDBT!=0');
while($match=$matchCommencer->fetch())
{
	$nbCommencer=$match['nb'];
}
// on vérifie si le tournoi est fini
$matchFini=$bdd->query('SELECT COUNT(IDMATCH) AS nb
							FROM matchs
							WHERE HEUREFIN!=0');
while($match=$matchFini->fetch())
{
	$nbFini=$match['nb'];
}

// si le tournoi est commencé et il reste des matchs à jouer
if($nbCommencer>=1 OR $nbFini>=1)
{
	// alors on redirge l'utilisateur sur la page tournoi
	header("Location: http://localhost/badminton/tableau_tournoi/");
}


// on récupère le nombre de joueur pour un match
	//initialisation du tableau depatement, 1er ligne pour identifier les départements, puis la seconde pour enregistrer le nombre d'élève par departement
	$departement[0]=array(49,72,85,53,44);
	$departement[1]=array(0,0,0,0,0);
	$j=0;
	for($i=0; $i<5; $i++)
	{
		$nbjoueurDep=$bdd->prepare('SELECT COUNT(ID) AS nb
								FROM joueur
								WHERE ID_DEPARTEMENT=?
								');
		$nbjoueurDep->execute(array($departement[0][$i]));


		
		while($nbJDep=$nbjoueurDep->fetch())
		{
			$departement[1][$j]=$nbJDep['nb'];
			// affichage pour test
			echo $departement[0][$j] . " : " . $departement[1][$j] . "<br />";
			$j++;
		}
	}	

	// on détermine le "poid" des dépatements
		$placeDP=4;
		$classementDP;
		$i=0; // correspond au nombre de joueur par département ($i prend les valeurs)
		while($i<32) // tant que les 5 départemants ne sont pas classés
		{	
			$l=0;
			$equivalence=0;
			for($j=0; $j<5; $j++) // on test les 5 départements
			{
				
				if($departement[1][$j]==$i) // si le nombre de joueur du département est égale à $i
				{
					$donnees[$l]=$departement[0][$j];
					$equivalence++;
					$l++;
				}
			}
	
			if($equivalence!=0) // si au moins une valeur est égale à $i
			{
				while(count($donnees)!=0)
				{
					$nb=count($donnees); // on conmpte le nombre de département égale à $i
					$rand=rand(0, 100); // on défini une valeur aléatoire
					$reste=$rand%$nb; // on fait un modulo du nombre aléatoire par le nombre de département
					$classementDP[$placeDP]=$donnees[$reste]; // on valorise classementDP (de 4 à 0) avec une des valeurs de^$donnees
					array_splice($donnees, $reste, 1); // on supprime la valeur ajouter au classement
					$placeDP--;
				}
					

			}
				
		$i++;
		}
		// affichache pour vérifier
			echo "<br/>-----------------<br/>";
				for($p=0; $p<5; $p++)
				{
					echo $classementDP[$p] . " - ";
					//echo "<br />" . $p . " : " . $donnees[$p];
				}

	//on récupère les joueurs par département
				$nombreJoueur=0;
		for($i=0; $i<5; $i++)
		{
			$j=0; // lorsque l'on change de département, on remet $j à 0
			$joueursBase=$bdd->prepare("SELECT ID
										FROM joueur
										WHERE ID_DEPARTEMENT=?
										AND POINTAGE=1
										ORDER BY CLASSEMENTDPT
										");
			$joueursBase->execute(array($classementDP[$i]));

			while($donneesJoueurs=$joueursBase->fetch())
			{
				// on ajoute le joueur dans un tableau correspondant à son département 
				$Joueur[$i][$j]=$donneesJoueurs['ID'];
				$j++;
				$nombreJoueur++;
			}
		}

	// on récupère les joueurs qui ne sont pas pointé
		
		for($i=0; $i<5; $i++)
		{
			$j=0;
			 // lorsque l'on change de département, on remet $j à 0
			$joueursAbs=$bdd->prepare("SELECT ID
										FROM joueur
										WHERE ID_DEPARTEMENT=?
										AND POINTAGE=0
										ORDER BY CLASSEMENTDPT
										");
			$joueursAbs->execute(array($classementDP[$i]));

			while($donneesJoueursAbs=$joueursAbs->fetch())
			{
				// on ajoute le joueur dans un tableau correspondant à son département 
				$absents[$i][$j]=$donneesJoueursAbs['ID'];
				$j++;
			}
		}
		
	//  on défini les rangs aux joueurs présents
		$p=0;
		echo "nb joueurs presents : " . $nombreJoueur;
		for($i=0; $i<$nombreJoueur; $i++)
		{
			while(!isset($Joueur[$p%5]) OR count($Joueur[$p%5])==0)
			{
				$p++;
			}
			$rang[$i]=$Joueur[$p%5][0];
			array_shift($Joueur[$p%5]); // on supprime la valeur ajouter au classement
			$p++;		
		}

	//  on ajoute les rangs des joueurs qui ne sont pas présent
		$p=0;
		for($i=$nombreJoueur; $i<32; $i++)
		{
			while(!isset($absents[$p%5]) OR count($absents[$p%5])==0)
			{
				$p++;
			}
			$rang[$i]=$absents[$p%5][0];
			array_shift($absents[$p%5]); // on supprime la valeur ajouter au classement
			$p++;	
		}

		// affichage pour tester
			echo "<br/>-----------------<br/>";
			for($i=0; $i<32; $i++)
			{
				echo $i+1 . ": ". $rang[$i] . "<br />";
			}

	//  on enregistre les rangs dans la base de données
		for($i=0; $i<32; $i++)
		{
			$ajouterRang=$bdd->prepare('UPDATE joueur
								SET RANG=?
								WHERE ID=?
								');
			$ajouterRang->execute(array($i+1, $rang[$i]));
		}

	// on ajoute les rencontres des 16 premiers matchs
		// on défini les joueurs 
		$joueursMatchDefaut[0]=array(1, 32);
		$joueursMatchDefaut[1]=array(17, 16);
		$joueursMatchDefaut[2]=array(9, 24);
		$joueursMatchDefaut[3]=array(25, 8);
		$joueursMatchDefaut[4]=array(5, 28);
		$joueursMatchDefaut[5]=array(21, 12);
		$joueursMatchDefaut[6]=array(13, 20);
		$joueursMatchDefaut[7]=array(29, 4);
		$joueursMatchDefaut[8]=array(3, 30);
		$joueursMatchDefaut[9]=array(19, 14);
		$joueursMatchDefaut[10]=array(11, 22);
		$joueursMatchDefaut[11]=array(27, 6);
		$joueursMatchDefaut[12]=array(7, 26);
		$joueursMatchDefaut[13]=array(23, 10);
		$joueursMatchDefaut[14]=array(15, 18);
		$joueursMatchDefaut[15]=array(31, 2);

		$viderRencontre=$bdd->query("TRUNCATE rencontre");
		
		for($i=0; $i<16; $i++)
		{
			for($j=0; $j<2; $j++)
			{
				for($k=0; $k<3; $k++)
				{
					$recupererIdJoueur=$bdd->prepare('SELECT ID 
											FROM joueur
											WHERE RANG=?');
					$recupererIdJoueur->execute(array($joueursMatchDefaut[$i][$j]));

					while($idJoueur=$recupererIdJoueur->fetch())
					{
						$ajouterMatch=$bdd->prepare('INSERT INTO rencontre(IDJOUEUR, IDMATCH, IDSET)
											VALUES(?, ?, ?)');
						$ajouterMatch->execute(array($idJoueur['ID'], ($i+1), ($k+1)));
					}
					
				}
			}
			
		}

		// redirection vers page de confirmation du classement
		header("Location: http://localhost/badminton/affichageClassementRang.php");
		?>