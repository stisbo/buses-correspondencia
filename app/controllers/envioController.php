<?php

namespace App\Controllers;

use App\Models\Envio;

class EnvioController {
  public function create($data, $files) {
    $envio = new Envio();
    $envio->codigo = $envio->genCode();
    $envio->id_usuario_envio = $data['id_usuario_envio'];
    $envio->detalle_envio = $data['detalle_envio'];
    $envio->nombre_origen = $data['nombre_origen'];
    $envio->ci_origen = $data['ci_origen'];
    $envio->celular_origen = $data['celular_origen'];
    $envio->id_lugar_origen = $data['origen'];
    $envio->fecha_envio = $data['fecha_envio'];
    $envio->fecha_estimada = str_replace('T', ' ', $data['fecha_estimada']);
    $envio->nombre_destino = $data['nombre_destino'];
    $envio->ci_destino = $data['ci_destino'];
    $envio->celular_destino = $data['celular_destino'];
    $envio->id_lugar_destino = $data['destino'];
    $res = $envio->insert();
    if ($res > 0) {
      $envio->idEnvio = $res;
      echo json_encode(['status' => 'success', 'mensaje' => 'Envio registrado exitosamente', 'envio' => $envio]);
    } else {
      echo json_encode(['status' => 'error', 'mensaje' => 'Error al registrar el envio']);
    }
  }
  public function envios_nuevos($data) {
  }
  public function envios_recibidos($data) {
  }
}
