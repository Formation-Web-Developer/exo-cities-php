<?php
    require 'inc/pdo.php';
    require 'inc/functions.php';

    $query  = $pdo -> prepare('SELECT COUNT(*) as count FROM city');
    $query -> execute();
    $count  = $query -> fetchColumn();

    $query  = $pdo -> prepare('SELECT * FROM city ORDER BY Name ASC');
    $query -> execute();
    $cities = $query -> fetchAll();

    $title='Accueil';

    include 'inc/header.php';

?>
    <div class="container">
        <h1>Liste des villes</h1>
        <p class="right">Total: <?=$count?> <a class="btn btn-green" href="/register.php">Ajouter une ville</a></p>
        <div class="cities">
        <?php
            foreach ($cities as $city): ?>
                <div class="city">
                    <h2><?=$city['Name']?></h2>
                    <div class="buttons">
                        <a class="btn" href="/edit.php?id=<?=$city['ID']?>">Modifier</a>
                        <a class="btn" href="/city.php?id=<?=$city['ID']?>">Voir plus</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php
    include 'inc/footer.php';
