<?php
// on identifie la page du site
session_start();
$_GET['page']="Traitpage_Pointage";
?>
<!doctype>
<html>
  <head>
    <title>Traitement Pointage</title>
    <!-- On inclue la parie du head général à toutes les pages -->
    <?php include('../head.php'); ?>
  </head>

<body>
 <!-- On inclue l'entête du site -->
  <?php include('../header.php'); ?>

  <!-- La page du site -->
    <div class='page'>
   

<?php
    
$i=1;
$nb=(isset($_POST['nb'])) ? $_POST['nb'] : "";



while($i<= 32) 
{

    $pointage="checkbox_" . $i;

    // on regarde si le premier match du joueur est commencé
    $match=$bdd->prepare('SELECT DISTINCT HEUREDBT
                        FROM matchs M, rencontre R 
                        WHERE M.IDMATCH=R.IDMATCH
                        AND R.IDJOUEUR=?
                        AND M.IDMATCH<=16
                        ');
    $match->execute(array($i));

    while($HeureDebutmatch=$match -> fetch())
    {
     $hDebut=$HeureDebutmatch['HEUREDBT'];
      
    }
     // si le premier match du joueur n'a pas encore commencé, alors on peut modifier son absence, sinon on ne peut pas
     // c'est une sécurité pour ne pas perturber les scores des matchs déjà joués
    if(!isset($hDebut) OR $hDebut==0)
    {
      if (empty($_POST[$pointage]))
      {
         $presence=0;
      }
      else
      {
         $presence=1;
      }

      $sql = $bdd->prepare('UPDATE joueur SET POINTAGE = :presence WHERE ID= :i');
      $sql->execute(array('presence' => $presence, 'i' => $i));
    }
    
    $i++;
}

    $res = $bdd->prepare('SELECT COUNT(ID) AS nb FROM joueur WHERE POINTAGE= 1');
    $res -> execute();

    while($nbpointage = $res-> fetch()){

      $nbpointage=$nbpointage['nb'];

?></br></br>
<?php

      echo  $nbpointage." joueurs ont été pointés";
    }
    
// on ragarde si le tournoi à commencé
    $tournoi=$bdd->query('SELECT COUNT(IDMATCH) AS nb
                    FROM matchs
                    WHERE HEUREDBT!=0');
    while($nombreMatch=$tournoi -> fetch())
    {
      $nbMatch=$nombreMatch['nb'];
    }

    if($nbMatch==0)
    {
      ?>
        <form action="http://localhost/badminton/classementRang.php/" method="link" ><input type="submit" value = "commencer le tournoi" /> </form>
      <?php
    }
    else
    {
      ?>
        <form action="http://localhost/badminton/tableau_tournoi/" method="link" ><input type="submit" value = "Voir le tableau tournoi" /> </form>
      <?php
    }
?>
<form action="http://localhost/badminton/pointage/" method="link" ><input type="submit" value = "Retour au pointage" /> </form>  
</body></html>

