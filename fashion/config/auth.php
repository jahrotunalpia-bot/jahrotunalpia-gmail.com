<?php
session_start();
function wajibLogin(){if(!isset($_SESSION['user_id'])){header('Location: ../login.php');exit;}}
function wajibAdmin(){wajibLogin();if($_SESSION['role']!=='admin'){header('Location: ../index.php');exit;}}
function wajibPelanggan(){wajibLogin();if($_SESSION['role']!=='pelanggan'){header('Location: ../admin/dashboard.php');exit;}}
