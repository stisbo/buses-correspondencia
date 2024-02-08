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
      $res = $envio->saveImages($data);
      if ($res > 0) {
        echo json_encode(['status' => 'success', 'mensaje' => 'Envio registrado exitosamente', 'envio' => $envio]);
      } else {
        echo json_encode(['status' => 'error', 'mensaje' => 'Error al registrar las imagenes del envio']);
      }
    } else {
      echo json_encode(['status' => 'error', 'mensaje' => 'Error al registrar el envio']);
    }
  }

  public function lista_envios_a_recibir($data) {
    if (isset($data['idLugar'])) {
      $estado = $data['estado'] ?? null;
      $envios = Envio::getRecibir($data['idLugar'], $estado);
      echo json_encode(['status' => 'success', 'envios' => $envios]);
    } else {
      echo json_encode(['status' => 'error', 'mensaje' => 'Necesario datos de usuario COOKIES']);
    }
  }
  public function lista_entregados($data) {
    if (isset($data['idUsuario'])) {
      $envios = Envio::getEntregados($data['idUsuario']);
      echo json_encode(['status' => 'success', 'envios' => $envios]);
    } else {
      echo json_encode(['status' => 'error', 'mensaje' => 'Necesario datos de usuario COOKIES']);
    }
  }
  public function envio($data) {
    if (isset($data['idEnvio'])) {
      $envio = new Envio($data['idEnvio']);
      if ($envio->idEnvio) {
        echo json_encode(['status' => 'success', 'envio' => $envio]);
      } else {
        echo json_encode(['status' => 'error', 'message' => 'Envio no encontrado']);
      }
    } else {
      echo json_encode(['status' => 'error', 'message' => 'Necesario datos id de envio']);
    }
  }
  public function registra_llegada($data) {
    date_default_timezone_set('America/La_Paz');
    $user = json_decode($_COOKIE['user_obj']);
    if (isset($data['idEnvio'])) {
      $envio = new Envio($data['idEnvio']);
      $anterior = clone $envio;
      if ($envio->idEnvio) {
        $envio->estado = "RECIBIDO";
        $envio->fecha_llegada = date('Y-d-m H:i:s');
        $envio->id_usuario_recibe = $user->idUsuario;
        $res = $envio->update($anterior);
        if ($res > 0) {
          echo json_encode(['status' => 'success', 'message' => 'Envio registrado como recibido']);
        } else {
          echo json_encode(['status' => 'error', 'message' => 'Error al registrar el envio']);
        }
      } else {
        echo json_encode(['status' => 'error', 'message' => 'Envio no encontrado']);
      }
    } else {
      echo json_encode(['status' => 'error', 'message' => 'Necesario datos id de envio']);
    }
  }
  public function registra_entrega($data) {
    $user = json_decode($_COOKIE['user_obj']);
    if (isset($data['idEnvio'])) {
      $envio = new Envio($data['idEnvio']);
      $anterior = clone $envio;
      if ($envio->idEnvio) {
        $envio->estado = "ENTREGADO";
        $envio->fecha_entrega = date('Y-d-m H:i:s');
        $envio->id_usuario_entrega = $user->idUsuario;
        $envio->observacion_llegada = $data['obs'];
        $res = $envio->update($anterior);
        if ($res > 0) {
          echo json_encode(['status' => 'success', 'message' => 'Envio registrado como entregado']);
        } else {
          echo json_encode(['status' => 'error', 'message' => 'Error al registrar el envio']);
        }
      } else {
        echo json_encode(['status' => 'error', 'message' => 'Envio no encontrado']);
      }
    } else {
      echo json_encode(['status' => 'error', 'message' => 'Necesario datos id de envio']);
    }
  }
}
