<?php
session_start();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="style.css">
        <title>Mon super mini-forum</title>
    </head>

 <body>

 <?php
if(isset($_SESSION['pseudo']))
{
echo'
<em><a href="billets.php">Retour à la liste des billets</a></em> </br>
<em><a href="../deconnexion.php">Se déconnecter</a></em>
';
}

if(!isset($_SESSION['pseudo']))
{
echo'
<em><a href="billets.php">Retour à la liste des billets</a></em> </br>
<em><a href="../connexion.html">Se connecter</a></em> </br>
<em><a href="../inscription.html">S\'inscrire</a></em> </br>
';
}
?>

 <h1>Mon mini-forum!</h1>


<?php
// Connection à la BDD
try
{
$bdd = new PDO('mysql:host=localhost;dbname=forum_avec_espace_membres_et_commentaires;charset=utf8', 'root', '');
}
catch (Exception $e)
{
die('Erreur : ' . $e->getMessage());
}

// Récupération du billet sélectionné par l'utilisateur
$req = $bdd->prepare('SELECT id, titre, auteur, contenu, DATE_FORMAT(date_creation, \'%d/%m/%Y à %Hh%imin\') AS date_creation_fr FROM billets WHERE id = ?');
$req -> execute(array(htmlspecialchars($_GET['billet'])));

// Affichage du billet sélectionné par l'utilisateur
$donnees = $req->fetch();

if (empty($donnees)) // Si aucun id billet ne correspond au paramètre envoyé, on affiche un message d'erreur 
{
echo 'Aucun billet ne correspond au paramètre saisi!';
die();
}
else  // Un billet correspond bien au paramètre envoyé
{

?>
<div class="news">
<h3> <?php echo htmlspecialchars($donnees['titre']); ?> </h3>
<p> "<?php  echo nl2br(htmlspecialchars($donnees['contenu'])); ?>" <br />
Par "<?php echo htmlspecialchars($donnees['auteur']); ?>" le <?php echo ($donnees['date_creation_fr']); ?> <br /></p>
</div>

<?php
// Libération du curseur pour la prochaine requête 
$req->closeCursor(); 

// Récupération des commentaires déja rédigés
$req = $bdd->prepare('SELECT auteur, commentaire, DATE_FORMAT(date_commentaire, \'%d/%m/%Y à %Hh%imin\') AS date_commentaire_fr FROM commentaires WHERE id_billet = ? ORDER BY date_commentaire DESC');
$req->execute(array($_GET['billet']));

// Affichage des commentaires déja rédigés
?>
<h2>Commentaires sur ce billet:</h2>
<?php
while ($donnees = $req->fetch())
{
?>
<p><strong><?php echo htmlspecialchars($donnees['auteur']); ?></strong> le <?php echo $donnees['date_commentaire_fr']; ?> :</p>
<p><?php echo nl2br(htmlspecialchars($donnees['commentaire'])); ?></p>
<?php
} 
}

// Fin de la boucle des commentaires
$req->closeCursor();

// On fait apparaître le formulaire d'ajout de commentaire uniquement si l'utilisateur est connecté
if(isset($_SESSION['pseudo']))
{
?>
<form method="post" action="commentaires_post.php?billet=<?php echo $_GET['billet']; ?>" class="form">
<label for="commentaire">Votre commentaire:</label>
<textarea id="commentaire" name="commentaire" rows="20" cols="80" maxlength="1500"> </textarea>
<input type="submit" value="Valider" />
<?php
} 
?>
</body>
</html>