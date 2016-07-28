var FECHA_ACTUAL = new Date();
var CAMBIO_TURNO = "";
var PROX_TURNO = "";
var TURNOS_MES = [];
var HORARIOS_MES = [];
var HORARIOS_EXTRA = [];
var ESTADO_OK = "OK";
var dias = ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'];
var meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Setiembre', 'Octubre', 'Noviembre', 'Diciembre'];

function clear_fields(obj)
{
    obj.find("input[name='id_turno']").val("");
    obj.find("input[name='hora']").val("");
    obj.find("input[name='fecha']").val("");
    obj.find("input[name='id_especialista']").val("");
    obj.find("input[name='especialista']").val("");
    obj.find("select[name='especialidad']").empty();
    obj.find("textarea[name='observaciones_turno']").val("");
    obj.find("input[name='estado']").val("");
    obj.find("input[name='primera_vez']").prop('checked',true);

    obj.find("input[name='id_paciente']").val("");
    obj.find("input[name='nombre']").val("");
    obj.find("input[name='apellido']").val("");
    obj.find("input[name='tel1']").val("");
    obj.find("input[name='tel2']").val("");
    obj.find("input[name='cel1']").val("");
    obj.find("input[name='cel2']").val("");
    obj.find("input[name='dni']").val("");
    obj.find("input[name='localidad']").val("");
    obj.find("input[name='direccion']").val("");
    obj.find("textarea[name='observaciones_paciente']").val("");

    obj.find("input[name='id_facturacion']").val("");
    obj.find("input[name='total']").val("");
    obj.find("#total").hide();
}

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

function init()
{
    FECHA_ACTUAL = new Date();
    get_turnos_mes(FECHA_ACTUAL);
    actualizar_datos();
}

function actualizar_datos()
{
    $(".display_date").html(dias[FECHA_ACTUAL.getDay()]+" "+FECHA_ACTUAL.getDate()+", "+meses[FECHA_ACTUAL.getMonth()]+" "+FECHA_ACTUAL.getFullYear());
}

function dia_siguiente()
{
    FECHA_ACTUAL.setDate(FECHA_ACTUAL.getDate()+1);
    actualizar_datos();
    show_turnos(FECHA_ACTUAL);
}

function dia_anterior()
{
    FECHA_ACTUAL.setDate(FECHA_ACTUAL.getDate()-1);
    actualizar_datos();
    show_turnos(FECHA_ACTUAL);
}

function dia_actual()
{
    FECHA_ACTUAL = new Date();
    actualizar_datos();
    show_turnos(FECHA_ACTUAL);
}

function set_fecha(fecha)
{
    fecha = format_date(fecha);
    fecha = fecha.replace(/-/g, '/'); // Para que funcione en celulares
    FECHA_ACTUAL = new Date(fecha);
    // FECHA_ACTUAL = new Date(fecha+" 00:00:00");

    actualizar_datos();
    show_turnos(FECHA_ACTUAL);
}

function format_date(fecha) {

    var day = fecha.getDay();
    var mm = (fecha.getMonth() + 1).toString();
    var dd = fecha.getDate().toString();
    var yy = fecha.getFullYear().toString();
    var date = yy + "-" + (mm.length == 2?mm:"0"+mm) + "-" + (dd.length == 2?dd:"0"+dd);

    return date;
}

function get_turnos_mes(fecha)
{
  var agenda = $("#agendas").val();
  var esp = $("#especialidades").val();

  $.ajax({
      url: base_url+"/main/get_data_turnos_json/"+fecha.getFullYear()+"/"+parseInt(fecha.getMonth()+1)+"/"+agenda+"/"+esp,
      dataType: 'json',
      success:function(response)
      {
        // console.log(response);
        var bloqueados = [];
        var t_mes = [];
        var h_mes = [];

        TURNOS_MES = response.turnos; // Aca seteo las variables locales TURNOS_MES y HORARIOS_MES
        HORARIOS_MES = response.horarios; // que contienen toda la info de los turnos
        HORARIOS_EXTRA = response.horarios_extra;

        crear_calendario(fecha, HORARIOS_MES, TURNOS_MES, HORARIOS_EXTRA, bloqueados);
        show_turnos(fecha);
      }
  });

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
    daysOfWeekHighlighted: dias_turnos_array(h_mes),
    // datesDisabled: bloqueados,
    beforeShowDay: function(date){

      var cant_turnos_ocupados = 0;
      var cant_turnos_disp = 0;

      var fecha = format_date(date);
      var dia_semana = dias_turnos_array_inv(date.getDay());

      if (h_mes.hasOwnProperty(dia_semana)) {
        $.each( h_mes[dia_semana], function(index,turnos) {
            cant_turnos_disp += turnos.length;
        });
      }

      if (h_extra.hasOwnProperty(fecha)) {
        $.each( h_extra[fecha], function(index,turnos) {
            cant_turnos_disp += turnos.length;
        });
      }

      if (t_mes.hasOwnProperty(fecha)) {
        cant_turnos_ocupados += t_mes[fecha].cant;
      }

      // console.log("Fecha: ",date," - ","Cant. Horarios: ",cant_turnos_disp, "Cant. Turnos: ",cant_turnos_ocupados);

      factor = cant_turnos_ocupados / cant_turnos_disp;

      switch (true){
          case (factor > 0 && factor < 0.30) :
              return {
                  classes : "celda_low"
              }
          case (factor >= 0.30 && factor < 0.60) :
              return {
                  classes : "celda_medium"
              }
          case (factor >= 0.60 && factor < 0.90):
              return {
                  classes : "celda_high"
              }
          case (factor >= 0.90):
              return {
                  classes : "celda_full"
              }
          case (factor == 0 && cant_turnos_disp > 0):
              return {
                  classes : "highlighted"
              }
      }

      return;
    }

  });
}

function show_turnos(fecha) {

    get_notas();
    $(".horarios").empty();
    $(".abrir_agenda").css('visibility','hidden');

    var esp = $("#agendas").val();

    var html = "";
    var primera_vez = "";
    var date = format_date(fecha);
    var dia = dias_turnos_array_inv(fecha.getDay());

    if (TURNOS_MES.hasOwnProperty(date)) { // Si en la fecha hay turnos entonces los muestro

      html += make_header_turnos();

      $.each( TURNOS_MES[date].datos, function(key, value) {
        if (value.id_turno == "") {
          html += make_turno_vacio(value.hora);
        }
        else {
          html += make_turno_ocupado(value);
        }
      });
    }
    else { // Si no hay turnos en esta fecha tengo que verificar si se debe a que no estoy en un dia de agenda
        if (esp != "todos") { // Si tengo especialista, tengo que mostrar los horarios disponibles de su agenda si los hay para este dia
          if (HORARIOS_MES.hasOwnProperty(dia)) {
            html += make_header_horarios();
            $.each( HORARIOS_MES[dia][0], function(key, value) {
                html += make_turno_vacio(value.hora);
            });
          }
          else if (HORARIOS_EXTRA.hasOwnProperty(date)) {
            html += make_header_horarios();
            $.each( HORARIOS_EXTRA[date][0], function(key, value) {
                html += make_turno_vacio(value.hora);
            });
          }
          else { // Si no los hay entonces ofrezco abrir agenda
            abrir_agenda();
          }
        }
        else { // Si no hay turnos y estoy en la vista de todos, entonces muestro un mensaje de que no hay turnos
          if (HORARIOS_MES.hasOwnProperty(dia) || HORARIOS_EXTRA.hasOwnProperty(date))
            html += '<div class = "panel panel-default text-muted" style = "padding:50px;overflow:inherit;margin-bottom:30px;height:150px;font-size:30px;text-align:center"><i>No hay turnos</i></div>';
          else
            html += '<div class = "panel panel-default text-muted" style = "padding:50px;overflow:inherit;margin-bottom:30px;height:150px;font-size:30px;text-align:center"><i>Disponible para abrir agenda <br> Seleccionar especialista</i></div>';
        }
    }

    $(".horarios").html(html);

}

function make_header_horarios() {
  html = '<div class="row cabecera hidden-xs hidden-sm">'
      +'<div class="col-md-11 cell_header">Hora</div>'
      +'<div class="col-md-1 cell_header" style = "overflow:visible;text-align:right">'
        +'<a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" style = "color:white"><span class="glyphicon glyphicon-chevron-down"></span></a>'
        +'<ul class="dropdown-menu pull-right" style = "margin-top:10px">'
            +'<li><a href="#" onclick = "return nuevo_turno(\'\')" data-toggle="modal">Agregar Sobreturno</a></li>'
            +'<li><a href="#" onclick = "return editar_agenda_extra_item(\'\')" data-toggle="modal">Editar Agenda Extra</a></li>'
            +'<li><a href="#" onclick = "return eliminar_agenda_extra_item(\'\')" data-toggle="modal">Eliminar Agenda Extra</a></li>'
        +'</ul>'
      +'</div>'
  +'</div>';

  return html;
}

function make_header_turnos() {
  var lala = "hola";

  html =  '<div class="row cabecera hidden-xs hidden-sm">'
            +'<div class="col-md-2 cell_header">Hora</div>'
            +'<div class="col-md-4 cell_header">Paciente</div>'
            +'<div class="col-md-3 cell_header">Especialidad</div>'
            +'<div class="col-md-2 cell_header">Especialista</div>'
            +'<div class="col-md-1 cell_header" style = "overflow:visible;text-align:right">'
              +'<a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" style = "color:white"><span class="glyphicon glyphicon-chevron-down"></span></a>'
              +'<ul class="dropdown-menu pull-right" style = "margin-top:10px">'
                  +'<li><a href="#" onclick = "return nuevo_turno(\'\')" data-toggle="modal">Agregar Sobreturno</a></li>'
                  +'<li><a href="#" onclick = "return editar_agenda_extra_item(\'\')" data-toggle="modal">Editar Agenda Extra</a></li>'
                  +'<li><a href="#" onclick = "return eliminar_agenda_extra_item(\'\')" data-toggle="modal">Eliminar Agenda Extra</a></li>'
              +'</ul>'
            +'</div>'
          +'</div>';

  return html;
}

function make_turno_vacio(hora) {

    if (hora == "")
      separador = "separador";
    else
      separador = "";

    if (CAMBIO_TURNO != "")
        funcion = "return cambiar_turno(\'"+hora+"\')";
    else if (PROX_TURNO != "")
        funcion = "return proximo_turno(\'"+hora+"\')";
    else
        funcion = "return nuevo_turno(\'"+hora+"\')";

    html = '<div class="row fila-turno '+separador+'">'
              +'<div class="col-md-12 cell_vacia turno_vacio" onclick = "'+funcion+'">'
                +hora
              +'</div>'
            +'</div>';

    return html;
}

function make_turno_ocupado(data) {
  var data_extra = $.parseJSON(data.data_extra);

  if (data.estado != ESTADO_OK)
      estado = 'glyphicon glyphicon-unchecked';
  else
      estado = 'glyphicon glyphicon-check';

  if (data_extra.indexOf("primera_vez") >= 0)
      primera_vez = 'style = "color:rgba(245, 33, 8, 0.86)"';
  else
      primera_vez = '';

  html = '<div class="row fila-turno" '+primera_vez+'">'
      +'<div class="col-md-4 col-md-push-2 cell_turno fix_on_xs">'
          +data.paciente
      +'</div>'
      +'<div class="col-md-2 col-md-pull-4 col-xs-3 cell_turno" style = "font-size:18px">'
          +data.hora
      +'</div>'
      +'<div class="col-md-3 col-xs-4 cell_turno">'
          +data.especialidad
      +'</div>'
      +'<div class="col-md-2 col-xs-3 cell_turno">'
          +data.especialista
      +'</div>'
      +'<div class="col-md-1 col-xs-2 cell_turno" style = "border-right: none; text-align:center">'
        +'<div class = "dropdown">'
          +'<button class="btn btn-default dropdown-toggle" data-toggle="dropdown" role="button"><span class = "'+estado+'"></span></button>'
          +'<ul class="dropdown-menu pull-right">'
              +'<li><a href="#" onclick = "return modificar_datos_item(\''+data.id_turno+'\')" data-toggle="modal">Modificar Datos</a></li>'
              // +'<li><a href="#" onclick = "return editar_turno(\''+data.id_turno+'\')" data-toggle="modal">Editar Turno</a></li>'
              +'<li><a href="#" onclick = "return eliminar_turno_item(\''+data.id_turno+'\')" data-toggle="modal">Eliminar Turno</a></li>'
              +'<li><a href="#" onclick = "return cambiar_turno_item(\''+data.id_turno+'\')" data-toggle="modal">Cambiar Fecha/Hora</a></li>'
              +'<li><a href="#" onclick = "return proximo_turno_item(\''+data.id_turno+'\')" data-toggle="modal">Nuevo Turno</a></li>'
          +'</ul>'
        +'</div>'
      +'</div>'
  +'</div>';

  return html;
}

$('#datepicker').on("changeDate", function(e) {

    var fecha = new Date(e.date);

    if (e.dates.length > 0)
        set_fecha(fecha);
    else
        set_fecha(FECHA_ACTUAL);
});

$('#datepicker').on("changeMonth", function(e) {

    var fecha = new Date(e.date);
    get_turnos_mes(fecha);

 });

function dias_turnos_array(array)
{
  var dias = {'do' : 0, 'lu' : 1, 'ma' : 2, 'mi' : 3, 'ju' : 4, 'vi' : 5, 'sa' : 6};
  var agenda = [];

  agenda = $.map(array, function(value, index) {
    return dias[index];
  });

  return agenda;
}

function dias_turnos_array_inv(num)
{
  var dias = ['do', 'lu', 'ma', 'mi', 'ju', 'vi', 'sa'];
  return dias[num];
}

function fill_especialidades(esp, callback) {

    $.ajax({
        url: base_url+"/main/get_data_agenda_json/"+esp,
        dataType: 'json',
        success:function(response)
        {
            data = [];
            if (response != null && response.especialidad != null) {
                data = JSON.parse(response.especialidad);
            }

            callback(data);
        }
    });

}

$("#agendas").change(function () {
    get_turnos_mes(FECHA_ACTUAL);
});

$("#especialidades").change(function () {

  var esp = $(this).val();

  $.ajax({
      url: base_url+"/main/get_agendas_by_esp_json/"+esp,
      dataType: 'json',
      success:function(response)
      {
        $("#agendas").empty();

        if (response.length > 1)
          $("#agendas").append($("<option />").val("todos").text("Todos"));
        $.each( response, function(key, value) {
            $("#agendas").append($("<option />").val(value.id_agenda).text(value.nombre_agenda));
        });

        get_turnos_mes(FECHA_ACTUAL);
        //$("#agendas").val($("#agendas option:first").val());
      }
  });
    // get_turnos_mes(FECHA_ACTUAL);
});

function nuevo_turno(hora) {

    esp = $("#agendas").val();

    if (esp == "todos") {
      return mensaje_error('Se debe seleccionar una agenda primero');
    }

    fecha = format_date(FECHA_ACTUAL); // FECHA_ACTUAL.getFullYear()+"-"+parseInt(FECHA_ACTUAL.getMonth()+1)+"-"+FECHA_ACTUAL.getDate();

    clear_fields($("#modal_turno"));

    $("#modal_turno").find("#titulo").html("Nuevo Turno");

    if (hora == "") {
      $("#modal_turno").find("select[name='hora_turno']").attr('disabled', false);
      $("#modal_turno").find("select[name='min_turno']").attr('disabled', false);
    }
    else {
      $("#modal_turno").find("select[name='hora_turno']").attr('disabled', true);
      $("#modal_turno").find("select[name='min_turno']").attr('disabled', true);
    }

    var hora_split = hora.split(":");
    $("#modal_turno").find("input[name='hora']").val(hora);
    $("#modal_turno").find("select[name='hora_turno']").val(hora_split[0]);
    $("#modal_turno").find("select[name='min_turno']").val(hora_split[1]);
    $("#modal_turno").find("input[name='fecha']").val(fecha);
    $("#modal_turno").find("input[name='id_agenda']").val(esp);
    $("#modal_turno").find("input[name='especialista']").val($("#agendas option:selected").text());

    fill_especialidades(esp, function(data){

        $.each( data, function(key,val) {
            $("#modal_turno").find("select[name='especialidad']").append($('<option>', {
                    value: val,
                    text : val
            }));
        });

        $("#modal_turno").modal({
            show: true
        });
    });
}

function ok_nuevo_turno(event) {
    event.preventDefault();

    var hora = $("#modal_turno").find("select[name='hora_turno']").val()+":"+$("#modal_turno").find("select[name='min_turno']").val();

    $("#modal_turno").find("input[name='hora']").val(hora);

    form = $("#modal_turno").find('form');

    $.ajax({
        type: "POST",
        url: base_url+"/main/nuevo_turno/",
        data: form.serialize(),
        // dataType: 'json',
        success:function(response)
        {
          // console.log(response);
          PROX_TURNO = "";
          get_turnos_mes(FECHA_ACTUAL);
          $("#modal_turno").modal('hide');
        }
    });
}

function confirmar(titulo, callback, form)
{
  $("#modal_confirmacion").find("#titulo").html(titulo);
  $("#modal_confirmacion").find("#aceptar").attr('onclick','ok_confirmar("'+callback+'","'+form+'")');
  $("#modal_confirmacion").modal({
      show: true
  });
}

function ok_confirmar(callback, form_id)
{
  var form = $("#"+form_id);

  $.ajax({
      type: "POST",
      url: base_url+"/main/"+callback,
      data: form.serialize(),
      success:function(response)
      {
        get_turnos_mes(FECHA_ACTUAL);
        $("#modal_confirmacion").modal('hide');
      }
  });

}

function eliminar_turno_item(id)
{
  $("#modal_confirmacion").find("#form_content").html(
    '<form id = "eliminar_turno_'+id+'">'
      +'<input type="text" name = "id_turno" value="'+id+'">'
    +'</form>');

  confirmar("¿Eliminar Turno?", "del_turno", "eliminar_turno_"+id);
}

function modificar_datos_item(id)
{
    $.ajax({
        type: "POST",
        url: base_url+"/main/get_turno_json/"+id,
        dataType: "json",
        success:function(response)
        {
            clear_fields($("#modal_datos"));

            // total = "";
            datos_paciente = response.datos_paciente;
            datos_turno = response.datos_turno;
            datos_facturacion = response.datos_facturacion;

            $("#modal_datos").find("#titulo").html("Confirmar Datos");

            $("#modal_datos").find("input[name='id_turno']").val(id);
            $("#modal_datos").find("input[name='hora']").val(datos_turno.hora.substring(0,datos_turno.hora.length - 3)); //.substring(0,datos_turno.hora.length - 3)
            $("#modal_datos").find("input[name='fecha']").val(datos_turno.fecha);
            $("#modal_datos").find("input[name='especialista']").val(datos_turno.name_especialista);
            $("#modal_datos").find("input[name='id_agenda']").val(datos_turno.agenda);
            $("#modal_datos").find("input[name='especialidad']").val(datos_turno.especialidad);
            $("#modal_datos").find("textarea[name='observaciones_turno']").val(datos_turno.observaciones);
            $("#modal_datos").find("select[name='estado']").val(datos_turno.estado);

            $("#modal_datos").find("input[name='id_paciente']").val(datos_paciente.id_paciente);
            $("#modal_datos").find("input[name='nombre']").val(datos_paciente.nombre);
            $("#modal_datos").find("input[name='apellido']").val(datos_paciente.apellido);
            $("#modal_datos").find("input[name='tel1']").val(split_telefono(datos_paciente.tel1).prefijo);
            $("#modal_datos").find("input[name='tel2']").val(split_telefono(datos_paciente.tel1).telefono);
            $("#modal_datos").find("input[name='cel1']").val(split_telefono(datos_paciente.tel2).prefijo);
            $("#modal_datos").find("input[name='cel2']").val(split_telefono(datos_paciente.tel2).telefono);
            $("#modal_datos").find("input[name='dni']").val(datos_paciente.dni);
            $("#modal_datos").find("input[name='localidad']").val(datos_paciente.localidad);
            $("#modal_datos").find("input[name='direccion']").val(datos_paciente.direccion);
            $("#modal_datos").find("textarea[name='observaciones_paciente']").val(datos_paciente.observaciones);

            if (datos_turno.estado == ESTADO_OK) {
              $("#modal_datos").find("#total").show();
            }

            if (datos_facturacion != null) {
                total = JSON.parse(datos_facturacion.datos).total;
                $("#modal_datos").find("input[name='total']").val(total);
                $("#modal_datos").find("input[name='id_facturacion']").val(datos_facturacion.id_facturacion);
            }

            if (JSON.parse(datos_turno.data_extra).indexOf('primera_vez') >= 0)
                $("#modal_datos").find("input[name='primera_vez']").prop('checked',true);
            else
                $("#modal_datos").find("input[name='primera_vez']").removeAttr('checked');

            fill_especialidades(datos_turno.agenda, function(data){
              $.each( data, function(key,val) {
                  $("#modal_datos").find("select[name='especialidad']").append($('<option>', {
                          value: val,
                          text : val
                  }));
              });
              $("#modal_datos").find("input[name='especialidad']").val(datos_turno.especialidad);
              $("#modal_datos").modal({
                  show: true
              });
            });
        }
    });
}

function ok_modificar_datos(event) {
    event.preventDefault();

    form = $("#modal_datos").find('form');

    $.ajax({
        type: "POST",
        url: base_url+"/main/modificar_datos/",
        data: form.serialize(),
        // dataType: 'json',
        success:function(response)
        {
          // console.log(response);
          get_turnos_mes(FECHA_ACTUAL);
          $("#modal_datos").modal('hide');
        }
    });
}

function cambiar_turno_item(id) {
    CAMBIO_TURNO = id;
    get_turnos_mes(FECHA_ACTUAL);
}

function cambiar_turno(hora) {

    fecha = format_date(FECHA_ACTUAL); //FECHA_ACTUAL.getFullYear()+"-"+parseInt(FECHA_ACTUAL.getMonth()+1)+"-"+FECHA_ACTUAL.getDate();

    $.ajax({
        type: "POST",
        url: base_url+"/main/get_turno_json/"+CAMBIO_TURNO,
        dataType: "json",
        success:function(response)
        {
            $("#modal_cambiar_turno").find("input[name='id_turno']").val(CAMBIO_TURNO);
            $("#modal_cambiar_turno").find("input[name='fecha']").val(fecha);
            $("#modal_cambiar_turno").find("input[name='hora']").val(hora);

            $("#modal_cambiar_turno").find('#nombre').html(response.datos_paciente.apellido+', '+response.datos_paciente.nombre);
            $("#modal_cambiar_turno").find('#fecha_hora').html(dias[FECHA_ACTUAL.getDay()]+" "+FECHA_ACTUAL.getDate()+", "+meses[FECHA_ACTUAL.getMonth()]+" "+FECHA_ACTUAL.getFullYear()+" a las "+hora+"Hs");
            $("#modal_cambiar_turno").modal({
                show: true
            });

        }
    });
}

function ok_cambiar_turno(event) {
    event.preventDefault();

    form = $("#modal_cambiar_turno").find('form');

    $.ajax({
        type: "POST",
        url: base_url+"/main/cambiar_turno/",
        data: form.serialize(),
        success:function(response)
        {
          CAMBIO_TURNO = "";
          get_turnos_mes(FECHA_ACTUAL);
          $("#modal_cambiar_turno").modal('hide');
        }
    });
}

function proximo_turno_item(id) {
    PROX_TURNO = id;
    get_turnos_mes(FECHA_ACTUAL);
}

function proximo_turno(hora) {

    esp = $("#agendas").val();
    fecha = format_date(FECHA_ACTUAL); //FECHA_ACTUAL.getFullYear()+"-"+parseInt(FECHA_ACTUAL.getMonth()+1)+"-"+FECHA_ACTUAL.getDate();

    clear_fields($("#modal_turno"));

    $("#modal_turno").find("#titulo").html("Nuevo Turno");
    $("#modal_turno").find("input[name='hora']").val(hora);
    $("#modal_turno").find("input[name='fecha']").val(fecha);
    $("#modal_turno").find("input[name='id_agenda']").val(esp);
    $("#modal_turno").find("input[name='especialista']").val($("#agendas option:selected").text());

    $.ajax({
        type: "POST",
        url: base_url+"/main/get_turno_json/"+PROX_TURNO,
        dataType: "json",
        success:function(response)
        {
          datos_paciente = response.datos_paciente;
          datos_turno = response.datos_turno;
          datos_facturacion = response.datos_facturacion;

          $("#modal_turno").find("#titulo").html("Nuevo Turno");

          $("#modal_turno").find("input[name='id_paciente']").val(datos_paciente.id_paciente);
          $("#modal_turno").find("input[name='nombre']").val(datos_paciente.nombre);
          $("#modal_turno").find("input[name='apellido']").val(datos_paciente.apellido);
          $("#modal_turno").find("input[name='tel1']").val(split_telefono(datos_paciente.tel1).prefijo);
          $("#modal_turno").find("input[name='tel2']").val(split_telefono(datos_paciente.tel1).telefono);
          $("#modal_turno").find("input[name='cel1']").val(split_telefono(datos_paciente.tel2).prefijo);
          $("#modal_turno").find("input[name='cel2']").val(split_telefono(datos_paciente.tel2).telefono);
          // $("#modal_turno").find("input[name='dni']").val(datos_paciente.dni);
          // $("#modal_turno").find("input[name='localidad']").val(datos_paciente.localidad);
          // $("#modal_turno").find("input[name='direccion']").val(datos_paciente.direccion);
          // $("#modal_turno").find("textarea[name='observaciones_paciente']").val(datos_paciente.observaciones);

          $("#modal_turno").find("input[name='primera_vez']").removeAttr('checked');
          $("#modal_turno").find("#anular").css('visibility','visible')

          fill_especialidades(datos_turno.agenda, function(data){
            $.each( data, function(key,val) {
                $("#modal_turno").find("select[name='especialidad']").append($('<option>', {
                        value: val,
                        text : val
                }));
            });

            $("#modal_turno").modal({
                show: true
            });
          });
        }
    });
}

function anular_accion(id) {
    CAMBIO_TURNO = "";
    PROX_TURNO = "";

    $("#modal_turno").modal('hide');
    $("#modal_turno").find("#anular").css("visibility", "hidden");

    $("#modal_cambiar_turno").modal('hide');
    get_turnos_mes(FECHA_ACTUAL);
}

function add_notas() {

    esp = $("#agendas").val();
    // fecha = FECHA_ACTUAL.getFullYear()+"-"+parseInt(FECHA_ACTUAL.getMonth()+1)+"-"+FECHA_ACTUAL.getDate();
    fecha = format_date(FECHA_ACTUAL);

    $("#modal_notas").find('input[name="fecha"]').val(fecha);
    $("#modal_notas").find('select[name="destinatario_sel"]').val("todos");
    // $("#modal_notas").find('input[name="destinatario"]').val(esp);
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
        url: base_url+"/main/get_nota_json/todas"+"/"+fecha,
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
        }
    });
}

function editar_nota(id) {

    $.ajax({
        url: base_url+"/main/get_nota_json/"+id,
        dataType: "json",
        success:function(response)
        {
          if (response != null) {
            $("#modal_notas").find('#eliminar_nota').css('visibility','visible');
            $("#modal_notas").find('select[name="destinatario_sel"]').val(response.destinatario);
            // $("#modal_notas").find('input[name="destinatario"]').val(response.destinatario);
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

function clear_agenda_extra(form) {
  form.find("input[name='crear_agenda_fecha']").val("");
  form.find("input[name='crear_agenda_id_txt']").val("");
  form.find("input[name='crear_agenda_id']").val("");
  form.find("select[name='crear_agenda_desde_man_hora']").val("");
  form.find("select[name='crear_agenda_desde_man_min']").val("");
  form.find("select[name='crear_agenda_hasta_man_hora']").val("");
  form.find("select[name='crear_agenda_hasta_man_min']").val("");
  form.find("select[name='crear_agenda_desde_tar_hora']").val("");
  form.find("select[name='crear_agenda_desde_tar_min']").val("");
  form.find("select[name='crear_agenda_hasta_tar_hora']").val("");
  form.find("select[name='crear_agenda_hasta_tar_min']").val("");
  form.find("select[name='crear_agenda_duracion']").val("30");
}

function abrir_agenda() {
  // $(".horarios").css('display','none');

  fecha = format_date(FECHA_ACTUAL);
  esp_txt = $("#agendas option:selected").text();
  esp = $("#agendas").val();

  form = $(".abrir_agenda").find('#form_agenda_extra');

  clear_agenda_extra(form);

  form.find("input[name='crear_agenda_fecha']").val(fecha);
  form.find("input[name='crear_agenda_id_txt']").val(esp_txt);
  form.find("input[name='crear_agenda_id']").val(esp);
  // form.find("input[name='crear_agenda_hora_desde_man']").val("");
  // form.find("input[name='crear_agenda_hora_hasta_man']").val("");
  // form.find("input[name='crear_agenda_hora_desde_tar']").val("");
  // form.find("input[name='crear_agenda_hora_hasta_tar']").val("");
  // form.find("select[name='crear_agenda_duracion']").val("30");

  $(".abrir_agenda").css('visibility','visible');

}

function crear_agenda() {

    event.preventDefault();
    form = $(".abrir_agenda").find('#form_agenda_extra');

    console.log(form.serialize());
    $.ajax({
        type: "POST",
        url: base_url+"/main/am_agenda_extra/",
        data: form.serialize(),
        success:function(response)
        {
          get_turnos_mes(FECHA_ACTUAL);
        }
    });

}

function editar_agenda() {

    event.preventDefault();
    form = $("#modal_agenda_extra").find('#form_agenda_extra');

    // console.log(form.serialize());
    $.ajax({
        type: "POST",
        url: base_url+"/main/am_agenda_extra/",
        data: form.serialize(),
        success:function(response)
        {
          get_turnos_mes(FECHA_ACTUAL);
          $("#modal_agenda_extra").modal('hide');
        }
    });

}

function editar_agenda_extra_item() {

  esp = $("#agendas").val();
  esp_txt = $("#agendas option:selected").text();

  if (esp == "todos") {
    return mensaje_error('Seleccionar una agenda primero');
  }

  fecha = format_date(FECHA_ACTUAL);

  if (HORARIOS_EXTRA.hasOwnProperty(fecha)) {

    $.ajax({
        type: "POST",
        url: base_url+"/main/get_agenda_extra_json/"+esp+"/"+fecha,
        dataType: "json",
        success:function(response)
        {
          var horarios = JSON.parse(response[0].horarios);

          form = $("#modal_agenda_extra").find('#form_agenda_extra');

          form.find("#crear_btn").css('visibility','hidden');
          form.find("#guardar_btn").css('visibility','visible');
          form.find("#cancelar_btn").css('visibility','visible');

          clear_agenda_extra(form);

          form.find("input[name='crear_id']").val(response[0].id);
          form.find("input[name='crear_agenda_fecha']").val(fecha);
          form.find("input[name='crear_agenda_id_txt']").val(esp_txt);
          form.find("input[name='crear_agenda_id']").val(esp);

          form.find("input[name='crear_agenda_duracion']").val(response[0].duracion);

          var desde_man = horarios[1].desde.split(":");
          var hasta_man = horarios[1].hasta.split(":");

          var desde_tar = horarios[2].desde.split(":");
          var hasta_tar = horarios[2].hasta.split(":");

          if (desde_man.length > 1) {
            desde_man_hora = desde_man[0];
            desde_man_min = desde_man[1];
          }
          else {
            desde_man_hora = "";
            desde_man_min = "";
          }

          if (hasta_man.length > 1) {
            hasta_man_hora = hasta_man[0];
            hasta_man_min = hasta_man[1];
          }
          else {
            hasta_man_hora = "";
            hasta_man_min = "";
          }

          if (desde_tar.length > 1) {
            desde_tar_hora = desde_tar[0];
            desde_tar_min = desde_tar[1];
          }
          else {
            desde_tar_hora = "";
            desde_tar_min = "";
          }

          if (hasta_tar.length > 1) {
            hasta_tar_hora = hasta_tar[0];
            hasta_tar_min = hasta_tar[1];
          }
          else {
            hasta_tar_hora = "";
            hasta_tar_min = "";
          }

          form.find("select[name='crear_agenda_desde_man_hora']").val(desde_man_hora);
          form.find("select[name='crear_agenda_desde_man_min']").val(desde_man_min);
          form.find("select[name='crear_agenda_hasta_man_hora']").val(hasta_man_hora);
          form.find("select[name='crear_agenda_hasta_man_min']").val(hasta_man_min);
          form.find("select[name='crear_agenda_desde_tar_hora']").val(desde_tar_hora);
          form.find("select[name='crear_agenda_desde_tar_min']").val(desde_tar_min);
          form.find("select[name='crear_agenda_hasta_tar_hora']").val(hasta_tar_hora);
          form.find("select[name='crear_agenda_hasta_tar_min']").val(hasta_tar_min);


          // form.find("input[name='crear_agenda_desde_man']").val(horarios[1].desde);
          // form.find("input[name='crear_agenda_hasta_man']").val(horarios[1].hasta);
          // form.find("input[name='crear_agenda_desde_tar']").val(horarios[2].desde);
          // form.find("input[name='crear_agenda_hasta_tar']").val(horarios[2].hasta);

          $("#modal_agenda_extra").modal({
              show: true
          });
        }
    });
  }
  else {
    return mensaje_error('Seleccionar una fecha que haya sido designada como agenda extra');
  }


}

function eliminar_agenda_extra_item() {

  esp = $("#agendas").val();
  esp_txt = $("#agendas option:selected").text();

  if (esp == "todos") {
    return mensaje_error('Se debe seleccionar una agenda primero');
  }

  fecha = format_date(FECHA_ACTUAL);

  if (HORARIOS_EXTRA.hasOwnProperty(fecha)) {
    $("#modal_confirmacion").find("#form_content").html(
      '<form id = "eliminar_agenda_extra_'+esp+'" style = "display:none">'
        +'<input type="text" name = "fecha" value="'+fecha+'">'
        +'<input type="text" name = "agenda" value="'+esp+'">'
      +'</form>');
    confirmar("¿Eliminar Agenda Extra?", "del_agenda_extra", "eliminar_agenda_extra_"+esp);
  }
  else {
    return mensaje_error('Seleccionar una fecha que haya sido designada como agenda extra');
  }

}
