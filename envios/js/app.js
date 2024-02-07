var formulario = false;
var camaras = [];
var capturas = 0;
$camaras_sel = $('#camaras_select');
$(document).on('submit', '#form_nuevo', async (e) => {
  e.preventDefault();

  if (!formulario) {
    formulario = true;
    const data = $(e.target).serialize();
    console.log(data);
    const res = await $.ajax({
      url: '../app/envio/create',
      data,
      type: 'POST',
      dataType: 'json'
    });
    // if (res.status == 'success') {
    //   $.toast({
    //     heading: '<b>PAGO AGREGADO</b>',
    //     text: 'Se agregó el pago exitosamente',
    //     icon: 'success',
    //     position: 'top-right',
    //     stack: 3,
    //     hideAfter: 1500
    //   });
    //   const pago = JSON.parse(res.pago)
    //   setTimeout(() => {
    //     window.location.href = './';
    //     window.open('../reports/pago.php?pagid=' + pago.idPago, 'blank');
    //   }, 1500);
    // } else {
    //   formulario = false;
    //   $.toast({
    //     heading: '<b>OCURRIÓ UN ERROR</b>',
    //     text: 'No se pudo agregar el pago',
    //     icon: 'danger',
    //     position: 'top-right',
    //     stack: 2,
    //     hideAfter: 2800
    //   })
    // }
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
  if (capturas < 3) {
    capturas++;
    const data = capturarFoto();
    const $foto = $(`<img src="${data}" class="img-fluid" />`);
    $('#fotos').append($foto);
  } else {
    toast('Limite', 'No puedes agregar más fotos', 'error', 2300)
  }
  // const canvas = document.getElementById('canvas');
  // const video = document.getElementById('video');
  // canvas.width = video.videoWidth;
  // canvas.height = video.videoHeight;
  // canvas.getContext('2d').drawImage(video, 0, 0);
  // const data = canvas.toDataURL('image/png');
  // return data;
}