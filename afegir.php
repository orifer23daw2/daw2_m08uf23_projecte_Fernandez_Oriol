<?php
session_start();

if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    header("Location: login.php");
    exit();
}
require 'vendor/autoload.php';
use Laminas\Ldap\Attribute;
use Laminas\Ldap\Ldap;

ini_set('display_errors', 0);

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uid = $_POST['uid'];
    $unorg = $_POST['ou'];
    $num_id = $_POST['uidNumber'];
    $grup = $_POST['gidNumber'];
    $dir_pers = $_POST['homeDirectory'];
    $sh = $_POST['loginShell'];
    $cn = $_POST['cn'];
    $sn = $_POST['sn'];
    $nom = $_POST['givenName'];
    $mobil = $_POST['mobile'];
    $adressa = $_POST['postalAddress'];
    $telefon = $_POST['telephoneNumber'];
    $titol = $_POST['title'];
    $descripcio = $_POST['description'];
    $objcl = ['inetOrgPerson','organizationalPerson','person','posixAccount','shadowAccount','top'];
    
    $domini = 'dc=fjeclot,dc=net';
    $opciones = [
        'host' => 'zend-orfefo.fjeclot.net',
        'username' => "cn=admin,$domini",
        'password' => '1234',
        'bindRequiresDn' => true,
        'accountDomainName' => 'fjeclot.net',
        'baseDn' => 'dc=fjeclot,dc=net',
    ];
    $ldap = new Ldap($opciones);
    $ldap->bind();
    
    $filter = "(ou=$unorg)";
    $result = $ldap->search($filter, 'dc=fjeclot,dc=net', Ldap::SEARCH_SCOPE_ONE);
    
    if ($result->count() === 0) {
        $message = "Error: La unitat organizativa no existeix.";
    } else {
        $data = [];
        Attribute::setAttribute($data, 'objectClass', $objcl);
        Attribute::setAttribute($data, 'uid', $uid);
        Attribute::setAttribute($data, 'uidNumber', $num_id);
        Attribute::setAttribute($data, 'gidNumber', $grup);
        Attribute::setAttribute($data, 'homeDirectory', $dir_pers);
        Attribute::setAttribute($data, 'loginShell', $sh);
        Attribute::setAttribute($data, 'cn', $cn);
        Attribute::setAttribute($data, 'sn', $sn);
        Attribute::setAttribute($data, 'givenName', $nom);
        Attribute::setAttribute($data, 'mobile', $mobil);
        Attribute::setAttribute($data, 'postalAddress', $adressa);
        Attribute::setAttribute($data, 'telephoneNumber', $telefon);
        Attribute::setAttribute($data, 'title', $titol);
        Attribute::setAttribute($data, 'description', $descripcio);
        $dn = 'uid='.$uid.',ou='.$unorg.',dc=fjeclot,dc=net';
        if($ldap->add($dn, $data)) {
            $message = "Usuari creat.";
        } else {
            $message = "Error al crear l'usuari: " . $ldap->getLastError();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Afegir usuari</title>
</head>
<body>
    <?php echo $message; ?>
    <h2>Dades de l'usuari:</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        UID: <input type="text" name="uid"><br>
        Unitat organizativa: <input type="text" name="ou"><br>
        UID Number: <input type="number" name="uidNumber"><br>
        GID Number: <input type="number" name="gidNumber"><br>
        Home Directory: <input type="text" name="homeDirectory"><br>
        Login Shell: <input type="text" name="loginShell"><br>
        CN: <input type="text" name="cn"><br>
        SN: <input type="text" name="sn"><br>
        Given Name: <input type="text" name="givenName"><br>
        Mòvil: <input type="text" name="mobile"><br>
        Adreça postal: <input type="text" name="postalAddress"><br>
        Telèfon: <input type="text" name="telephoneNumber"><br>
        Títol: <input type="text" name="title"><br>
        Descripció: <input type="text" name="description"><br>
        <input type="submit" value="Crear">
    </form>
    <form action="menu.php">
        <input type="submit" value="Volver al menú">
    </form>
</body>
</html>