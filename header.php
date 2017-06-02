
<?php 
if(!isset($_GET['page']))
			$_GET['page']="accueil";?>

		<div class="header">
			<h1><span class="bleu">Tournoi</span><span class="gris"> régional de </span><span class="vert">badminton</span></h1>
			<p class="titreRegion">Région Pays de la loire</p>
			<table class='nav'>
				<tr>
		  			<td <?php if(isset($_GET['page']) and $_GET['page']=='accueil') { echo 'class="lienMenuActif"'; } else { echo 'class="lienMenu"'; } ?> > 
		  				<a  href='http://localhost/badminton/' alt='accueil'><div class="homeImage"></div></a>
		  			</td>
		  			<td <?php if(isset($_GET['page']) and $_GET['page']=='pointage') { echo 'class="lienMenuActif"'; } else { echo 'class="lienMenu"'; } ?> > 
			  			<a  href='http://localhost/badminton/pointage' alt='pointage'>pointage</a>
			  		</td>
		  			<td <?php if(isset($_GET['page']) and $_GET['page']=='matchsenattente') { echo 'class="lienMenuActif"'; } else { echo 'class="lienMenu"'; } ?> > 
			  			<a  href='http://localhost/badminton/matchs_en_attentes/' alt='matchs en attentes'>matchs en attentes</a>
			  		</td>
		  			<td <?php if(isset($_GET['page']) and $_GET['page']=='matchsencours') { echo 'class="lienMenuActif"'; } else { echo 'class="lienMenu"'; } ?>> 
			  			<a  href='http://localhost/badminton/matchs_en_cours/' alt='matchs en cours'>matchs en cours</a>
			  		</td>
		  			<td <?php if(isset($_GET['page']) and $_GET['page']=='matchsfinis') { echo 'class="lienMenuActif"'; } else { echo 'class="lienMenu"'; } ?>> 
			  			<a  href='http://localhost/badminton/matchs_finis/' alt='matchs en finis'>matchs finis</a>
			  		</td>
		  			<td <?php if(isset($_GET['page']) and $_GET['page']=='tableau_tournoi') { echo 'class="lienMenuActif"'; } else { echo 'class="lienMenu"'; } ?>> 
			  			<a  href='http://localhost/badminton/tableau_tournoi/' alt='tableau tournoi'>tableau tournoi</a>
			  		</td>
		  			<td <?php if(isset($_GET['page']) and $_GET['page']=='classement') { echo 'class="lienMenuActif"'; } else { echo 'class="lienMenu"'; } ?>> 
		  			<a  href='http://localhost/badminton/classement' alt='classement'>classement</a>
		  			</td>
		  		</tr>
			</table>
		</div>

        <?php
        try
        {
            $bdd = new PDO('mysql:host=localhost;dbname=badminton;charset=utf8', 'root', ''); // connexion à la base de données
        }
        catch(Exception $e)
        {
            die('Erreur : '.$e->getMessage()); // En cas d'échec, il affiche un message d'erreur
        }

        ?>