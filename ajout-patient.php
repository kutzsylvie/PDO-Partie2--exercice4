<?php
// include pour la validation du formulaire
require 'val-form-patient.php';
//si post du formulaire complété sans erreurs, affichage du résultat
if ($isSubmitted && count($errors) == 0) {
// include qui appelle le fichier permettant la connexion à la DATABASE
require 'login-hospitalE2N.php';
// fonction permettant de continuer si la connexion est réussi
$db = connectDb();
// variable pour afficher les données souhaité d'un client
$query = 'INSERT INTO `patients` (`lastname`, `firstname`, `birthdate`, `phone`, `mail`) VALUES (:lastname, :firstname, :birthdate, :phone, :mail)';
// Envoie de la requête vers la base de données
$sth = $db->prepare($query);
$sth->bindValue(':lastname', $lastname, PDO::PARAM_STR);
$sth->bindValue(':firstname', $firstname, PDO::PARAM_STR);
$sth->bindValue(':birthdate', $birthdate, PDO::PARAM_STR);
$sth->bindValue(':phone', $phone, PDO::PARAM_STR);
$sth->bindValue(':mail', $mail, PDO::PARAM_STR);
// éxecution de la requête
$execute = $sth->execute();
var_dump($execute);
// if ($sth->execute()) {
//   header('Location: liste-patients.php');
//   exit();
// }
// $sth->execute([
//   ':lastname' => $lastname,
//   ':firstname' => $firstname,
//   ':birthdate' => $birthdate,
//   ':phone' => $phone,
//   ':mail' => $mail,
// ]);
}
?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
  <meta charset="utf-8">
       <link href="https://stackpath.bootstrapcdn.com/bootswatch/4.4.1/lumen/bootstrap.min.css" rel="stylesheet" integrity="sha384-715VMUUaOfZR3/rWXZJ9agJ/udwSXGEigabzUbJm2NR4/v5wpDy8c14yedZN6NL7" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css">
  <title>Ajout de patient | PDO</title>
</head>
<body class="body-form">
    <h1>PDO - Partie 2 : Écrire des données</h1>
  </div>
  <div class="container box-form">
    <h2 class="h2formulaire pt-5">Formulaire - pour créer un patient</h2>
    <form action="#" method="post" novalidate>
      <div class="form-group form-row">
        <div class="form-group col">
          <label for="lastName">Nom</label>
          <input name="lastname" type="text" id="lastName" class="form-control" placeholder="" value="<?= $lastname ?>">
          <span class="text-danger"><?= ($errors['firstname']) ?? '' ?></span>
        </div>
        <div class="form-group col">
          <label for="firstName">Prénom</label>
          <input name="firstname" type="text" id="firstName" class="form-control" placeholder="" value="<?= $firstname ?>">
          <span class="text-danger"><?= ($errors['lastname']) ?? '' ?></span>
        </div>
        <div class="form-group col-md">
          <label for="birthDAte">Date d'anniversaire</label>
          <input name="birthdate" type="date" class="form-control" id="birthDAte" min="1900-01-01" max="2020-01-01" value="<?= $birthdate ?>">
          <span class="text-danger"><?= ($errors['birthdate']) ?? '' ?></span>
        </div>
      </div>
      <div class="form-group form-row">
        <div class="col">
          <label for="numberPhone">Téléphone mobile</label>
          <input name="phone" type="tel" id="numberPhone" class="form-control" placeholder="" value="<?= $phone ?>">
          <span class="text-danger"><?= ($errors['phone']) ?? '' ?></span>
        </div>
        <div class="form-group col">
          <label for="mail">Email</label>
          <input name="mail" type="text" id="mail" class="form-control" placeholder="xxx@outlook.com" value="<?= $mail ?>">
          <span class="text-danger"><?= ($errors['mail']) ?? '' ?></span>
        </div>
      </div>
      <input name="submit" type="submit" class="btn btn-warning" value="Ajouter un patient"/>
      <input name="reset" type="reset" class="btn btn-danger" value="Effacez"/>
      <a class="btn btn-secondary" href="index.php">Accueil</a>
    </form>
  </div>
</body>
</html>
