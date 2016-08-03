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

    .sel_agenda {
        cursor: pointer;
    }

    .heading {
      height:40px;
      padding-left:0px;
      font-weight:700
    }

</style>

<div class="container-fluid">
    <ul class="nav nav-tabs">
        <li role="presentation" class="active"><a data-toggle="tab" href="#usuarios">Usuarios</a></li>
        <li role="presentation"><a data-toggle="tab" href="#agendas">Agendas</a></li>
    </ul>
    <!-- <div class="col-md-2 sidebar" style = "background-color: white;border-color: #e7e7e7">
        <ul class="nav nav-sidebar">
            <li><a href="#usuarios">Usuarios</a></li>
            <li><a href="#agendas"">Especialistas</a></li>
          </ul>
    </div> -->
    <div class="tab-content">

        <div id = "usuarios" class="tab-pane fade in active col-md-10">
            <div class="col-md-2 page-header">
                <h3>Usuarios</h3>
            </div>
            <div class="col-md-10 page-header" style = "padding-top:15px">
                <button class="btn btn-primary dropdown-toggle" onclick = "return nuevo_usuario()">Nuevo Usuario</button>
            </div>
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading heading">
                        <div class="col-md-4">
                            Nombre
                        </div>
                        <div class="col-md-2">
                            Usuario
                        </div>
                        <div class="col-md-5">
                            Funciones
                        </div>
                        <div class="col-md-1">
                            Acciones
                        </div>
                    </div>
                    <div class="panel-body content_usuario">
                    </div>
                </div>
            </div>

        </div>

        <div id = "agendas" class="tab-pane fade col-md-10">
            <div class="col-md-2 page-header">
                <h3>Agendas</h3>
            </div>
            <div class="col-md-10 page-header" style = "padding-top:15px">
                <button class="btn btn-primary dropdown-toggle" onclick = "return nueva_agenda()">Nueva Agenda</button>
            </div>
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading heading">
                        <div class="col-md-3">
                            Nombre Agenda
                        </div>
                        <div class="col-md-3">
                            Usuario
                        </div>
                        <div class="col-md-5">
                            Especialidades
                        </div>
                        <div class="col-md-1">
                            Acciones
                        </div>
                    </div>
                    <div class="panel-body content_agenda">
                    </div>
                </div>
            </div>
        </div>
    </div><!--col-md-10-->
</div>
<script src="<?php echo base_url('libs/admin_helper.js')?>"></script>
<script type="text/javascript">

    $(document).ready(function() {
        get_datos_usuarios();
        get_datos_agendas();
    });

</script>
