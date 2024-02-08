<?php
date_default_timezone_set('America/La_Paz');
if (isset($_COOKIE['user_obj'])) {
	$user = json_decode($_COOKIE['user_obj']);
} else {
	header('Location: ../auth/login.php');
	die();
}
require_once('../app/models/lugar.php');
require_once('../app/config/accesos.php');
require_once('../app/config/database.php');

use App\Models\Lugar;

$lugares = Lugar::all();
?>
<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<title>Nuevo envio</title>
	<link rel="stylesheet" href="../assets/datatables/datatables.bootstrap5.min.css">
	<link href="../css/styles.css" rel="stylesheet" />
	<link rel="stylesheet" href="../assets/jquery/jqueryToast.min.css">
	<link rel="stylesheet" href="../css/custom.css">
	<script src="../assets/fontawesome/fontawesome6.min.js"></script>
	<script src="../assets/jquery/jquery.js"></script>
	<script src="../assets/jquery/jqueryToast.min.js"></script>
	<script src="../assets/sweetalert2/sweetalert2.all.min.js"></script>
</head>

<body>
	<?php include('./modals.php'); ?>
	<?php include("../common/header.php"); ?>
	<div id="layoutSidenav"> <!-- contenedor -->
		<?php include("../common/sidebar.php"); ?>
		<div id="layoutSidenav_content">
			<main id="main_egresos">
				<div class="container-fluid px-4">
					<div class="mt-4">
						<h1>Nuevo envio</h1>
					</div>
					<div class="buttons-head col-md-6 col-sm-12 mb-3">
						<button type="button" class="btn btn-secondary" onclick="history.back()"><i class="fa fa-arrow-left"></i> Volver </button>
					</div>
					<div class="row" id="card-egresos">
						<form id="form_nuevo">
							<input type="hidden" name="id_usuario_envio" value="<?= $user->idUsuario ?>">
							<div class="card shadow">
								<div class="card-body">
									<div class="collapse multi-collapse show" id="collapse_nuevo_1">
										<div class="row">
											<p class="fs-4 fw-bold"><i class="fa fa-solid fa-boxes-packing"></i> Datos remitente</p>
											<div class="col-md-4">
												<div class="form-floating mb-3">
													<input type="text" class="form-control" id="nombre_remitente" value="" placeholder="Remitente" name="nombre_origen" autocomplete="off">
													<label for="nombre_remitente">Nombre remitente</label>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-floating mb-3">
													<input type="text" class="form-control" id="ci_remitente" value="" name="ci_origen" placeholder="Carnet de identidad" autocomplete="off">
													<label for="ci_remitente">C.I. remitente</label>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-floating mb-3">
													<input type="date" class="form-control" placeholder="Fecha envio" name="fecha_envio" id="fecha_envio" required value="<?= date('Y-m-d') ?>">
													<label for="">Fecha envio</label>
												</div>
											</div>
											<div class="col-md-4">
												<input type="hidden" name="origen" value="<?= $user->idLugar ?>">
												<div class="form-floating mb-3">
													<input type="text" class="form-control" value="<?= $user->lugar ?>" disabled>
													<label for="">Lugar Origen</label>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-floating mb-3">
													<input type="text" class="form-control" placeholder="Observacion" name="detalle_envio">
													<label for="">Detalle envio</label>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-floating mb-3">
													<input type="text" class="form-control" placeholder="Observacion" name="celular_origen" value="">
													<label for="">Celular (opcional)</label>
												</div>
											</div>
											<p class="fs-4 fw-bold"><i class="fa fa-solid fa-people-carry-box"></i> Datos receptor</p>
											<div class="col-md-4">
												<div class="form-floating mb-3">
													<input type="text" class="form-control" placeholder="Nombre receptor" name="nombre_destino">
													<label for="">Nombre receptor</label>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-floating mb-3">
													<input type="text" class="form-control" id="ci_receptor" value="" name="ci_destino" placeholder="Carnet de identidad" autocomplete="off">
													<label for="ci_receptor">C.I. receptor</label>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-floating mb-3">
													<input type="text" class="form-control" id="celular_destino" value="" name="celular_destino" placeholder="Celular Destino" autocomplete="off">
													<label for="celular_destino">Celular receptor (opcional)</label>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-floating mb-3">
													<input id="fecha_estimada" type="datetime-local" class="form-control" placeholder="Fecha llegada" name="fecha_estimada" required value="<?=date('Y-m-d H:i', (time() + 24*3600))?>">
													<label for="">Fecha - hora llegada (estimado)</label>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-floating mb-3">
													<select name="destino" class="form-select">
														<?php foreach ($lugares as $lugar) : ?>
															<option value="<?= $lugar['idLugar'] ?>"><?= $lugar['lugar'] ?></option>
														<?php endforeach; ?>
													</select>
													<label for="">Destino</label>
												</div>
											</div>
											<div class="col-md-4">
												<button id="btn_agregar_fotos" type="button" class="btn btn-primary" data-bs-toggle="collapse" data-bs-target=".multi-collapse" aria-expanded="false" aria-controls="collapse_nuevo_1 collapse_nuevo_2"><i class="fa fa-camera"></i> ¿Agregar fotos?</button>
											</div>
										</div>
									</div>
									<div class="collapse multi-collapse" id="collapse_nuevo_2">
										<div class="row">
											<div class="col-md-6">
												<div style="width:100%;height:290px;border:1px solid red">
													<video muted="muted" id="video" style="width:100%;height:100%;margin:0px;"></video>
												</div>
											</div>
											<div class="col-md-6">
												<select id="camaras_select" class="form-select mb-2"></select>
												<button type="button" class="btn btn-success rounded-pill" onclick="capturar()"><i class="fa fa-camera"></i> Capturar</button>
												<button onclick="detenerStream()" type="button" class="btn btn-secondary" data-bs-toggle="collapse" data-bs-target=".multi-collapse" aria-expanded="false" aria-controls="collapse_nuevo_1 collapse_nuevo_2">Seguir editando</button>
												<div class="d-flex">
													<div class=""></div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="card-footer">
									<div class="d-flex justify-content-center">
										<button type="submit" class="btn btn-success shadow">GUARDAR</button>
									</div>
								</div>
							</div><!-- end card -->
						</form>
					</div>
				</div>
			</main>
		</div>
	</div><!-- fin contenedor -->

	<script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="../js/scripts.js"></script>
	<script src="../assets/datatables/datatables.jquery.min.js"></script>
	<script src="../assets/datatables/datatables.bootstrap5.min.js"></script>
	<script src="./js/nuevo.js"></script>
</body>

</html>