<?php
$dato = $_GET['rq'];
if($dato == 1){
  include_once('envio.php');
}else{
  include_once('not_found.php');
}