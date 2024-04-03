<?php
session_start();

if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    header("Location: login.php");
    exit();
}
require "vendor/autoload.php";
use Laminas\Ldap\Ldap;
$missatge = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $domini = "dc=fjeclot,dc=net";
    $opcions = [
        "host" => "zend-orfefo.fjeclot.net",
        "username" => "cn=admin,$domini",
        "password" => "1234",
        "bindRequiresDn" => true,
        "accountDomainName" => "fjeclot.net",
        "baseDn" => "dc=fjeclot,dc=net",
    ];

    $uid = $_POST['uid'];
    $unitat_organitzativa = $_POST['unitat_organitzativa'];
    $atribut_a_modificar = $_POST['atribut_a_modificar'];
    $nou_valor = $_POST['nou_valor'];

    $ldap = new Ldap($opcions);
    try {
        $ldap->bind();
        $dn = 'uid=' . $uid . ',ou=' . $unitat_organitzativa . ',dc=fjeclot,dc=net';
        if ($ldap->exists($dn)) {
            $modificacio = [$atribut_a_modificar => $nou_valor];
            $ldap->update($dn, $modificacio);
            $missatge = "Atribut modificat correctament<br>";
        } else {
            $missatge = "L'usuari no existeix<br>";
        }
    } catch (Exception $e) {
    }
}

?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Atributs d'Usuari LDAP</title>
</head>
<body>
    <?php echo $missatge; ?>
    <h2>Modificar Atributs d'Usuari LDAP</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        UID: <input type="text" name="uid"><br>
        Unitat Organitzativa: <input type="text" name="unitat_organitzativa"><br>
        Atribut a modificar:
        <input type="radio" name="atribut_a_modificar" value="uidNumber"> uidNumber
        <input type="radio" name="atribut_a_modificar" value="gidNumber"> gidNumber
        <input type="radio" name="atribut_a_modificar" value="homeDirectory"> Directori personal
        <input type="radio" name="atribut_a_modificar" value="loginShell"> Shell
        <input type="radio" name="atribut_a_modificar" value="cn"> cn
        <input type="radio" name="atribut_a_modificar" value="sn"> sn
        <input type="radio" name="atribut_a_modificar" value="givenName"> givenName
        <input type="radio" name="atribut_a_modificar" value="postalAddress"> PostalAdress
        <input type="radio" name="atribut_a_modificar" value="mobile"> mobile
        <input type="radio" name="atribut_a_modificar" value="telephoneNumber"> telephoneNumber
        <input type="radio" name="atribut_a_modificar" value="title"> title
        <input type="radio" name="atribut_a_modificar" value="description"> description<br>
        Nou Valor: <input type="text" name="nou_valor"><br>
        <input type="submit" value="Modificar">
    </form>
</body>
</html>
