<div id="modal_notas" class="modal fade in" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-xs">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 style = "text-align: center" class="modal-title">Notas</h4>
            </div>
            <div class="modal-body">
                <div class = "container-fluid">
                    <form method="post" class="row form-horizontal">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <label class="col-sm-3 control-label">Compartir con: </label>
                                <div class="col-sm-4">
                                  <?php if ($usuarios != null) { ?>
                                    <select class="form-control" name = "destinatario_sel">
                                        <?php
                                            echo '<option selected value = "todos">Todos</option>';
                                            foreach ($usuarios as $key => $value) {
                                                //
                                                // if ($value->usuario == $usuario)
                                                //   $selected = "selected";
                                                // else
                                                //   $selected = "";

                                                echo '<option value = "'.$value->usuario.'">'.$value->apellido.', '.$value->nombre[0].'</option>';
                                            }
                                        ?>
                                    </select>
                                    <?php } ?>
                                </div>
                            </div>
                            <input type="hidden" name="destinatario">
                            <input type="hidden" name="fecha">
                            <input type="hidden" name="id_nota">
                            <input type="hidden" name="usuario" val = "<?php echo $usuario?>">
                            <input type="hidden" name="is_admin" val = "<?php echo $is_admin?>">
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <textarea class = "form-control" name="texto" rows="8" cols="40"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button id = "eliminar_nota" style = "visibility:hidden" onclick="eliminar_nota(event)"class="btn btn-default">Eliminar</button>
                <button onclick="ok_am_nota(event)" class="btn btn-default">Guardar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
