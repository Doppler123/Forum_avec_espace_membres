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

// requête préparée pour récupérer les pseudos déja existants dans la BDD
$pseudo = $_POST['pseudo'];
$req = $bdd->prepare("SELECT * FROM membres WHERE pseudo=?");
$req->execute ([$pseudo]); 
$user_pseudo = $req->fetch();

$req->closeCursor(); 

// requête préparée pour récupérer les emails déja existants dans la BDD
$email = $_POST['email'];
$req = $bdd->prepare("SELECT * FROM membres WHERE email=?");
$req->execute ([$email]); 
$user_email= $req->fetch();

$req->closeCursor(); 

if ($user_pseudo)
{
echo 'Ce pseudo est déja utilisé par un autre membre...<br /> <a href="inscription.html"> Faire un nouvel essai </a>';
}

elseif ($user_email)
{
echo 'Cet email est déja utilisé par un autre membre...<br /> <a href="inscription.html"> Faire un nouvel essai </a>';
}

elseif ( $_POST["pass"] !== $_POST["confirm_pass"])
{
echo 'Les mots de passe ne correspondent pas...<br /> <a href="inscription.html"> Faire un nouvel essai </a>';
}

elseif (!preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}.[a-z]{2,4}$#", $_POST['email']))
{
echo 'Le format de l\'adresse mail renseignée n\'est pas valide...<br /> <a href="inscription.html"> Faire un nouvel essai </a>';
}
    
// Sinon, hashage du pass, ajout de la nouvelle entrée dans la BDD puis envoi vers la page de connexion
else 
{
$pass_hash = password_hash(($_POST['pass']), PASSWORD_DEFAULT);
$req = $bdd->prepare('INSERT INTO membres (pseudo, pass, email) VALUES(?, ?, ?)');
$req->execute(array(htmlspecialchars($_POST['pseudo']), $pass_hash, htmlspecialchars($_POST['email'])));
$req->closeCursor(); 
header('Location: connexion.html');
}
 
?>
<?php
 
