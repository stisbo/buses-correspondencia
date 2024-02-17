<?php
require_once('../app/config/accesos.php');
require_once('../app/config/database.php');
use App\Config\Accesos;

$crendenciales = Accesos::getCredentialsEmp('bolivar', 1);
print_r($crendenciales);
?>