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

if ($_POST["newpass"] !== $_POST["confirm_newpass"])
{
echo 'Le nouveau mot de passe et sa confirmation ne correspondent pas...<br /> <a href="changer_mdp.html"> Faire un nouvel essai </a>';
}

else
{
    if (password_verify($_POST['pass'], $resultat['pass']))
    {   
        $pseudo = $_POST["pseudo"];
        $newpass_hash = password_hash(($_POST['newpass']), PASSWORD_DEFAULT);
        $req = $bdd->prepare('UPDATE membres SET pass = :newpass_hash WHERE pseudo = :pseudo');
        $req->execute(array('newpass_hash' => $newpass_hash, ':pseudo' => $pseudo));
        echo 'Votre mot de passe a bien été modifié! <br /> <a href="connexion.html"> Revenir sur la page de connexion </a>';
    }
    else 
    {
        echo 'Le pseudo et l\'ancien mot de passe ne correspondent pas...<br /> <a href="changer_mdp.html"> Faire un nouvel essai </a>';
    }
}




?>

