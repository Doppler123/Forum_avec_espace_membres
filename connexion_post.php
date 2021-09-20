<?php 
// Connection à la BDD
try
{
	$bdd = new PDO('mysql:host=localhost;dbname=forum_avec_espace_membres_et_commentaires;charset=utf8', 'root', '');
}
    catch(Exception $e)
{
    die('Erreur : '.$e->getMessage());
}

$pseudo = $_POST["pseudo"];
//  Récupération de l'utilisateur et de son pass hashé
$req = $bdd->prepare('SELECT id, pass FROM membres WHERE pseudo = :pseudo');
$req->execute(array('pseudo' => $pseudo));
$resultat = $req->fetch();

$req->closeCursor(); 

if (!$resultat) 
{
    echo 'Mauvais identifiant ou mot de passe ! <br /> <a href="connexion.html"> Faire un nouvel essai </a>';
}
else
{
    if (password_verify($_POST['pass'], $resultat['pass']))
    {
        session_start();
        $_SESSION['id'] = $resultat['id'];
        $_SESSION['pseudo'] = $pseudo;
        echo 'Vous êtes connecté ! <br /> 
        <a href="changer_mdp.html"> Changez de mot de passe </a> <br /> 
        <a href="deconnexion.php"> Vous déconnecter </a> <br /> 
        <a href="billets_et_commentaires/billets.php"> Participer au forum</a> ' ; 
    }
    else 
    {
        echo 'Mauvaise correspondance entre le pseudo et le mot de passe ou compte non activé ! <br /> <a href="connexion.html"> Faire un nouvel essai </a>';
    }
}


?>