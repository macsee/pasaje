function confirmar_admin(titulo, tipo, callback, form)
{
    $("#modal_confirmacion").find("#titulo").html(titulo);
        if (tipo == "usuario")
            $("#modal_confirmacion").find("#aceptar").attr('onclick','ok_confirmar_usuario("'+callback+'","'+form+'")');
        else
            $("#modal_confirmacion").find("#aceptar").attr('onclick','ok_confirmar_agenda("'+callback+'","'+form+'")');

    $("#modal_confirmacion").modal({
      show: true
    });
}

function ok_confirmar_usuario(callback, form_id)
{
  var form = $("#"+form_id);

  $.ajax({
      type: "POST",
      url: base_url+"/main/"+callback,
      data: form.serialize(),
    //   dataType: 'json',
      success:function(response)
      {
          console.log(response);
          get_datos_usuarios();
          $("#modal_confirmacion").modal('hide');
      }
  });

}

function ok_confirmar_agenda(callback, form_id)
{
  var form = $("#"+form_id);

  $.ajax({
      type: "POST",
      url: base_url+"/main/"+callback,
      data: form.serialize(),
      success:function(response)
      {
          get_datos_agendas()
          $("#modal_confirmacion").modal('hide');
      }
  });

}

function get_datos_agendas() {

    $.ajax({
        url: base_url+"/main/get_agenda_json/todos",
        dataType: 'json',
        success:function(response)
        {
            if (response != null) {
                html = "";
                $.each(response, function(key,val) {

                    html +=  '<div class="row" style = "height:50px;padding-top:10px;border-bottom: 1px solid #ddd;">'
                                +'<div class="col-md-3">'
                                    +val.nombre_agenda
                                +'</div>'
                                +'<div class="col-md-3">'
                                        +val.usuario
                                +'</div>'
                                +'<div class="col-md-5">'
                                    // +val.especialidad.split(",").join(", ")
                                    +JSON.parse(val.especialidad).toString().split(",").join(", ")// +str_replace($replace," ",$value->especialidad);
                                +'</div>'
                                +'<div class="col-md-1" style = "padding-top:0px">'
                                    +'<div class = "dropdown">'
                                        +'<button class="btn btn-default dropdown-toggle" data-toggle="dropdown" role="button"><span class = "glyphicon glyphicon-menu-hamburger"></span></button>'
                                        +'<ul class="dropdown-menu pull-right">'
                                            +'<li><a href="#" onclick = "return modificar_datos_agenda_item(\''+val.id_agenda+'\')" data-toggle="modal">Modificar Datos</a></li>'
                                            +'<li><a href="#" onclick = "return eliminar_agenda_item(\''+val.id_agenda+'\')" data-toggle="modal">Eliminar Agenda</a></li>'
                                        +'</ul>'
                                    +'</div>'
                                +'</div>'
                            +'</div>'

                });

                $(".content_agenda").html(html);
            }
        }
    });
}

function get_datos_usuarios() {

    $.ajax({
        url: base_url+"/main/get_usuarios_json/todos",
        dataType: 'json',
        success:function(response)
        {
            if (response != null) {
                html = "";
                $.each(response, function(key,val) {

                    html += '<div class="row" style = "height:50px;padding-top:10px;border-bottom: 1px solid #ddd;">'
                                +'<div class="col-md-4">'
                                    +val.apellido+", "+val.nombre
                                +'</div>'
                                +'<div class="col-md-2">'
                                    +val.usuario
                                +'</div>'
                                +'<div class="col-md-5">'
                                    +JSON.parse(val.funciones).toString().split(",").join(", ")
                                +'</div>'
                                +'<div class="col-md-1" style = "padding-top:0px">'
                                    +'<div class = "dropdown">'
                                        +'<button class="btn btn-default dropdown-toggle" data-toggle="dropdown" role="button"><span class = "glyphicon glyphicon-menu-hamburger"></span></button>'
                                        +'<ul class="dropdown-menu pull-right">'
                                            +'<li><a href="#" onclick = "return modificar_datos_usuario_item(\''+val.usuario+'\')" data-toggle="modal">Modificar Datos</a></li>'
                                            +'<li><a href="#" onclick = "return eliminar_usuario_item(\''+val.usuario+'\')" data-toggle="modal">Eliminar Usuario</a></li>'
                                            +'<li><a href="#" onclick = "return reset_pass_item(\''+val.usuario+'\')" data-toggle="modal">Resetear Password</a></li>'
                                        +'</ul>'
                                    +'</div>'
                                +'</div>'
                            +'</div>';

                });

                $(".content_usuario").html(html);
            }
        }
    });
}

function clear_agd() {
    $('#especialidades').val("");
    $('.tag-editor').empty();

    $("#agenda_nombre").val("");
    $("#agenda_usuario").empty();

    $("#agenda_duracion").val("30");
    $("#agenda_id").val("");

    $("#lu").prop('checked', false);
    $("#lu_desde_man").val("");
    $("#lu_hasta_man").val("");
    $("#lu_desde_tar").val("");
    $("#lu_hasta_tar").val("");

    $("#ma").prop('checked', false);
    $("#ma_desde_man").val("");
    $("#ma_hasta_man").val("");
    $("#ma_desde_tar").val("");
    $("#ma_hasta_tar").val("");

    $("#mi").prop('checked', false);
    $("#mi_desde_man").val("");
    $("#mi_hasta_man").val("");
    $("#mi_desde_tar").val("");
    $("#mi_hasta_tar").val("");

    $("#ju").prop('checked', false);
    $("#ju_desde_man").val("");
    $("#ju_hasta_man").val("");
    $("#ju_desde_tar").val("");
    $("#ju_hasta_tar").val("");

    $("#vi").prop('checked', false);
    $("#vi_desde_man").val("");
    $("#vi_hasta_man").val("");
    $("#vi_desde_tar").val("");
    $("#vi_hasta_tar").val("");

    $("#sa").prop('checked', false);
    $("#sa_desde_man").val("");
    $("#sa_hasta_man").val("");
    $("#sa_desde_tar").val("");
    $("#sa_hasta_tar").val("");
}

function clear_usr() {
    $("#usr_nombre").val("");
    $("#usr_apellido").val("");
    $("#usr_usuario").val("");
    $("#usr_usuario").attr('readonly',false);

    $("#chk_tur").prop('checked', false);
    $("#chk_pac").prop('checked', false);
    $("#chk_fac").prop('checked', false);
    $("#chk_esp").prop('checked', false);
    $("#chk_adm").prop('checked', false);
}

function nuevo_usuario() {

    clear_usr();

    $("#modal_usuario").modal({
        show: true
    });
}

function eliminar_usuario_item(id) {

    $("#modal_confirmacion").find("#form_content").html(
      '<form id = "eliminar_usuario_'+id+'">'
        +'<input type="text" name = "id_usuario" value="'+id+'">'
      +'</form>');

    confirmar_admin("¿Eliminar Usuario?", "usuario", "del_usuario", "eliminar_usuario_"+id);
}

function reset_pass_item(id) {
    $("#modal_confirmacion").find("#form_content").html(
      '<form id = "reset_usuario_'+id+'">'
        +'<input type="text" name = "usr_usuario" value="'+id+'">'
      +'</form>');

    confirmar_admin("¿Resetear Password Usuario?", "usuario", "reset_usuario", "reset_usuario_"+id);
}

function modificar_datos_usuario_item(id) {

    clear_usr();
    $("#usr_usuario").attr('readonly',true);
    var usr_logged = "<?php echo $usuario?>";

    $.ajax({
        url: base_url+"/main/get_usuarios_json/"+id,
        dataType: 'json',
            success: function(response) {

            $("#usr_nombre").val(response.nombre);
            $("#usr_apellido").val(response.apellido);
            $("#usr_usuario").val(response.usuario);

            if (response.usuario == usr_logged)
                $(".admin").css('visibility','hidden');
            else
                $(".admin").css('visibility','visible');

            $.each(JSON.parse(response.funciones), function(key, value)
            {
                switch(value) {
                    case 'turnos':
                        $("#chk_tur").prop('checked', true);
                        break;
                    case 'pacientes':
                        $("#chk_pac").prop('checked', true);
                        break;
                    case 'facturacion':
                        $("#chk_fac").prop('checked', true);
                        break;
                    case 'especialista':
                        $("#chk_esp").prop('checked', true);
                        break;
                    default:
                        $("#chk_adm").prop('checked', true);

                }

            });

            $("#modal_usuario").modal({
                show: true
            });

        }
    });
}

function guardar_datos_usuario(){

    var form = $("#modal_usuario").find('form');

    $.ajax({
        type: "POST",
        url: base_url+"/main/am_usuario",
        data: form.serialize(),
        // dataType: "json",
        success:function(response)
        {
            get_datos_usuarios();
            $("#modal_usuario").modal('hide');
        }
    });

}

function nueva_agenda() {

    clear_agd();
    get_especialistas();

    $("#modal_agenda").modal({
        show: true
    });
}

function eliminar_agenda_item(id) {

    $("#modal_confirmacion").find("#form_content").html(
      '<form id = "eliminar_agenda_'+id+'">'
        +'<input type="text" name = "id_agenda" value="'+id+'">'
      +'</form>');

    confirmar_admin("¿Eliminar Agenda?", "agenda", "del_agenda", "eliminar_agenda_"+id);

}

function get_especialistas() {
    $.ajax({
        url: base_url+"/main/get_especialistas_json/",
        dataType: 'json',
        success:function(response)
        {
            if (response != null) {

                $.each(response, function(key,val) {

                    $("#agenda_usuario").append($('<option>', {
                            value: val.usuario,
                            text : val.nombre
                    }));
                });
            }
        }
    });
}

function modificar_datos_agenda_item(id){

    clear_agd();
    get_especialistas();

    $.ajax({
        url: base_url+"/main/get_agenda_json/"+id,
        dataType: 'json',
        success: function(response) {

            $("#agenda_id").val(id);
            $("#agenda_nombre").val(response.nombre_agenda);
            $("#agenda_usuario").val(response.usuario);
            if (response.dias_horarios != "") {
                $("#agenda_duracion").val(response.duracion);
                $.each(JSON.parse(response.dias_horarios), function(key, value)
                {

                    switch(key) {
                        case "lu":
                            $("#lu").prop('checked', true);
                            $("#lu_desde_man").val(value['1'].desde);
                            $("#lu_hasta_man").val(value['1'].hasta);
                            $("#lu_desde_tar").val(value['2'].desde);
                            $("#lu_hasta_tar").val(value['2'].hasta);
                            break;
                        case "ma":
                            $("#ma").prop('checked', true);
                            $("#ma_desde_man").val(value['1'].desde);
                            $("#ma_hasta_man").val(value['1'].hasta);
                            $("#ma_desde_tar").val(value['2'].desde);
                            $("#ma_hasta_tar").val(value['2'].hasta);
                            break;
                        case "mi":
                            $("#mi").prop('checked', true);
                            $("#mi_desde_man").val(value['1'].desde);
                            $("#mi_hasta_man").val(value['1'].hasta);
                            $("#mi_desde_tar").val(value['2'].desde);
                            $("#mi_hasta_tar").val(value['2'].hasta);
                            break;
                        case "ju":
                            $("#ju").prop('checked', true);
                            $("#ju_desde_man").val(value['1'].desde);
                            $("#ju_hasta_man").val(value['1'].hasta);
                            $("#ju_desde_tar").val(value['2'].desde);
                            $("#ju_hasta_tar").val(value['2'].hasta);
                            break;
                        case "vi":
                            $("#vi").prop('checked', true);
                            $("#vi_desde_man").val(value['1'].desde);
                            $("#vi_hasta_man").val(value['1'].hasta);
                            $("#vi_desde_tar").val(value['2'].desde);
                            $("#vi_hasta_tar").val(value['2'].hasta);
                            break;
                        default:
                            $("#sa").prop('checked', true);
                            $("#sa_desde_man").val(value['1'].desde);
                            $("#sa_hasta_man").val(value['1'].hasta);
                            $("#sa_desde_tar").val(value['2'].desde);
                            $("#sa_hasta_tar").val(value['2'].hasta);
                    }

                });
            }

            $("#modal_agenda").modal({
                show: true
            });

            if (response.especialidad != "") {
                $.each(JSON.parse(response.especialidad), function(key, value)
                {
                    $('#agenda_especialidades').tagEditor('addTag', value);
                });
            }
        }
    });
}

function guardar_datos_agenda(){
    tags = $('#agenda_especialidades').tagEditor('getTags')[0].tags;

    $('#agenda_especialidades').val(JSON.stringify(tags));

    var form = $("#modal_agenda").find('form');

    $.ajax({
        type: "POST",
        url: base_url+"/main/am_agenda",
        data: form.serialize(),
        success:function(response)
        {
            get_datos_agendas();
            $("#modal_agenda").modal('hide');
        }
    });

}
