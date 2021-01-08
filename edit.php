<?php
    if( empty($_GET['id']) || !is_numeric($_GET['id']) ) {
        header('Location: /404.php');
        exit;
    }

    require 'inc/functions.php';
    require 'inc/pdo.php';

    $query = $pdo -> prepare('SELECT * FROM city WHERE ID=:id');
    $query -> bindValue(':id', $_GET['id'], PDO::PARAM_INT);
    $query -> execute();
    $city = $query->fetch();

    if(!$city) {
        header('Location: /404.php');
        exit;
    }

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
            $query = $pdo -> prepare('UPDATE city SET Name=:name,CountryCode=:countryCode,District=:district,Population=:population WHERE ID=:id');
            $query->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
            $query->bindValue(':name', $name);
            $query->bindValue(':countryCode', $countryCode);
            $query->bindValue(':district', $district);
            $query->bindValue(':population', $population, PDO::PARAM_INT);
            $success = $query->execute();
        }
    }

    $title = 'Modifier';
    include 'inc/header.php';
?>
    <div class="container">
        <?php if(isset($success) && $success) { ?>
            <div class="success">
                La ville a bien été modifier ! <a href="/city.php?id=<?=$_GET['id']?>">Voir la ville</a>
            </div>
        <?php } else { ?>
            <h1>Nouvelle Ville</h1>
            <form action="" method="post">
                <?php
                    buildInput(getValueByArray($_POST, 'name', $city['Name']), 'Nom *', 'text', 'name', $errors);
                    buildInput(getValueByArray($_POST, 'district', $city['District']), 'District *', 'text', 'district', $errors);
                    buildInput(getValueByArray($_POST, 'countryCode', $city['CountryCode']), 'Country Code *', 'text', 'countryCode', $errors);
                    buildInput(getValueByArray($_POST, 'population', $city['Population']), 'Population *', 'number', 'population', $errors);
                ?>
                <input type="submit" name="submitted" value="Enregistrer">
            </form>
        <?php } ?>
    </div>
<?php
include 'inc/footer.php';
