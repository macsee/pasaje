<div id="modal_cambiar_turno" class="modal fade in" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-xs" style = "width:300px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 style = "text-align: center" class="modal-title">¿Cambiar Turno?</h4>
            </div>
            <div class="modal-header">
                <div class="row">
                    <div class="col-md-12" id = "nombre" style = "text-align:center">

                    </div>
                </div>
                <div class="row" style = "margin-top:10px">
                    <div class="col-md-12" style = "text-align:center">
                        para
                    </div>
                </div>
                <div class="row" style = "margin-top:10px">
                    <div class="col-md-12" id = "fecha_hora" style = "text-align:center">

                    </div>
                </div>
                <form method="post">
                    <input type="hidden" name="id_turno">
                    <input type="hidden" name="fecha">
                    <input type="hidden" name="hora">
                </form>
            </div>
            <div class="modal-footer">
                <button onclick="ok_cambiar_turno(event)"class="btn btn-default">Aceptar</button>
                <button onclick="anular_accion(event)"class="btn btn-default">Anular</button>
                <button id = "cancelar" type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
