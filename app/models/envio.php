<?php

namespace App\Models;

class Envio {
  public int $idEnvio;
  public int $id_usuario_envio;
  public int $id_usuario_recibe;
  public string $estado, $detalle_envio, $fecha_envio, $fecha_llegada, $observacion_llegada;

  public string $nombe_origen;
  public string $ci_origen;
  public int $id_lugar_origen;
  public string $origen;

  public string $nombre_destino;
  public string $ci_detino;
  public int $id_lugar_destino;
  public string $destino;

  public function __construct($idEnvio = 0){
    if($idEnvio == 0){

    }else{

    }
  }
  
}
