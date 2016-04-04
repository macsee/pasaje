<style media="screen">
    .turno_vacio, .turno_cambio, .turno_prox{
        cursor: pointer;
    }

    .calendario {
        height: 250px;
        width: 250px;
        border-style: none;
    }

    .label-danger {
        font-size: 65%;
        border-radius: 3.25em;
    }

    .notas_body {
        height: 220px;
        overflow: auto;
    }

    .notas_body a {
        cursor: pointer;
    }
</style>
<div class="container-fluid">
    <div class="col-md-9 main">
        <div class = "panel panel-default">
            <div class = "panel-body">
                <div class = "col-md-4 col-xs-6 display_date" style = "padding-top:7px;font-size:18px;font-weight:400;text-align:center;margin-bottom:20px">
                    <?php //echo $display_date;?>
                </div>
                <div class = "col-md-3 col-xs-6">
                    <div class="btn-group" role="group">
                        <a href="#" type="button" class="btn btn-default glyphicon glyphicon-chevron-left" onclick="return dia_anterior();"></a>
                        <a href="#" type="button" class="btn btn-default glyphicon glyphicon-calendar" onclick="return dia_actual();"></a>
                        <a href="#" type="button" class="btn btn-default glyphicon glyphicon-chevron-right" onclick="return dia_siguiente()";></a>
                      <!-- <a href="<?php echo base_url('index.php/main/agenda/'.date('Y-m-d',strtotime($fecha.' -1 day')))?>" type="button" class="btn btn-default glyphicon glyphicon-chevron-left"></a>
                      <a href="<?php echo base_url('index.php/main/agenda/'.date('Y-m-d'))?>" type="button" class="btn btn-default glyphicon glyphicon-calendar"></a>
                      <a href="<?php echo base_url('index.php/main/agenda/'.date('Y-m-d',strtotime($fecha.' +1 day')))?>" type="button" class="btn btn-default glyphicon glyphicon-chevron-right"></a> -->
                    </div>
                </div>
                <div class = "col-md-5 col-xs-12">
                    <div class = "col-md-5 col-xs-5" style = "padding-top:7px;">
                        <label class = "label-control">Especialista</label>
                    </div>
                    <div class = "col-md-7 col-xs-7">
                        <select class = "form-control" id = "especialistas">
                            <?php
                                if ($especialistas != null) {
                                    echo '<option value = "todos">Todos</option>';
                                    foreach ($especialistas as $key => $value) {
                                        if ($especialista_sel == $value->usuario)
                                            $selected = "selected";
                                        else
                                            $selected = "";

                                        echo '<option '.$selected.' value = "'.$value->usuario.'">'.$value->apellido.', '.$value->nombre[0].'</option>';
                                    }
                                }
                                else
                                    echo '<option selected value = "'.$especialista_sel.'">'.$nom_especialista_sel.'</option>';
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class = "panel-body" style = "padding:0px;overflow:auto">
            <div class="table-responsive horarios" style = "height:1020px"></div>
        </div>

    </div>
    <div class="col-md-3">
        <div class="panel panel-default" style = "height:280px">
            <div class="panel-heading">
                <div class = "row">
                    <div class="col-md-6 col-xs-6">
                        <i class = "glyphicon glyphicon-bell"></i> <strong>Notas</strong>
                    </div>
                    <div class="col-md-1 col-md-offset-4 col-xs-1 col-xs-offset-4" style = "font-size:18px;color:black">
                        <a style = "color:black" href="#" onclick = "return add_notas()"><span class = "glyphicon glyphicon-plus-sign"></span></a>
                    </div>
                </div>
            </div>
            <div class = "panel-body notas_body">
                <?php echo '<iframe class = "notas_iframe" src="" style = "border:none;width:250px"></iframe>'; ?>
            </div>
        </div>

        <div class="panel panel-default" style = "height:320px">
            <div class="panel-heading" style = "font-weight:700px">
                <i class = "glyphicon glyphicon-calendar"></i> <strong>Calendario</strong>
            </div>
            <div class = "panel-body">
                <?php echo '<iframe class = "calendario" src="'.base_url('index.php/calendar/make_calendar/'.$especialista_sel.'/'.$especialidad_sel).'"></iframe>'; ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    var fecha_actual = new Date();
    var cambio_turno = "";
    var prox_turno = "";
    var dias = ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'];
    var meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Setiembre', 'Octubre', 'Noviembre', 'Diciembre'];

    $(document).ready(function () {
        // actualizar_fecha();
        // get_turnos();
        // get_notas();
        dia_actual();
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

    function actualizar_fecha()
    {
        $(".display_date").html(dias[fecha_actual.getDay()]+" "+fecha_actual.getDate()+", "+meses[fecha_actual.getMonth()]+" "+fecha_actual.getFullYear());
    }

    function dia_siguiente()
    {
        fecha_actual.setDate(fecha_actual.getDate()+1);
        actualizar_fecha();
        get_turnos();
        // get_notas();
    }

    function dia_anterior()
    {
        fecha_actual.setDate(fecha_actual.getDate()-1);
        actualizar_fecha();
        get_turnos();
        // get_notas();
    }

    function dia_actual()
    {
        fecha_actual = new Date();
        actualizar_fecha();
        get_turnos();
        // get_notas();
    }

    function set_fecha(fecha)
    {
        fecha_actual = new Date(fecha+" 00:00:00");
        actualizar_fecha();
        get_turnos();
    }

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

    function get_turnos()
    {
        $(".horarios").empty();
        get_notas();

        // var esp = $("#especialistas").val();
        // var especialidad = "";
        // var fecha = fecha_actual.getFullYear()+"-"+parseInt(fecha_actual.getMonth()+1)+"-"+fecha_actual.getDate();
        //
        // $.ajax({
        //     url: base_url+"/main/show_turnos/"+fecha+"/"+esp+"/"+especialidad,
        //     // dataType: 'json',
        //     success:function(response)
        //     {
        //         $(".horarios").html(response);
        //     }
        // });

        var esp = $("#especialistas").val();
        var especialidad = "";
        var fecha = fecha_actual.getFullYear()+"-"+parseInt(fecha_actual.getMonth()+1)+"-"+fecha_actual.getDate();
        var tabla = "";
        var header_especialista = "";

        if (esp == "todos")
            header_especialista = '<th>Especialista</th>';

        $.ajax({
            url: base_url+"/main/get_turnos_dia_esp_json/"+fecha+"/"+esp+"/"+especialidad,
            dataType: 'json',
            success:function(response)
            {
                if (response != null) {

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
                        turno_vacio = "turno_vacio";
                        estado = 'glyphicon glyphicon-unchecked';

                        if (val != "") {

                            if (JSON.parse(val.data_extra).indexOf('primera_vez') >= 0)
                                primera_vez = "color:#d9534f";

                            if (esp == "todos")
                                row_especialista = '<td>'+val.especialista+'</td>';

                            if (val.estado != "")
                                estado = "glyphicon glyphicon-check";

                            tabla +=
                                '<tr id = "'+val.id_turno+'">'+
                                    '<td style = "font-size:16px;'+primera_vez+'">'+key+'</td>'+
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
                                    '<td><button onclick = "return confirmar_datos(\''+val.id_turno+'\')" style = "font-size: 18px;padding: 3px 11px 3px 11px" class = "btn btn-default"><i class = "'+estado+'"></i></button></td>'+
                                '</tr>';
                        }
                        else {

                            if (esp == "todos")
                                row_vacia = '<td></td>';

                            if (cambio_turno != "")
                                turno_vacio = "turno_cambio";

                            if (prox_turno != "")
                                turno_vacio = "turno_prox";

                            tabla +=
                                '<tr class = "'+turno_vacio+'" id = "'+key+'">'+
                                    '<td>'+key+'</td>'+
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
                }

                $(".horarios").html(tabla);
                // get_notas();
            }

        });


    }

    function fill_especialidades(esp, callback) {

        $.ajax({
            url: base_url+"/main/get_especialidades_json/"+esp,
            dataType: 'json',
            success:function(response)
            {
                if (response != null) {
                    $.each( response, function(key,val) {
                        $("#modal_turno").find("select[name='especialidad']").append($('<option>', {
                                value: val.especialidad,
                                text : val.especialidad
                        }));
                    });
                }

                callback();
            }
        });

    }

    $(document).on("click",".turno_vacio",function() {

        id = $(this).attr('id');
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
    });

    function update_calendar() {
        var especialista = $("#especialistas").val();
        var especialidad = ""
        $(".calendario").attr( 'src', base_url+"/calendar/make_calendar/"+especialista+"/"+especialidad);
    }

    $("#especialistas").change(function () {
        update_calendar();
        get_turnos();
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

                $.each( datos_turno.especialidades, function(key,val) {
                    $("#modal_turno").find("select[name='especialidad']").append($('<option>', {
                            value: val.especialidad,
                            text : val.especialidad
                    }));
                });

                $("#modal_turno").find("input[name='especialidad']").val(datos_turno.especialidad);

                $("#modal_turno").modal({
                    show: true
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

    $(document).on("click",".turno_cambio",function() {
        id = $(this).attr('id');
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

    });

    function proximo_turno(id) {
        prox_turno = id;
        get_turnos();
    }

    $(document).on("click",".turno_prox",function() {

        id = $(this).attr('id');
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
    });

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
        console.log(form.serialize());
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

        esp = $("#especialistas").val();
        fecha = fecha_actual.getFullYear()+"-"+parseInt(fecha_actual.getMonth()+1)+"-"+fecha_actual.getDate();
        $('.notas_body').empty();

        $.ajax({
            url: base_url+"/main/show_notas/"+fecha+"/"+esp,
            success:function(response)
            {
                $('.notas_body').html(response);
            }
        });


        // user_logged = "<?php echo $usuario?>";
        // is_admin = "<?php echo $is_admin?>";
        // notas = '<i>No hay notas para la fecha</i>';
        //
        // esp = $("#especialistas").val();
        // $('.notas_body').empty();
        // $('.notas_body').html(notas);
        //
        // fecha = fecha_actual.getFullYear()+"-"+parseInt(fecha_actual.getMonth()+1)+"-"+fecha_actual.getDate();
        //
        // $.ajax({
        //     url: base_url+"/main/get_notas_json/"+fecha,
        //     dataType: "json",
        //     success:function(response)
        //     {
        //         if (response != null) {
        //             notas = '<ul style = "margin-left:-25px">';
        //
        //             $.each( response, function(key,val) {
        //
        //                 if (val.destinatario == esp || val.destinatario == "todos" || esp == "todos") {
        //
        //                     if (user_logged == val.usuario) {
        //                         var onclick = "return editar_nota('"+val.id_nota+"')";
        //                     }
        //                     else {
        //                         var onclick = 'return error_nota()';
        //                     }
        //
        //                     notas +=
        //                         '<li style = "min-height:40px;margin-bottom:25px">'+
        //                             '<a onclick = "'+onclick+'">'+val.texto+'</a>'+
        //                             '<span class = "pull-right text-muted small" style = "width:100%"><i>'+val.last_update+' - '+val.nombre_usuario+'</i></span>'+
        //                         '</li>';
        //                 }
        //
        //             });
        //             notas += '</ul>';
        //         }
        //
        //         $('.notas_body').html(notas);
        //     }
        // });
    }

    function editar_nota(id) {

        $.ajax({
            url: base_url+"/main/get_nota_json/"+id,
            dataType: "json",
            success:function(response)
            {
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

</script>
