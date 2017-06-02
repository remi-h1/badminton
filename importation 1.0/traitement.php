<?php 
for($i=0; $i<104; $i++)
{
	$q=$bdd->prepare('INSERT INTO `matchs` (`IDMATCH`, `idTour`, `HEUREDBT`, `HEUREFIN`) VALUES
	(?, '', '10:20:00', '00:30:00')');
	$q->execute(array());
}