<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <form class="navbar-form navbar-right">
                <input type="text" class="form-control" placeholder="Buscar turnos...">
            </form>
            <ul class="nav navbar-nav navbar-right">
                <?php echo $navbar ?>
                <!-- <li class="<?php echo $admin ?>"><a href="<?php echo $admin_url ?>"><span class = "glyphicon glyphicon-dashboard"></span> Admin</a></li>
                <li class="<?php echo $agenda ?>"><a href="<?php echo $agenda_url ?>"><span class = "glyphicon glyphicon-list-alt"></span> Agenda</a></li>
                <li class="<?php echo $pacientes ?>"><a href="<?php echo $pacientes_url ?>"><span class = "glyphicon glyphicon-user"></span> Pacientes</a></li>
                <li class="<?php echo $facturacion ?>"><a href="<?php echo $facturacion_url ?>"><span class = "glyphicon glyphicon-flag"></span> Facturación</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">Opciones<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                      <li><a href="#"><span class = "glyphicon glyphicon-lock"></span> Bloquear Agenda</a></li>
                      <li><a href="<?php echo base_url('index.php/login/logout')?>"><span class = "glyphicon glyphicon-log-out"></span> Salir</a></li>
                    </ul>
                </li> -->
            </ul>
        </div><!--/.nav-collapse -->
    </div><!--/.container-fluid -->
</nav>
