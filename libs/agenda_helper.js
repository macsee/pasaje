var fecha_actual = new Date();
var cambio_turno = "";
var prox_turno = "";
var turnos_mes = [];
var horarios_mes = []
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

function actualizar_fecha()
{
    $(".display_date").html(dias[fecha_actual.getDay()]+" "+fecha_actual.getDate()+", "+meses[fecha_actual.getMonth()]+" "+fecha_actual.getFullYear());
}

function dia_siguiente()
{
    fecha_actual.setDate(fecha_actual.getDate()+1);
    actualizar_fecha();
    // get_turnos();
}

function dia_anterior()
{
    fecha_actual.setDate(fecha_actual.getDate()-1);
    actualizar_fecha();
    // get_turnos();

}

function dia_actual()
{
    fecha_actual = new Date();
    actualizar_fecha();
    // get_turnos();

}

function set_fecha(fecha)
{
    fecha = fecha+" 00:00:00";
    fecha_actual = new Date(fecha.replace(/-/g, '/')); // Para que funcione en celulares
    actualizar_fecha();
    show_turnos(fecha_actual);
    // get_turnos();
}

function format_date(fecha) {

    var day = fecha.getDay();
    var mm = (fecha.getMonth() + 1).toString();
    var dd = fecha.getDate().toString();
    var yy = fecha.getFullYear().toString();
    var date = yy + "-" + (mm.length == 2?mm:"0"+mm) + "-" + (dd.length == 2?dd:"0"+dd);

    return date;
}

function show_turnos(fecha) {

    var date = format_date(fecha);
    var html = "";

    $.each( turnos_mes[date].turnos, function(key,val) {

        primera_vez = "";
        row_especialista = "";
        row_vacia = "";
        tipo_turno = 'return turno_vacio(\''+key+'\')';
        estado = 'glyphicon glyphicon-unchecked';

        if (val.id_turno != "") {
          html += '<div class="row fila-turno">'
              +'<div class="col-md-4 col-md-push-2 cell_turno fix_on_xs">'
                  +val.paciente
              +'</div>'
              +'<div class="col-md-2 col-md-pull-4 col-xs-3 cell_turno" style = "font-size:18px">'
                  +val.hora
              +'</div>'
              +'<div class="col-md-3 col-xs-4 cell_turno">'
                  +val.especialidad
              +'</div>'
              +'<div class="col-md-2 col-xs-3 cell_turno">'
                  +val.especialista
              +'</div>'
              +'<div class="col-md-1 col-xs-2 cell_turno" style = "border-right: none; text-align:center">'
                +'<button class="btn btn-default dropdown-toggle" data-toggle="dropdown" role="button"><span class = "glyphicon glyphicon-check"></span></button>'
                +'<ul class="dropdown-menu pull-right">'
                    +'<li><a href="#" onclick = "return editar_turno(\''+val.id_turno+'\')" data-toggle="modal">Cambiar Estado</a></li>'
                    +'<li><a href="#" onclick = "return editar_turno(\''+val.id_turno+'\')" data-toggle="modal">Editar Turno</a></li>'
                    +'<li><a href="#" onclick = "return eliminar_turno(\''+val.id_turno+'\')" data-toggle="modal">Eliminar Turno</a></li>'
                    +'<li><a href="#" onclick = "return cambiar_turno(\''+val.id_turno+'\')" data-toggle="modal">Cambiar Fecha/Hora</a></li>'
                    +'<li><a href="#" onclick = "return proximo_turno(\''+val.id_turno+'\')" data-toggle="modal">Nuevo Turno</a></li>'
                +'</ul>'
              +'</div>'
          +'</div>';
        }
        else {
          html += '<div class="row fila-turno">'
                    +'<div class="col-md-12 cell_vacia">'
                      +val.hora
                    +'</div>'
          +'</div>';
        }

    });

    $(".horarios").html(html);

}

function crear_calendario(dias_agenda, dias_turnos, dias_bloqueados) {

    $('#datepicker').datepicker('remove');

    $('#datepicker').datepicker({
        language: "es",
        format: 'yyyy-mm-dd',
        todayHighlight: true,
        toggleActive: true,
        // datesDisabled: dias_bloqueados,
        daysOfWeekHighlighted: dias_agenda,
        beforeShowDay: function(date){

            var date = format_date(date);

            if (dias_turnos.hasOwnProperty(date)) {

                switch (true){
                    case (dias_turnos[date].factor < 0.50) :
                        return {
                            classes : "celda_low"
                        }
                    case (dias_turnos[date].factor >= 0.50 && dias_turnos[date].factor < 0.75) :
                        return {
                            classes : "celda_medium"
                        }
                    case (dias_turnos[date].factor >= 0.75):
                        return {
                            classes : "celda_high"
                        }
                }
            // if ($.inArray(date, dias_turnos) != -1){
            //    return {
            //       //enabled : false,
            //       classes : "celda_vacia"
            //    };
            }
            return;
      }
    });

    $('#datepicker').on("changeDate", function() {
        set_fecha($('#datepicker').datepicker('getFormattedDate'));
    });

}

function get_turnos_mes()
{
    $(".horarios").empty();

    var esp = $("#especialistas").val();
    var especialidad = "";

    // var fecha = fecha_actual.getFullYear()+"-"+parseInt(fecha_actual.getMonth()+1)+"-"+fecha_actual.getDate();

    $.ajax({
        url: base_url+"/main/get_data_turnos_json/"+fecha_actual.getFullYear()+"/"+parseInt(fecha_actual.getMonth()+1)+"/"+esp,
        dataType: 'json',
        success:function(response)
        {

          var agenda = []; //dias para poner turnos
          var bloqueados = [];

          turnos_mes = response[esp].fechas;
          horarios_mes = response[esp].horarios;

          $.each(horarios_mes, function(key,val) {

            switch (true) {
              case (key == "lu"):
                agenda.push(1);
                break;
              case (key == "ma"):
                agenda.push(2);
                break;
              case (key == "mi"):
                agenda.push(3);
                break;
              case (key == "ju"):
                agenda.push(4);
                break;
              case (key == "vi"):
                agenda.push(5);
                break;
            }

          });

          crear_calendario(agenda, turnos_mes, bloqueados);
        }
    });
}

function get_turnos()
{
    $(".horarios").empty();
    get_notas();

    is_admin = $("#is_admin").val();
    var esp = $("#especialistas").val();
    var especialidad = "";
    var fecha = fecha_actual.getFullYear()+"-"+parseInt(fecha_actual.getMonth()+1)+"-"+fecha_actual.getDate();
    var tabla = "";
    var header_especialista = "";

    if (esp == "todos")
        header_especialista = '<th>Especialista</th>';

    $.ajax({
        url: base_url+"/main/get_turnos_fecha_json/"+fecha+"/"+esp+"/"+especialidad,
        dataType: 'json',
        success:function(response)
        {

            if (response != "") {

                tabla = '<table class="table">'+
                            '<thead class = "cabecera">'+
                                '<tr>'+
                                    '<th>Hora</th>'+
                                    '<th>Paciente</th>'+
                                    '<th>Especialidad</th>'+
                                    header_especialista+
                                    '<th>Acciones</th>'+
                                    '<th>Estado</th>'+
                                '</tr>'+
                            '</thead>'+
                            '<tbody>';

                $.each( response, function(key,val) {

                    primera_vez = "";
                    row_especialista = "";
                    row_vacia = "";
                    tipo_turno = 'return turno_vacio(\''+key+'\')';
                    estado = 'glyphicon glyphicon-unchecked';

                    if (val.id_turno != "") {

                        if (JSON.parse(val.data_extra).indexOf('primera_vez') >= 0)
                            primera_vez = "color:#d9534f";

                        if (esp == "todos")
                            row_especialista = '<td>'+val.especialista+'</td>';

                        if (val.estado != "")
                            estado = "glyphicon glyphicon-check";

                        if (is_admin != 0)
                            accion = 'onclick = "return confirmar_datos(\''+val.id_turno+'\')"';
                        else
                            accion = "";

                        tabla +=
                            '<tr id = "'+val.id_turno+'">'+
                                '<td style = "font-size:16px;'+primera_vez+'">'+val.hora+'</td>'+
                                '<td>'+val.paciente+'</td>'+
                                '<td>'+val.especialidad+'</td>'+
                                row_especialista+
                                '<td>'+
                                    '<div class="dropdown">'+
                                        '<button class="btn btn-default dropdown-toggle" data-toggle="dropdown" role="button"><i class = "glyphicon glyphicon-th-list"></i><span class="caret"></span></button>'+
                                        '<ul class="dropdown-menu">'+
                                            '<li><a href="#" onclick = "return editar_turno(\''+val.id_turno+'\')" data-toggle="modal">Editar Turno</a></li>'+
                                            '<li><a href="#" onclick = "return eliminar_turno(\''+val.id_turno+'\')" data-toggle="modal">Eliminar Turno</a></li>'+
                                            '<li><a href="#" onclick = "return cambiar_turno(\''+val.id_turno+'\')" data-toggle="modal">Cambiar Fecha/Hora</a></li>'+
                                            '<li><a href="#" onclick = "return proximo_turno(\''+val.id_turno+'\')" data-toggle="modal">Nuevo Turno</a></li>'+
                                        '</ul>'+
                                    '</div>'+
                                '</td>'+
                                '<td><button '+accion+' style = "font-size: 18px;padding: 3px 11px 3px 11px" class = "btn btn-default"><i class = "'+estado+'"></i></button></td>'+
                            '</tr>';
                    }
                    else {

                        if (esp == "todos")
                            row_vacia = '<td></td>';

                        if (cambio_turno != "")
                            tipo_turno = 'return turno_cambio(\''+key+'\')';//"turno_cambio";

                        if (prox_turno != "")
                            tipo_turno = 'return turno_prox(\''+key+'\')';//"turno_prox";

                        tabla +=
                            '<tr onclick = "'+tipo_turno+'" class = "row_vacia">'+
                                '<td>'+val.hora+'</td>'+
                                '<td></td>'+
                                '<td></td>'+
                                row_vacia+
                                '<td></td>'+
                                '<td></td>'+
                            '</tr>';
                    }

                });

                tabla +=
                        '</tbody>'+
                    '</table>';

                $(".horarios").css('display','');
                $(".abrir_agenda").css('display','none');
                $(".horarios").html(tabla);
            }
            else if (response == ""  && esp == "todos"){

                $(".horarios").css('display','');
                $(".abrir_agenda").css('display','none');
                // $(".horarios").html("<h3>No hay agenda abierta para este día.</h3>");
                $(".horarios").html("<h3>No existen turnos para ningún especialista para la fecha.</h3>");
            }
            else {
                abrir_agenda();
            }

            // $(".horarios").fadeOut('fast', function() {
            //     $(".horarios").html(tabla);
            //     $(".horarios").fadeIn('fast');
            // });

        }

    });


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

function turno_vacio(id) {

    esp = $("#especialistas").val();
    fecha = fecha_actual.getFullYear()+"-"+parseInt(fecha_actual.getMonth()+1)+"-"+fecha_actual.getDate();

    clear_fields($("#modal_turno"));

    $("#modal_turno").find("#titulo").html("Nuevo Turno");
    $("#modal_turno").find("input[name='hora']").val(id);
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
    // update_calendar();
    get_turnos_mes();
    // get_turnos();
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
            get_turnos();
            update_calendar();
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
            get_turnos();
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

            get_turnos();
            $("#modal_datos").modal('hide');

        }
    });
}

function cambiar_turno(id) {
    cambio_turno = id;
    get_turnos();
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
            get_turnos();
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
    get_turnos();
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
    get_turnos();
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
            get_turnos();
            update_calendar();
            // get_notas();
        }
    });

}
