<?php
// connexion a la DATABASE
require 'login-hospitalE2N.php';
// fonction permettant de continuer si la connexion est réussi
$db = connectDb();
// variable pour afficher les données souhaité d'une table
$sql = 'SELECT `id`, `lastname`, `firstname`, `birthdate`, `phone`, `mail` FROM `patients` LIMIT 50';
// Envoie de la requête vers la base de données
$usersQueryStat = $db->query($sql);
$usersList = [];
if ($usersQueryStat instanceOf PDOStatement) {
  // Collection des données dans un tableau associatif (FETCH_ASSOC)
  $usersList = $usersQueryStat->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
  <meta charset="utf-8">
       <link href="https://stackpath.bootstrapcdn.com/bootswatch/4.4.1/lumen/bootstrap.min.css" rel="stylesheet" integrity="sha384-715VMUUaOfZR3/rWXZJ9agJ/udwSXGEigabzUbJm2NR4/v5wpDy8c14yedZN6NL7" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css">
  <title>Liste de patients | PDO</title>
</head>
<body>
  <h1>PDO - Partie 2 : Écrire des données</h1>
  <div class="container">
    <h2>Liste des patients</h2>
<table class="table table-bordered text-white">
  <thead>
    <tr>
      <th>0</th>
      <th>Nom</th>
      <th>Prénom</th>
      <th>Date d'anniversaire</th>
      <th>Télephone</th>
      <th>Email</th>
      <th>Informations</th>
    </tr>
  </thead>
  <tbody>
    <?php
    if (count($usersList) > 0) {
    foreach ($usersList AS $key => $user):
      ?>
      <tr>
        <td><?= $key + 1 ?></td>
        <td><a href="profil-patient.php?idPatient=<?= $user['id'] ?>"><?= $user['lastname'] ?></a></td>
        <td><?= $user['firstname']?></td>
        <td><?= $user['birthdate']?></td>
        <td><?= $user['phone']?></td>
        <td><?= $user['mail']?></td>
        <td><a href="http://pdopartie2/profil-patient.php">page profil</a></td>
      </tr>
      <?php
    endforeach;
  }
    ?>
  </tbody>
</table>
<a class="btn btn-warning" href="ajout-patient.php">Créer un patient</a>
<a class="btn btn-secondary" href="index.php">Accueil</a>
</div>
</body>
</html>
