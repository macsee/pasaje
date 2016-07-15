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
</style>

<div id="modal_turno" class="modal fade in" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- <iframe class = "iframe_turno" src="" width="100%" height="430px"></iframe> -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 id = "titulo" class="modal-title"></h3>
            </div>
            <div class = "modal-body">
                <div class = "content">
                    <form method = "post" class="row form-horizontal">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Especialista</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name = "especialista" readonly>
                                    <input type="hidden" class="form-control" name = "id_agenda">
                                    <input type="hidden" class="form-control" name = "id_turno">
                                    <input type="hidden" class="form-control" id = "id_paciente" name = "id_paciente">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Especialidad</label>
                                <div class="col-sm-5">
                                    <select class="form-control" name = "especialidad">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Hora</label>
                                <div class="col-sm-5">
                                    <input type="hidden" class="form-control" name = "fecha" readonly>
                                    <input type="time" class="form-control" name = "hora" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-xs-5 control-label">Primera vez</label>
                                <div class="col-sm-4 col-xs-4">
                                     <input id = "primera_vez" type="checkbox" name = "primera_vez" checked>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Observaciones</label>
                                <div class="col-sm-9">
                                     <textarea class="form-control" name = "observaciones"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Apellido</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id = "apellido" name = "apellido" style = "text-transform:capitalize" required autocomplete="off" autofocus>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Nombre</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id = "nombre" name = "nombre" style = "text-transform:capitalize" required autocomplete="off">
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
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button id = "anular" style = "visibility:hidden" onclick="anular_accion(event)"class="btn btn-default">Anular</button>
                <button id = "am_turno" onclick="am_turno(event)" class="btn btn-default">Guardar</button>
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
