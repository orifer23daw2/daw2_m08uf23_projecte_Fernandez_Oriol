<?php
session_start();

if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    header("Location: login.php");
    exit();
}
require "vendor/autoload.php";
use Laminas\Ldap\Ldap;
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['unidad_organizativa']) && isset($_GET['identificador'])) {

    
    $domini = "dc=fjeclot,dc=net";
    $opciones = [
        "host" => "zend-orfefo.fjeclot.net",
        "username" => "cn=admin,$domini",
        "password" => "1234",
        "bindRequiresDn" => true,
        "accountDomainName" => "fjeclot.net",
        "baseDn" => "dc=fjeclot,dc=net",
    ];
    
    $ldap = new Ldap($opciones);
    $ldap->bind();  
    $filtro = "uid=" .$_GET['identificador'] . ',ou=' .$_GET['unidad_organizativa'] . ',dc=fjeclot,dc=net';
    $resultado = $ldap->getEntry($filtro);
    
    echo '<h2>Resultados de la búsqueda:</h2>';
        echo "<b><u>" . $resultado["dn"] . "</b></u><br>";
        foreach ($resultado as $atributo => $valor) {
            if ($atributo != "dn") {
                echo $atributo . ": " . $valor[0] . "<br>";
            }
        }
    
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Usuarios LDAP</title>
</head>
<body>

<h2>Consulta de Usuarios LDAP</h2>

<form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label for="unidad_organizativa">Unidad Organizativa:</label>
    <input type="text" id="unidad_organizativa" name="unidad_organizativa" value="<?php echo isset($_GET['unidad_organizativa']) ? $_GET['unidad_organizativa'] : ''; ?>"><br><br>

    <label for="identificador">Identificador:</label>
    <input type="text" id="identificador" name="identificador" value="<?php echo isset($_GET['identificador']) ? $_GET['identificador'] : ''; ?>"><br><br>

    <input type="submit" value="Consultar">
</form>

</body>
</html>
