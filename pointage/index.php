<!-- Réaliser par : Florentin Merlet
    Page : Pointage
    But : Cette page a pour but de pointer les joueurs présent au tournoi. Au début du tournoi, chaque joueur ira signaler sa présence
          et sera pointé. Un joueur pourra être pointé jusqu'à ce que son premier soit commencé. -->


<?php
// on identifie la page du site
session_start();
$_GET['page']="pointage";
?>
<!doctype>
<html>
  <head>
    <title>POINTAGE</title>
    <!-- On inclue la parie du head général à toutes les pages -->
    <?php include('../head.php'); ?>
  </head>
  <body>
<center>

  <!-- On inclue l'entête du site -->
  <?php include('../header.php'); ?>

  <!-- La page du site -->
    <div class='page'>

 <form action="Traitpage_Pointage.php" method="post">
            <h1>Pointage des joueurs</h1>

      <p /><table border="2" >
    		<tr><th width=150>NOM</th><th width=150>PRENOM</th><th width=150>DEPARTEMENT </th><th width=150>SCOLARISER </th><th width=100>POINTAGE </th></tr>


    <?php
          // on va chercher dans la base chaque joueur inscrit au tournoi (nom prenom etablissement et departement)
          $res = $bdd->query('SELECT J.NOM AS nomJoueur, PRENOM, ID_DEPARTEMENT, E.NOM AS nomEtablissement, D.NOM AS nomDepartement, POINTAGE, J.ID AS idJ
                              FROM joueur J, etablissement E, departement D
                              WHERE J.ID_DEPARTEMENT=d.ID AND J.ID_SCOLARISER =E.ID 
                              ORDER BY J.ID');
        
      $nb=1;     		

        while($ligne = $res->fetch())
    		{
          // on regarde si son premier match à déjà commencé
          $case="available";
          $match=$bdd->prepare('SELECT DISTINCT HEUREDBT
                              FROM matchs M, rencontre R 
                              WHERE M.IDMATCH=R.IDMATCH
                              AND R.IDJOUEUR=?
                              AND M.IDMATCH<=16
                              ');
          $match->execute(array($ligne['idJ']));

          while($HeureDebutmatch=$match -> fetch())
          {
            // si le premier match à déjà commencé, alors on ne peut plus modifier son absence
            if($HeureDebutmatch['HEUREDBT']!=0)
              $case="disable";
          }

          
    ?>
    			<tr>
    				<td width=150><center><?php echo $ligne["nomJoueur"]?></center></td>
    				<td width=150><center><?php echo $ligne["PRENOM"]?></center></td>
            <td width=150><center><?php echo $ligne["nomDepartement"]?></center></td>
            <td width=150><center><?php echo $ligne["nomEtablissement"]?></center></td>
            <td width=100><center><input type="checkbox" name="checkbox_<?php echo $nb; ?>" <?php if($ligne["POINTAGE"]==1) { echo " checked='checked' ";} 
            if ($case=="disable") { echo " disabled=false "; }?>/><br /></center></td>
              <!-- ici on verifie si le premier match du joueur a pointé est commencé ou non -->

    			</tr>
     <?php 
      $nb++;   
      }
      ?>
      <input name="nb" id="nb" value="<?php echo $nb?>" type="hidden"> <!-- On calcul le nombre de -->

  <input type="submit" value = "Valider le pointage" />     
</form>     


</center>
</body>
</html>


