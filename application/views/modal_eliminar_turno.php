<div id="modal_eliminar_turno" class="modal fade in" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-xs" style = "width:200px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 style = "text-align: center" class="modal-title">¿Eliminar Turno?</h4>
            </div>
            <form id = "del_turno_form" method="post">
                <input type="hidden" name="id_turno">
            </form>
            <div class="modal-footer">
                <button id = "ok_eliminar_turno" onclick="ok_eliminar_turno(event)"class="btn btn-default">Aceptar</button>
                <button id = "cancelar" type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
