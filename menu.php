<?php
session_start();

if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    header("Location: login.php");
    exit();
}
?>
<html>
	<head>
		<title>
			PÀGINA WEB DEL MENÚ PRINCIPAL DE L'APLICACIÓ D'ACCÉS A BASES DE DADES LDAP
		</title>
	</head>
	<body>
		<h2> MENÚ PRINCIPAL DE L'APLICACIÓ D'ACCÉS A BASES DE DADES LDAP</h2>
		<a href="https://zend-orfefo.fjeclot.net/projecte/index.php">Torna a la pàgina inicial</a>&nbsp;&nbsp;&nbsp;
		<a href="https://zend-orfefo.fjeclot.net/projecte/consultar.php">Consultar</a>&nbsp;&nbsp;&nbsp;
		<a href="https://zend-orfefo.fjeclot.net/projecte/afegir.php">Afegir</a>&nbsp;&nbsp;&nbsp;
		<a href="https://zend-orfefo.fjeclot.net/projecte/esborrar.php">Esborrar</a>&nbsp;&nbsp;&nbsp;
		<a href="https://zend-orfefo.fjeclot.net/projecte/modificar.php">Modificar</a>
	</body>
</html>