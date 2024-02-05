var formulario = false;
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

