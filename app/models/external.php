<?php

namespace App\Models;

use PDO;

/**
 * Funciones externas para recuperar datos 
 * de la base de datos de sistema BOLETOS
 */
class External {
  public static function get_trips($con, $filters) {
    try {
      $today = date('Y-m-d');
      $date = isset($filters['date']) ?
        "WHERE a.departure_date = '" . $filters['date'] . "'" :
        "WHERE a.departure_date = '$today'";
      $where = isset($filters['next_days']) ? "WHERE a.departure_date >= '$today'" : $date;
      $sql = "SELECT a.*, b.location as origen, c.location as destino, d.placa, e.fullname as conductor FROM trips a 
        INNER JOIN locations b ON a.location_id_origin = b.id
        INNER JOIN locations c ON a.location_id_dest = c.id
        INNER JOIN buses d ON a.bus_id = d.id
        INNER JOIN drivers e ON a.driver_id = e.id
        $where 
        ORDER BY a.departure_date ASC;";
      $stmt = $con->prepare($sql);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return $rows;
    } catch (\Throwable $th) {
      var_dump($th);
    }
    return [];
  }
  public static function get_trip($con, $trip_id) {
    try {
      $sql = "SELECT a.*, b.location as origen, c.location as destino, d.placa, e.fullname as conductor FROM trips a
        INNER JOIN locations b ON a.location_id_origin = b.id
        INNER JOIN locations c ON a.location_id_dest = c.id
        INNER JOIN buses d ON a.bus_id = d.id
        INNER JOIN drivers e ON a.driver_id = e.id
        WHERE a.id = $trip_id;";
      $stmt = $con->prepare($sql);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      return $row;
    } catch (\Throwable $th) {
      var_dump($th);
    }
    return [];
  }
  /**
   * Retorna un nuevo calo
   * @param mixed $con
   * @param mixed $trip_id
   * @return mixed
   */
  public static function get_total_amount_trip($con, $trip_id) {
    try {
      $sql = "SELECT sum(costo) total FROM tblEnvio WHERE trip_id = $trip_id AND (pagado = 'PAGADO' OR pagado = 'SERVICIO INTERNO'); ";
      $stmt = $con->prepare($sql);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      return $row['total'] ?? 0;
    } catch (\Throwable $th) {
      var_dump($th);
    }
    return 0;
  }
}
