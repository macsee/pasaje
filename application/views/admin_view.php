<style media="screen">
    .control-label {
        text-align: left!important;
    }

    .opciones {
        font-weight: 300!important;
    }

    .sidebar {
        position: fixed;
        background-color: #f5f5f5;
        border-right: 1px solid #eee;
    }

    .nav-sidebar {
        margin-left: -15px;
        margin-right: -15px;
    }

    input {
        text-transform: capitalize;
    }

    .sel_user {
        cursor: pointer;
    }

    .sel_especialista {
        cursor: pointer;
    }

</style>

<div class="container-fluid">
    <div class="col-md-2 sidebar" style = "width:15%">
        <ul class="nav nav-sidebar">
            <li><a href="#usuarios">Usuarios</a></li>
            <li><a href="#especialistas">Especialistas</a></li>
          </ul>
    </div>
    <div class = "col-md-10 col-md-offset-2">
        <div id = "usuarios" class="row" style = "height:700px">
            <h3 class="page-header">Usuarios</h3>
            <div class="col-md-7">
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
                    <div class="col-md-6 col-md-offset-6" style = "margin-bottom:20px">
                        <hr>
                        <button id = "reset" type="submit" class="btn btn-warning" formaction="<?php echo base_url('index.php/main/rst_usuario')?>">Resetear</button>
                        <button id = "eliminar" type="submit" class="btn btn-danger" formaction="<?php echo base_url('index.php/main/del_usuario')?>">Eliminar</button>
                        <button id = "aceptar" type="submit" class="btn btn-success" formaction="<?php echo base_url('index.php/main/add_usuario')?>">Guardar</button>
                    </div>
                </form>
            </div>
            <div class="col-md-5">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Usuarios Registrados
                    </div>
                    <div class="panel-body" style = "height: 450px;overflow-y: scroll;">
                        <table class = "table">
                            <thead>
                                <th>
                                    Nombre
                                </th>
                                <th>
                                    Usuario
                                </th>
                            </thead>
                            <tbody>
                                <?php
                                    if (isset($usuarios))
                                        foreach ($usuarios as $key => $value) {
                                            echo '<tr id = "'.$value->usuario.'" class = "sel_user">';
                                                echo '<td>'.$value->apellido.", ".$value->nombre.'</td>';
                                                echo '<td>'.$value->usuario.'</td>';
                                            echo '</tr>';
                                        }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> <!--row-->
        <div id = "especialistas" class="row">
            <h3 class="page-header">Especialistas</h3>
            <div class="col-md-7">
                <form class="form-horizontal" method="post">
                    <div class="form-group">
                        <!-- <div class="col-md-6" style ="padding-left:0px">
                            <label class="col-md-2 control-label">Especialista</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id = "esp_especialista" name = "esp_especialista" readonly>
                            </div>
                        </div>
                        <div class="col-md-5"> -->
                            <label class="col-md-2 control-label">Usuario</label>
                            <div class="col-md-4">
                                <input style = "text-transform:none" type="text" class="form-control" id = "esp_usuario" name = "esp_usuario" readonly required>
                            </div>
                            <div class ="col-md-3">
                                <button id = "nuevo" class="btn btn-default">Limpiar</button>
                            </div>
                        <!-- </div> -->
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Especialidad</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id = "esp_especialidad" name = "esp_especialidad" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Días</label>
                        <div class="col-md-10">
                            <div class="form-group">
                                <label class="col-md-2 control-label opciones">Lu</label>
                                <div class="col-md-1 checkbox">
                                     <input type="checkbox" name = "esp_dias[]" id = "lu" value = "lu">
                                </div>
                                <div class="col-md-3">
                                    Desde <input type="time" class="form-control" id = "lu_desde" name = "lu_desde">
                                </div>
                                <div class="col-md-3">
                                    Hasta <input type="time" class="form-control" id = "lu_hasta" name = "lu_hasta">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label opciones">Ma</label>
                                <div class="col-md-1 checkbox">
                                     <input type="checkbox" name = "esp_dias[]" id = "ma" value = "ma">
                                </div>
                                <div class="col-md-3">
                                    Desde <input type="time" class="form-control" id = "ma_desde" name = "ma_desde">
                                </div>
                                <div class="col-md-3">
                                    Hasta <input type="time" class="form-control" id = "ma_hasta" name = "ma_hasta">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label opciones">Mi</label>
                                <div class="col-md-1 checkbox">
                                     <input type="checkbox" name = "esp_dias[]" id = "mi" value = "mi">
                                </div>
                                <div class="col-md-3">
                                    Desde <input type="time" class="form-control" id = "mi_desde" name = "mi_desde">
                                </div>
                                <div class="col-md-3">
                                    Hasta <input type="time" class="form-control" id = "mi_hasta" name = "mi_hasta">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label opciones">Ju</label>
                                <div class="col-md-1 checkbox">
                                     <input type="checkbox" name = "esp_dias[]" id = "ju" value = "ju">
                                </div>
                                <div class="col-md-3">
                                    Desde <input type="time" class="form-control" id = "ju_desde" name = "ju_desde">
                                </div>
                                <div class="col-md-3">
                                    Hasta <input type="time" class="form-control" id = "ju_hasta" name = "ju_hasta">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label opciones">Vi</label>
                                <div class="col-md-1 checkbox">
                                     <input type="checkbox" name = "esp_dias[]" id = "vi" value = "vi">
                                </div>
                                <div class="col-md-3">
                                    Desde <input type="time" class="form-control" id = "vi_desde" name = "vi_desde">
                                </div>
                                <div class="col-md-3">
                                    Hasta <input type="time" class="form-control" id = "vi_hasta" name = "vi_hasta">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Duración</label>
                        <div class="col-md-3">
                            <select class="form-control" id = "esp_duracion" name = "duracion" required>
                                <option value="30">30min</option>
                                <option value="40">40min</option>
                                <option value="60">60min</option>
                                <option value="90">90min</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 col-md-offset-10" style = "margin-bottom:20px">
                        <hr>
                        <button id = "aceptar" type="submit" class="btn btn-success" formaction="<?php echo base_url('index.php/main/add_especialidad')?>">Guardar</button>
                    </div>
                </form>
            </div>
            <div class="col-md-5">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Especialidades y Especialistas Registrados
                    </div>
                    <div class="panel-body" style = "height: 450px;overflow-y: scroll;">
                        <table class = "table">
                            <thead>
                                <th>
                                    Usuario
                                </th>
                                <th>
                                    Especialidades
                                </th>
                            </thead>
                            <tbody>
                                <?php
                                    if (isset($especialistas)) {
                                        $last = "";
                                        foreach ($especialistas as $key => $value) {
                                            echo '<tr class = "sel_especialista" id = "'.$value->usuario.'">';
                                                if ($last != $value->usuario) {
                                                    echo '<td>'.$value->usuario.'</td>';
                                                    $last = $value->usuario;
                                                }
                                                else {
                                                    echo '<td></td>';
                                                }
                                                echo '<td class = "especialidad">'.$value->especialidad.'</td>';
                                            echo '</tr>';
                                        }
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> <!--row-->
    </div><!--col-md-10-->
</div>
<script type="text/javascript">
    function clear_esp() {
        $("#esp_especialidad").val("");
        $("#esp_duracion").val("30");

        $("#lu").prop('checked', false);
        $("#lu_desde").val("");
        $("#lu_hasta").val("");

        $("#ma").prop('checked', false);
        $("#ma_desde").val("");
        $("#ma_hasta").val("");

        $("#mi").prop('checked', false);
        $("#mi_desde").val("");
        $("#mi_hasta").val("");

        $("#ju").prop('checked', false);
        $("#ju_desde").val("");
        $("#ju_hasta").val("");

        $("#vi").prop('checked', false);
        $("#vi_desde").val("");
        $("#vi_hasta").val("");
    }

    function clear_usr() {
        $("#usr_nombre").val("");
        $("#usr_apellido").val("");
        $("#usr_usuario").val("");

        $("#chk_tur").prop('checked', false);
        $("#chk_pac").prop('checked', false);
        $("#chk_fac").prop('checked', false);
        $("#chk_esp").prop('checked', false);
        $("#chk_adm").prop('checked', false);
    }

    $(".sel_user").click(function(){

        clear_usr();
        id = $(this).attr('id');
        $("#usr_usuario").attr('readonly',true);

        $.ajax({
            url: base_url+"/main/get_usuario_json/"+id,
            dataType: 'json',
    	 	success: function(response) {

                $("#usr_nombre").val(response.nombre);
                $("#usr_apellido").val(response.apellido);
                $("#usr_usuario").val(response.usuario);

                $.each(JSON.parse(response.funciones), function(key, value)
                {
                    switch(value) {
                        case 'turnos':
                            $("#chk_tur").prop('checked', true);
                            break;
                        case 'pacientes':
                            $("#chk_pac").prop('checked', true);
                            break;
                        case 'facturacion':
                            $("#chk_fac").prop('checked', true);
                            break;
                        case 'especialista':
                            $("#chk_esp").prop('checked', true);
                            break;
                        default:
                            $("#chk_adm").prop('checked', true);

                    }

                });
            }
        });
    });

    $(".sel_especialista").click(function(){

        clear_esp();
        usuario = $(this).attr('id');
        $("#esp_usuario").val(usuario);

        especialidad = $(this).find('.especialidad').html();
        $("#esp_especialidad").val(especialidad);

        $.ajax({
            url: base_url+"/main/get_especialidad_json/"+usuario+"/"+especialidad,
            dataType: 'json',
    	 	success: function(response) {

                if (response.dias_horarios != "") {
                    $("#esp_duracion").val(response.duracion);

                    $.each(JSON.parse(response.dias_horarios), function(key, value)
                    {
                        switch(key) {
                            case "lu":
                                $("#lu").prop('checked', true);
                                $("#lu_desde").val(value.desde);
                                $("#lu_hasta").val(value.hasta);
                                break;
                            case "ma":
                                $("#ma").prop('checked', true);
                                $("#ma_desde").val(value.desde);
                                $("#ma_hasta").val(value.hasta);
                                break;
                            case "mi":
                                $("#mi").prop('checked', true);
                                $("#mi_desde").val(value.desde);
                                $("#mi_hasta").val(value.hasta);
                                break;
                            case "ju":
                                $("#ju").prop('checked', true);
                                $("#ju_desde").val(value.desde);
                                $("#ju_hasta").val(value.hasta);
                                break;
                            default:
                                $("#vi").prop('checked', true);
                                $("#vi_desde").val(value.desde);
                                $("#vi_hasta").val(value.hasta);
                        }

                    });
                }
            }
        });
    });

    $("#nuevo").click( function(event){
        event.preventDefault();
        clear_esp();
    })
</script>