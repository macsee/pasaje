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
<div id="modal_agenda" class="modal fade in" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Datos Agenda</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="post">
                    <div class="form-group">
                        <label class="col-md-2 control-label">Nombre Agenda</label>
                        <div class="col-md-4">
                            <input style = "text-transform:none" type="text"    class="form-control" id = "agenda_nombre"  name = "agenda_nombre" required autocomplete="off">
                            <input style = "text-transform:none" type="hidden"  class="form-control" id = "agenda_id"  name = "agenda_id">
                        </div>
                        <label class="col-md-2 control-label">Usuario</label>
                        <div class="col-md-4">
                            <select class="form-control" id = "agenda_usuario" name="agenda_usuario" required>
                                <?php
                                    foreach ($especialistas as $key => $value) {
                                        echo '<option value="'.$value->usuario.'">'.$value->nombre.'</option>';
                                    }
                                 ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Especialidades</label>
                        <div class="col-md-4 especialidades">
                            <input id = "agenda_especialidades" style = "margin-top:10px" type="text" class="form-control" name = "agenda_especialidades[]" autocomplete="off" required>
                        </div>
                        <label class="col-md-2 control-label">Duración</label>
                        <div class="col-md-3">
                            <select class="form-control" id = "agenda_duracion" name = "agenda_duracion" required>
                                <option value="30">30min</option>
                                <option value="40">40min</option>
                                <option value="60">60min</option>
                                <option value="90">90min</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Días</label>
                        <div class="col-md-8" style = "margin-top:30px">
                            <table class = "table" style = "margin-bottom:-30px">
                                <thead>
                                    <tr>
                                        <!-- <th rowspan="2"></th> -->
                                        <th colspan="2" scope="colgroup" style="text-align:center">Dia</th>
                                        <th colspan="2" scope="colgroup" style="text-align:center" class="tabla">Turno Mañana</th>
                                        <th colspan="2" scope="colgroup" style="text-align:center" class="tabla">Turno Tarde</th>
                                    </tr>
                                    <tr>
                                        <th colspan="2"></th>
                                        <th scope="col" style="text-align:center" class="tabla">Desde</th>
                                        <th scope="col" style="text-align:center" class="tabla">Hasta</th>
                                        <th scope="col" style="text-align:center" class="tabla">Desde</th>
                                        <th scope="col" style="text-align:center" class="tabla">Hasta</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <!-- <th scope="row">Lu</th> -->
                                        <td>Lu</td>
                                        <td>
                                            <input type="checkbox" name = "agenda_dias[]" id = "lu" value = "lu">
                                        </td>
                                        <td class="tabla">
                                            <input type="time" class="form-control" id = "lu_desde_man" name = "lu_desde_man">
                                        </td>
                                        <td class="tabla">
                                            <input type="time" class="form-control" id = "lu_hasta_man" name = "lu_hasta_man">
                                        </td>
                                        <td class="tabla">
                                            <input type="time" class="form-control" id = "lu_desde_tar" name = "lu_desde_tar">
                                        </td>
                                        <td class="tabla">
                                            <input type="time" class="form-control" id = "lu_hasta_tar" name = "lu_hasta_tar">
                                        </td>
                                    </tr>
                                    <tr>
                                        <!-- <th scope="row">Ma</th> -->
                                        <td>Ma</td>
                                        <td>
                                            <input type="checkbox" name = "agenda_dias[]" id = "ma" value = "ma">
                                        </td>
                                        <td class="tabla">
                                            <input type="time" class="form-control" id = "ma_desde_man" name = "ma_desde_man">
                                        </td>
                                        <td class="tabla">
                                            <input type="time" class="form-control" id = "ma_hasta_man" name = "ma_hasta_man">
                                        </td>
                                        <td class="tabla">
                                            <input type="time" class="form-control" id = "ma_desde_tar" name = "ma_desde_tar">
                                        </td>
                                        <td class="tabla">
                                            <input type="time" class="form-control" id = "ma_hasta_tar" name = "ma_hasta_tar">
                                        </td>
                                    </tr>
                                    <tr>
                                        <!-- <th scope="row">Mi</th> -->
                                        <td>Mi</td>
                                        <td>
                                            <input type="checkbox" name = "agenda_dias[]" id = "mi" value = "mi">
                                        </td>
                                        <td class="tabla">
                                            <input type="time" class="form-control" id = "mi_desde_man" name = "mi_desde_man">
                                        </td>
                                        <td class="tabla">
                                            <input type="time" class="form-control" id = "mi_hasta_man" name = "mi_hasta_man">
                                        </td>
                                        <td class="tabla">
                                            <input type="time" class="form-control" id = "mi_desde_tar" name = "mi_desde_tar">
                                        </td>
                                        <td class="tabla">
                                            <input type="time" class="form-control" id = "mi_hasta_tar" name = "mi_hasta_tar">
                                        </td>
                                    </tr>
                                    <tr>
                                        <!-- <th scope="row">Mi</th> -->
                                        <td>Ju</td>
                                        <td>
                                            <input type="checkbox" name = "agenda_dias[]" id = "ju" value = "ju">
                                        </td>
                                        <td class="tabla">
                                            <input type="time" class="form-control" id = "ju_desde_man" name = "ju_desde_man">
                                        </td>
                                        <td class="tabla">
                                            <input type="time" class="form-control" id = "ju_hasta_man" name = "ju_hasta_man">
                                        </td>
                                        <td class="tabla">
                                            <input type="time" class="form-control" id = "ju_desde_tar" name = "ju_desde_tar">
                                        </td>
                                        <td class="tabla">
                                            <input type="time" class="form-control" id = "ju_hasta_tar" name = "ju_hasta_tar">
                                        </td>
                                    </tr>
                                    <tr>
                                        <!-- <th scope="row">Mi</th> -->
                                        <td>Vi</td>
                                        <td>
                                            <input type="checkbox" name = "agenda_dias[]" id = "vi" value = "vi">
                                        </td>
                                        <td class="tabla">
                                            <input type="time" class="form-control" id = "vi_desde_man" name = "vi_desde_man">
                                        </td>
                                        <td class="tabla">
                                            <input type="time" class="form-control" id = "vi_hasta_man" name = "vi_hasta_man">
                                        </td>
                                        <td class="tabla">
                                            <input type="time" class="form-control" id = "vi_desde_tar" name = "vi_desde_tar">
                                        </td>
                                        <td class="tabla">
                                            <input type="time" class="form-control" id = "vi_hasta_tar" name = "vi_hasta_tar">
                                        </td>
                                    </tr>
                                    <tr>
                                        <!-- <th scope="row">Mi</th> -->
                                        <td>Sa</td>
                                        <td>
                                            <input type="checkbox" name = "agenda_dias[]" id = "sa" value = "sa">
                                        </td>
                                        <td class="tabla">
                                            <input type="time" class="form-control" id = "sa_desde_man" name = "sa_desde_man">
                                        </td>
                                        <td class="tabla">
                                            <input type="time" class="form-control" id = "sa_hasta_man" name = "sa_hasta_man">
                                        </td>
                                        <td class="tabla">
                                            <input type="time" class="form-control" id = "sa_desde_tar" name = "sa_desde_tar">
                                        </td>
                                        <td class="tabla">
                                            <input type="time" class="form-control" id = "sa_hasta_tar" name = "sa_hasta_tar">
                                        </td>
                                    </tr>
                                </tbody>

                            </table>
                        </div>
                    </div>
                    <div class="form-group">

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id = "guardar_usuario" onclick="guardar_datos_agenda(event)"class="btn btn-default">Guardar</button>
                <button id = "cancelar" type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    // var ESPECIALIDADES = [];
    $(document).ready( function () {
        $.ajax({
            url: base_url+"/main/get_especialidades_json/todos",
            dataType: 'json',
            success:function(response)
            {
                data = [];
                if (response != null) {
                    $.each( response, function(key, value) {
                        data.push(value);
                    });
                }

                $('#agenda_especialidades').tagEditor({
                    autocomplete: {
                        delay: 0, // show suggestions immediately
                        position: { collision: 'flip' }, // automatic menu position up/down
                        source: data
                    },
                    forceLowercase: false
                });
            }
        });

    });
</script>
