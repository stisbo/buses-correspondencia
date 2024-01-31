$(document).ready(() => {
  listarUsuarios();
})

$(document).on('click', '#btn_modal_user', async () => {
  $(".alert-dismissible").remove()
  const data = $("#form_nuevo_user").serialize();
  const res = await $.ajax({
    url: '../app/usuario/create',
    data: data,
    type: 'POST',
    dataType: 'JSON'
  });
  if (res.status == 'success') {
    $("#form_nuevo_user").append(`<div class="alert alert-success alert-dismissible fade show" role="alert">
      <strong>¡Registro exitoso!</strong> El nuevo usuario puede ingresar al sistema. <u>La contraseña es la misma que el usuario.</u> 
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>`);
    $("#btn_modal_user").attr('disabled', true);
  } else {
    $("#form_nuevo_user").append(`<div class="alert alert-danger alert-dismissible fade show" role="alert">
      <strong>¡Ocurrió un error!</strong> ${res.message}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>`);
  }
})
$(document).on('hide.bs.modal', '#modal_usuario_nuevo', () => {
  setTimeout(() => {
    document.querySelector('#form_nuevo_user').reset();
    $("#btn_modal_user").attr('disabled', false);
  }, 900);
})

async function listarUsuarios() {
  const res = await $.ajax({
    url: '../app/usuario/getallUsers',
    type: 'GET',
    dataType: 'JSON'
  });

  if (res.status == 'success') {
    const data = JSON.parse(res.data);
    let htmlTable = '';
    $.each(data, (i, item) => {
      htmlTable += `<tr>
        <td>${item.idUsuario}</td>
        <td>${item.nombre.toUpperCase()}</td>
        <td>${item.usuario}</td>
        <td>${item.rol}</td>
        <td class="text-center">${item.lugar}</td>
        <td>
          <div class="d-flex justify-content-around">
            <div>
              <button class="btn btn-primary rounded-circle" type="button" data-bs-toggle="modal" data-bs-target="#modal_usuario_edit" data-alias="${item.alias}" data-rol="${item.rol}" data-id="${item.idUsuario}"><i class="fa fa-pencil"></i></button>
            </div>
            <div>
              <button class="btn btn-secondary rounded-circle" title="Restablecer contraseña" type="button" data-bs-toggle="modal" data-bs-target="#modal_reset_pass" data-id="${item.idUsuario}"><i class="fa fa-lock"></i></button>
            </div>
            <div>
              <button class="btn btn-danger rounded-circle" title="eliminar usuario" type="button" data-bs-toggle="modal" data-bs-target="#modal_delete_usuario" data-id="${item.idUsuario}"><i class="fa fa-trash"></i></button>
            </div>
          </div>
        </td>
      </tr>`;
    });
    $("#tbl_users").html(htmlTable);
    $("#table_usuarios").DataTable({
      language: lenguaje,
      info: false,
      scrollX: true,
    });
  }
}

$(document).on('show.bs.modal', '#modal_usuario_edit', (e) => {
  const alias = $(e.relatedTarget).data('alias');
  const rol = $(e.relatedTarget).data('rol');
  const id = $(e.relatedTarget).data('id');
  $("#user_alias_edit").val(alias);
  $("#id_usuario_edit").val(id);
  $("#user_rol_edit").html(`
    <option value="VISOR" ${rol == 'VISOR' ? 'selected' : ''}>VISOR</option>
    <option value="EDITOR" ${rol == 'EDITOR' ? 'selected' : ''}>EDITOR</option>
  `);
});
$(document).on('hide.bs.modal', '#modal_usuario_edit', () => {
  setTimeout(() => {
    $("#user_alias_edit").val('');
    $("#id_usuario_edit").val('');
    $("#user_rol_edit").html(``);
  }, 900);
});

async function updateUser() {
  const data = $("#form_user_edit").serialize();
  const res = await $.ajax({
    url: '../app/cusuario/update',
    data,
    type: 'PUT',
    dataType: 'JSON'
  });
  if (res.status == 'success') {
    $.toast({
      heading: 'ACTUALIZACIÓN CORRECTA',
      icon: 'success',
      position: 'top-right',
      hideAfter: 1100
    })
    setTimeout(() => {
      window.location.reload();
    }, 1109);
  } else {
    console.warn(res)
    $.toast({
      heading: 'OCURRIÓ UN ERROR',
      icon: 'warning',
      position: 'top-right',
      hideAfter: 1100
    })
  }
}

$(document).on('show.bs.modal', '#modal_delete_usuario', (e) => {
  const id = $(e.relatedTarget).data('id');
  $("#idUsuario_delete").val(id);
});
async function deleteUser() {
  const res = await $.ajax({
    url: '../app/cusuario/delete',
    type: 'DELETE',
    data: { idUsuario: $("#idUsuario_delete").val() },
    dataType: 'JSON'
  });
  if (res.status == 'success') {
    $.toast({
      heading: 'USUARIO ELIMINADO',
      icon: 'success',
      position: 'top-right',
      hideAfter: 1100
    })
    setTimeout(() => {
      window.location.reload();
    }, 1109);
  } else {
    console.warn(res)
    $.toast({
      heading: 'OCURRIÓ UN ERROR',
      icon: 'warning',
      position: 'top-right',
      hideAfter: 1100
    })
  }
}

$(document).on('show.bs.modal', '#modal_reset_pass', (e) => {
  const id = $(e.relatedTarget).data('id');
  $("#idUsuario_reset_pass").val(id);
})

async function user_reset_pass() {
  const res = await $.ajax({
    url: '../app/usuario/resetPass',
    type: 'PUT',
    data: { idUsuario: $("#idUsuario_reset_pass").val() },
    dataType: 'JSON'
  });
  if (res.status == 'success') {
    $.toast({
      heading: 'CONTRASEÑA CAMBIADA',
      icon: 'success',
      position: 'top-right',
      hideAfter: 1100
    })
    setTimeout(() => {
      // window.location.reload();
    }, 1109);
  } else {
    console.warn(res)
    $.toast({
      heading: 'OCURRIÓ UN ERROR',
      icon: 'warning',
      position: 'top-right',
      hideAfter: 1100
    })
  }
}