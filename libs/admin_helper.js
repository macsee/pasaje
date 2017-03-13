function confirmar_admin(titulo, tipo, callback, id)
{
    $("#modal_confirmacion").find("#titulo").html(titulo);
    $("#modal_confirmacion").find("#aceptar").attr('onclick','ok_confirmar("'+callback+'","'+id+'","'+tipo+'")');
    // ok_confirmar(callback, id, tipo);

    $("#modal_confirmacion").modal({
      show: true
    });
}

function check_validity(object) {
    if (object.val() == "") {
        object.parent().addClass('has-error');
        object.parent().find(".error").css('visibility','visible');
    }
    else {
        object.parent().removeClass('has-error');
        object.parent().find(".error").css('visibility','hidden');
    }
}

function ok_confirmar(get, id, tipo)
{
  // var form = $("#"+form_id);

  $.ajax({
      url: base_url+"/main/"+get+"/"+id,
      success:function(response)
      {
          if (tipo == "usuario")
              get_datos_usuarios();
          else if (tipo == "turnos")
              get_datos_agendas();
          else
              get_datos_grupos();

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

function get_datos_grupos() {

    $.ajax({
        url: base_url+"/main/get_grupos_json/todos/todos",
        dataType: 'json',
        success:function(response)
        {
            if (response != null) {
                html = "";

                $.each(response, function(key,value) {
                    $.each(value, function(k,val) {

                        html +=  '<div class="row" style = "height:50px;padding-top:10px;border-bottom: 1px solid #ddd;">'
                                    +'<div class="col-md-1">'
                                        +val.id_grupo
                                    +'</div>'
                                    +'<div class="col-md-3">'
                                        +val.id_usuario
                                    +'</div>'
                                    +'<div class="col-md-3">'
                                        // +val.especialidad.split(",").join(", ")
                                        +val.dia+" - "+val.horario_desde.substring(0,5)+" a "+val.horario_hasta.substring(0,5)// +str_replace($replace," ",$value->especialidad);
                                    +'</div>'
                                    +'<div class="col-md-3" style ="text-transform:capitalize">'
                                        // +val.especialidad.split(",").join(", ")
                                        +val.tipo_nombre
                                    +'</div>'
                                    +'<div class="col-md-1">'
                                        // +val.especialidad.split(",").join(", ")
                                        +val.cant_integrantes
                                    +'</div>'
                                    +'<div class="col-md-1" style = "padding-top:0px">'
                                        +'<div class = "dropdown">'
                                            +'<button class="btn btn-default dropdown-toggle" data-toggle="dropdown" role="button"><span class = "glyphicon glyphicon-menu-hamburger"></span></button>'
                                            +'<ul class="dropdown-menu pull-right">'
                                                +'<li><a href="#" onclick = "return modificar_datos_grupo_item(\''+val.id_grupo+'\')" data-toggle="modal">Modificar Datos</a></li>'
                                                +'<li><a href="#" onclick = "return eliminar_grupo_item(\''+val.id_grupo+'\')" data-toggle="modal">Eliminar Grupo</a></li>'
                                            +'</ul>'
                                        +'</div>'
                                    +'</div>'
                                +'</div>'
                    });
                });

                $(".content_grupos").html(html);
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
    $("#chk_gru").prop('checked', false);
}

function clear_grupo() {

    $("#modal_grupos").find("#agenda_horario_desde").parent().removeClass('has-error');
    $("#modal_grupos").find("#agenda_horario_desde").parent().find(".error").css('visibility','hidden');

    $("#modal_grupos").find("#agenda_horario_hasta").parent().removeClass('has-error');
    $("#modal_grupos").find("#agenda_horario_hasta").parent().find(".error").css('visibility','hidden');

    $("#modal_grupos").find("#agenda_id_grupo").val();
    $("#modal_grupos").find("#agenda_usuario").empty();
    $("#modal_grupos").find("#agenda_tipo").val("");
    $("#modal_grupos").find("#agenda_dia").val("Lu");
    $("#modal_grupos").find("#agenda_horario_desde").val("");
    $("#modal_grupos").find("#agenda_horario_hasta").val("");
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

    confirmar_admin("¿Eliminar Usuario?", "usuario", "del_usuario", id);
}

function reset_pass_item(id) {
    $("#modal_confirmacion").find("#form_content").html(
      '<form id = "reset_usuario_'+id+'">'
        +'<input type="text" name = "usr_usuario" value="'+id+'">'
      +'</form>');

    confirmar_admin("¿Resetear Password Usuario?", "usuario", "reset_usuario", id);
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
                    case 'grupos':
                        $("#chk_gru").prop('checked', true);
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
    get_especialistas($("#modal_agenda"),"turnos");

    $("#modal_agenda").modal({
        show: true
    });
}

function nuevo_grupo() {

    clear_grupo();
    get_especialistas($("#modal_grupos"),"grupos");

    $("#modal_grupos").modal({
        show: true
    });
}

function eliminar_agenda_item(id) {
    confirmar_admin("¿Eliminar Agenda?", "turnos", "del_agenda", id);
}

function get_especialistas(modal, tipo) {

    $.ajax({
        url: base_url+"/main/get_especialistas_json/"+tipo,
        dataType: 'json',
        success:function(response)
        {
            if (response != null) {
                $.each(response, function(key,val) {

                    modal.find("#agenda_usuario").append($('<option>', {
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
    get_especialistas($("#modal_agenda"),"turnos");

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

function modificar_datos_grupo_item(id){

    clear_grupo();
    get_especialistas($("#modal_grupos"),"grupos");

    $.ajax({
        url: base_url+"/main/get_grupo_by_id/"+id,
        dataType: 'json',
        success: function(response) {
            $("#modal_grupos").find("#agenda_id_grupo").val(response.id_grupo);
            $("#modal_grupos").find("#agenda_usuario").val(response.id_usuario);
            $("#modal_grupos").find("#agenda_tipo").val(response.tipo);
            $("#modal_grupos").find("#agenda_dia").val(response.dia);
            $("#modal_grupos").find("#agenda_horario_desde").val(response.horario_desde);
            $("#modal_grupos").find("#agenda_horario_hasta").val(response.horario_hasta);
            $("#modal_grupos").find("#agenda_personas").val(response.cant_integrantes);

            $("#modal_grupos").modal({
                show: true
            });
        }
    });
}

function guardar_datos_grupo() {
    var form = $("#modal_grupos").find('form');

    check_validity($("#modal_grupos").find("#agenda_horario_desde"));
    check_validity($("#modal_grupos").find("#agenda_horario_hasta"));

    if ($("#modal_grupos").find("#agenda_horario_desde").val() != "" && $("#modal_grupos").find("#agenda_horario_hasta").val() != "") {
        $.ajax({
            type: "POST",
            url: base_url+"/main/am_grupo",
            data: form.serialize(),
            success:function(response)
            {
                get_datos_grupos();
                $("#modal_grupos").modal('hide');
            }
        });
    }

}

function eliminar_grupo_item(id) {

    confirmar_admin("¿Eliminar Grupo?", "grupos", "del_grupo", id);

}
