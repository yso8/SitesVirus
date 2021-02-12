<?php
require_once 'configs/MySqlConfig.php';
class VariablesGlobales {

}
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

}

//Test de connexion
//$lesMembres = GestMembres::getLesMembres();
//var_dump($lesMembres);

//$lesMails = GestMembres::getLesMails();
//var_dump($lesMails);

if(isset($_POST["formconnexion"]))
{
  //$mailconnect = htmlspecialchars($_POST['mailconnect']);
  //$mdpconnect = sha1($_POST['mdpconnect']);
  if(!empty($mailconnect) AND !empty($mdpconnect))
  {
    //$lesComptes = GestMembres::getLesComptes();
    //$lesLo = GestMembres::$requete['membrexist'];
    //var_dump($lesLo);
    //if (GestMembres::$requete['membrexist'] == true)
    //{
      //$erreur = "Le compte existe !";
    //}
    //else
    //{
      //$erreur = "Le compte n'existe pas !";
    //}

  }
  else
  {
    $erreur = "Le formulaire d'inscription n'est pas terminé !";
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
      <h2>Connexion</h2>
      <br><br>
      <form method="POST" action="">
        <input type="text" name="mailconnect" placeholder="Mail"/>
        <br>
        <input type="password" name="mdpconnect" placeholder="Mot de passe"/>
        <br>
        <input type="submit" name="formconnexion" value="Se connecter"/>
        <br>
        <br>

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
