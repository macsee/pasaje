<style media="screen">
    .control-label {
        text-align: left!important;
    }
    .ui-autocomplete {
        max-height: 300px;
        width: 300px;
        overflow-y: auto;
        /* prevent horizontal scrollbar */
        overflow-x: hidden;
     }

    .ui-menu .ui-menu-item {
      font-size: 14px;
      font-family: 'Roboto', sans-serif;
    }
    .ui-widget-content {
        background: white;
    }
    .ui-state-focus {
        background: none !important;
        background-color: #EAEAEA !important;
        border: none !important;
        color: black;
    }

    .ui-state-focus a{
        color: black;
    }

    .ui-state-focus a:hover {
        color: black;
    }

    .lista_results {
        color: black;
    }

    .ui-autocomplete {
      z-index: 1050 !important;
    }

    .page-header {
        margin: 5px 0 10px;
    }

    .datos_turno {
        margin-top: 5px;
        font-size: 16px;
    }
</style>

<div id="modal_datos" class="modal fade in" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 id = "titulo" class="modal-title"></h3>
            </div>
            <div class = "modal-body">
                <div class = "content">
                    <form method = "post" class="row form-horizontal">
                        <div class="col-md-6">
                            <div class="page-header">
                                <h4 class="modal-title">Datos Paciente</h4>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Apellido</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id = "apellido" name = "apellido" style = "text-transform:capitalize" required autocomplete="off" autofocus>
                                    <input type="hidden" class="form-control" name = "id_turno">
                                    <input type="hidden" class="form-control" name = "id_paciente">
                                    <input type="hidden" class="form-control" name = "id_facturacion">
                                    <input type="hidden" class="form-control" name = "estado">
                                    <input type="hidden" class="form-control" name = "fecha">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Nombre</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id = "nombre" name = "nombre" style = "text-transform:capitalize" required autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">DNI</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id = "dni" name = "dni" required autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Localidad</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id = "localidad" name = "localidad" style = "text-transform:capitalize" required autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Direccion</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id = "direccion" name = "direccion" style = "text-transform:capitalize" required autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Teléfono</label>
                                <div class="col-sm-10">
                                    <input type="tel" class="form-control" id = "tel1" style = "width:20%;display:inline" name = "tel1" autocomplete="off">
                                    <input type="tel" class="form-control" id = "tel2" style = "width:50%;display:inline" name = "tel2" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Celular</label>
                                <div class="col-sm-10">
                                    <input type="tel" class="form-control" id = "cel1" style = "width:20%;display:inline" name = "cel1" autocomplete="off">
                                    <input type="tel" class="form-control" id = "cel2" style = "width:50%;display:inline" name = "cel2" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Observaciones</label>
                                <div class="col-sm-9">
                                     <textarea class="form-control" name = "observaciones_paciente"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="page-header">
                                <h4 class="modal-title">Datos Turno</h4>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Especialista</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id = "especialista" name = "especialista" readonly>
                                </div>
                                <!-- <div id = "" class="datos_turno col-sm-6"></div> -->
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Especialidad</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id = "especialidad" name = "especialidad" readonly>
                                </div>
                                <!-- <div id = "especialidad" class="datos_turno col-sm-6"></div> -->
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Hora</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id = "hora" name = "hora" readonly>
                                </div>
                                <!-- <div id = "hora" class="datos_turno col-sm-6"></div> -->
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Observaciones</label>
                                <div class="col-sm-9">
                                    <textarea type="text" class="form-control" id = "observaciones" name = "observaciones" readonly></textarea>
                                </div>
                                <!-- <div id = "observaciones" class="datos_turno col-sm-9" style = "height:60px"></div> -->
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Total</label>
                                <div class="col-sm-3">
                                     <input type="tel" class="form-control" name = "total" autocomplete="off" required>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button id = "confirmar_datos" onclick="ok_confirmar_datos(event)"class="btn btn-default">Guardar</button>
                <button id = "cancelar" type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $( "#apellido" ).autocomplete({
        source: function( request, response ) {
            $.ajax({
                type: "POST",
                url: base_url+"/main/autocomplete_pacientes",
                dataType: "json",
                data: {
                    query: request.term
                },
                success: function( data ) {
                    response(data);
                }
            });
        },
        minLength: 3,
        select: function( event, ui ) {

            tel1 = "";
            tel2 = ui.item.tel;
            cel1 = "";
            cel2 = ui.item.cel;

            if (ui.item.tel.indexOf("-") > 0) {
                tel1 = ui.item.tel.split("-")[0];
                tel2 = ui.item.tel.split("-")[1];
            }

            if (ui.item.cel.indexOf("-") > 0) {
                cel1 = ui.item.tel.split("-")[0];
                cel2 = ui.item.tel.split("-")[1];
            }

            $("#id_paciente").val(ui.item.id);
            $( "#nombre" ).val(ui.item.nombre);
            $( "#tel1" ).val(tel1);
            $( "#tel2" ).val(tel2);
            $( "#cel1" ).val(cel1);
            $( "#cel2" ).val(cel2);
            $( "#primera_vez" ).removeAttr('checked');
        }
    })
    .autocomplete( "instance" )._renderItem = function( ul, item ) {
      return $( "<li>" )
        .append('<div style = "overflow:auto">'+
                    '<div class = "lista_results">'+item.label+ ', '+item.nombre+'</div>'+
                    '<div class = "lista_results">DNI: '+item.dni+'</div>'+
                    '<div class = "lista_results">Direccion: '+item.direccion+'</div>'+
                    '<div style = "width:100%">'+
                        '<div style = "float:left" class = "lista_results">Tel: '+item.tel+'</div>'+
                        '<div style = "float:right" class = "lista_results">Cel: '+item.cel+'</div>'+
                    '</div>'+
                '</div>'+
                '<hr style = "margin-top:2px;margin-bottom:2px">'
        )
        .appendTo( ul );
    };
</script>
