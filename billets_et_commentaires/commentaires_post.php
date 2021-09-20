<?php 
session_start();
// Connection à la BDD
try
{
	$bdd = new PDO('mysql:host=localhost;dbname=forum_avec_espace_membres_et_commentaires;charset=utf8', 'root', '');
}
catch(Exception $e)
{
    die('Erreur : '.$e->getMessage());
}

// Affichage d'un message d'erreur si pas de commentaire renseigné
if (!strlen(trim($_POST['commentaire']))) 
{
echo 'Tu n\'a rien écrit! Si tu veux faire un hommage, il faut écrire dans la zone de texte puis valider.<br /> <a href="commentaires.php?billet='.$_GET['billet'].'"> Faire un nouvel essai </a>';
}

// Sinon, ajout de la nouvelle entrée dans la BDD puis retour à l'affichage des commentaires pour le bon billet
else 
{
$req = $bdd->prepare('INSERT INTO commentaires(id_billet, auteur, commentaire) VALUES(:id_billet, :auteur, :commentaire)');
$req->execute(array(
    'id_billet'=>htmlspecialchars($_GET['billet']),
    'auteur'=>htmlspecialchars($_SESSION['pseudo']),
    'commentaire'=>nl2br(htmlspecialchars($_POST['commentaire']))));

header('location: commentaires.php?billet='.$_GET['billet'].'');
}
?>




