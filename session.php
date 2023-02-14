<?php
session_start();
if (!isset($_SESSION['login_username'])) {
	header("location: login.php");
}
