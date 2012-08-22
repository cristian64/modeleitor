<?php
	require_once 'minilibreria.php';
	session_destroy();
	
	// Hay que eliminar la cookie.
	setcookie("email");
	setcookie("contrasena");

	header("location: index.php");
	exit();
?>
