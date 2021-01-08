<?php
    require 'inc/functions.php';

    $errors = [];
    if(!empty($_POST['submitted'])) {
        $name = secureTextByArray($_POST, 'name');
        $district = secureTextByArray($_POST, 'district');
        $countryCode = secureTextByArray($_POST, 'countryCode');
        $population = getValueByArray($_POST, 'population');

        checkLengthTextValid($name, 1, 35, $errors, 'name');
        checkLengthTextValid($district, 1, 20, $errors, 'district');
        checkLengthTextValid($countryCode, 3, 3, $errors, 'countryCode');
        checkNumericValid($population, $errors, 'population', 0, 99_999_999_999);

        if( empty($errors) ) {
            require 'inc/pdo.php';

            $query = $pdo -> prepare('INSERT INTO city (Name,CountryCode,District,Population) VALUES (:name,:countryCode,:district,:population)');
            $query->bindValue(':name', $name);
            $query->bindValue(':countryCode', $countryCode);
            $query->bindValue(':district', $district);
            $query->bindValue(':population', $population, PDO::PARAM_INT);
            $query->execute();

            $keyLast = $pdo -> lastInsertId();
        }
    }

    $title = 'Nouvelle ville';
    include 'inc/header.php';

?>
    <div class="container">
        <?php if(isset($keyLast)) { ?>
            <div class="success">
                La ville a bien été ajouté ! <a href="/city.php?id=<?=$keyLast?>">Voir la ville</a>
            </div>
        <?php } else { ?>
            <h1>Nouvelle Ville</h1>
            <form action="" method="post">
                <?php
                    buildInputByArray($_POST, 'Nom *', 'text', 'name', $errors);
                    buildInputByArray($_POST, 'District *', 'text', 'district', $errors);
                    buildInputByArray($_POST, 'Country Code *', 'text', 'countryCode', $errors);
                    buildInputByArray($_POST, 'Population *', 'number', 'population', $errors);
                ?>
                <input type="submit" name="submitted" value="Enregistrer">
            </form>
        <?php } ?>
    </div>
<?php
    include 'inc/footer.php';
