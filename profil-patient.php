<?php
// connexion a la DATABASE
require 'login-hospitalE2N.php';
if (empty($_GET['idPatient']) || !filter_input(INPUT_GET, 'idPatient', FILTER_VALIDATE_INT)) {
    header('location: liste-patients.php');
    exit();
}
$idPatient = filter_input(INPUT_GET, 'idPatient', FILTER_SANITIZE_NUMBER_INT);

// fonction permettant de continuer si la connexion est réussi
$db = connectDb();
require_once 'val-form-patient.php';
if (isset($_POST['idPatient'])) {
    if (!filter_input(INPUT_POST, 'idPatient', FILTER_VALIDATE_INT) || $_POST['idPatient'] <= 0) {
        $errors['idPatient'] = 'Ce patient n\'existe pas';
    }
}
if ($isSubmitted && count($errors) == 0) {
    $sql = 'UPDATE `patients` SET `lastname`= :lastname, `firstname`= :firstname,`birthdate`= :birthdate,`phone`= :phone, `mail`= :mail WHERE `id`= :idPatient';
    $usersQueryStat = $db->prepare($sql);
    $usersQueryStat->bindValue(':idPatient', $idPatient, PDO::PARAM_INT);
    $usersQueryStat->bindValue(':lastname', $lastname, PDO::PARAM_STR);
    $usersQueryStat->bindValue(':firstname', $firstname, PDO::PARAM_STR);
    $usersQueryStat->bindValue(':birthdate', $birthdate, PDO::PARAM_STR);
    $usersQueryStat->bindValue(':phone', $phone, PDO::PARAM_STR);
    $usersQueryStat->bindValue(':mail', $mail, PDO::PARAM_STR);
    $message = "La modification de votre profil a échoué, merci de contacter l'administrateur !";
    if ($usersQueryStat->execute()) {
        if ($usersQueryStat->rowCount() > 0) {
            $message = "Votre profil a été modifié avec succés !";
            $success = true;
        }
    }
}
// variable pour afficher les données souhaité d'un client
$sql = 'SELECT `id`, `lastname`, `firstname`, DATE_FORMAT(`birthDate`, "%d/%m/%Y") `birthdate`, `birthdate` AS date, `phone`, `mail` FROM `patients` WHERE `id` = :idPatient';
// Envoie de la requête vers la base de données
$usersQueryStat = $db->prepare($sql);
$usersQueryStat->bindValue(':idPatient', $idPatient, PDO::PARAM_INT);
try {
    $usersQueryStat->execute();
    $userInfos = $usersQueryStat->fetch(PDO::FETCH_ASSOC);
    if (!$userInfos) {
        throw new Exception('Une erreur s\'est produite veuillez contacter l\'admin du site');
    }
} catch (\Exception $e) {
    $sleep = 5;
    header('Refresh:' . $sleep . ';http://pdo-partie2/liste-patients.php');
    exit($e->getMessage());
}

// éxecution de la requête
// $userInfos = [];
// if ($usersQueryStat->execute()) {
//   if ($usersQueryStat instanceOf PDOStatement) {
//     // Collection des données dans un tableau associatif (FETCH_ASSOC)
//     $usersList = $usersQueryStat->fetch(PDO::FETCH_ASSOC);
//   }
// }
// if (count($userInfos) == 0) {
?>
<!-- <p>Aucun utilisateur n'a été trouvé</p> -->
<?php
// $sleep = 5;
// header('Refresh:'. $sleep .';http://pdo-partie2/liste-patients.php');
// exit();
// }
?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
    <head>
        <meta charset="utf-8">
        <link href="https://stackpath.bootstrapcdn.com/bootswatch/4.4.1/lumen/bootstrap.min.css" rel="stylesheet" integrity="sha384-715VMUUaOfZR3/rWXZJ9agJ/udwSXGEigabzUbJm2NR4/v5wpDy8c14yedZN6NL7" crossorigin="anonymous">
        <link rel="stylesheet" href="style.css">
        <title>Profil | Patient</title>
    </head>
    <body>
        <div class="container text-center">
            <?php if (isset($message)) { ?>
                <div class="alert <?= isset($success) ? 'alert-success' : 'alert-danger' ?>  alert-dismissible fade show" role="alert">
                    <p><?= $message ?></p>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php
            }
            ?>
            <img src="" alt="">
            <p><?= $userInfos['lastname'] . ' ' . $userInfos['firstname'] ?></p>
            <table class="table table-bordered text-dark">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Date d'anniversaire</th>
                        <th>Télephone</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?= $userInfos['lastname'] ?></td>
                        <td><?= $userInfos['firstname'] ?></td>
                        <td><?= $userInfos['birthdate'] ?></td>
                        <td><?= $userInfos['phone'] ?></td>
                        <td><?= $userInfos['mail'] ?></td>
                    </tr>
                </tbody>
            </table>
            <a class="btn btn-success" href="index.php">Accueil</a>
            <a class="btn btn-light" href="liste-patients.php">Liste des patients</a>

            <input name="submit" type="submit" class="btn btn-danger" value="Supprimer"/>
            <!-- Button trigger modal -->
            <button id="updatemodal" type="button" class="btn btn-primary" data-toggle="modal" data-target="#updateuser">
                Modifier
            </button>

            <!-- Modal -->
            <div class="modal fade" id="updateuser" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Modification de profil</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="?idPatient=<?= $userInfos['id'] ?>" method="post" novalidate>
                                <input type="hidden" name="idPatient" value="<?= $userInfos['id'] ?>">
                                <div class="form-group form-row">
                                    <div class="form-group col">
                                        <label for="lastName">Nom</label>
                                        <input name="lastname" type="text" id="lastName" class="form-control" placeholder="" value="<?= $userInfos['lastname'] ?>">

                                    </div>
                                    <div class="form-group col">
                                        <label for="firstName">Prénom</label>
                                        <input name="firstname" type="text" id="firstName" class="form-control" placeholder="" value="<?= $userInfos['firstname'] ?>">

                                    </div>
                                    <div class="form-group col-md">
                                        <label for="birthdate">Date d'anniversaire</label>
                                        <input name="birthdate" type="date" class="form-control" id="birthdate" min="1900-01-01" max="2020-01-01" value="<?= $userInfos['date'] ?>">

                                    </div>
                                </div>
                                <div class="form-group form-row">
                                    <div class="col">
                                        <label for="numberPhone">Telephone mobile</label>
                                        <input name="phone" type="tel" id="numberPhone" class="form-control" placeholder="" value="<?= $userInfos['phone'] ?>">

                                    </div>
                                    <div class="form-group col">
                                        <label for="mail">Email</label>
                                        <input name="mail" type="text" id="mail" class="form-control" placeholder="xxx@outlook.com" value="<?= $userInfos['mail'] ?>">

                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                    <button type="submit" class="btn btn-primary">Modifier</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://code.jquery.com/jquery-1.12.4.min.js"  crossorigin="anonymous"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" crossorigin="anonymous"></script>
        <script>
            $(function () {
                $('#updatemodal').on('click', function () {
                    $('#updateuser').modal('show');
                })
            })
        </script>
    </body>
</html>
