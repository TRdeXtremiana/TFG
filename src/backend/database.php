<?php

function connect_db()
{
	$host = 'localhost';
	$dbname = 'gestiongastos';
	$username = 'root';
	$password = '';
	try {
		return new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
	} catch (PDOException $e) {
		die("Error de conexión: " . $e->getMessage());
	}
}
