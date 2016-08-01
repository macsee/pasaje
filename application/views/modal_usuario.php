<div id="modal_usuario" class="modal fade in" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Datos Usuario</h4>
            </div>
            <div class="modal-body" style="text-align:center">

                <form class="form-horizontal" method="post">
                    <div class="form-group">
                        <label class="col-md-2 control-label">Apellido</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" id = "usr_apellido" name = "usr_apellido" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Nombre</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" id = "usr_nombre" name = "usr_nombre" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Usuario</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" id = "usr_usuario" name = "usr_usuario" style = "text-transform:none" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Funciones</label>
                        <div class="col-md-10">
                            <div class="form-group">
                                <label class="col-md-3 col-xs-4 control-label opciones">Especialista</label>
                                <div class="col-md-1 col-xs-1 checkbox">
                                    <input type="checkbox" id = "chk_esp" name = "usr_funciones[]" value = "especialista">
                                </div>
                                <label class="col-md-3 col-xs-4 control-label opciones">Facturacion</label>
                                <div class="col-md-1 col-xs-1 checkbox">
                                    <input type="checkbox" id = "chk_fac" name = "usr_funciones[]" value = "facturacion">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-4 control-label opciones">Turnos</label>
                                <div class="col-md-1 col-xs-1 checkbox">
                                    <input type="checkbox" id = "chk_tur" name = "usr_funciones[]" value = "turnos">
                                </div>
                                <label class="col-md-3 col-xs-4 control-label opciones">Pacientes</label>
                                <div class="col-md-1 col-xs-1 checkbox">
                                    <input type="checkbox" id = "chk_pac" name = "usr_funciones[]" value = "pacientes">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-4 control-label opciones">Admin</label>
                                <div class="col-md-1 col-xs-1 checkbox">
                                    <input type="checkbox" id = "chk_adm" name = "usr_funciones[]" value = "admin">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
            <div class="modal-footer">
                <button id = "guardar_usuario" onclick="guardar_datos_usuario(event)"class="btn btn-default">Guardar</button>
                <button id = "cancelar" type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
