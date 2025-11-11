<?php 
 # Start php session
 session_start();

 if (isset($_SESSION['STORE_KEEPER_USER'])) {
 	$_SESSION['STORE_KEEPER_USER'] = '';
 }
?>
