<div class="container-fluid">
    <?php
    if ($notas != null) {
        echo '<ul style = "margin-left:-25px">';

        foreach ($notas as $key => $value) {
            if ($value->destinatario == $especialista_sel || $value->destinatario == "todos" || $especialista_sel == "todos") {

                if ($usuario == $value->usuario) {
                    $onclick = "return editar_nota('".$value->id_nota."')";
                }
                else {
                    $onclick = 'return error_nota()';
                }

                echo   '<li style = "min-height:40px;margin-bottom:25px">
                            <a onclick = "'.$onclick.'">'.$value->texto.'</a>
                            <span class = "pull-right text-muted small" style = "width:100%"><i>'.$value->last_update.' - '.$value->nombre_usuario.'</i></span>
                        </li>';
            }
        }

        echo '</ul>';
    }
    else {
    	echo '<i>No hay notas para fecha</i>';
    }
    ?>
</div>
