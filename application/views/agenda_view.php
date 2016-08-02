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
        background-color: transparent;;
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
      border-radius: 2px;
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

    .panel-default .panel-heading {
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
                <div class = "col-md-7 col-xs-12">
                  <div class = "col-md-7 col-xs-5 display_date" style = "padding:0px;padding-top:10px;font-size:18px;font-weight:400;text-align:center;margin-bottom:20px"></div>
                  <div class = "col-md-5 col-xs-7" style = "padding:0px;padding-top:5px;text-align:center">
                      <input type="hidden" id = "usuario" name="usuario" value="<?php echo $usuario ?>">
                      <input type="hidden" id = "is_admin" name="is_admin" value="<?php echo $is_admin ?>">
                      <div class="btn-group" role="group">
                          <a href="#" type="button" class="btn btn-default glyphicon glyphicon-chevron-left" onclick="return dia_anterior();"></a>
                          <a href="#" type="button" class="btn btn-default glyphicon glyphicon-calendar" onclick="return dia_actual();"></a>
                          <a href="#" type="button" class="btn btn-default glyphicon glyphicon-chevron-right" onclick="return dia_siguiente()";></a>
                      </div>
                  </div>
                  <div class = "col-md-7 col-xs-12"></div>
                </div>
                <div class = "col-md-5 col-xs-12">
                    <div class = "col-md-5 col-xs-5" style = "padding-top:15px;">
                        <label class = "label-control">Especialidad</label>
                    </div>
                    <div class = "col-md-7 col-xs-7" style = "padding-top:7px;">
                        <select class = "form-control" id = "especialidades">
                          <?php
                              if (count($especialidades) > 1)
                                echo '<option value = "todos">Todas</option>';
                              foreach ($especialidades as $key => $value) {
                                echo '<option value = "'.$value.'">'.$value.'</option>';
                              }
                          ?>
                        </select>
                    </div>

                    <div class = "col-md-5 col-xs-5" style = "padding-top:20px;">
                        <label class = "label-control">Agenda</label>
                    </div>
                    <div class = "col-md-7 col-xs-7" style = "padding-top:15px;">
                        <select class = "form-control" id = "agendas">
                            <?php
                                if ($especialista_sel == "todos")
                                    echo '<option value = "todos">Todos</option>';

                                foreach ($agendas as $key => $value) {
                                  echo '<option value = "'.$value->id_agenda.'">'.$value->nombre_agenda.'</option>';
                                }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- <div class = "panel-body" style = "padding:0px;overflow:inherit;margin-bottom:30px"> -->
        <!-- <div style = "padding:0px;overflow:inherit;margin-bottom:30px"> -->

            <div class="horarios">
              <!-- ACA VAN TODOS LOS TURNOS Y HORARIOS -->
            </div>

            <div class = "panel panel-default abrir_agenda" style = "display:none;padding:10px">
              <?php
                echo $agenda_extra;
                // if ($is_admin) {
                //   echo '<h3>Crear Agenda</h3>';
                //   echo '<hr>';
                //   echo $agenda_extra;
                // }
                // else {
                //   echo '<div class = "text-muted" style = "font-size:30px;text-align:center;height:150px;padding:50px"><i>No hay agenda abierta para este día</i></div>';
                // }
            //   ?>

            </div>

        <!-- </div> -->

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
