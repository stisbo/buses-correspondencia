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
      $date = $filters['date'] ?? date('Y-m-d');
      $sql = "SELECT a.*, b.location as origen, c.location as destino, d.placa, e.fullname as conductor FROM trips a 
        INNER JOIN locations b ON a.location_id_origin = b.id
        INNER JOIN locations c ON a.location_id_dest = c.id
        INNER JOIN buses d ON a.bus_id = d.id
        INNER JOIN drivers e ON a.driver_id = e.id
        WHERE a.departure_date >= '$date'
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
}
