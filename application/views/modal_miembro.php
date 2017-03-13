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

<?php
    // $option_group = [];
    // foreach ($grupos as $k => $grupo) {
    //     foreach ($grupo as $key => $value) {
    //         $option_group[] = '<option value="'.$value->id_grupo.'">'.$k.' - '.date('H:i', strtotime($value->horario_desde)).' a '.date('H:i', strtotime($value->horario_hasta)).' - '.$value->apellido.', '.$value->nombre.'</option>';
    //     }
    // }
 ?>

<div id="modal_miembro" class="modal fade in" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- <iframe class = "iframe_turno" src="" width="100%" height="430px"></iframe> -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 id = "titulo" class="modal-title"></h3>
            </div>
            <div class = "modal-body">
                <div class = "content">
                    <form method = "post" class="row form-horizontal" role="form" data-toggle="validator">
                        <div class="col-md-6">
                            <div class="form-group">
                                <!-- <input type="hidden" class="form-control" id = "dni" name = "dni">
                                <input type="hidden" class="form-control" id = "localidad" name = "localidad">
                                <input type="hidden" class="form-control" id = "direccion" name = "direccion"> -->
                                <input type="hidden" class="form-control" id = "id_integrante" name = "id_integrante">
                                <input type="hidden" class="form-control" id = "id_int_grupo_1"  name = "id_int_grupo_1">
                                <input type="hidden" class="form-control" id = "id_int_grupo_2"  name = "id_int_grupo_2">
                                <input type="hidden" class="form-control" id = "id_int_grupo_3"  name = "id_int_grupo_3">
                                <!-- <label class="col-md-3 control-label">Especialista</label>
                                <div class="col-md-5">
                                    <input type="text" class="form-control" name = "especialista" readonly>
                                </div> -->
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Grupo #1</label>
                                <div class="col-md-9">
                                    <select class="form-control grupo_1" name = "grupo_1">
                                        <!-- <?php
                                            foreach ($option_group as $grupo) {
                                                echo $grupo;
                                            }
                                         ?> -->
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Grupo #2</label>
                                <div class="col-md-9">
                                    <select class="form-control grupo_2" name = "grupo_2">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Grupo #3</label>
                                <div class="col-md-9">
                                    <select class="form-control grupo_3" name = "grupo_3">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Observaciones</label>
                                <div class="col-md-9">
                                     <textarea class="form-control" id = "observaciones" rows="8" name = "observaciones"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Apellido</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control required" id = "apellido" name = "apellido" style = "text-transform:capitalize" required="true" autocomplete="off">
                                    <span class="glyphicon glyphicon-remove form-control-feedback error" style="right:10px;visibilityle:hidden"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Nombre</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id = "nombre" name = "nombre" style = "text-transform:capitalize" required="true" autocomplete="off">
                                    <span class="glyphicon glyphicon-remove form-control-feedback error" style="right:10px;visibility:hidden"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Direccion</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id = "direccion" name = "direccion" style = "text-transform:capitalize" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">DNI</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id = "dni" name = "dni" style = "text-transform:capitalize" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Obra Social</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id = "obra_social" name = "obra_social" style = "text-transform:capitalize" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Celular</label>
                                <div class="col-md-9">
                                    <input type="tel" class="form-control" id = "cel1" style = "width:25%;display:inline" name = "cel1" autocomplete="off">
                                    <input type="tel" class="form-control" id = "cel2" style = "width:60%;display:inline" name = "cel2" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Tel. Contact.</label>
                                <div class="col-md-9">
                                    <input type="tel" class="form-control" id = "tel1" style = "width:25%;display:inline" name = "tel1" autocomplete="off">
                                    <input type="tel" class="form-control" id = "tel2" style = "width:60%;display:inline" name = "tel2" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-10" style="margin-top:20px">
                            <div class="form-group">
                                <label class="col-md-1 control-label">Concep.</label>
                                <div class="col-md-2">
                                    <select class="form-control concepto" name="concepto">
                                        <!-- <option value=""></option>
                                        <option value="abono">Abono</option>
                                        <option value="deuda">Deuda</option> -->
                                    </select>
                                </div>
                                <label class="col-md-1 control-label">Valor</label>
                                <div class="col-md-2">
                                    <input type="text" class="form-control" id = "valor" name = "valor" style = "text-transform:capitalize" required autocomplete="off">
                                    <span class="glyphicon glyphicon-remove form-control-feedback error" style="right:10px;visibilityle:hidden"></span>
                                </div>
                                <label class="col-md-3 control-label">Vencimiento Prox.</label>
                                <div class="col-md-2" id = "prox_vencimiento" style ="margin-top:5px;padding:0px"></div>
                                <!-- <input type="hidden" class="form-control" id = "prox_vencimiento_txt" name = "prox_vencimiento_txt" required autocomplete="off"> -->
                            </div>
                            <div class="form-group">
                                <label class="col-md-1 control-label">Paga</label>
                                <div class="col-md-2">
                                    <input type="text" class="form-control" id = "paga" name = "paga" style = "text-transform:capitalize" required autocomplete="off">
                                </div>
                                <label class="col-md-1 control-label">Saldo</label>
                                <div class="col-md-2">
                                    <input type="text" class="form-control" id = "saldo" name = "saldo" style = "text-transform:capitalize" required autocomplete="off" readonly="">
                                </div>
                                <label class="col-md-3 control-label">Vencimiento Actual</label>
                                <div class="col-md-2" id = "act_vencimiento" style ="margin-top:5px;padding:0px"></div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id = "anular" style = "visibility:hidden" onclick="anular_accion(event)"class="btn btn-default">Anular</button>
                    <button id = "nuevo_turno" onclick="ok_datos(event)" class="btn btn-default">Guardar</button>
                    <button id = "cancelar" type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

  $(document).ready(function () {

      // $('#hora_turno').datetimepicker({
      //     format: 'HH:mm',
      //     defaultDate:new Date()
      //     // use24hours: true,
      // });
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
                cel1 = ui.item.cel.split("-")[0];
                cel2 = ui.item.cel.split("-")[1];
            }

            $("#id_integrante").val(ui.item.id);
            $( "#nombre" ).val(ui.item.nombre);
            $( "#tel1" ).val(tel1);
            $( "#tel2" ).val(tel2);
            $( "#cel1" ).val(cel1);
            $( "#cel2" ).val(cel2);
            $( "#dni").val(ui.item.dni);
            $( "#direccion").val(ui.item.direccion);
            $( "#obra_social").val(ui.item.obra_social);
            $( "#observaciones_paciente").val(ui.item.observaciones);

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
  });
</script>
