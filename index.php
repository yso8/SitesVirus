<?php
require_once 'configs/chemins.class.php';
require Chemins::VUES . 'ventete.php';

 $cas = (!isset($_REQUEST['cas'])) ?'afficherAccueil' :  $_REQUEST['cas'];

//Aiguillage vers le bon corps de page
switch ($cas) {
 case 'afficherAccueil': {
 require Chemins::VUES . 'vaccueil.php';
 break;
 }
 case 'afficherPresentation': {
 require Chemins::VUES . 'vpresentation.php';
 break;
 }
  case 'afficherPrevention': {
 require Chemins::VUES . 'vprevention.php';
 break;
 }
  case 'afficherRecommandation': {
 require Chemins::VUES . 'vrecommandation.php';
 break;
 }
   case 'afficherId': {
 require Chemins::VUES . 'vId.php';
 break;
 }
//case 'afficherLogin': {
//require Chemins::VUES . 'vlogin.php';
//break;
//}
//case 'afficherInscription': {
//require Chemins::VUES . 'vinscription.php';
//break;
//}
case 'afficherContact': {
require Chemins::VUES . 'vcontact.php';
break;
}
}

?>
