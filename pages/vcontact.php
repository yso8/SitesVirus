<?php
require_once 'configs/MySqlConfig.php';


class GestMembres {

  // <editor-fold defaultstate="collapsed" desc="ChampsStatique">
  /**
  * Objet de la classe PDO
  * @var PDO
  */
  public static $pdoCnxBase = null;
  /**
  * Objet de la classe PDOStatement
  * @var PDOStatement
  */
  public static $pdoStResults = null;
  public static $requete = "";//texte de la requête
  public static $resultat = null;//résultat de la requête
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

	public static function InsertInto($Nom,$EmailVisiteur,$Objet,$Message)
	{
        self::seConnecter();
        self::$requete = "insert into contact(Nom,EmailVisiteur,Objet,Message) values(:Nom , :EmailVisiteur , :Objet , :Message)";
        self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
        self::$pdoStResults->bindValue('Nom', $Nom);
				self::$pdoStResults->bindValue('EmailVisiteur', $EmailVisiteur);
				self::$pdoStResults->bindValue('Objet', $Objet);
				self::$pdoStResults->bindValue('Message', $Message);
        self::$pdoStResults->execute();
	}

	public static function getLesMails() {
	    self::seConnecter();

	    self::$requete = "SELECT MailMembre FROM membre";
	    self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
	    self::$pdoStResults->execute();
	    self::$resultat = self::$pdoStResults->fetchAll();

	    self::$pdoStResults->closeCursor();

	    return self::$resultat;
	}

  public static function getLesComptes()
{
  self::seConnecter();
  $mailconnect = htmlspecialchars($_POST['mailconnect']);
  $mdpconnect = sha1($_POST['mdpconnect']);
  self::$requete = "SELECT Exists(Select * FROM membre where MailMembre ='$mailconnect' And MotDepasse='$mdpconnect') as membrexist";
  self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
  self::$pdoStResults->execute();
  self::$resultat = self::$pdoStResults->fetchAll();
  self::$pdoStResults->closeCursor();

  return self::$resultat;



}

public static function getExist()
{
      self::seConnecter();

      self::$requete = "SELECT * FROM membre";
      self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
      self::$pdoStResults->execute();
      self::
      $resultat = self::$pdoStResults->fetchAll();

      self::$pdoStResults->closeCursor();

      return self::$resultat;

}

public static function getLesContacts() {
    self::seConnecter();

    self::$requete = "SELECT * FROM contact";
    self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
    self::$pdoStResults->execute();
    self::$resultat = self::$pdoStResults->fetchAll();

    self::$pdoStResults->closeCursor();

    return self::$resultat;
}
}

//Test de connexion
//$lesMembres = GestMembres::getLesMembres();
//var_dump($lesMembres);

//$lesMails = GestMembres::getLesMails();
//var_dump($lesMails);

if(isset($_POST['formcontact']))
{
	if(!empty($_POST['nom']) and !empty($_POST['email']) and !empty($_POST['objet']) and !empty($_POST['message']))
{
	$nom = htmlspecialchars($_POST['nom']);
	$email = htmlspecialchars($_POST['email']);
	$objet = htmlspecialchars($_POST['objet']);
	$message = htmlspecialchars($_POST['message']);

$messagelength = strlen($message);
		if($messagelength <= 255)
				{
						GestMembres::InsertInto($nom,$email,$objet,$message);
            $lesContacts = GestMembres::getLesContacts();
						$erreur = "Votre message a bien été transmis !";
						//$insertmbr = $bdd->prepare("INSERT INTO membre(pseudo, mail, motdepasse) VALUES(?,?,?)");
						//$insertmbr->execute(array($pseudo,$mail,$mdp));
				}
		else
				{
				$erreur = "Votre message ne doit pas dépasser 255 caractères !";
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
      <h2>Contactez-nous!</h2>
      <br><br>

      <form id="form1" method="post" action="">
	<fieldset><legend>Vos coordonnées</legend>
		<p><label for="nom">Nom :</label><input type="text" id="nom" name="nom" /></p>
		<p><label for="email">Email :</label><input type="text" id="email" name="email" /></p>
	</fieldset>
<br>
	<fieldset><legend>Votre message :</legend>
		<p><label for="objet">Objet :</label><input type="text" id="objet" name="objet" /></p>
		<p><label for="message">Message :</label><textarea id="message" name="message" cols="30" rows="8"></textarea></p>
	</fieldset>
<br>
	<div style="text-align:center;">
  <input type="submit" name="formcontact" value="Envoyer le formulaire !" />
  </div>
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
