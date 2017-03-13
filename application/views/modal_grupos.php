<style media="screen">

    .tag-editor {
        border: 1px solid #ccc;
        border-radius: 4px;
        min-height: 34px;
    }

    .tabla {
        /*border-left: 1px solid #ddd;*/
    }

</style>
<div id="modal_grupos" class="modal fade in" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Datos Grupo</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="post">
                    <div class="form-group">
                        <label class="col-md-2 control-label">Usuario</label>
                        <div class="col-md-3">
                            <input class="form-control" type="hidden" id = "agenda_id_grupo" name="agenda_id_grupo">
                            <select class="form-control" id = "agenda_usuario" name="agenda_usuario" required>
                            </select>
                        </div>
                        <label class="col-md-1 col-md-offset-2 control-label">Tipo</label>
                        <div class="col-md-3 especialidades">
                            <select class="form-control" id = "agenda_tipo" name="agenda_tipo" required>
                                <?php
                                    echo '<option value = "todos">Todos</option>';
                                    foreach ($tipos as $key => $value) {
                                        echo '<option value = "'.$value->id.'">'.ucfirst($value->nombre).'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Dia</label>
                        <div class="col-md-2" style = "">
                            <select class="form-control" id = "agenda_dia" name="agenda_dia" required>
                                <option value="Lu">Lu</option>
                                <option value="Ma">Ma</option>
                                <option value="Mi">Mi</option>
                                <option value="Ju">Ju</option>
                                <option value="Vi">Vi</option>
                                <option value="Sa">Sa</option>
                            </select>
                        </div>
                        <label class="col-md-1 control-label">Desde</label>
                        <div class="col-md-3" style = "">
                            <input class="form-control" type="time" id = "agenda_horario_desde" name="agenda_horario_desde" value="">
                            <span class="glyphicon glyphicon-remove form-control-feedback error" style="right:10px;visibilityle:hidden"></span>
                        </div>
                        <label class="col-md-1 control-label">Hasta</label>
                        <div class="col-md-3" style = "">
                            <input class="form-control" type="time" id = "agenda_horario_hasta" name="agenda_horario_hasta" value="">
                            <span class="glyphicon glyphicon-remove form-control-feedback error" style="right:10px;visibilityle:hidden"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Cant. Personas</label>
                        <div class="col-md-2" style = "">
                            <input class="form-control" type="text" id = "agenda_personas" name="agenda_personas" value="6">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id = "guardar_usuario" onclick="guardar_datos_grupo(event)"class="btn btn-default">Guardar</button>
                <button id = "cancelar" type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    // var ESPECIALIDADES = [];
    // $(document).ready( function () {
    //     $.ajax({
    //         url: base_url+"/main/get_especialidades_json/todos",
    //         dataType: 'json',
    //         success:function(response)
    //         {
    //             data = [];
    //             if (response != null) {
    //                 $.each( response, function(key, value) {
    //                     data.push(value);
    //                 });
    //             }
    //
    //             $('#agenda_especialidades').tagEditor({
    //                 autocomplete: {
    //                     delay: 0, // show suggestions immediately
    //                     position: { collision: 'flip' }, // automatic menu position up/down
    //                     source: data
    //                 },
    //                 forceLowercase: false
    //             });
    //         }
    //     });
    //
    // });
</script>
