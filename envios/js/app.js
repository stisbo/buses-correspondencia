var tabla = null;
var id_trip = null;
$(document).ready(async () => {
  await load_trips();
  const data = await getDataEnvios();
  initTable(data);
})
$(document).on('click', '#btn-search-filter', filterDate)
$(document).on('change', '#fechaViaje', (e) => id_trip = null)
$(document).on('change', '#trip_id', (e) => id_trip = e.target.value)
async function filterDate(e) {
  e.preventDefault();
  changeLoader();
  const value = $("#fechaViaje").val();
  console.log(value)
  if (value.length == 10) {
    if (id_trip == null)
      await load_trips(value);
    const data = await getDataEnvios();
    initTable(data);
  }
}
function changeLoader() {
  $("#loader_cont").toggleClass('d-none')
  $("#filters_head").toggleClass('d-none')
}
async function getDataEnvios() {
  if (id_trip == null) return []
  const res = await $.ajax({
    url: '../app/envio/data_by_trip_id',
    type: 'GET',
    data: { trip_id: id_trip },
    dataType: 'json'
  });
  if (res.success) return res.data;
}
function initTable(data) {
  if (tabla != null) tabla.destroy();

  tableHtml(data)
  changeLoader();
  tabla = $("#tabla_mis_envios").DataTable({
    language: lenguaje,
    info: false,
    scrollX: true,
    columnDefs: [
      { orderable: false, targets: [5, 7] }
    ],
  })

}
/**
 * @returns {boolean} TRUE si es una fecha mayor a la actual FALSE si no es mayor  
 */
function fechaProxima(fecha, hora, current) {
  const newHour = new Date(fecha.join('-') + 'T' + [hora[0], hora[1], '00'].join(':'))
  return newHour.getTime() > current.getTime()
}
async function load_trips(date = '') {
  const res = await $.ajax({
    url: '../app/external/trips_starting_date',
    type: 'GET',
    data: { date: date },
    dataType: 'json'
  })
  if (res.success) {
    let trips = res.data;
    loadTripSelect(trips, date);
  }
}
function loadTripSelect(trips, date) {
  let currentDateSelected = date == '' ? new Date() : new Date(date);
  let html = '';
  let seleccionado = false;
  let selected = '';
  id_trip = null;
  trips.forEach(t => {
    let hora = t.departure_time.split(':');
    let fecha = t.departure_date.split('-');
    if (fechaProxima(fecha, hora, currentDateSelected) && !seleccionado) {
      id_trip = t.id;
      console.log('Se seleccionara ', t)
      selected = 'selected'
      seleccionado = true;
    } else selected = ''
    html += `<option value="${t.id}" ${selected}>${fecha[2] + '/' + fecha[1]} ${hora[0] + ':' + hora[1]} - ${t.destino}</option>`;
  })
  $('#trip_id').html(html);
}
async function loadData() { }
$(document).on('show.bs.modal', '#modal_ver_capturas', async (e) => {
  const id = e.relatedTarget.dataset.id
  console.log(id)
  await $("#body_capturas").load('../app/envio/obtenercapturas', { idEnvio: id })
})
$(document).on('hide.bs.modal', '#modal_ver_capturas', () => {
  $("#body_capturas").html('')
})
$(document).on('show.bs.modal', '#modal_eliminar_envio', async (e) => {
  const id = e.relatedTarget.dataset.id
  $("#id_envio_delete").val(id)
})
$(document).on('hide.bs.modal', '#modal_eliminar_envio', () => {
  setTimeout(() => {
    $("#id_envio_delete").val('0')
  }, 990);
})
async function eliminar_envio() {
  const res = await $.ajax({
    url: '../app/envio/delete',
    data: { idEnvio: $("#id_envio_delete").val() },
    type: 'DELETE',
    dataType: 'json',
  });
  if (res.status == 'success') {
    toast('Operación exitosa', res.message, 'success', 1500)
    setTimeout(() => {
      location.reload()
    }, 1500);
  } else {
    toast('Error', res.message, 'error', 1500)
  }
}
function tableHtml(data) {
  let html = '';
  data.forEach(e => {
    let clss = '';
    switch (e.estado) {
      case 'ENVIADO':
        clss = 'bg-warning';
        break;
      case 'EN ALMACEN':
        clss = 'bg-primary';
        break;
      case 'ENTREGADO':
        clss = 'bg-success';
        break;
      default:
        clss = '';
        break;
    }
    let imagenes = e.capturas ?? '';
    let codigo = imagenes != '' ? `<button class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#modal_ver_capturas' data-id='${e.idEnvio}'>${e.idEnvio} - ${e.codigo} </button>` : `${e.idEnvio} - ${e.codigo}`;
    html += `<tr>
                <td class="text-center">${e.idEnvio}</td>
                <td class="text-center">${codigo}</td>
                <td class="text-center align-middle">${e.nombre_origen} | ${e.ci_origen}</td>
                <td class="text-center align-middle">${e.nombre_destino} | ${e.ci_destino}</td>
                <td class="text-center">${e.destino}</td>
                <td class="text-center">${new Date(e.fecha_envio).toLocaleDateString()}</td>
                <td class="text-center align-middle"><span class="badge ${clss}">${e.estado}</span></td>
                <td class="align-middle">
                  <div class="d-flex gap-1">
                    <a href="../reports/pdfEnvio.php?enid=${e.idEnvio}" target="_blank" class="btn btn-secondary"><i class="fa fa-solid fa-print"></i></a>
                      <a href="./edit.php?enid=${e.idEnvio}" class="btn btn-primary"><i class="fa fa-pen"></i></a>
                      <button type="button" class="btn btn-danger" data-bs-toggle='modal' data-bs-target='#modal_eliminar_envio' data-id="${e.idEnvio}"><i class="fa fa-trash"></i></button>
                  </div>
                </td>
              </tr>`
  });
  $("#t_body_envios").html(html)
}