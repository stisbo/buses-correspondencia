var tabla = null;
$(document).ready(async () => {
  loadInicio()
});

async function loadInicio() {
  listar({});
}

async function listar(data) {
  if (tabla) {
    tabla.destroy();
  }
  const res = await $.ajax({
    url: '../app/cproyecto/getProjects',
    type: 'GET',
    dataType: 'json',
    data: { ...data, tipo: 'EGRESO' },
  });
  if (res.status == 'success') {
    const data = JSON.parse(res.data);
    const pagos = JSON.parse(res.pagos);
    $("#t_body_egresos").html(generarTabla(data, pagos));
    tabla = $("#table_egresos").DataTable({
      language: lenguaje,
      info: false,
      scrollX: true,
      columnDefs: [
        { orderable: false, targets: [5, 7] }
      ],
    })
  }
}
function generarTabla(data, pagos) {
  let html = '';
  // console.log(pagos)
  const cookie = JSON.parse(decodeURIComponent(getCookie('user_obj')));
  data.forEach((element) => {
    let clsEstado = element.estado == 'PENDIENTE' ? 'text-bg-warning' : 'text-bg-success';
    let fecha = new Date(element.fechaCreacion);
    let opciones = `
      <div><a class="btn btn-success rounded-circle" href="./nuevo.php?proid=${element.idProyecto}"><i class="fa fa-solid fa-circle-plus"></i></a></div>
      <div><a class="btn btn-primary text-white" type="button" href="./pagoslist.php?proid=${element.idProyecto}"><i class="fa fa-eye"></i></a></div>`;
    if (cookie.rol == 'ADMIN' || cookie.rol == 'EDITOR') {
      opciones += `<div><button class="btn btn-info tet" type="button"  data-bs-toggle="modal" data-bs-target="#modal_egreso_nuevo" data-idproyecto="${element.idProyecto}"><i class="fa fa-pencil"></i></button></div>`;
    }
    if (cookie.rol == 'ADMIN') {
      opciones += `<div><button class="btn btn-danger" type="button" data-bs-toggle="modal" data-bs-target="#modal_delete_egreso" data-idproyecto="${element.idProyecto}"><i class="fa fa-trash"></i></button></div>`;
    }
    let pago = pagos.find(p => p.idProyecto == element.idProyecto);
    let monto = pago ? pago.total : 0;
    // console.log(monto)
    html += `<tr>
    <td class="text-center">${element.idProyecto}</td>
    <td>${element.proyecto.toUpperCase()}</td>
    <td>${element.tipo}</td>
    <td class="text-end">${Number(element.montoRef).toFixed(2)}</td>
    <td class="text-end">${Number(monto).toFixed(2)}</td>
    <td class="text-center">${element.alias.toUpperCase()}</td>
    <td>${fecha.toLocaleDateString()}</td>
    <td align="center"><span class="badge ${clsEstado}">${element.estado}</span></td>
    <td align="center">
      <div class="d-flex justify-content-between gap-2">
        ${opciones}
      </div>
    </td>
  </tr>`
  })
  return html;
}

$("#modal_egreso_nuevo").on('show.bs.modal', async (e) => {
  if (parseInt(e.relatedTarget.dataset.idproyecto) != 0) { // edit
    const res = await $.ajax({
      url: '../app/cproyecto/getProjectID',
      type: 'GET',
      data: { idProyecto: e.relatedTarget.dataset.idproyecto },
      dataType: 'json'
    });
    if (res.status == 'success') {
      const data = JSON.parse(res.data);
      $("#idProyecto_egreso").val(data.idProyecto);
      $("#descripcion_e").val(data.proyecto.toUpperCase());
      $("#monto_e").val(data.montoRef);
      let htmlOptions = `<option value="PENDIENTE" ${data.estado == 'PENDIENTE' ? 'selected' : ''}>PENDIENTE</option>
      <option value="SALDADO" ${data.estado == 'SALDADO' ? 'selected' : ''}>SALDADO</option>`;
      $("#estado_e").html(htmlOptions);
    } else {
      console.warn(res);
    }
  } else { //create
    let htmlOptions = `
    <option value="">-- Seleccione una opción --</option>
    <option value="PENDIENTE">PENDIENTE</option>
    <option value="SALDADO" >SALDADO</option>`;
    $("#estado_e").html(htmlOptions);
  }
})

$("#modal_egreso_nuevo").on('hide.bs.modal', async () => {
  setTimeout(() => {
    $("#idProyecto_egreso").val('0');
    $("#descripcion_e").val('');
    $("#monto_e").val('');
    $("#estado_e").val('');
  }, 800);
})
$("#modal_delete_egreso").on('show.bs.modal', async (e) => {
  $('#idProyecto_delete').val(e.relatedTarget.dataset.idproyecto);
})
async function egresoUp() {
  let data = $("#form_egreso").serialize();
  data += '&tipo=EGRESO';
  const res = await $.ajax({
    url: '../app/cproyecto/create',
    type: 'POST',
    data,
    dataType: 'json'
  });
  if (res.status == 'success') {
    $.toast({
      heading: 'Operación exitosa',
      icon: 'success',
      position: 'top-right',
      hideAfter: 1100
    })
    setTimeout(() => {
      location.reload()
    }, 1200);
  }
  $("#modal_egreso_nuevo").modal('hide')
}

$(document).on('click', '.filter-btns', async (e) => {
  const list = e.target.classList;
  if (list.value.split(' ').includes('active')) return;
  const value = e.target.dataset.value;
  const anio = $('#filter_year').val()
  await listar({ estado: value, year: anio });
  $('.filter-btns').each((index, element) => {
    $(element).removeClass('active');
  });
  $(e.target).addClass('active');
});

$(document).on('change', '#filter_year', async (e) => {
  let val;
  $('.filter-btns').each((_, element) => {
    if ($(element).hasClass('active')) {
      val = $(element).data('value');
    }
  });
  const data = {
    year: e.target.value,
    estado: val
  }
  await listar(data);
})

async function eliminar_egreso() {
  const res = await $.ajax({
    url: '../app/cproyecto/delete',
    type: 'DELETE',
    data: { idProyecto: $('#idProyecto_delete').val() },
    dataType: 'json'
  });
  if (res.status == 'success') {
    $.toast({
      heading: 'Proyecto eliminado',
      icon: 'success',
      position: 'top-right',
      hideAfter: 1100
    })
    setTimeout(() => {
      location.reload();
    }, 1200);
  } else {
    $.toast({
      heading: res.message,
      icon: 'danger',
      position: 'top-right',
      hideAfter: 2500
    })
  }
}