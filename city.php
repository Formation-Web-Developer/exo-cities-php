<?php
    if(empty($_GET['id']) || !is_numeric($_GET['id'])) {
        header('Location: /404.php');
        exit;
    }

    require 'inc/pdo.php';
    require 'inc/functions.php';

    if(!empty($_POST['delete'])) {
        $query = $pdo -> prepare('DELETE FROM city WHERE ID=:id');
        $query -> bindValue(':id', $_GET['id'], PDO::PARAM_INT);
        $deleted = $query -> execute();

        $title = 'Ville supprimé !';
    }else {
        $query = $pdo -> prepare('SELECT * FROM city WHERE ID=:id');
        $query -> bindValue(':id', $_GET['id'], PDO::PARAM_INT);
        $query -> execute();
        $city = $query->fetch();

        if(!$city) {
            header('Location: /404.php');
            exit;
        }

        $title = $city['Name'];
    }

    include 'inc/header.php';
?>
    <div class="container">
        <?php if (isset($deleted) && $deleted) { ?>
            <div class="success">
                La ville a bien été supprimé ! <a href="/">Revenir sur la liste des villes</a>
            </div>
        <?php } else { ?>
            <h1><?=$city['Name']?></h1>
            <p><span>District</span>: <?=$city['District']?></p>
            <p><span>Country Code</span>: <?=$city['CountryCode']?></p>
            <p><span>Population</span>: <?=$city['Population']?></p>

            <form action="/city.php?id=<?=$city['ID']?>" method="post">
                <input type="submit" name="delete" value="Supprimer">
            </form>
        <?php } ?>
    </div>
<?php
    include 'inc/footer.php';
