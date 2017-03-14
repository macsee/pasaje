var FECHA_ACTUAL = new Date();
var CAMBIO_TURNO = "";
var PROX_TURNO = "";
var TURNOS_MES = [];
var HORARIOS_MES = [];
var HORARIOS_EXTRA = [];
var ESTADO_OK = "OK";
var SALDO = 0;
var dias = ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'];
var meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Setiembre', 'Octubre', 'Noviembre', 'Diciembre'];

function set_fecha(fecha)
{
    fecha = format_date(fecha);
    fecha = fecha.replace(/-/g, '/'); // Para que funcione en celulares
    FECHA_ACTUAL = new Date(fecha);
    // FECHA_ACTUAL = new Date(fecha+" 00:00:00");
    get_notas();
    // notify_prox_vencimientos(fecha);
    // actualizar_datos();
    // show_turnos(FECHA_ACTUAL);
}


function crear_calendario(fecha, h_mes, t_mes, h_extra, bloqueados) {
  // console.log(h_extra);
  $('#datepicker').datepicker('remove');

  $('#datepicker').datepicker({
    language: "es",
    format: 'yyyy-mm-dd',
    todayHighlight: true,
    toggleActive: true,
    defaultViewDate: { year: fecha.getFullYear(), month: fecha.getMonth(), day: 01 },
    // daysOfWeekHighlighted: dias_turnos_array(h_mes),
    // datesDisabled: bloqueados,
    // beforeShowDay: function(date){}
  });
}

$('#datepicker').on("changeDate", function(e) {

    var fecha = new Date(e.date);

    if (e.dates.length > 0)
        set_fecha(fecha);
    else
        set_fecha(FECHA_ACTUAL);
});

$('#datepicker').on("changeMonth", function(e) {
    FECHA_ACTUAL = new Date(e.date);
 });

function split_telefono(t1)
{
    if (t1 != "") {
        if (t1.indexOf("-") >= 0) {
            t11 = t1.split("-")[0];
            t12 = t1.split("-")[1];
        }
        else {
            t11 = "";
            t12 = t1;
        }
    }
    else {
        t11 = "";
        t12 = "";
    }

    return {
        'prefijo': t11,
        'telefono': t12
    }
}

function format_date(fecha)
{

    var day = fecha.getDay();
    var mm = (fecha.getMonth() + 1).toString();
    var dd = fecha.getDate().toString();
    var yy = fecha.getFullYear().toString();
    var date = yy + "-" + (mm.length == 2?mm:"0"+mm) + "-" + (dd.length == 2?dd:"0"+dd);

    return date;
}

function display_date(fecha) {
    var mm = (fecha.getMonth() + 1);
    var dd = fecha.getDate();
    var yy = fecha.getFullYear();

    var date = (dd < 10 ? 0+dd.toString() : dd.toString())+"-"+(mm < 10 ? 0+mm.toString() : mm.toString())+"-"+yy;
    return date;
}

function init()
{
    // FECHA_ACTUAL = new Date();
    var tipo = $("#tipo").val();
    var profesor = $("#profesor").val();
    get_grupos(tipo, profesor, html_get_grupos);

    crear_calendario(FECHA_ACTUAL, "", "", "", "");
    set_fecha(FECHA_ACTUAL);
}

function get_grupos(tipo, profesor, callback)
{
    $.ajax({
        url: base_url+"/main/get_grupos_json/"+tipo+"/"+profesor,
        dataType: 'json',
        success:function(response)
        {
            if (response != null)
                callback(response);
                // html_get_grupos(response);
        }
    });
}

function html_get_grupos(content)
{
    var tabs = html_tab_header(content);
    var tabs_body = html_tab_body(content);
    $(".horarios").html(tabs);
    $(".horarios").append(tabs_body);

    $('.tabs').first().addClass("active");
    $('.tab-pane').first().addClass("active");
}

function html_tab_header(content)
{
    var tabs = '<ul class="nav nav-tabs">';

    if (content.hasOwnProperty('Lu'))
        tabs += '<li role="presentation" class="tabs"><a href="#lunes" data-toggle="tab">Lunes</a></li>';
    if (content.hasOwnProperty('Ma'))
        tabs += '<li role="presentation" class="tabs"><a href="#martes" data-toggle="tab">Martes</a></li>';
    if (content.hasOwnProperty('Mi'))
        tabs += '<li role="presentation" class="tabs"><a href="#miercoles" data-toggle="tab">Miercoles</a></li>';
    if (content.hasOwnProperty('Ju'))
        tabs += '<li role="presentation" class="tabs"><a href="#jueves" data-toggle="tab">Jueves</a></li>';
    if (content.hasOwnProperty('Vi'))
        tabs += '<li role="presentation" class="tabs"><a href="#viernes" data-toggle="tab">Viernes</a></li>';
    if (content.hasOwnProperty('Sa'))
        tabs += '<li role="presentation" class="tabs"><a href="#sabado" data-toggle="tab">Sabado</a></li>';

    tabs += '</ul>';

    return tabs;
}

function html_tab_body(content)
{
    var tab_content = '<div class="tab-content">';
    var text = false;

    if (content.hasOwnProperty('Lu')) {
        tab_content += '<div class="tab-pane" style ="padding-top:10px" id="lunes">';
        tab_content += get_data_grupo_dia(content.Lu);
        tab_content += '</div>';
        text = true;
    }

    if (content.hasOwnProperty('Ma')) {
        tab_content += '<div class="tab-pane" style ="padding-top:10px" id="martes">';
        tab_content += get_data_grupo_dia(content.Ma);
        tab_content += '</div>';
        text = true;
    }

    if (content.hasOwnProperty('Mi')) {
        tab_content += '<div class="tab-pane" style ="padding-top:10px" id="miercoles">';
        tab_content += get_data_grupo_dia(content.Mi);
        tab_content += '</div>';
        text = true;
    }

    if (content.hasOwnProperty('Ju')) {
        tab_content += '<div class="tab-pane" style ="padding-top:10px" id="jueves">';
        tab_content += get_data_grupo_dia(content.Ju);
        tab_content += '</div>';
        text = true;
    }

    if (content.hasOwnProperty('Vi')) {
        tab_content += '<div class="tab-pane" style ="padding-top:10px" id="viernes">';
        tab_content += get_data_grupo_dia(content.Vi);
        tab_content += '</div>';
        text = true;
    }

    if (content.hasOwnProperty('Sa')) {
        tab_content += '<div class="tab-pane" style ="padding-top:10px" id="sabado">';
        tab_content += get_data_grupo_dia(content.Sa);
        tab_content += '</div>';
        text = true;
    }

    if (!text) {
        tab_content += '<div class="panel panel-default text-muted" style="padding:50px;overflow:inherit;margin-bottom:30px;height:150px;font-size:30px;text-align:center">'+
            '<i>No existen grupos para la seleccion actual</i>'+
        '</div>';
    }

    tab_content += '</div>'
    return tab_content;
}

function get_data_grupo_dia(data) {
    var content = "";
    var cant_miembros = 0;
    var disponible = "";
    var vencimiento = "";

    $.each( data, function(index, grupos) {
        cant_miembros = grupos.miembros != null ? grupos.miembros.length : 0;

        if (cant_miembros/grupos.cant_integrantes < 0.4)
            disponible = 'disponible_high';
        else if (cant_miembros/grupos.cant_integrantes < 0.7)
            disponible = 'disponible_med';
        else if (cant_miembros/grupos.cant_integrantes < 1)
            disponible = 'disponible_low';
        else if (cant_miembros/grupos.cant_integrantes == 1)
            disponible = 'disponible_no';

        content +=  '<div id ="grupo_id_'+grupos.id_grupo+'" class="col-md-12 grupo">'
                        +'<div class="panel panel-default">'
                            +'<div class="titulo-grupo">'
                                +'<div class="col-md-11 col-xs-10" style ="padding-left:0px">'
                                    +'<a data-toggle="collapse" href="#collapse_'+grupos.id_grupo+'">'
                                        +'<div class="horario_grupo col-md-1 col-xs-3">'+grupos.horario_desde.substring(0,5)+'<br>'+grupos.horario_hasta.substring(0,5)+'</div>'
                                        +'<div class="profesor_grupo col-md-6 col-xs-9" style ="text-transform:capitalize">'+grupos.tipo_nombre+' &#8212; '+grupos.apellido+', '+grupos.nombre+'</div>'
                                        // +'<div class="profesor_grupo col-md-1 col-xs-9">-</div>'
                                        // +'<div class="profesor_grupo col-md-4 col-xs-9">'+grupos.apellido+', '+grupos.nombre+'</div>'
                                    +'</a>'
                                +'</div>'
                                +'<div class="col-md-1 col-xs-2 titulo-grupo-available '+disponible+'">'
                                    +(grupos.cant_integrantes - cant_miembros)
                                +'</div>'
                            +'</div>'

                            +'<div id="collapse_'+grupos.id_grupo+'" class="panel-body collapse">'
                                +'<div class="panel panel-default">'

                                    +'<div class="panel-heading" style="height:40px;padding-left:0px">'
                                        +'<div class="col-md-5 col-xs-4">Nombre</div>'
                                        +'<div class="col-md-3 col-xs-4">Acciones</div>'
                                        +'<div class="col-md-4 col-xs-4">Vencimiento</div>'
                                    +'</div>'

                                    +'<div class="panel-body">';
                                        $.each( grupos.miembros, function(indx, miembro) {

                                            var diferencia = "";

                                            if (miembro.data_extra != "") {
                                                var date = JSON.parse(miembro.data_extra).prox_vencimiento;
                                                if (date != "") {
                                                    vencimiento = new Date(date+" 00:00");
                                                    diferencia = Math.round((vencimiento - FECHA_ACTUAL)/(1000*60*60*24));

                                                    if (diferencia <= 0)
                                                        aviso = 'txt-danger';
                                                    else if (diferencia <= 5)
                                                        aviso = 'txt-warning';
                                                    else
                                                        aviso = 'txt-success';

                                                    mensaje = '<div class="col-md-4 col-xs-3 text-muted small '+aviso+'">Faltan <span class="cuota-integrante ">'+diferencia+'</span> dias</div>';
                                                }
                                                else {
                                                    mensaje = '<div class="col-md-4 col-xs-3 text-muted small txt-danger">No hay datos</div>';
                                                }
                                            }
                                            else {
                                                mensaje = '<div class="col-md-4 col-xs-3 text-muted small txt-danger">No hay datos</div>';
                                            }

                                    content += '<div class="row row_integrantes">'
                                                +'<div class="col-md-5 col-xs-5 nombre-integrante" style="overflow:hidden">'+miembro.apellido+', '+miembro.nombre+'</div>'
                                                +'<div class="col-md-3 col-xs-4">'
                                                    +'<span class="act-btn accion"><a href="#" onclick = "return eliminar_integrante(\''+miembro.id_gm+'\')"><span class="glyphicon glyphicon-remove"></span></a></span>'
                                                    +'<span class="act-btn accion"><a href="#" onclick = "return form_ingresante(\''+miembro.id_socio+'\',\''+grupos.id_grupo+'\')"><span class="glyphicon glyphicon-pencil"></span></a></span>'
                                                    // +'<span class="glyphicon glyphicon-refresh act-btn"><a href="#" onclick="return cambiar(\''+miembro.id_socio+'\')"></a></span>'
                                                +'</div>'
                                                +mensaje
                                            +'</div>';
                                        });

                                        for (var i = 0; i < grupos.cant_integrantes - cant_miembros; i++) {
                                            content +=  '<div class="row row_integrantes">'
                                                            +'<div class="col-md-5 col-xs-5 nuevo_miembro"><a htref = "#" onclick = "return form_ingresante(\'\',\''+grupos.id_grupo+'\')" data-toggle="modal">Nuevo Integrante</a></div>'
                                                        +'</div>';
                                        }
                        content += '</div>'

                                +'</div>'
                            +'</div>'

                        +'</div>'
                    +'</div>';
    });

    return content;
}

$("#tipo").change(function () {

  var tipo = $(this).val();

  $.ajax({
      url: base_url+"/main/get_profesores_tipo_json/"+tipo,
      dataType: 'json',
      success:function(response)
      {
        $("#profesor").empty();

        // if (response.length > 1)
          $("#profesor").append($("<option />").val("todos").text("Todos"));

        $.each( response, function(key, value) {
            $("#profesor").append($("<option />").val(value.id_usuario).text(value.apellido+", "+value.nombre));
        });

        get_grupos(tipo, $("#profesor").val(), html_get_grupos);
      }
  });
    // get_turnos_mes();
});

$("#profesor").change(function () {
    var profesor = $(this).val();

    // $.ajax({
    //     url: base_url+"/main/get_tipos_profesor_json/"+profesor,
    //     dataType: 'json',
    //     success:function(response)
    //     {
    //       $("#tipo").empty();
    //
    //     //   if (response.length > 1)
    //         $("#tipo").append($("<option />").val("todos").text("Todos"));
    //
    //       $.each( response, function(key, value) {
    //           $("#tipo").append($("<option />").val(value.tipo).text(value.tipo.charAt(0).toUpperCase() + value.tipo.substr(1).toLowerCase()));
    //       });

          get_grupos($("#tipo").val(), profesor, html_get_grupos);

    //     }
    // });

});

function actualizar_grupos(select, val_sel) {

    var id_select = $("#modal_miembro").find("."+select);
    var tipo = $("#tipo").val();
    var profesor = $("#profesor").val();

    id_select.empty();
    id_select.append($('<option>', {
            value: "",
            text : ""
    }));

    get_grupos(tipo,profesor,function(data) {
        console.log(data);
        $.each( data, function(k,values) {
            $.each( values, function(key,val) {
                if ((val.cant_integrantes - val.miembros.length) > 0) {
                    id_select.append($('<option>', {
                            value: val.id_grupo,
                            text : k+" - "+val.horario_desde.substring(0,5)+" - "+val.horario_hasta.substring(0,5)+" - "+val.apellido+", "+val.nombre
                    }));
                }
            });

        });
        id_select.val(val_sel);
    })
}

function actualizar_vencimiento(tipo) {
    var nuevo_vencimiento = new Date(FECHA_ACTUAL);
    nuevo_vencimiento.setMonth(nuevo_vencimiento.getMonth()+1);
    var prox_vencimiento = display_date(nuevo_vencimiento);

    if (tipo == "abono"){
        $("#valor").removeAttr('disabled');
        $("#valor").attr('required', 'true');
        $("#paga").removeAttr('disabled');
        $("#paga").attr('required', 'true');
        $("#prox_vencimiento").html(prox_vencimiento);
        $("#valor").val("0");
        $("#paga").val("0");
    }
    else if (tipo == "deuda"){
        $("#valor").attr('disabled', 'true');
        $("#paga").removeAttr('disabled');
        $("#paga").attr('required', 'true');
        $("#prox_vencimiento").empty();
        $("#valor").val("0");
        $("#paga").val("0");
    }
    else {
        $("#valor").attr('disabled', 'true');
        $("#valor").removeAttr('required');
        $("#paga").attr('disabled', 'true');
        $("#paga").removeAttr('required');
        $("#prox_vencimiento").empty();
        $("#valor").val("0");
        $("#paga").val("0");
    }
}

function clear_fields(modal) {
    modal.find(".grupo_1").empty();
    modal.find(".grupo_2").empty();
    modal.find(".grupo_3").empty();
    modal.find("#id_integrante").val("");
    modal.find("#id_int_grupo").val("");
    modal.find("#apellido").val("");
    modal.find("#apellido").closest(".form-group").removeClass('has-error');
    modal.find("#apellido").closest(".form-group").find(".error").css('visibility','hidden');

    modal.find("#nombre").val("");
    modal.find("#nombre").closest(".form-group").removeClass('has-error');
    modal.find("#nombre").closest(".form-group").find(".error").css('visibility','hidden');

    modal.find("#direccion").val("");
    modal.find("#dni").val("");
    modal.find("#obra_social").val("");
    modal.find("#tel1").val("");
    modal.find("#tel2").val("");
    modal.find("#cel1").val("");
    modal.find("#cel2").val("");
    modal.find("#observaciones").val("");
    modal.find(".concepto").empty();
    modal.find(".concepto").append($('<option>', {
            value: "",
            text : ""
    }));
    modal.find(".concepto").closest(".form-group").removeClass('has-error');
    modal.find(".concepto").closest(".form-group").find(".error").css('visibility','hidden');

    modal.find("#prox_vencimiento").val("");
    modal.find("#act_vencimiento").empty();
    modal.find("#valor").val("0");
    modal.find("#paga").val("0");
    modal.find("#saldo").val("0");
}

function form_ingresante(id_usuario, id_grupo) {

    // fecha = format_date(FECHA_ACTUAL); // FECHA_ACTUAL.getFullYear()+"-"+parseInt(FECHA_ACTUAL.getMonth()+1)+"-"+FECHA_ACTUAL.getDate();
    clear_fields($("#modal_miembro"));

    if (id_usuario != "") {
        cargar_datos(id_usuario);
    }
    else {
        nuevos_datos(id_grupo);
    }

    $("#modal_miembro").modal({
        show: true
    });
}

function nuevos_datos(id_grupo) {

    $("#modal_miembro").find("#titulo").html("Nuevo Integrante");
    $("#modal_miembro").find(".grupo_1").val(id_grupo);

    actualizar_grupos("grupo_1", id_grupo);
    actualizar_grupos("grupo_2", "");
    actualizar_grupos("grupo_3", "");

    $("#modal_miembro").find(".concepto").append($('<option>', {
            value: "abono",
            text : "Abono"
    }));

    $("#modal_miembro").find(".concepto").val("abono");

    actualizar_vencimiento("abono");
}

function cargar_datos(id) {
    $.ajax({
        type: "POST",
        url: base_url+"/main/get_data_integrante_json/"+id,
        dataType: "json",
        success:function(response)
        {
            $("#modal_miembro").find("#titulo").html("Editar Datos");
            if (response != null) {

                $("#modal_miembro").find(".concepto").append($('<option>', {
                        value: "abono",
                        text : "Abono"
                }));

                $("#modal_miembro").find(".concepto").append($('<option>', {
                        value: "deuda",
                        text : "Deuda"
                }));

                $("#modal_miembro").find(".concepto").val("");

                actualizar_vencimiento("");

                // console.log(response);
                actualizar_grupos("grupo_1", response.grupos.hasOwnProperty(0) ? response.grupos[0].id_grupo : "");
                actualizar_grupos("grupo_2", response.grupos.hasOwnProperty(1) ? response.grupos[1].id_grupo : "");
                actualizar_grupos("grupo_3", response.grupos.hasOwnProperty(2) ? response.grupos[2].id_grupo : "");

                $("#modal_miembro").find("#id_integrante").val(id);
                $("#modal_miembro").find("#id_int_grupo_1").val(response.grupos.hasOwnProperty(0) ? response.grupos[0].id_gm : "");
                $("#modal_miembro").find("#id_int_grupo_2").val(response.grupos.hasOwnProperty(1) ? response.grupos[1].id_gm : "");
                $("#modal_miembro").find("#id_int_grupo_3").val(response.grupos.hasOwnProperty(2) ? response.grupos[2].id_gm : "");

                $("#modal_miembro").find("#apellido").val(response.integrante.apellido);
                $("#modal_miembro").find("#nombre").val(response.integrante.nombre);
                $("#modal_miembro").find("#direccion").val(response.integrante.direccion);
                $("#modal_miembro").find("#dni").val(response.integrante.dni);
                $("#modal_miembro").find("#obra_social").val(response.integrante.obra_social);
                $("#modal_miembro").find("#tel1").val(split_telefono(response.integrante.tel1).prefijo);
                $("#modal_miembro").find("#tel2").val(split_telefono(response.integrante.tel1).telefono)
                $("#modal_miembro").find("#cel1").val(split_telefono(response.integrante.tel2).prefijo);
                $("#modal_miembro").find("#cel2").val(split_telefono(response.integrante.tel2).telefono);
                $("#modal_miembro").find("#observaciones").val(response.integrante.observaciones);


                // $("#modal_miembro").find(".concepto").val("");

                if (response.integrante.data_extra != "") {
                    datos_abono = JSON.parse(response.integrante.data_extra);
                    if (datos_abono.prox_vencimiento != "") {
                        fecha = new Date(Date.parse(datos_abono.prox_vencimiento+" 00:00:00"));
                        $("#modal_miembro").find("#act_vencimiento").html(display_date(fecha));
                    }
                    // else {
                    //     fecha = "No hay datos";
                    //     $("#modal_miembro").find("#act_vencimiento").html(fecha);
                    // }

                    SALDO = datos_abono.saldo;
                    $("#modal_miembro").find("#saldo").val(SALDO);
                }
                else {
                    fecha = "No hay datos";
                    $("#modal_miembro").find("#act_vencimiento").html(fecha);
                }

            }
        }
    });
}

function validar_form(callback) {

    if ($("#apellido").val() == "") {
        $("#apellido").closest(".form-group").addClass('has-error');
        $("#apellido").closest(".form-group").find(".error").css('visibility','visible');
        return;
    }
    else {
        $("#apellido").closest(".form-group").removeClass('has-error');
        $("#apellido").closest(".form-group").find(".error").css('visibility','hidden');
    }

    if ($("#nombre").val() == "") {
        $("#nombre").closest(".form-group").addClass('has-error');
        $("#nombre").closest(".form-group").find(".error").css('visibility','visible');
        return;
    }
    else {
        $("#nombre").closest(".form-group").removeClass('has-error');
        $("#nombre").closest(".form-group").find(".error").css('visibility','hidden');
    }

    if ($(".concepto").val() == "abono" && $("#valor").val() == "0") {
        $("#valor").closest(".form-group").addClass('has-error');
        $("#valor").closest(".form-group").find(".error").css('visibility','visible');
        return;
    }
    else {
        $(".concepto").closest(".form-group").removeClass('has-error');
        $(".concepto").closest(".form-group").find(".error").css('visibility','hidden');
    }

    if ($(".concepto").val() == "deuda" && $("#paga").val() == "0") {
        $("#paga").closest(".form-group").addClass('has-error');
        $("#paga").closest(".form-group").find(".error").css('visibility','visible');
        return;
    }
    else {
        $("#paga").closest(".form-group").removeClass('has-error');
        $("#paga").closest(".form-group").find(".error").css('visibility','hidden');
    }



    callback();
}

function ok_datos(event) {
    event.preventDefault();

    var tipo = $("#tipo").val();
    var profesor = $("#profesor").val();

    form = $("#modal_miembro").find('form');

    validar_form(function() {
        $.ajax({
            type: "POST",
            url: base_url+"/main/guardar_datos/",
            data: form.serialize(),
            // dataType: 'json',
            success:function(response)
            {
                get_grupos(tipo, profesor, html_get_grupos);
                $("#modal_miembro").modal('hide');
            }
        });
    });
}

function confirmar(titulo, callback, id)
{
  $("#modal_confirmacion").find("#titulo").html(titulo);
  $("#modal_confirmacion").find("#aceptar").attr('onclick','ok_confirmar("'+callback+'","'+id+'")');
  $("#modal_confirmacion").modal({
      show: true
  });
}

function ok_confirmar(callback, id)
{
  var tipo = $("#tipo").val();
  var profesor = $("#profesor").val();

  $.ajax({
      url: base_url+"/main/"+callback+"/"+id,
      success:function(response)
      {
        get_grupos(tipo, profesor, html_get_grupos);
        $("#modal_confirmacion").modal('hide');
      }
  });

}

function eliminar_integrante(id)
{
  confirmar("¿Eliminar Integrante?", "del_integrante_grupo", id);
}

function add_notas() {

    esp = $("#agendas").val();
    // fecha = FECHA_ACTUAL.getFullYear()+"-"+parseInt(FECHA_ACTUAL.getMonth()+1)+"-"+FECHA_ACTUAL.getDate();
    fecha = format_date(FECHA_ACTUAL);

    $("#modal_notas").find('input[name="fecha"]').val(fecha);
    $("#modal_notas").find('select[name="destinatario_sel"]').val("todos");
    $("#modal_notas").find('input[name="tipo"]').val("grupos");
    $("#modal_notas").find('textarea[name="texto"]').val("");
    $("#modal_notas").find('input[name="id_nota"]').val("");
    $("#modal_notas").find('#eliminar_nota').css('visibility','hidden');

    $("#modal_notas").modal({
        show: true
    });
}

function ok_am_nota() {

    // $("#modal_notas").find('input[name="destinatario"]').val($("#modal_notas").find('select[name="destinatario_sel"]').val());
    form = $("#modal_notas").find('form');

    $.ajax({
        type: "POST",
        url: base_url+"/main/am_nota",
        data: form.serialize(),
        success:function(response)
        {
            get_notas();
            $("#modal_notas").modal('hide');

        }
    });
}

function get_notas() {

    user_logged = $("#usuario").val();
    is_admin = $("#is_admin").val();
    notas = "";

    // esp = $("#agendas").val();
    $('.notas_body').empty();
    $('.notas_body').html(notas);

    // fecha = FECHA_ACTUAL.getFullYear()+"-"+parseInt(FECHA_ACTUAL.getMonth()+1)+"-"+FECHA_ACTUAL.getDate();
    fecha = format_date(FECHA_ACTUAL);

    $.ajax({
        url: base_url+"/main/get_nota_json/todas/grupos/"+fecha,
        dataType: "json",
        success:function(response)
        {

            if (response != null) {

                $.each( response, function(key,val) {
                    if (val.destinatario == user_logged || val.destinatario == "todos" || val.usuario == user_logged || is_admin == 1) {

                        if (user_logged == val.usuario) {
                            var onclick = "return editar_nota('"+val.id_nota+"')";
                        }
                        else {
                            var onclick = "return mensaje_error('No tiene permiso para editar esta nota')";
                        }

                        notas +=
                            '<li style = "min-height:40px;margin-bottom:25px">'+
                                '<a onclick = "'+onclick+'">'+val.texto+'</a>'+
                                '<span class = "pull-right text-muted small" style = "width:100%"><i>'+val.last_update+' - '+val.nombre_usuario+'</i></span>'+
                            '</li>';
                    }

                });

                if (notas != "") {
                    notas = '<ul style = "margin-left:-25px">'+notas+'</ul>';
                }
                else {
                  notas = '<i>No hay notas para la fecha</i>';
                }

            }
            else {
                notas = '<i>No hay notas para la fecha</i>';
            }

            $('.notas_body').html(notas);

            notify_prox_vencimientos(fecha);

        }
    });
}

function editar_nota(id) {
    $.ajax({
        url: base_url+"/main/get_nota_json/"+id+"/grupos/",
        dataType: "json",
        success:function(response)
        {
            if (response != null) {
                $("#modal_notas").find('#eliminar_nota').css('visibility','visible');
                $("#modal_notas").find('select[name="destinatario_sel"]').val(response.destinatario);
                $("#modal_notas").find('input[name="tipo"]').val(response.tipo);
                $("#modal_notas").find('textarea[name="texto"]').val(response.texto);
                $("#modal_notas").find('input[name="id_nota"]').val(response.id_nota);
                $("#modal_notas").modal({
                    show: true
                });
            }
        }
    });

}

function eliminar_nota(event) {
    event.preventDefault();
    form = $("#modal_notas").find('form');

    $.ajax({
        type: "POST",
        url: base_url+"/main/del_nota/",
        data: form.serialize(),
        success:function(response)
        {
            get_notas();
            $("#modal_notas").modal('hide');
        }
    });

}

function mensaje_error(msg) {
    $("#modal_error").find("#mensaje_error").html(msg);
    $("#modal_error").modal({
        show: true
    });
}

function notify_prox_vencimientos(fecha) {
    // var fecha = format_date(new Date(fecha));
    var notas = "";
    $.ajax({
        url: base_url+"/main/get_vencimientos/"+fecha,
        dataType: 'json',
        success:function(response)
        {
            $.each( response, function(key,val) {
                notas +=
                    '<li style = "min-height:40px;margin-bottom:25px;">'+
                        '<a class ="text-muted small" style ="color:red">Vencimiento abono '+val.socio+' en '+val.dias+' dias</a>'+
                        // '<span class = "pull-right text-muted small" style = "width:100%"><i>Vencimiento abono '+val.socio+' en '+val.dias+'dias</i></span>'+
                    '</li>';
            });

            if (notas != "") {
                notas = '<ul style = "margin-left:-25px">'+notas+'</ul>';
            }
            $(".notas_body").append(notas);
        }
    });
}
