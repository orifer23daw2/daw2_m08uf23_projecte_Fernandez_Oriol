<?php
session_start();

if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    header("Location: login.php");
    exit();
}
require "vendor/autoload.php";
use Laminas\Ldap\Ldap;
$missatge = '';
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
    
    $ldap = new Ldap($opcions);
    try {
        $ldap->bind();
        $dn = 'uid=' . $uid . ',ou=' . $unitat_organitzativa . ',dc=fjeclot,dc=net';
        if ($ldap->exists($dn)) {
            $ldap->delete($dn);
            $missatge = "Usuari eliminat correctament<br>";
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
    <title>Eliminar Usuari LDAP</title>
</head>
<body>
    <?php echo $missatge; ?>
    <h2>Eliminar Usuari LDAP</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        UID: <input type="text" name="uid"><br>
        Unitat Organitzativa: <input type="text" name="unitat_organitzativa"><br>
        <input type="submit" value="Eliminar">
    </form>
</body>
</html>
