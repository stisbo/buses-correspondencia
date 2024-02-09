var tabla = null;
$(document).ready(async () => {
  listar({})
});

async function listar(data) {
  if (tabla) {
    tabla.destroy();
  }
  const user = getCookie('user_obj');
  if (!(user && user != '')) {
    window.location.href = '../auth/login.php';
    return;
  }
  const cookie_u = JSON.parse(decodeURIComponent(user));
  console.log(cookie_u)
  const res = await $.ajax({
    url: '../app/envio/lista_envios_a_recibir',
    type: 'GET',
    dataType: 'json',
    data: { idLugar: cookie_u.idLugar, ...data },
  });
  console.log(res);
  if (res.status == 'success') {
    $("#t_body_recepcion").html(generarTabla(res.envios));
    tabla = $("#table_recepcion").DataTable({
      language: lenguaje,
      info: false,
      scrollX: true,
      columnDefs: [
        // { orderable: false, targets: [5, 7] }
      ],
    })
  }
}

function generarTabla(data) {
  let html = '';
  data.forEach((element) => {
    let clsEstado = element.estado == 'RECIBIDO' ? 'text-bg-success' : 'text-bg-warning';
    let estado = element.estado == 'RECIBIDO' ? 'RECIBIDO' : 'PENDIENTE';
    let fechaEnvio = new Date(element.fecha_envio);
    let fechaLlegada = element.fecha_llegada ? new Date(element.fecha_llegada).toLocaleDateString() : 'No llegó';
    let opciones = `
      <div><button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#modal_entregar_envio" data-codigo="${element.codigo}" data-id="${element.idEnvio}">Entregar</button></div>
    `;
    let codigo = (element.capturas != '' && element.capturas != null) ?
      `<button class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#modal_ver_capturas' data-id="${element.idEnvio}">${element.idEnvio}-${element.codigo}</button>
    </div>`: `${element.idEnvio}-${element.codigo}`;
    html += `<tr>
    <td class="text-center">${element.idEnvio}</td>
    <td class="text-center">${codigo}</td>
    <td>${fechaEnvio.toLocaleDateString()}</td>
    <td class="text-center"><span class="badge ${clsEstado}">${estado}</span></td>
    <td>${element.detalle_envio}</td>
    <td class="text-center">${fechaLlegada}</td>
    <td>${element.nombre_destino}</td>
    <td align="center">${element.ci_destino}</td>
    <td align="center">${element.celular_destino}</td>
    <td align="center">
      <div class="d-flex justify-content-between gap-2">
        ${opciones}
      </div>
    </td>
  </tr>`
  })
  return html;
}

$(document).on('click', '.filter-btns', async (e) => {
  const list = e.target.classList;
  if (list.value.split(' ').includes('active')) return;
  const value = e.target.dataset.value;
  if (value == '') {
    await listar({});
  } else {
    await listar({ estado: value });
  }
  $('.filter-btns').each((_, element) => {
    $(element).removeClass('active');
  });
  $(e.target).addClass('active');
});

$(document).on('show.bs.modal', '#modal_entregar_envio', cargarEntrega);
// $(document).on('hide.bs.modal', '#modal_entregar_envio', );
async function cargarEntrega(e) {
  const codigo = e.relatedTarget.dataset.codigo;
  const id = e.relatedTarget.dataset.id;
  $("#codigo_modal_entregar").html(`${id}-${codigo}`)
  const res = await $.ajax({
    url: '../app/envio/envio',
    data: { idEnvio: id },
    type: 'GET',
    dataType: 'json',
  })
  if (res.status == 'success') {
    const envio = res.envio;
    const fechaEnvio = new Date(envio.fecha_envio);
    const estado = envio.estado == 'RECIBIDO' ? 'RECIBIDO' : 'PENDIENTE';
    const fechaRecibido = envio.fecha_llegada ? new Date(envio.fecha_llegada).toLocaleDateString() : 'S/F';
    html = `<div class="row fs-5">
      <input type="hidden" id="id_envio_entrega" value="${envio.idEnvio}"> 
      <p class="fw-bold">DATOS ENVIO</p>
      <div class="col-lg-6"><b>Remitente:</b> ${envio.nombre_origen} CI: ${envio.ci_origen} CEL: ${envio.celular_origen ?? ''}</div>
      <div class="col-lg-6"><b>Envio: </b> ${envio.detalle_envio} desde ${envio.origen} en fecha: ${fechaEnvio.toLocaleDateString()}</div>
      <p class="fw-bold mb-0 mt-3">DATOS DESTINATARIO</p>
      <div class="col-lg-6"><b>Destinatario:</b> ${envio.nombre_destino} CI: ${envio.ci_destino} CEL: ${envio.celular_destino ?? ''}</div>
      <div class="col-lg-6"><b>Destino: </b>${envio.destino} <b>Estado: </b>${estado}</div>
      <p class="fw-bold mb-0 mt-3">DETALLES ENTREGA</p>
      <div class="col-lg-6">
        <div class="form-floating mb-3">
          <input type="text" class="form-control" id="obs_entrega" value="" placeholder="Remitente" autocomplete="off">
          <label for="obs_entrega">Observación (opcional)</label>
        </div>
      </div>
    </div>`;
    $("#body_modal_entregar_envio").html(html)
  } else {
    $("#modal_entregar_envio").modal('hide');
    toast('Ocurrió un error', res.message, 'error')
  }
}

async function entregarEnvio() {
  const idEnvio = $("#id_envio_entrega").val();
  const obs = $("#obs_entrega").val();
  console.log(idEnvio, obs)
  const res = await $.ajax({
    url: '../app/envio/registra_entrega',
    data: { idEnvio, obs },
    type: 'PUT',
    dataType: 'json',
  })
  if (res.status == 'success') {
    toast('Exito', res.message, 'success', 1700)
    window.open('../reports/pdfEntrega.php?enid=' + idEnvio, '_blank');
    setTimeout(() => {
      location.reload();
    }, 1800);
  } else {
    toast('Error', res.message, 'error')
  }
}


$(document).on('show.bs.modal', '#modal_ver_capturas', async (e) => {
  const id = e.relatedTarget.dataset.id
  console.log(id)
  await $("#body_capturas").load('../app/envio/obtenercapturas', { idEnvio: id })
})
$(document).on('hide.bs.modal', '#modal_ver_capturas', () => {
  $("#body_capturas").html('')
})