var fecha_actual = new Date();
var cambio_turno = "";
var prox_turno = "";
// var turnos_mes = [];
// var horarios_mes = [];
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
    obj.find("textarea[name='observaciones']").val("");
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

// function get_horarios(esp, callback)
// {
//   // var esp = $("#especialistas").val();
//
//   $.ajax({
//       url: base_url+"/main/get_horarios_json/"+esp,
//       dataType: 'json',
//       success:function(response)
//       {
//         // horarios_mes = response;
//         callback(response);
//         // actualizar_datos();
//         // get_turnos_mes(fecha_actual);
//         // return response
//       }
//   });
// }

function init()
{
    fecha_actual = new Date();
    get_turnos_mes(fecha_actual);
    actualizar_datos();
}

function actualizar_datos()
{
    $(".display_date").html(dias[fecha_actual.getDay()]+" "+fecha_actual.getDate()+", "+meses[fecha_actual.getMonth()]+" "+fecha_actual.getFullYear());
}

function dia_siguiente()
{
    fecha_actual.setDate(fecha_actual.getDate()+1);
    actualizar_datos();
    show_turnos(fecha_actual);
}

function dia_anterior()
{
    fecha_actual.setDate(fecha_actual.getDate()-1);
    actualizar_datos();
    show_turnos(fecha_actual);
}

function dia_actual()
{
    fecha_actual = new Date();
    actualizar_datos();
    show_turnos(fecha_actual);
}

function set_fecha(fecha)
{
    fecha = format_date(fecha);
    fecha = fecha.replace(/-/g, '/'); // Para que funcione en celulares
    fecha_actual = new Date(fecha+" 00:00:00");
    actualizar_datos();
    show_turnos(fecha_actual);
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
  var esp = $("#especialistas").val();

  $.ajax({
      url: base_url+"/main/get_data_turnos_json/"+fecha.getFullYear()+"/"+parseInt(fecha.getMonth()+1)+"/"+esp,
      dataType: 'json',
      success:function(response)
      {
        var bloqueados = [];
        var t_mes = [];
        var h_mes = [];

        turnos_mes = response.turnos; // Aca seteo las variables locales turnos_mes y horarios_mes
        horarios_mes = response.horarios; // que contienen toda la info de los turnos

        crear_calendario(fecha, horarios_mes, turnos_mes, bloqueados);
        show_turnos(fecha);
      }
  });

}

function crear_calendario(fecha, h_mes, t_mes, bloqueados) {

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

        if (t_mes.hasOwnProperty(fecha)) {
          cant_turnos_ocupados = Object.keys(t_mes[fecha]).length;
        }

      }

      // console.log("Fecha: ",date," - ","Cant. Horarios: ",cant_turnos_disp, "Cant. Turnos: ",cant_turnos_ocupados);

      factor = cant_turnos_ocupados / cant_turnos_disp;

      switch (true){
          case (factor > 0 && factor < 0.50) :
              return {
                  classes : "celda_low"
              }
          case (factor >= 0.50 && factor < 0.75) :
              return {
                  classes : "celda_medium"
              }
          case (factor >= 0.75):
              return {
                  classes : "celda_high"
              }
      }

      return;
    }

  });
}

function show_turnos(fecha) {

    $(".horarios").empty();

    var esp = $("#especialistas").val();

    var html = "";
    var primera_vez = "";
    var date = format_date(fecha);
    var dia = dias_turnos_array_inv(fecha.getDay());

    if (turnos_mes.hasOwnProperty(date)) { // Si en la fecha hay turnos entonces los muestro

      $.each( turnos_mes[date].datos, function(key, value) {
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
          if (horarios_mes.hasOwnProperty(dia)) {
            $.each( horarios_mes[dia][0], function(key, value) {
                html += make_turno_vacio(value.hora);
            });
          }
          else { // Si no los hay entonces ofrezco abrir agenda
            html += "Abrir agenda";
          }
        }
        else { // Si no hay turnos y estoy en la vista de todos, entonces muestro un mensaje de que no hay turnos
          html += "No hay turnos";
        }
    }

    $(".horarios").html(html);

}

function make_turno_vacio(hora) {

    if (hora == "")
      separador = "separador";
    else
      separador = "";

    html = '<div class="row fila-turno '+separador+'">'
              +'<div class="col-md-12 cell_vacia turno_vacio" onclick = "return turno_vacio(\''+hora+'\')">'
                +hora
              +'</div>'
            +'</div>';

    return html;
}

function make_turno_ocupado(data) {

  if (data.estado != "OK")
      estado = 'glyphicon glyphicon-unchecked';
  else
      estado = 'glyphicon glyphicon-check';

  html = '<div class="row fila-turno">'
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
              +'<li><a href="#" onclick = "return confirmar_datos(\''+data.id_turno+'\')" data-toggle="modal">Cambiar Estado</a></li>'
              +'<li><a href="#" onclick = "return editar_turno(\''+data.id_turno+'\')" data-toggle="modal">Editar Turno</a></li>'
              +'<li><a href="#" onclick = "return eliminar_turno(\''+data.id_turno+'\')" data-toggle="modal">Eliminar Turno</a></li>'
              +'<li><a href="#" onclick = "return cambiar_turno(\''+data.id_turno+'\')" data-toggle="modal">Cambiar Fecha/Hora</a></li>'
              +'<li><a href="#" onclick = "return proximo_turno(\''+data.id_turno+'\')" data-toggle="modal">Nuevo Turno</a></li>'
          +'</ul>'
        +'</div>'
      +'</div>'
  +'</div>';

  return html;
}

$('#datepicker').on("changeDate", function(e) {
    // var fecha = $('#datepicker').datepicker('getFormattedDate');
    // var fecha = $('#datepicker').datepicker('getDate');
    var fecha = new Date(e.date);

    if (fecha != null)
        set_fecha(fecha);
    else
        set_fecha(fecha_actual);
});

$('#datepicker').on("changeMonth", function(e) {

    // var fecha = $('#datepicker').datepicker('getDate');
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
        url: base_url+"/main/get_especialidades_json/"+esp,
        dataType: 'json',
        success:function(response)
        {
            if (response != null && response.especialidad != null) {
                data = JSON.parse(response.especialidad);
                $.each( data, function(key,val) {
                    $("#modal_turno").find("select[name='especialidad']").append($('<option>', {
                            value: val,
                            text : val
                    }));
                });
            }

            callback();
        }
    });

}

function turno_vacio(hora) {

    esp = $("#especialistas").val();
    fecha = fecha_actual.getFullYear()+"-"+parseInt(fecha_actual.getMonth()+1)+"-"+fecha_actual.getDate();

    clear_fields($("#modal_turno"));

    $("#modal_turno").find("#titulo").html("Nuevo Turno");
    $("#modal_turno").find("input[name='hora']").val(hora);
    $("#modal_turno").find("input[name='fecha']").val(fecha);
    $("#modal_turno").find("input[name='id_especialista']").val(esp);
    $("#modal_turno").find("input[name='especialista']").val($("#especialistas option:selected").text());

    fill_especialidades(esp, function(){
        $("#modal_turno").modal({
            show: true
        });
    });
}

function update_calendar() {
    var especialista = $("#especialistas").val();
    var especialidad = ""
    $(".calendario").attr( 'src', base_url+"/calendar/make_calendar/"+especialista+"/"+especialidad);
}

$("#especialistas").change(function () {
    // get_horarios();
    get_turnos_mes(fecha_actual);
});

function am_turno(event) {
    event.preventDefault();

    form = $("#modal_turno").find('form');

    $.ajax({
        type: "POST",
        url: base_url+"/main/am_turno/",
        data: form.serialize(),
        success:function(response)
        {

            prox_turno = "";
            get_turnos_mes(fecha_actual);
            // update_calendar();
            $("#modal_turno").modal('hide');

        }
    });
}

function editar_turno(id) {
    $.ajax({
        type: "POST",
        url: base_url+"/main/get_turno_json/"+id,
        dataType: "json",
        success:function(response)
        {
            clear_fields($("#modal_turno"));

            datos_paciente = response.datos_paciente;
            datos_turno = response.datos_turno;

            $("#modal_turno").find("#titulo").html("Editar Turno");
            $("#modal_turno").find("input[name='id_turno']").val(id);
            $("#modal_turno").find("input[name='id_paciente']").val(datos_paciente.id_paciente);
            $("#modal_turno").find("input[name='nombre']").val(datos_paciente.nombre);
            $("#modal_turno").find("input[name='apellido']").val(datos_paciente.apellido);
            $("#modal_turno").find("input[name='tel1']").val(split_telefono(datos_paciente.tel1).prefijo);
            $("#modal_turno").find("input[name='tel2']").val(split_telefono(datos_paciente.tel1).telefono);
            $("#modal_turno").find("input[name='cel1']").val(split_telefono(datos_paciente.tel2).prefijo);
            $("#modal_turno").find("input[name='cel2']").val(split_telefono(datos_paciente.tel2).telefono);
            $("#modal_turno").find("textarea[name='observaciones']").val(datos_turno.observaciones);

            $("#modal_turno").find("input[name='hora']").val(datos_turno.hora);
            $("#modal_turno").find("input[name='fecha']").val(datos_turno.fecha);
            $("#modal_turno").find("input[name='id_especialista']").val(datos_turno.especialista);
            $("#modal_turno").find("input[name='especialista']").val(datos_turno.name_especialista);

            if (JSON.parse(datos_turno.data_extra).indexOf('primera_vez') >= 0)
                $("#modal_turno").find("input[name='primera_vez']").prop('checked',true);
            else
                $("#modal_turno").find("input[name='primera_vez']").removeAttr('checked');

            fill_especialidades(datos_turno.especialista, function(){
                $("#modal_turno").find("input[name='especialidad']").val(datos_turno.especialidad);
                $("#modal_turno").modal({
                    show: true
                });
            });

        }
    });
}

function eliminar_turno(id)
{
    $("#modal_eliminar_turno").find("input[name='id_turno']").val(id);

    $("#modal_eliminar_turno").modal({
        show: true
    });
}

function ok_eliminar_turno(event) {

    event.preventDefault();
    form = $("#del_turno_form");

    $.ajax({
        type: "POST",
        url: base_url+"/main/del_turno/",
        data: form.serialize(),
        success:function(response)
        {
            update_calendar();
            get_turnos_mes();
            $("#modal_eliminar_turno").modal('hide');

        }
    });
}

function confirmar_datos(id)
{
    $.ajax({
        type: "POST",
        url: base_url+"/main/get_turno_json/"+id,
        dataType: "json",
        success:function(response)
        {
            clear_fields($("#modal_datos"));

            total = "";
            datos_paciente = response.datos_paciente;
            datos_turno = response.datos_turno;
            datos_facturacion = response.datos_facturacion;

            $("#modal_datos").find("#titulo").html("Confirmar Datos");
            $("#modal_datos").find("input[name='id_turno']").val(id);
            $("#modal_datos").find("input[name='hora']").val(datos_turno.hora);
            $("#modal_datos").find("input[name='fecha']").val(datos_turno.fecha);
            $("#modal_datos").find("input[name='especialista']").val(datos_turno.name_especialista);
            $("#modal_datos").find("input[name='especialidad']").val(datos_turno.especialidad);
            $("#modal_datos").find("textarea[name='observaciones']").val(datos_turno.observaciones);
            $("#modal_datos").find("input[name='estado']").val("OK");

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

            if (datos_facturacion != null) {
                total = JSON.parse(datos_facturacion.datos).total;
                $("#modal_datos").find("input[name='total']").val(total);
                $("#modal_datos").find("input[name='id_facturacion']").val(datos_facturacion.id_facturacion);
            }


            $("#modal_datos").modal({
                show: true
            });

        }
    });
}

function ok_confirmar_datos(event) {
    event.preventDefault();

    form = $("#modal_datos").find('form');

    $.ajax({
        type: "POST",
        url: base_url+"/main/am_facturacion/",
        data: form.serialize(),
        success:function(response)
        {

            get_turnos_mes(fecha_actual);
            $("#modal_datos").modal('hide');

        }
    });
}

function cambiar_turno(id) {
    cambio_turno = id;
    get_turnos_mes();
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
            cambio_turno = "";
            get_turnos_mes();
            $("#modal_cambiar_turno").modal('hide');

        }
    });
}

function turno_cambio(id) {

    fecha = fecha_actual.getFullYear()+"-"+parseInt(fecha_actual.getMonth()+1)+"-"+fecha_actual.getDate();

    $.ajax({
        type: "POST",
        url: base_url+"/main/get_turno_json/"+cambio_turno,
        dataType: "json",
        success:function(response)
        {
            $("#modal_cambiar_turno").find("input[name='id_turno']").val(cambio_turno);
            $("#modal_cambiar_turno").find("input[name='fecha']").val(fecha);
            $("#modal_cambiar_turno").find("input[name='hora']").val(id);

            $("#modal_cambiar_turno").find('#nombre').html(response.datos_paciente.apellido+', '+response.datos_paciente.nombre);
            $("#modal_cambiar_turno").find('#fecha_hora').html(dias[fecha_actual.getDay()]+" "+fecha_actual.getDate()+", "+meses[fecha_actual.getMonth()]+" "+fecha_actual.getFullYear()+" a las "+id+"Hs");
            $("#modal_cambiar_turno").modal({
                show: true
            });

        }
    });
}

function proximo_turno(id) {
    prox_turno = id;
    get_turnos_mes();
}

function turno_prox(id) {

    esp = $("#especialistas").val();
    fecha = fecha_actual.getFullYear()+"-"+parseInt(fecha_actual.getMonth()+1)+"-"+fecha_actual.getDate();

    clear_fields($("#modal_turno"));

    $("#modal_turno").find("#titulo").html("Nuevo Turno");
    $("#modal_turno").find("input[name='hora']").val(id);
    $("#modal_turno").find("input[name='fecha']").val(fecha);
    $("#modal_turno").find("input[name='id_especialista']").val(esp);
    $("#modal_turno").find("input[name='especialista']").val($("#especialistas option:selected").text());

    $.ajax({
        type: "POST",
        url: base_url+"/main/get_turno_json/"+prox_turno,
        dataType: "json",
        success:function(response)
        {
            datos_paciente = response.datos_paciente;
            datos_turno = response.datos_turno;

            $("#modal_turno").find("input[name='id_paciente']").val(datos_paciente.id_paciente);
            $("#modal_turno").find("input[name='nombre']").val(datos_paciente.nombre);
            $("#modal_turno").find("input[name='apellido']").val(datos_paciente.apellido);
            $("#modal_turno").find("input[name='tel1']").val(split_telefono(datos_paciente.tel1).prefijo);
            $("#modal_turno").find("input[name='tel2']").val(split_telefono(datos_paciente.tel1).telefono);
            $("#modal_turno").find("input[name='cel1']").val(split_telefono(datos_paciente.tel2).prefijo);
            $("#modal_turno").find("input[name='cel2']").val(split_telefono(datos_paciente.tel2).telefono);
            $("#modal_turno").find("input[name='primera_vez']").removeAttr('checked');
            $("#modal_turno").find("#anular").css("visibility", "visible");

            fill_especialidades(esp, function(){
                $("#modal_turno").modal({
                    show: true
                });
            });

        }
    });
}

function anular_accion(id) {
    cambio_turno = "";
    prox_turno = "";

    $("#modal_turno").modal('hide');
    $("#modal_turno").find("#anular").css("visibility", "hidden");

    $("#modal_cambiar_turno").modal('hide');
    get_turnos_mes();
}

function add_notas() {
    esp = $("#especialistas").val();
    fecha = fecha_actual.getFullYear()+"-"+parseInt(fecha_actual.getMonth()+1)+"-"+fecha_actual.getDate();

    $("#modal_notas").find('input[name="fecha"]').val(fecha);
    $("#modal_notas").find('select[name="destinatario_sel"]').val(esp);
    $("#modal_notas").find('input[name="destinatario"]').val(esp);
    $("#modal_notas").find('textarea[name="texto"]').val("");
    $("#modal_notas").find('input[name="id_nota"]').val("");
    $("#modal_notas").find('#eliminar_nota').css('display','none');

    $("#modal_notas").modal({
        show: true
    });
}

function ok_am_nota() {

    $("#modal_notas").find('input[name="destinatario"]').val($("#modal_notas").find('select[name="destinatario_sel"]').val());
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

    esp = $("#especialistas").val();
    $('.notas_body').empty();
    $('.notas_body').html(notas);

    fecha = fecha_actual.getFullYear()+"-"+parseInt(fecha_actual.getMonth()+1)+"-"+fecha_actual.getDate();

    $.ajax({
        url: base_url+"/main/get_nota_json/todas"+"/"+fecha,
        dataType: "json",
        success:function(response)
        {
            if (response != null) {
                notas = '<ul style = "margin-left:-25px">';
                //TERMINAR LA IMPLEMENTACION DE ESPECIALIDADES Y AGENDAS
                //VER EL TEMA DE MAÑANA Y TARDE EN LAS AGENDAS

                $.each( response, function(key,val) {

                    if (val.destinatario == esp || val.destinatario == "todos" || esp == "todos") {

                        if (user_logged == val.usuario) {
                            var onclick = "return editar_nota('"+val.id_nota+"')";
                        }
                        else {
                            var onclick = 'return error_nota()';
                        }

                        notas +=
                            '<li style = "min-height:40px;margin-bottom:25px">'+
                                '<a onclick = "'+onclick+'">'+val.texto+'</a>'+
                                '<span class = "pull-right text-muted small" style = "width:100%"><i>'+val.last_update+' - '+val.nombre_usuario+'</i></span>'+
                            '</li>';
                    }

                });
                notas += '</ul>';
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
            console.log(response);
            if (response != null) {
                $("#modal_notas").find('#eliminar_nota').css('display','');
                $("#modal_notas").find('select[name="destinatario_sel"]').val(response.destinatario);
                $("#modal_notas").find('input[name="destinatario"]').val(response.destinatario);
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

function error_nota() {
    $("#modal_error_notas").modal({
        show: true
    });
}

function abrir_agenda() {
    $(".horarios").css('display','none');
    $(".abrir_agenda").css('display','');

    $(".abrir_agenda").find("input[name='crear_agenda_fecha']").val("");
    $(".abrir_agenda").find("input[name='crear_agenda_especialistas_txt']").val("");
    $(".abrir_agenda").find("input[name='crear_agenda_especialistas']").val("");
    $(".abrir_agenda").find("select[name='crear_agenda_especialidad']").val("");
    $(".abrir_agenda").find("select[name='crear_agenda_hora_desde']").val("");
    $(".abrir_agenda").find("select[name='crear_agenda_hora_hasta']").val("");
    $(".abrir_agenda").find("select[name='crear_agenda_duracion']").val("30");

    fecha = fecha_actual.getFullYear()+"-"+parseInt(fecha_actual.getMonth()+1)+"-"+fecha_actual.getDate();

    esp_txt = $("#especialistas option:selected").text();
    esp = $("#especialistas").val();

    $(".abrir_agenda").find("input[name='crear_agenda_fecha']").val(fecha);
    $(".abrir_agenda").find("input[name='crear_agenda_especialistas_txt']").val(esp_txt);
    $(".abrir_agenda").find("input[name='crear_agenda_especialistas']").val(esp);

    // $.ajax({
    //     url: base_url+"/main/get_especialidades_json/"+esp,
    //     dataType: 'json',
    //     success:function(response)
    //     {
    //         if (response != null && response[0].especialidad != null) {
    //             data = JSON.parse(response[0].especialidad);
    //             $.each( data, function(key,val) {
    //                 $(".abrir_agenda").find("select[name='crear_agenda_especialidad']").append($('<option>', {
    //                         value: val,
    //                         text : val
    //                 }));
    //             });
    //         }
    //     }
    // });

}

function crear_agenda() {

    event.preventDefault();
    form = $(".abrir_agenda").find('form');

    $.ajax({
        type: "POST",
        url: base_url+"/main/crear_agenda/",
        data: form.serialize(),
        success:function(response)
        {
            get_turnos_mes();
            update_calendar();
            // get_notas();
        }
    });

}
