<!-- Réaliser par : Florentin Merlet
    Page : Classement Final 
    But : Cette page a pour but de creer le classement final en allant chercher 
          le gagnant et le perdant de chaque match de classment -->




<?php
// on identifie la page du site
session_start();
$_GET['page']="classement";

?>
<!doctype>
<html>
  <head>
    <title>Classement Final</title>
    <!-- On inclue la parie du head général à toutes les pages -->
    <?php include('../head.php'); ?>
  </head>
  <body>

  <!-- On inclue l'entête du site -->
  <?php include('../header.php'); ?>

  <!-- La page du site -->
    <div class='pageClassement'>

<?php

        // on récupère chaque match
        $Matchs=$bdd->query("SELECT IDMATCH
                  FROM matchs
                  ORDER BY IDMATCH
                  ");

        while($match=$Matchs -> fetch())
        {
          /// on récupère les informations des deux joueurs du match à afficher (nom, prenom, école, ville)
          $reqJoueur=$bdd->prepare('SELECT J.NOM AS nomJoueur, PRENOM, E.NOM AS ecole, V.NOM AS ville, J.ID AS idJ
                        FROM rencontre R, joueur J, etablissement E, ville V
                        WHERE J.ID=R.IDJOUEUR
                        AND J.ID_SCOLARISER=E.ID
                        AND E.ID_LOCALISER=V.ID
                        AND IDMATCH=?
                                                GROUP BY IDJOUEUR
                                                ORDER BY IDJOUEUR');
          $reqJoueur->execute(array($match['IDMATCH']));

          $i=0;
          // on récupère les informations du joueur dans le tableau joueurs
          while($donneesJoueur=$reqJoueur -> fetch())
          {
            if($donneesJoueur['idJ']!=0) // si le joueur est enregisté 
            {
              $joueurs[$i]=array($donneesJoueur['idJ'], $donneesJoueur['nomJoueur'] . ' ' . $donneesJoueur['PRENOM'] . ", " . $donneesJoueur['ecole'] . " " . $donneesJoueur['ville']);
              $i++;
            }
            else // sinon, les joueurs du match ne sont pas encore défini
            {
              $joueurs[$i]="0";
            }
            
          }
          $reqJoueur->closeCursor();

          // si il n'y a pas de joueur, les scores sont nuls
          if($joueurs[0]==0 OR $joueurs[1]==0)
          {
            $scoreSet[0]=array(0,0,0);
            $scoreSet[1]=array(0,0,0);
          }
          else
          {
            // sinon on récupère les scores des joueures dans la table rencontre
            for ($i=0; $i<2; $i++) // pour les joueurs
            {
              for($j=0; $j<3; $j++) // pour les sets
              {
                $ScoreMatch=$bdd->prepare('SELECT SCORE
                              FROM rencontre R, matchs M
                              WHERE R.IDMATCH=M.IDMATCH
                                                  AND R.IDMATCH = ?
                              AND IDSET= ?
                              AND IDJOUEUR = ?
                              ');

                $ScoreMatch->execute(array($match['IDMATCH'], ($j+1), $joueurs[$i][0]));

                while($donneesScoresMatch= $ScoreMatch -> fetch())
                {
                  $scoreSet[$i][$j]=$donneesScoresMatch['SCORE'];
                }
                $ScoreMatch->closeCursor();
              }
            }
          }
          

          $setGagné[0]=0;
          $setGagné[1]=0;
          // on regarde qui a gagné pour chaque set
          for($j=0; $j<3; $j++)
          { 
            // si les scores sont égales à 0, alors le set n'a pas été joué (les deux premiers ont été gagné par le méme joueur) ou alors le math n'a pas encore été joué
            if($scoreSet[1][$j]==0 AND $scoreSet[0][$j]==0)
            {

            }
            // si le score du joueur 1 est inférieur à celui du joueur 2,
            elseif($scoreSet[0][$j]<$scoreSet[1][$j])
              $setGagné[1]++; // alors le joueur 2 a gagné le set
            else // dans le cas contraire
              $setGagné[0]++; // le joueur 1 a gagné le set
          }

          if($setGagné[0]==$setGagné[1])
            $vainqueursPerdants[($match['IDMATCH']-1)]=array(0, 0);
          elseif($setGagné[0]>$setGagné[1])
            $vainqueursPerdants[($match['IDMATCH']-1)]=array($joueurs[0][1], $joueurs[1][1]);
          else
            $vainqueursPerdants[($match['IDMATCH']-1)]=array($joueurs[1][1], $joueurs[0][1]);
        
        }
        $Matchs->closeCursor();

         ?>


<center>

  <!-- On crée un tableau dans lequel on insère les gagnants et les perdants des matchs de classement que l'on a récupéré précedémment calculé dans le programme  -->

<h1>Classement Final</h1> 
  <p /><table border="10" >
    <tr><th width=75>RANG</th><th width-min=150>JOUEUR</th><th width=200 class="vide"></th><th width=75>RANG</th><th width-min=150>JOUEUR</th></tr>
 <?php

?>
      <tr>
        <td width=75><?php echo 1 ?></td> <!-- ici, le [103][0] symbolise le gagnant du match 104 (la grande finale)-->
        <td width-min=150><?php if(!empty($vainqueursPerdants[103][0])) { echo $vainqueursPerdants[103][0]; } else { echo "<span class='defautVP'>Vainqueur du Match 104"; } ?></td>
      
        <td widh=200 class="vide"><?php echo " "?>

        <td width=75><?php echo 17 ?></td>
        <td width-min=150><?php if(!empty($vainqueursPerdants[95][0])) { echo $vainqueursPerdants[95][0]; } else { echo "<span class='defautVP'>Vainqueur du Match 96"; } ?></td>
      <tr>



      <tr>
        <td width=75><?php echo 2 ?></td> <!-- ici, le [103][1] symbolise le perdant du match 104 (la grande finale)-->
        <td width-min=150><?php if(!empty($vainqueursPerdants[103][1])) { echo $vainqueursPerdants[103][1]; } else { echo "<span class='defautVP'>Perdant du Match 104"; } ?></td>

        <td widh=200 class="vide"><?php echo " "?>

        <td width=75><?php echo 18 ?></td>
        <td width-min=150><?php if(!empty($vainqueursPerdants[95][1])) { echo $vainqueursPerdants[95][1]; } else { echo "<span class='defautVP'>Perdant du Match 96"; } ?></td>
      <tr>
     
      

      <tr>
        <td width=75><?php echo 3 ?></td>
        <td width-min=150><?php if(!empty($vainqueursPerdants[102][0])) { echo $vainqueursPerdants[102][0]; } else { echo "<span class='defautVP'>Vainqueur du Match 103"; } ?></td>
      
        <td widh=200 class="vide"><?php echo " "?>

        <td width=75><?php echo 19 ?></td>
        <td width-min=150><?php if(!empty($vainqueursPerdants[94][0])) { echo $vainqueursPerdants[94][0]; } else { echo "<span class='defautVP'>Vainqueur du Match 95"; } ?></td>
      <tr>
      


      <tr>
        <td width=75><?php echo 4 ?></td>
        <td width-min=150><?php if(!empty($vainqueursPerdants[102][1])) { echo $vainqueursPerdants[102][1]; } else { echo "<span class='defautVP'>Perdant du Match 103"; } ?></td>
     
        <td widh=200 class="vide"><?php echo " "?>

        <td width=75><?php echo 20 ?></td>
        <td width-min=150><?php if(!empty($vainqueursPerdants[94][1])) { echo $vainqueursPerdants[94][1]; } else { echo "<span class='defautVP'>Perdant du Match 95"; } ?></td>
      <tr>

     

      <tr>
        <td width=75><?php echo 5 ?></td>
        <td width-min=150><?php if(!empty($vainqueursPerdants[101][0])) { echo $vainqueursPerdants[101][0]; } else { echo "<span class='defautVP'>Vainqueur du Match 102"; } ?></td>
     
        <td widh=200 class="vide"><?php echo " "?>

        <td width=75><?php echo 21 ?></td>
        <td width-min=150><?php if(!empty($vainqueursPerdants[93][0])) { echo $vainqueursPerdants[93][0]; } else { echo "<span class='defautVP'>Vainqueur du Match 94"; } ?></td>
      <tr>



      <tr>
        <td width=75><?php echo 6 ?></td>
        <td width-min=150><?php if(!empty($vainqueursPerdants[101][1])) { echo $vainqueursPerdants[101][1]; } else { echo "<span class='defautVP'>Perdant du Match 102"; } ?></td>
     
        <td widh=200 class="vide"><?php echo " "?>

        <td width=75><?php echo 22 ?></td>
        <td width-min=150><?php if(!empty($vainqueursPerdants[93][1])) { echo $vainqueursPerdants[93][1]; } else { echo "<span class='defautVP'>Perdant du Match 94"; } ?></td>
      <tr>
      


      <tr>
        <td width=75><?php echo 7 ?></td>
        <td width-min=150><?php if(!empty($vainqueursPerdants[100][0])) { echo $vainqueursPerdants[100][0]; } else { echo "<span class='defautVP'>Vainqueur du Match 101"; } ?></td>
     
        <td widh=200 class="vide"><?php echo " "?>

        <td width=75><?php echo 23 ?></td>
        <td width-min=150><?php if(!empty($vainqueursPerdants[92][0])) { echo $vainqueursPerdants[92][0]; } else { echo "<span class='defautVP'>Vainqueur du Match 93"; } ?></td>
      <tr>

   

      <tr>
        <td width=75><?php echo 8 ?></td>
        <td width-min=150><?php if(!empty($vainqueursPerdants[100][1])) { echo $vainqueursPerdants[100][1]; } else { echo "<span class='defautVP'>Perdant du Match 101"; } ?></td>
     
        <td widh=200 class="vide"><?php echo " "?>

        <td width=75><?php echo 24 ?></td>
        <td width-min=150><?php if(!empty($vainqueursPerdants[92][1])) { echo $vainqueursPerdants[92][1]; } else { echo "<span class='defautVP'>Perdant du Match 93"; } ?></td>
      <tr>
      


      <tr>
        <td width=75><?php echo 9 ?></td>
        <td width-min=150><?php if(!empty($vainqueursPerdants[99][0])) { echo $vainqueursPerdants[99][0]; } else { echo "<span class='defautVP'>Vainqueur du Match 100"; } ?></td>
      
        <td widh=200 class="vide"><?php echo " "?>
         
        <td width=75><?php echo 25 ?></td>
        <td width-min=150><?php if(!empty($vainqueursPerdants[91][0])) { echo $vainqueursPerdants[91][0]; } else { echo "<span class='defautVP'>Vainqueur du Match 92"; } ?></td>
      <tr>

     

      <tr>
        <td width=75><?php echo 10 ?></td>
        <td width-min=150><?php if(!empty($vainqueursPerdants[99][1])) { echo $vainqueursPerdants[99][1]; } else { echo "<span class='defautVP'>Perdant du Match 100"; } ?></td>
     
        <td widh=200 class="vide"><?php echo " "?>

        <td width=75><?php echo 26 ?></td>
        <td width-min=150><?php if(!empty($vainqueursPerdants[91][1])) { echo $vainqueursPerdants[91][1]; } else { echo "<span class='defautVP'>Perdant du Match 92"; } ?></td>
      <tr>

     

      <tr>
        <td width=75><?php echo 11 ?></td>
        <td width-min=150><?php if(!empty($vainqueursPerdants[98][0])) { echo $vainqueursPerdants[98][0]; } else { echo "<span class='defautVP'>Vainqueur du Match 99"; } ?></td>
     
        <td widh=200 class="vide"><?php echo " "?>

        <td width=75><?php echo 27 ?></td>
        <td width-min=150><?php if(!empty($vainqueursPerdants[90][0])) { echo $vainqueursPerdants[90][0]; } else { echo "<span class='defautVP'>Vainqueur du Match 91"; } ?></td>
      <tr>
     


      <tr>
        <td width=75><?php echo 12 ?></td>
        <td width-min=150><?php if(!empty($vainqueursPerdants[98][1])) { echo $vainqueursPerdants[98][1]; } else { echo "<span class='defautVP'>Perdant du Match 99"; } ?></td>
     
        <td widh=200 class="vide"><?php echo " "?>

        <td width=75><?php echo 28 ?></td>
        <td width-min=150><?php if(!empty($vainqueursPerdants[90][1])) { echo $vainqueursPerdants[90][1]; } else { echo "<span class='defautVP'>Perdant du Match 91"; } ?></td>
      <tr>

      

      <tr>
        <td width=75><?php echo 13 ?></td>
        <td width-min=150><?php if(!empty($vainqueursPerdants[97][0])) { echo $vainqueursPerdants[97][0]; } else { echo "<span class='defautVP'>Vainqueur du Match 98"; } ?></td>
     
        <td widh=200 class="vide"><?php echo " "?>

        <td width=75><?php echo 29 ?></td>
        <td width-min=150><?php if(!empty($vainqueursPerdants[89][0])) { echo $vainqueursPerdants[89][0]; } else { echo "<span class='defautVP'>Vainqueur du Match 90"; } ?></td>
      <tr>  

    

      <tr>
        <td width=75><?php echo 14 ?></td>
        <td width-min=150><?php if(!empty($vainqueursPerdants[97][1])) { echo $vainqueursPerdants[97][1]; } else { echo "<span class='defautVP'>Perdant du Match 98"; } ?></td>
     
        <td widh=200 class="vide"><?php echo " "?>

        <td width=75><?php echo 30 ?></td>
        <td width-min=150><?php if(!empty($vainqueursPerdants[89][1])) { echo $vainqueursPerdants[89][1]; } else { echo "<span class='defautVP'>Perdant du Match 90"; } ?></td>
      <tr>
     


      <tr>
        <td width=75><?php echo 15 ?></td>
        <td width-min=150><?php if(!empty($vainqueursPerdants[96][0])) { echo $vainqueursPerdants[96][0]; } else { echo "<span class='defautVP'>Vainqueur du Match 97"; } ?></td>
     
        <td widh=200 class="vide"><?php echo " "?>

        <td width=75><?php echo 31 ?></td>
        <td width-min=150><?php if(!empty($vainqueursPerdants[88][0])) { echo $vainqueursPerdants[88][0]; } else { echo "<span class='defautVP'>Vainqueur du Match 89"; } ?></td>
      <tr>

     
      
      <tr>
        <td width=75><?php echo 16 ?></td>
        <td width-min=150><?php if(!empty($vainqueursPerdants[96][1])) { echo $vainqueursPerdants[96][1]; } else { echo "<span class='defautVP'>Perdant du Match 97"; } ?></td>
      
        <td widh=200 class="vide"><?php echo " "?>

        <td width=75><?php echo 32 ?></td>
        <td width-min=150><?php if(!empty($vainqueursPerdants[88][1])) { echo $vainqueursPerdants[88][1]; } else { echo "<span class='defautVP'>Vainqueur du Match 89"; } ?></td>
      <tr>  
      
    </div>    

</center>
  </body>
</html>