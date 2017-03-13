<style media="screen">
    .panel-default .panel-heading {
        background-color: #454545;
        color: white;
        font-weight: 400;
    }
</style>

<div class="container-fluid">
    <div class="col-md-8">
        <div class = "panel panel-default" style="height:600px;padding-right:0px">
            <div class = "panel-body">
                <div class="row">
                    <div class = "col-md-1 col-xs-6 col-md-offset-1" style = "padding-top:20px;">
                        <label class = "label-control">Desde</label>
                    </div>
                    <div class = "col-md-3 col-xs-6" style = "padding-top:15px;">
                        <input type="date" class = "form-control" id = "desde">
                    </div>
                    <div class = "col-md-1 col-xs-6" style = "padding-top:20px;">
                        <label class = "label-control">Hasta</label>
                    </div>
                    <div class = "col-md-3 col-xs-6" style = "padding-top:15px;">
                        <input type="date" class = "form-control" id = "hasta">
                    </div>
                    <div class = "col-md-2 col-xs-6" style = "padding-top:15px;">
                        <button type="button" name="button" class="form-control btn-primary" onclick="return buscar_facturacion()">Buscar</button>
                    </div>
                </div>
                <hr style="margin-bottom:0px">
                <div class="row">
                    <div class="col-md-12 content_facturacion">

                    </div>
                    <!-- ACA VA EL RESULTADO DE LA CONSULTA DE FACTURACION  -->


                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class = "panel panel-default">
            <div class="panel-heading">
                Deudores
            </div>
            <div class = "panel-body content_deudores" style ="height:300px;padding-top:0px;padding-right:0px">
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    function format_date(fecha)
    {

        var day = fecha.getDay();
        var mm = (fecha.getMonth() + 1).toString();
        var dd = fecha.getDate().toString();
        var yy = fecha.getFullYear().toString();
        var date = yy + "-" + (mm.length == 2?mm:"0"+mm) + "-" + (dd.length == 2?dd:"0"+dd);

        return date;
    }

    $(document).ready(function() {
        var desde = format_date(new Date());
        // get_facturacion(desde,"");
        get_deudores();
    });

    function buscar_facturacion() {
        var desde = $("#desde").val();
        var hasta = $("#hasta").val();
        get_facturacion(desde,hasta);
    }

    function get_facturacion(desde, hasta) {
        var contenido="";

        $(".content_facturacion").empty();

        $.ajax({
            type: "POST",
            url: base_url+"/main/get_facturacion_grupos_json/",
            data: {fecha_hasta:hasta,fecha_desde:desde},
            dataType: 'json',
            success:function(response)
            {
                if (response.length != 0) {
                    contenido =
                        '<div class="row" style ="overflow-y:scroll;height:490px">'+
                                '<table class="table table-striped">'+
                                    '<thead>'+
                                        '<tr>'+
                                            '<th>Fecha</th>'+
                                            '<th>Valor</th>'+
                                            '<th>Concepto</th>'+
                                            '<th>Socio</th>'+
                                        '</tr>'+
                                    '</thead>'+
                                    '<tbody>';
                                    $.each(response, function(key, value)
                                    {
                                        contenido += '<tr id = "'+value.id_facturacion+'">'+
                                                    '<td>'+
                                                        value.fecha+
                                                    '</td>'+
                                                    '<td>'+
                                                        value.monto+
                                                    '</td>'+
                                                    '<td>'+
                                                        value.concepto+
                                                    '</td>'+
                                                    '<td>'+
                                                        value.apellido+', '+value.nombre+
                                                    '</td>'+
                                                '</tr>';
                                    });

                                    contenido +=
                                    '</tbody>'+
                                '</table>'+
                            '</div>';
                }
                else {
                    contenido = "No hay informacion para el periodo suministrado";
                }

                $(".content_facturacion").html(contenido);
            }
        });

    }

    function get_deudores() {
        var data="";
        $.ajax({
            url: base_url+"/main/get_deudores_grupo_json/",
            dataType: 'json',
            success:function(response)
            {
                if (response.length != 0) {
                    data = '<div class="" style ="overflow-y:scroll;height:300px">'+
                            '<table class="table table">'+
                                '<thead>'+
                                    '<tr>'+
                                        '<th>Socio</th>'+
                                        '<th>Saldo</th>'+
                                    '</tr>'+
                                '</thead>'+
                                '<tbody>';
                                $.each(response, function(key, value)
                                {
                                    var saldo = JSON.parse(value.data_extra).saldo;
                                    data += '<tr id = "'+value.id_paciente+'">'+
                                                '<td>'+
                                                    value.apellido+', '+value.nombre+
                                                '</td>'+
                                                '<td>'+
                                                    saldo+
                                                '</td>'+
                                            '</tr>';
                                });

                                data +=
                                '</tbody>'+
                            '</table>'+
                        '</div>';
                    }
                    else {
                        data = "No hay deudores";
                    }

                    $(".content_deudores").html(data);

                }
        });
    }
</script>
