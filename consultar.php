<?php
session_start();

if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    header("Location: login.php");
    exit();
}
require "vendor/autoload.php";
use Laminas\Ldap\Ldap;
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['unitat_organitzativa']) && isset($_GET['identificador'])) {
    
    
    $domini = "dc=fjeclot,dc=net";
    $opcions = [
        "host" => "zend-orfefo.fjeclot.net",
        "username" => "cn=admin,$domini",
        "password" => "1234",
        "bindRequiresDn" => true,
        "accountDomainName" => "fjeclot.net",
        "baseDn" => "dc=fjeclot,dc=net",
    ];
    
    $ldap = new Ldap($opcions);
    $ldap->bind();
    $filtre = "uid=" .$_GET['identificador'] . ',ou=' .$_GET['unitat_organitzativa'] . ',dc=fjeclot,dc=net';
    $resultat = $ldap->getEntry($filtre);
    
    echo '<h2>Resultats de la cerca:</h2>';
    echo "<b><u>" . $resultat["dn"] . "</b></u><br>";
    foreach ($resultat as $atribut => $valor) {
        if ($atribut != "dn") {
            echo $atribut . ": " . $valor[0] . "<br>";
        }
    }
    
}
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta d'Usuaris LDAP</title>
</head>
<body>

<h2>Consulta d'Usuaris LDAP</h2>

<form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label for="unitat_organitzativa">Unitat Organitzativa:</label>
    <input type="text" id="unitat_organitzativa" name="unitat_organitzativa" value="<?php echo isset($_GET['unitat_organitzativa']) ? $_GET['unitat_organitzativa'] : ''; ?>"><br><br>

    <label for="identificador">Identificador:</label>
    <input type="text" id="identificador" name="identificador" value="<?php echo isset($_GET['identificador']) ? $_GET['identificador'] : ''; ?>"><br><br>

    <input type="submit" value="Consultar">
</form>

</body>
</html>
