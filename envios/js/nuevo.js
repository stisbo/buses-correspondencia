var formulario = false;
var camaras = [];
var capturas = {};
var idCap = 0;
$camaras_sel = $('#camaras_select');
$(document).on('submit', '#form_nuevo', async (e) => {
  e.preventDefault();

  if (!formulario) {
    formulario = false;
    const data = $(e.target).serializeArray();
    console.log(data);
    const form = new FormData();
    data.forEach(d => {
      form.append(d.name, d.value)
    })
    let indice = 1;
    for (const clave in capturas) {
      form.append(`captura_${indice}`, capturas[clave]);
      indice++;
    }
    const res = await $.ajax({
      url: '../app/envio/create',
      data: form,
      processData: false,
      contentType: false,
      type: 'POST',
      dataType: 'json'
    });
    if (res.status == 'success') {
      $.toast({
        heading: '<b>PAGO AGREGADO</b>',
        text: 'Se agregó el pago exitosamente',
        icon: 'success',
        position: 'top-right',
        stack: 3,
        hideAfter: 1500
      });
      const envio = res.envio
      console.log(envio)
      setTimeout(() => {
        window.open('../reports/pdfEnvio.php?enid=' + envio.idEnvio, 'blank');
        window.location.href = './';
      }, 1500);
    } else {
      formulario = false;
      $.toast({
        heading: '<b>OCURRIÓ UN ERROR</b>',
        text: 'No se pudo agregar el pago',
        icon: 'danger',
        position: 'top-right',
        stack: 2,
        hideAfter: 2800
      })
    }
  }
})

const tieneSoporteUserMedia = () =>
  !!(navigator.getUserMedia || (navigator.mozGetUserMedia || navigator.mediaDevices.getUserMedia) || navigator.webkitGetUserMedia || navigator.msGetUserMedia)

const _getUserMedia = (...arguments) =>
  (navigator.getUserMedia || (navigator.mozGetUserMedia || navigator.mediaDevices.getUserMedia) || navigator.webkitGetUserMedia || navigator.msGetUserMedia).apply(navigator, arguments);

const obtenerDispositivos = () => navigator
  .mediaDevices
  .enumerateDevices()

$(document).on('click', '#btn_agregar_fotos', async () => {
  $camaras_sel.html('')
  if (tieneSoporteUserMedia()) {
    let dispositivos = await obtenerDispositivos();
    camaras = dispositivos.filter(d => d.kind === 'videoinput');
    if (camaras.length > 0) {
      llenarCamaras()
      streamCamara(camaras[0].deviceId)
    } else {
      toast('Sin camara', 'No se encontró ningún dispositivo', 'error', 2300)
    }
  } else {
    toast('No soportado', 'No hay permisos de video', 'error', 2300)
  }
})

function llenarCamaras() {
  camaras.forEach(c => {
    $camaras_sel.append(`<option value="${c.deviceId}">${c.label}</option>`)
  })
}

$camaras_sel.on('change', (e) => {
  detenerStream();
  streamCamara(e.target.value);
})

async function streamCamara(id) {
  const stream = await _getUserMedia({
    video: {
      deviceId: id
    }
  }, (stream) => {
    const video = document.getElementById('video');
    video.srcObject = stream;
    video.play();
  }, (error) => {
    console.log(error);
  })
  return stream;
}
async function detenerStream() {
  const stream = document.getElementById('video').srcObject;
  const tracks = stream.getTracks();
  tracks.forEach(track => track.stop());
  document.getElementById('video').srcObject = null;
  return true;
}

function capturar() {
  if (Object.keys(capturas).length < 3) {
    capturarFoto();
  } else {
    toast('Limite', 'No puedes agregar más fotos', 'error', 2300)
  }
}
function capturarFoto() {
  var canvas = document.createElement('canvas');
  canvas.width = video.videoWidth * 0.27; // 25% del ancho original
  canvas.height = video.videoHeight * 0.27; // 25% de la altura original
  var ctx = canvas.getContext('2d');
  ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
  idCap++;
  capturas[idCap] = canvas.toDataURL('image/png');
  $("#imgs_capturas").append(`
    <div id="idCap-${idCap}" class="position-relative">
      <img src="${canvas.toDataURL('image/png')}" class="img-fluid" />
      <button type="button" class="btn btn-sm btn-danger position-absolute" style="bottom:5px; right:5px;" onclick="eliminarCaptura(${idCap})"><i class="fa fa-trash"></i></button>
    </div>`)
}
$(document).on('change', '#fecha_envio', (e) => {
  const fecha = new Date(e.target.value);
  fecha.setDate(fecha.getDate() + 1);
  $("#fecha_estimada").val(fecha.toISOString().slice(0, 16).replace('T', ' '))
})
function eliminarCaptura(id) {
  console.log('ELiminar', id)
  capturas[id] = undefined;
  if (delete capturas[id]) {
    $("#idCap-" + id).remove();
  }
}