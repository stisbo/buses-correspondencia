<?php // COPIA DE G22
require_once('../app/config/accesos.php');
require_once('../app/config/database.php');
require_once('../tcpdf/tcpdf.php');
require_once('../app/models/envio.php');
require_once('./letras-numeros.php');
require_once('../app/models/external.php');

use App\Config\Accesos;
use App\Config\Database;
use App\Models\Envio;
use App\Models\External;

if (!isset($_GET['enid'])) {
  echo '<h1 align="center">Parametro id necesario</h1>';
  die();
} else {

  $datos_emp = Accesos::getNombresCookies();
  $conExt = Database::db_boletos();;

  $subdominio = $datos_emp->name;
  $details_emp = $datos_emp->details;
  $cel_emp = $datos_emp->phone;
  // $nombre_suc = 'Sucursal Central';
  $envio = new Envio($_GET['enid']);
  if ($envio->idEnvio == 0) {
    header('Location: ../');
    die();
  }
  $trip = External::get_trip($conExt, $envio->trip_id);
  $fechaSalida = explode('-', $trip['departure_date'] ?? '---');
  $horaSalida = explode(':', $trip['departure_time'] ?? ':::');
  $width = 205;
  $height = 210;

  // Calculamos alto de pagina (unicamente por el campo detalle_envio) Es el unico que puede ser mas grande
  $tam_fuente = 8;
  $w = $width - 0; // width - margins-x
  $lineas = contarLineas($envio->detalle_envio, $w, $tam_fuente) + contarLineas($envio->observacion_envio ?? '', $w, $tam_fuente);
  $aumentar = $lineas > 1 ? ($lineas - 1) * $tam_fuente : 0;
  $height = $height + $aumentar;


  $pageLayout = array($width, $height);
  $pdf = new TCPDF('p', 'pt', $pageLayout, true, 'UTF-8', false);

  $pdf->SetCreator('PDF_CREATOR');
  $pdf->SetAuthor('STIS - BOLIVIA');
  $pdf->SetTitle('Nota de envio');
  $pdf->setPrintHeader(false);
  $pdf->setPrintFooter(false);
  $pdf->SetMargins(0, 0, 1, false);
  $pdf->SetAutoPageBreak(true, 2);
  $pdf->SetFont('Helvetica', '', $tam_fuente);
  $pdf->addPage();

  $baseUrl = "https://webinventario.com/buses";
  // $baseUrl = "http://localhost/correspondencia";
  $codeqr = $baseUrl . "/qr/?rq=" . $datos_emp->digest . "_" . $envio->idEnvio;

  $style = array(
    'border' => false,
    'padding' => 0,
    'fgcolor' => array(0, 0, 0),
    'bgcolor' => false
  );
  $pdf->write2DBarcode($codeqr, 'QRCODE,Q', 146, 0, 55, 55, $style, 'N'); // 2 en vez de 20
  // $content = '<h2 style="text-align:center;">NOTA DE ENVIO</h2>';
  // $pdf->writeHTML($content, true, 0, true, 0);
  $costo = $envio->costo ?? 0.00;
  $costo_literal = numtoletras($costo);
  $porPagar = $envio->pagado ?? 'POR PAGAR';
  $tabla = '<table border="0" cellpadding="0">
  <tr><td colspan="380" align="center" style="line-height:2;font-size:70%;"><b>ASOCIACIÓN DE TRANSPORTE LIBRE</b></td><td colspan="120"></td></tr>
  <tr><td colspan="380" align="center" style="line-height:1;"><b style="font-size:118%;">' . $subdominio . '</b></td><td colspan="120"></td></tr>
  <tr><td colspan="380" align="center" style="font-size:90%;">' . $details_emp . '</td><td colspan="120"></td></tr>
  <tr><td colspan="380" align="center">Cel. ' . $cel_emp . '</td><td colspan="120"></td></tr>
  <tr><td colspan="380" align="center" ><b>NOTA DE ENVIO</b> </td><td colspan="120"></td></tr>
  <tr><td colspan="90"></td><td colspan="200" align="center" style="border:1px solid #000"><b>' . $porPagar . '</b></td><td colspan="90"></td><td colspan="120"></td></tr>
  <tr><td colspan="500" style="border-bottom: 1px solid #000;"><b style="font-size:120%;">DESTINO: ' . $envio->destino . '</b></td></tr>
  </table>';
  $tabla .= '<table border="0" cellpadding="0">
            <tr><td colspan="500" style="font-size:50%;"></td></tr>
            <tr><td colspan="250"><b>CÓDIGO: ' . $envio->idEnvio . '-' . $envio->codigo . '</b></td><td colspan="250" align="right">' . date('d/m/Y H:i:s', strtotime($envio->fecha_envio)) . '</td></tr>
            <tr><td colspan="500"><b>Lugar origen: </b>' . $envio->origen . '</td></tr>
            <tr><td colspan="500" align="left"><b>Remitente: </b>' . strtoupper($envio->nombre_origen) . '</td></tr>
            <tr><td colspan="500"><b>Destinatario: </b>' . strtoupper($envio->nombre_destino) . '</td></tr>
            <tr><td colspan="500" style="border-bottom:1px solid #000;"><b>Salida: </b>' . $fechaSalida[2] . '/' . $fechaSalida[1] . ' ' . $horaSalida[0] . ':' . $horaSalida[1] . '</td></tr>
            </table>';
  $fechaEstimada = ($envio->fecha_estimada != null && $envio->fecha_estimada != '') ? date('d/m/Y', strtotime($envio->fecha_estimada)) : 'S/F';
  $tabla .= '<style>.border-bottom{border-bottom:1px solid #000;}.text-85{font-size:85%;;}</style>
  <table border="0" cellpadding="2">
  <tr><td colspan="500" style="font-size:40%;"></td></tr>
  <tr><td align="center" colspan="70" class="border-bottom"><b>Cant</b></td><td align="center" colspan="270" class="border-bottom"><b>Detalles</b></td><td align="center" colspan="70" class="border-bottom"><b>Kilos</b></td><td align="center" colspan="90" class="border-bottom"><b>Total</b></td></tr>
  <tr><td align="center" colspan="70" class="border-bottom">' . $envio->cantidad . '</td><td colspan="270" class="border-bottom">' . $envio->detalle_envio . '</td><td align="center" colspan="70" class="border-bottom">' . ($envio->peso ?? '0') . '</td><td align="right" colspan="90" class="border-bottom">' . $costo . '</td></tr>
  <tr><td colspan="500"><b>TOTAL</b></td></tr>
  <tr><td colspan="50"></td><td colspan="450">' . $costo_literal . '</td></tr>
  </table>';

  // $tabla .= '<table border="0" cellpadding="0"><tr><td colspan="500"></td></tr><tr><td colspan="500"></td></tr><tr><td colspan="500"></td></tr><tr><td colspan="500"></td></tr><tr><td colspan="500"></td></tr>';
  // $tabla .= '<tr><td colspan="200" align="center" style="padding: 8px; text-align: left; border-bottom: 1px solid #000;"></td><td colspan="100"></td><td colspan="200" align="center" style="padding: 8px; text-align: left; border-bottom: 1px solid #000;"></td></tr>';
  // $tabla .= '<tr><td colspan="200" align="center" style="padding: 8px; text-align: center;">' . $subdominio . '</td><td colspan="100"></td><td colspan="200" align="center" style="padding: 8px; text-align: center;">Firma remitente</td></tr>';
  // $tabla .= '</table>';
  $pdf->WriteHTMLCell(0, 0, 0, 0, $tabla, 0, 0);
  $pdf->output('dombre.pdf', 'I');
}

function contarLineas($cadena, $anchoPagina, $tamanoFuente) {
  $caracteresPorLinea = ceil($anchoPagina / ($tamanoFuente * 0.75));

  $lineas = str_split($cadena, $caracteresPorLinea);

  // Contar el número de líneas
  $numeroLineas = count($lineas);

  return $numeroLineas;
}
