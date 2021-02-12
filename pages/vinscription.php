<?php
require_once 'configs/MySqlConfig.php';

class GestMembres {

  // <editor-fold defaultstate="collapsed" desc="ChampsStatique">
  /**
  * Objet de la classe PDO
  * @var PDO
  */
  private static $pdoCnxBase = null;
  /**
  * Objet de la classe PDOStatement
  * @var PDOStatement
  */
  private static $pdoStResults = null;
  private static $requete = "";//texte de la requête
  private static $resultat = null;//résultat de la requête
  // </editor-fold>

  // <editor-fold defaultstate="collapsed" desc="MéthodesStatiques">
  /**
  * Permet de se connecter à la base de données
  */

public static function seConnecter() {
if (!isset(self::$pdoCnxBase)) { //S'il n'y a pas encore eu de connexion
try {
self::$pdoCnxBase = new PDO('mysql:host=' . MysqlConfig::SERVEUR . ';dbname=' .
MysqlConfig::BASE, MysqlConfig::UTILISATEUR, MysqlConfig::MOT_DE_PASSE);
self::$pdoCnxBase->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
self::$pdoCnxBase->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);
self::$pdoCnxBase->query("SET CHARACTER SET utf8");
} catch (Exception $e) {
// l’objet pdoCnxBase a généré automatiquement un objet de type Exception
echo 'Erreur : ' . $e->getMessage() . '<br />'; // méthode de la classe Exception
echo 'Code : ' . $e->getCode(); // méthode de la classe Exception
}
}
}

public static function seDeconnecter() {
    self::$pdoCnxBase = null;
}
// </editor-fold>


public static function getLesMembres() {
    self::seConnecter();

    self::$requete = "SELECT * FROM membre";
    self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
    self::$pdoStResults->execute();
    self::$resultat = self::$pdoStResults->fetchAll();

    self::$pdoStResults->closeCursor();

    return self::$resultat;
}

	public static function InsertInto($MotDepass,$UsernameMembre,$PrenomMembre,$NomMembre,$MailMembre)
	{
        self::seConnecter();
        self::$requete = "insert into Membre(MotDepasse,UsernameMembre,PrenomMembre,NomMembre,MailMembre) values(:MotDepasse , :UsernameMembre , :PrenomMembre , :NomMembre , :MailMembre)";
        self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
        self::$pdoStResults->bindValue('MotDepasse', $MotDepass);
				self::$pdoStResults->bindValue('UsernameMembre', $UsernameMembre);
				self::$pdoStResults->bindValue('PrenomMembre', $PrenomMembre);
				self::$pdoStResults->bindValue('NomMembre', $NomMembre);
				self::$pdoStResults->bindValue('MailMembre', $MailMembre);
        self::$pdoStResults->execute();
	}

	public static function getLesMails()
	{
	    self::seConnecter();

	    self::$requete = "SELECT MailMembre FROM membre";
	    self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
	    self::$pdoStResults->execute();
	    self::$resultat = self::$pdoStResults->fetchAll();

	    self::$pdoStResults->closeCursor();

	    return self::$resultat;
	}

}
?>


<?php

//Test de connexion
//$lesMembres = GestMembres::getLesMembres();
//var_dump($lesMembres);

//$lesMails = GestMembres::getLesMails();
//var_dump($lesMails);




if(isset($_POST['forminscription']))
{
	if(!empty($_POST['pseudo']) and !empty($_POST['mdp']) and !empty($_POST['prenom']) and !empty($_POST['nom']) and !empty($_POST['mail']) )
{
	$pseudo = htmlspecialchars($_POST['pseudo']);
	$mdp = sha1($_POST['mdp']);
	//$mdp = htmlspecialchars($_POST['mdp']);
	$prenom = htmlspecialchars($_POST['prenom']);
	$nom = htmlspecialchars($_POST['nom']);
	$mail = htmlspecialchars($_POST['mail']);

$pseudolength = strlen($pseudo);
		if($pseudolength <= 255)
				{
						GestMembres::InsertInto($mdp,$pseudo,$prenom,$nom,$mail);
						$lesMembres = GestMembres::getLesMembres();
						$erreur = "Votre compte a bien été crée !";
						//$insertmbr = $bdd->prepare("INSERT INTO membre(pseudo, mail, motdepasse) VALUES(?,?,?)");
						//$insertmbr->execute(array($pseudo,$mail,$mdp));
				}
		else
				{
				$erreur = "Votre pseudo ne doit pas dépasser 255 caractères !";
				}
		}
else
		{
$erreur = "Tous les champs doivent être complétés !";
		}
}




?>



<!DOCTYPE HTML>
<html>

			<section id="three" class="wrapper">
				<div class="inner">
					<header class="align-center">
            <br>
            <br>
		<h2>Inscription</h2>
		<br><br>
		<form method="POST" action="">

			<tr>
					<td>
						<br>
						<label for="pseudo">
							Pseudo :</label>
					</td>
					<td>
						<input type="text"
						id="pseudo"
						name="pseudo"/>
					</td>
				</tr>
			<tr>
					<td>
						<br>
						<label for="mdp">
							Mot de passe:</label>
					</td>
					<td>
						<input type="password"
						id="mdp"
						name="mdp"/>
					</td>
				</tr>

					<tr>
							<td>
								<br>
								<label for="prenom">
									Prénom :</label>
							</td>
							<td>
								<input type="text"
								id="prenom"
								name="prenom"/>
							</td>
						</tr>

						<tr>
								<td>
									<br>
									<label for="nom">
										Nom :</label>
								</td>
								<td>
									<input type="text"
									id="nom"
									name="nom"/>
								</td>
							</tr>

							<tr>
									<td>
										<br>
										<label for="mail">
											Mail :</label>
									</td>
									<td>
										<input type="text"
										id="mail"
										name="mail"/>
									</td>
								</tr>
								<td></td>
									<td>
										<br>
									<input type="submit" name="forminscription" value="Je m'inscris">
								</td>
			</form>
<?php
if(isset($erreur))
{
	echo $erreur;
}
 ?>
					</header>

				</div>
			</section>

                <section id="two" class="wrapper style1 special">
				<div class="inner">
          <br>
          <h3> Les différents accès du site</h3>
          <br>
          <br>
          <nav id="nav">
<button onclick="window.location.href = 'http://phpmyadmin.g05.joutes.club/';">Accès PhpMyAdmin</button>
<button onclick="window.location.href = 'http://matomo.g05.joutes.club/';">Accès Matomo</button>
<button onclick="window.location.href = 'http://owncloud.g05.joutes.club/';">Accès OwnCloud</button>
</nav>
				</div>
			</section>


<?php
require Chemins::VUES . 'baspied.php';
?>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>

	</body>
</html>
