<?php
include "../inc/session.php";
include '../inc/header.php';
$_SESSION['url'] = $_SERVER['REQUEST_URI'];

?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<section class="view">
    <?php
    include '../inc/db.php';
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = $db->prepare("SELECT * from produits_ordinateur where id_ordinateur=:id");
        $sql->execute([":id" => $id]);
        $view = $sql->fetchAll();
    ?>

        <div class="imag">
            <img src="../admin/image/<?= $view[0]['img_ordinateur'] ?>" alt="">
        </div>
        <div>
            <h1><?= $view[0]['nom_ordinateur'] ?></h1>
            <p>
                <?= $view[0]['dscription_ordinateur'] ?>
            </p>
        </div>
        <div class="viewprix">
            <h2><?= $view[0]['prix_ordinateur'] ?>DH</h2>
            <a href="panair.php?id=<?= $view[0]['id_ordinateur'] ?>" class="panier text-light">Ajouter au panier</a>
        </div>


    <?php
    } else {
        echo "<h1>Désolé, il n'y a pas de produit à afficher</h1>";
        echo '<p>Pour accéder à la liste des produits,  <a href="../nos-ordinatour.php">cliquez ici</p></a> ';
    }
    echo " </section>";
    if (isset($_GET['id'])) {
        $sql = $db->prepare(('SELECT * from clien p inner join commentes c on p.id = c.id_client  
        inner join produits_ordinateur pr on pr.id_ordinateur=c.id_ordinateur
        where pr.id_ordinateur=?'));
        $sql->execute([$_GET['id']]);
        $tab = $sql->fetchAll();
    ?>
        <div class="comments">
            <?php foreach ($tab as $val) { ?>
                <article>
                    <div>
                        <img src="../image/<?= $val['profil'] ?>" alt="">
                        <h5><?= $val["firstName"] . " " . $val["lastName"] ?> </h5>
                    </div>
                    <div>
                        <?php
                        for ($i = 0; $i < 5; $i++) {
                            if ($i < $val['star']) {
                                echo "<span><i class='fa fa-star' ></i></span>";
                            } else {
                                echo "<span><i class='fa-regular fa-star'></i></span>";
                            }
                        } ?>
                        <span> en <?= $val["date_pub"] ?></span>
                    </div>
                    <div>
                        <?= $val["commente"] ?>
                    </div>
                </article>
            <?php } ?>
            <hr>
            <form action="" method="post">
                <h4>Ajouter un nouveau commentaire</h4>
                <textarea class="form-control w-100" id="" rows="5" cols="50" name="val"></textarea>
                <div class="stars">
                    <span><i class="fa fa-star" onclick="star(1)"></i></span>
                    <span><i class="fa fa-star" onclick="star(2)"></i></span>
                    <span><i class="fa fa-star" onclick="star(3)"></i></span>
                    <span><i class="fa fa-star" onclick="star(4)"></i></span>
                    <span><i class="fa fa-star" onclick="star(5)"></i></span>
                    <input type="hidden" name="star" value="5" id="star">
                </div>
                <div class="b_envoyer">
                    <input type="submit" value="Envoyer" name="envoyer" class="btn btn-outline-primary">
                </div>
            </form>
        </div>

    <?php }
    include '../inc/footer.php' ?>
    <?php

    if (isset($_POST['envoyer'])) {

        $sql = $db->prepare("INSERT INTO `commentes`(`id_client`, `id_ordinateur`, `commente`, `date_pub`,`star`) VALUES (?,?,?,?,?)");
        $sql->execute([$_SESSION['id_clien'], $_GET['id'], $_POST['val'], date("Y-m-d"), $_POST['star']]);
    }

    ?>