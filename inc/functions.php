<?php

function debug(array $array): void
{
    echo '<pre>';
    print_r($array);
    echo '</pre>';
}

function getValueByArray(array $array, string $key, string $defaultValue = '') {
    return !empty($array[$key]) ? $array[$key] : $defaultValue;
}

function secureTextByArray(array $array, string $key): string
{
    return secureText(getValueByArray($array, $key));
}

function secureText(string $text): string
{
    return trim(strip_tags($text));
}

function checkLengthTextValid(string $text, int $min, int $max, array &$errors, string $key): void
{
    if( empty($text) ) {
        $errors[$key] = 'Veuillez renseigner ce champs !';
    } elseif (mb_strlen($text) < $min ) {
        $errors[$key] = 'Ce champs ne contient pas assez de caractère !';
    } elseif (mb_strlen($text) > $max) {
        $errors[$key] = 'Ce champs contient trop de caractère !';
    }
}

function checkNumericValid($number, array &$errors, string $key, int $min = PHP_INT_MIN, int $max = PHP_INT_MAX)
{
    if( empty($number) ) {
        $errors[$key] = 'Veuillez renseigner ce champs !';
    }elseif ( !filter_var($number, FILTER_VALIDATE_INT) ) {
        $errors[$key] = 'La valeur saisie n\'est pas un nombre valide !';
    }elseif (intval($number) < $min) {
        $errors[$key] = 'La valeur saisie est trop petite !';
    }elseif (intval($number) > $max) {
        $errors[$key] = 'La valeur saisie est trop grande !';
    }
}

function checkEmailValid(string $text, array &$errors, string $key): void
{
    if(empty($text)){
        $errors[$key] = 'Veuillez renseigner ce champs !';
    } elseif(!filter_var($text, FILTER_VALIDATE_EMAIL)) {
        $errors[$key] = 'Ceci n\'est pas un email valide !';
    }
}

function checkValueSelect(array $array, string $value, array &$errors, string $key): void
{
    if(empty($value) || empty($array[$value])) {
        $errors[$key] = 'Veuillez renseigner ce champs !';
    }
}

function buildInputByArray(array $array, string $label, string $type, string $id, array $errors) {
    buildInput((!empty($array[$id]) ? $array[$id] : ''), $label, $type, $id, $errors);
}

function buildInput(string $text, string $label, string $type, string $id, array $errors){?>
    <div class="form-group">
        <label for="<?=$id?>"><?=$label?></label>
        <input type="<?=$type?>" name="<?=$id?>" id="<?=$id?>"  value="<?=$text?>"<?= !empty($errors[$id]) ? ' class="error"' : '' ?>>
        <span class="error"><?= !empty($errors[$id]) ? $errors[$id] : '' ?></span>
    </div>
<?php }

function buildTextArea(array $array, string $label, string $id, int $rows, array $errors){ ?>
    <div class="form-group">
        <label for="<?=$id?>"><?=$label?></label>
        <textarea name="<?=$id?>" id="<?=$id?>" rows="<?=$rows?>"<?= !empty($errors[$id]) ? ' class="error"' : '' ?>><?= !empty($array[$id]) ? $array[$id] : '' ?></textarea>
        <span class="error"><?= !empty($errors[$id]) ? $errors[$id] : '' ?></span>
    </div>
<?php }

function buildSelect(string $label, string $id, array $values, $defaultValue, array $errors) { ?>
    <div class="form-group">
        <label for="<?=$id?>"><?=$label?></label>
        <select name="<?=$id?>" id="<?=$id?>"<?= !empty($errors[$id]) ? ' class="error"' : '' ?>>
            <?php foreach ($values as $key => $value): ?>
                <option value="<?=$key?>"<?= $key == $defaultValue ? ' selected' : '' ?>><?=$value?></option>
            <?php endforeach; ?>
        </select>
        <span class="error"><?= !empty($errors[$id]) ? $errors[$id] : '' ?></span>
    </div>
<?php }
