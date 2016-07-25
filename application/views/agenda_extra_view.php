<!-- <h3>Crear Agenda</h3>
<hr> -->
<form method = "post" class="row form-horizontal">
    <div class="col-sm-4" style = "margin-top:30px">
        <div class="form-group">
            <label class="col-sm-4 control-label">Agenda</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" name = "crear_agenda_id_txt" readonly autocomplete="off">
                <input type="hidden" class="form-control" name = "crear_agenda_id">
                <input type="hidden" class="form-control" name = "crear_agenda_fecha">
            </div>
        </div>
        <div class="form-group">
          <label class="col-md-4 control-label">Duración</label>
          <div class="col-md-8">
              <select class="form-control" id = "crear_agenda_duracion" name = "crear_agenda_duracion" required>
                  <option value="30">30min</option>
                  <option value="40">40min</option>
                  <option value="60">60min</option>
                  <option value="90">90min</option>
              </select>
          </div>
        </div>
      </div>
      <div class="col-sm-8">
            <div class="col-sm-6" style = "margin-bottom:15px">
              <div class="col-sm-12" style = "text-align:center;margin-bottom:10px;font-weight:500">
                Turno Mañana
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">Desde</label>
                <div class="col-sm-9">
                  <div class='input-group date' id='desde_man'>
                    <input type='text' name = "desde_man" class="form-control" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-time"></span>
                    </span>
                  </div>
                </div>
                <!-- <div class="col-sm-9">
                    <input type="time" class="form-control" name = "crear_agenda_hora_desde_man" required>
                </div> -->
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">Hasta</label>
                <div class="col-sm-9">
                  <div class='input-group date' id='hasta_man'>
                    <input type='text' name = "hasta_man" class="form-control" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-time"></span>
                    </span>
                  </div>
                </div>
                <!-- <div class="col-sm-9">
                    <input type="time" class="form-control" name = "crear_agenda_hora_hasta_man" required=>
                </div> -->
              </div>
            </div>
            <div class="col-sm-6">
              <div class="col-sm-12" style = "text-align:center;margin-bottom:10px;font-weight:500">
                Turno Tarde
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">Desde</label>
                <div class="col-sm-9">
                  <div class='input-group date' id='desde_tar'>
                    <input type='text' name = "desde_tar" class="form-control" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-time"></span>
                    </span>
                  </div>
                </div>
                <!-- <div class="col-sm-4">
                    <input type="time" class="form-control" name = "crear_agenda_hora_desde_tar" required>
                </div> -->
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">Hasta</label>
                <div class="col-sm-9">
                  <div class='input-group date' id='hasta_tar'>
                    <input type='text' name = "hasta_tar" class="form-control" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-time"></span>
                    </span>
                  </div>
                </div>
                <!-- <div class="col-sm-9">
                    <input type="time" class="form-control" name = "crear_agenda_hora_hasta_tar" required=>
                </div> -->
              </div>
            </div>
        </div>
        <div class="col-sm-12">
          <hr>
          <div class="col-sm-offset-7 col-sm-5">
              <button id = "aceptar_btn" onclick="crear_agenda(event)" style = "visibility:hidden" class="btn btn-default">Aceptar</button>
              <button id = "cancelar_btn" style = "visibility:hidden" class="btn btn-default" data-dismiss="modal">Cancelar</button>
              <button id = "crear_btn" onclick="crear_agenda(event)" class="btn btn-default">Crear</button>
          </div>
        </div>
    </form>
    <script type="text/javascript">
        $(function () {
            $('#desde_man').datetimepicker({
                format: 'HH:mm',
                // defaultDate:new Date()
                // use24hours: true,
            });

            $('#hasta_man').datetimepicker({
                format: 'HH:mm',
                // defaultDate:new Date()
                // use24hours: true,
            });

            $('#desde_tar').datetimepicker({
                format: 'HH:mm',
                // defaultDate:new Date()
                // use24hours: true,
            });

            $('#hasta_tar').datetimepicker({
                format: 'HH:mm',
                // defaultDate:new Date()
                // use24hours: true,
            });
        });
    </script>
