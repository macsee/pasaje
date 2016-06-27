<style media="screen">
    .turno_vacio, .turno_cambio, .turno_prox{
        cursor: pointer;
    }

    .calendario {
        height: 250px;
        width: 250px;
        border-style: none;
    }

    .label-danger {
        font-size: 65%;
        border-radius: 3.25em;
    }

    .notas_body {
        height: 220px;
        overflow: auto;
    }

    .notas_body a {
        cursor: pointer;
    }

    .row_vacia {
        cursor: pointer;
    }

    .celda_vacia {
        background-color: #EAEAEA;
    }

    .celda_low {
        background-color: rgba(69, 183, 11, 0.70)!important;
    }

    .celda_med_low {
        background-color: rgba(11, 199, 218, 0.70)!important;
    }

    .celda_medium {
        background-color: rgba(215, 218, 11, 0.70)!important;
    }

    .celda_high {
        background-color: rgba(218, 135, 11, 0.70)!important;
    }

    .celda_full {
        background-color: rgba(218, 11, 11, 0.70)!important;
    }

    .datepicker table tr td.today {
        /*background-color: #99D0FF;
        border-color: #99D0FF;*/
        font-weight: 700;
        font-size: 20px;
    }

    .datepicker table tr td.today:hover {
        /*background-color: #99D0FF;
        border-color: #99D0FF;*/
    }

    .datepicker table tr td.highlighted {
        background-color: #EAEAEA;
    }

    .datepicker table tr td.today {
        background-color: transparent;
    }

    .fila-turno {
        border: 1px solid #e8e8e5;
        background-color: #f9f9f9;
        border-radius: 4px;
        margin: 0px 0px 10px 0px;
    }

    .cell_turno {
        font-size: 14px;
        padding-left:5px;
        padding-top: 8px;
        border-right:1px solid #e8e8e5;
        min-height: 50px;
        /*overflow: hidden;*/
    }

    .cell_vacia {
      font-size: 14px;
      padding-left:5px;
      padding-top: 10px;
      min-height: 40px;
      overflow: hidden;
    }

    .cell_header {
      font-size: 15px;
      padding-left:5px;
      padding-top: 10px;
      /*border-right:1px solid #e8e8e5;*/
      overflow: hidden;
      color: white;
      font-weight: 400;
    }

    .datepicker-inline {
      margin: auto;
    }

    .cabecera {
      margin: 0px 0px 10px 0px;
      height: 40px;
      background-color: #454545; //#3f7ec1
      color: white;
      font-size: 16px;
      font-weight: 400;
      font-size: 15px;
    }

    @media (max-width: 990px) {
        .fix_on_xs {
          border-right: none;
          border-bottom: 1px solid #e8e8e5;
          min-height: 50px;
          font-size: 20px;
          /*background-color: #f8f8f8;*/
        }
    }

    .panel-default > .panel-heading {
        background-color: #454545;
        color: white;
        font-weight: 400;
    }

    .navbar-default {
      background-color: white;
    }

    .separador {
      background-color: transparent;
      border-color: transparent;
    }

</style>
<div class="container-fluid">
    <div class="col-md-7 col-md-offset-1">
        <div class = "panel panel-default">
            <div class = "panel-body">
                <div class = "col-md-4 col-xs-5 display_date" style = "padding:0px;padding-top:30px;font-size:18px;font-weight:400;text-align:center;margin-bottom:20px">
                    <?php //echo $display_date;?>
                </div>
                <div class = "col-md-3 col-xs-7" style = "padding:0px;padding-top:22px;text-align:center">
                    <input type="hidden" id = "usuario" name="usuario" value="<?php echo $usuario ?>">
                    <input type="hidden" id = "is_admin" name="is_admin" value="<?php echo $is_admin ?>">
                    <div class="btn-group" role="group">
                        <a href="#" type="button" class="btn btn-default glyphicon glyphicon-chevron-left" onclick="return dia_anterior();"></a>
                        <a href="#" type="button" class="btn btn-default glyphicon glyphicon-calendar" onclick="return dia_actual();"></a>
                        <a href="#" type="button" class="btn btn-default glyphicon glyphicon-chevron-right" onclick="return dia_siguiente()";></a>
                    </div>
                </div>
                <div class = "col-md-5 col-xs-12">
                    <div class = "col-md-5 col-xs-5" style = "padding-top:15px;">
                        <label class = "label-control">Especialidad</label>
                    </div>
                    <div class = "col-md-7 col-xs-7" style = "padding-top:7px;">
                        <select class = "form-control" id = "especialidad">
                          <option>Todas</option>
                        </select>
                    </div>

                    <div class = "col-md-5 col-xs-5" style = "padding-top:20px;">
                        <label class = "label-control">Especialista</label>
                    </div>
                    <div class = "col-md-7 col-xs-7" style = "padding-top:15px;">
                        <select class = "form-control" id = "especialistas">
                            <?php
                                if ($especialistas != null) {
                                    echo '<option value = "todos">Todos</option>';
                                    foreach ($especialistas as $key => $value) {
                                        if ($especialista_sel == $value->usuario)
                                            $selected = "selected";
                                        else
                                            $selected = "";

                                        echo '<option '.$selected.' value = "'.$value->usuario.'">'.$value->apellido.', '.$value->nombre[0].'</option>';
                                    }
                                }
                                else
                                    echo '<option selected value = "'.$especialista_sel.'">'.$nom_especialista_sel.'</option>';
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class = "panel-body" style = "padding:0px;overflow:inherit;margin-bottom:30px">

            <div class="row cabecera hidden-xs hidden-sm">
                <div class="col-md-2 cell_header">
                    Hora
                </div>
                <div class="col-md-4 cell_header">
                    Paciente
                </div>
                <div class="col-md-3 cell_header">
                    Especialidad
                </div>
                <div class="col-md-2 cell_header">
                    Especialista
                </div>
                <div class="col-md-1 cell_header">

                </div>
            </div>

            <div class="horarios">
              <!-- ACA VA TODO EL CONTENIDO -->
            </div>

                <div class = "container-fluid abrir_agenda" style = "display:none">
                <?php if ($is_admin) { ?>
                    <h3>Crear Agenda</h3>
                    <hr>
                    <form method = "post" class="row form-horizontal">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Especialista</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" name = "crear_agenda_especialistas_txt" readonly required autocomplete="off">
                                    <input type="hidden" class="form-control" name = "crear_agenda_especialistas">
                                    <input type="hidden" class="form-control" name = "crear_agenda_fecha">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-1 control-label">Desde</label>
                                <div class="col-sm-2">
                                    <input type="time" class="form-control" name = "crear_agenda_hora_desde" required>
                                </div>
                                <label class="col-sm-1 control-label">Hasta</label>
                                <div class="col-sm-2">
                                    <input type="time" class="form-control" name = "crear_agenda_hora_hasta" required=>
                                </div>
                                <label class="col-md-1 control-label">Duración</label>
                                <div class="col-md-2">
                                    <select class="form-control" id = "crear_agenda_duracion" name = "crear_agenda_duracion" required>
                                        <option value="30">30min</option>
                                        <option value="40">40min</option>
                                        <option value="60">60min</option>
                                        <option value="90">90min</option>
                                    </select>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <div class="col-sm-offset-10 col-sm-2">
                                    <button onclick="crear_agenda(event)" class="btn btn-default">Aceptar</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <?php }
                    else {
                        echo '<h3>No hay agenda abierta para este día.</h3>';
                    }?>
                </div>
        </div>

    </div>
    <div class="col-md-3">
        <div class="panel panel-default" style = "height:280px">
            <div class="panel-heading">
                <div class = "row">
                    <div class="col-md-6 col-xs-6">
                        <i class = "glyphicon glyphicon-bell"></i> Notas
                    </div>
                    <div class="col-md-1 col-md-offset-4 col-xs-1 col-xs-offset-4" style = "font-size:18px;color:black">
                        <a style = "color:white" href="#" onclick = "return add_notas()"><span class = "glyphicon glyphicon-plus-sign"></span></a>
                    </div>
                </div>
            </div>
            <div class = "panel-body notas_body">
            </div>
        </div>

        <div class="panel panel-default" style = "height:320px">
            <div class="panel-heading" style = "font-weight:700px">
                <i class = "glyphicon glyphicon-calendar"></i> Calendario
            </div>
            <div class = "panel-body">
                <div></div>
                <div id="datepicker"></div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url('libs/agenda_helper.js')?>"></script>
<script type="text/javascript">

$(document).ready(function () {
    //crear_calendario(agenda,turnos,bloqueados);
    // dia_actual();
    // get_turnos_mes();
    init();
});

</script>
