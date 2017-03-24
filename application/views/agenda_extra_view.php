<form id = "form_agenda_extra" method = "post" class="row form-horizontal">
    <div class="col-sm-4" style = "margin-top:30px">
        <div class="form-group">
            <label class="col-sm-4 control-label">Agenda</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" name = "crear_agenda_id_txt" readonly autocomplete="off">
                <input type="hidden" class="form-control" name = "crear_agenda_id">
                <input type="hidden" class="form-control" name = "crear_id">
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
                  <option value="120">120min</option>
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
                  <div class="col-sm-5" style = "padding:0px">
                    <select name = "crear_agenda_desde_man_hora" class="form-control" />
                      <option value=""></option>
                      <option value="08">08</option>
                      <option value="09">09</option>
                      <option value="10">10</option>
                      <option value="11">11</option>
                      <option value="12">12</option>
                      <option value="13">13</option>
                      <option value="14">14</option>
                      <option value="15">15</option>
                      <option value="16">16</option>
                      <option value="17">17</option>
                      <option value="18">18</option>
                      <option value="19">19</option>
                      <option value="20">20</option>
                    </select>
                  </div>
                  <div class="col-sm-5 col-sm-offset-1" style = "padding:0px">
                    <select name = "crear_agenda_desde_man_min" class="form-control" />
                      <option value=""></option>
                      <option value="00">00</option>
                      <option value="10">10</option>
                      <option value="15">15</option>
                      <option value="20">20</option>
                      <option value="30">30</option>
                      <option value="40">40</option>
                      <option value="45">45</option>
                      <option value="50">50</option>
                    </select>
                  </div>
                </div>

              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">Hasta</label>
                <div class="col-sm-9">
                  <div class="col-sm-5" style = "padding:0px">
                    <select name = "crear_agenda_hasta_man_hora" class="form-control" />
                        <option value=""></option>
                        <option value="08">08</option>
                        <option value="09">09</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                        <option value="16">16</option>
                        <option value="17">17</option>
                        <option value="18">18</option>
                        <option value="19">19</option>
                        <option value="20">20</option>
                    </select>
                  </div>
                  <div class="col-sm-5 col-sm-offset-1" style = "padding:0px">
                    <select name = "crear_agenda_hasta_man_min" class="form-control" />
                        <option value=""></option>
                        <option value="00">00</option>
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="20">20</option>
                        <option value="30">30</option>
                        <option value="40">40</option>
                        <option value="45">45</option>
                        <option value="50">50</option>
                    </select>
                  </div>
                </div>
              </div>

            </div>
            <div class="col-sm-6">
              <div class="col-sm-12" style = "text-align:center;margin-bottom:10px;font-weight:500">
                Turno Tarde
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">Desde</label>
                <div class="col-sm-9">
                  <div class="col-sm-5" style = "padding:0px">
                    <select name = "crear_agenda_desde_tar_hora" class="form-control" />
                      <option value=""></option>
                      <option value="08">08</option>
                      <option value="09">09</option>
                      <option value="10">10</option>
                      <option value="11">11</option>
                      <option value="12">12</option>
                      <option value="13">13</option>
                      <option value="14">14</option>
                      <option value="15">15</option>
                      <option value="16">16</option>
                      <option value="17">17</option>
                      <option value="18">18</option>
                      <option value="19">19</option>
                      <option value="20">20</option>
                    </select>
                  </div>
                  <div class="col-sm-5 col-sm-offset-1" style = "padding:0px">
                    <select name = "crear_agenda_desde_tar_min" class="form-control" />
                      <option value=""></option>
                      <option value="00">00</option>
                      <option value="10">10</option>
                      <option value="20">20</option>
                      <option value="30">30</option>
                      <option value="40">40</option>
                      <option value="50">50</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">Hasta</label>
                  <div class="col-sm-9">
                    <div class="col-sm-5" style = "padding:0px">
                      <select name = "crear_agenda_hasta_tar_hora" class="form-control" />
                        <option value=""></option>
                        <option value="08">08</option>
                        <option value="09">09</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                        <option value="16">16</option>
                        <option value="17">17</option>
                        <option value="18">18</option>
                        <option value="19">19</option>
                        <option value="20">20</option>
                      </select>
                    </div>
                    <div class="col-sm-5 col-sm-offset-1" style = "padding:0px">
                      <select name = "crear_agenda_hasta_tar_min" class="form-control" />
                        <option value=""></option>
                        <option value="00">00</option>
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="30">30</option>
                        <option value="40">40</option>
                        <option value="50">50</option>
                      </select>
                    </div>
                </div>

              </div>
            </div>
        </div>
        <div class="col-sm-12">
          <hr>
          <div class="col-sm-offset-7 col-sm-5">
              <button id = "guardar_btn" onclick="editar_agenda(event)" style = "visibility:hidden" class="btn btn-default">Guardar</button>
              <button id = "cancelar_btn" style = "visibility:hidden" class="btn btn-default" data-dismiss="modal">Cancelar</button>
              <button id = "crear_btn" onclick="crear_agenda(event)" class="btn btn-default">Crear</button>
          </div>
        </div>
    </form>
