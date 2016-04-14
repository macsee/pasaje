<?php

    // $turnos = $this->get_turnos_dia_esp($fecha,$especialista_sel,$especialidad_sel);
    $header_especialista = "";

    if ($especialista_sel == "todos")
        $header_especialista = '<th>Especialista</th>';

    if ($turnos != null) {

        echo	'<table class="table">
                        <thead class = "cabecera">
                            <tr>
                                <th>Hora</th>
                                <th>Paciente</th>
                                <th>Especialidad</th>'
                                .$header_especialista.
                                '<th>Acciones</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>';

        foreach ($turnos as $key => $value) {
            $primera_vez = "";
            $row_especialista = "";
            $row_vacia = "";
            $turno_vacio = "turno_vacio";
            $estado = 'glyphicon glyphicon-unchecked';

            if ($value != "") {

                if (in_array("primera_vez", json_decode($value->data_extra)))
                    $primera_vez = "color:#d9534f";

                if ($especialidad_sel == "todos")
                    $row_especialista = '<td>'.$value->especialista.'</td>';

                if ($value->estado != "")
                    $estado = "glyphicon glyphicon-check";

                echo
                    '<tr id = "'.$value->id_turno.'">
                        <td style = "font-size:16px;'.$primera_vez.'">'.$key.'</td>
                        <td>'.$value->paciente.'</td>
                        <td>'.$value->especialidad.'</td>'
                        .$row_especialista.
                        '<td>
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" data-toggle="dropdown" role="button"><i class = "glyphicon glyphicon-th-list"></i><span class="caret"></span></button>
                                <ul class="dropdown-menu">
                                    <li><a href="#" onclick = "return editar_turno(\''.$value->id_turno.'\')" data-toggle="modal">Editar Turno</a></li>
                                    <li><a href="#" onclick = "return eliminar_turno(\''.$value->id_turno.'\')" data-toggle="modal">Eliminar Turno</a></li>
                                    <li><a href="#" onclick = "return cambiar_turno(\''.$value->id_turno.'\')" data-toggle="modal">Cambiar Fecha/Hora</a></li>
                                    <li><a href="#" onclick = "return proximo_turno(\''.$value->id_turno.'\')" data-toggle="modal">Nuevo Turno</a></li>
                                </ul>
                            </div>
                        </td>
                        <td><button onclick = "return confirmar_datos(\''.$value->id_turno.'\')" style = "font-size: 18px;padding: 3px 11px 3px 11px" class = "btn btn-default"><i class = "'.$estado.'"></i></button></td>
                    </tr>';
            }
            else {

                if ($especialista_sel == "todos")
                    $row_vacia = '<td></td>';

                // if ($cambio_turno != "")
                // 	$turno_vacio = "turno_cambio";
                //
                // if ($prox_turno != "")
                // 	$turno_vacio = "turno_prox";

                echo
                    '<tr class = "'.$turno_vacio.'" id = "'.$key.'">
                        <td>'.$key.'</td>
                        <td></td>
                        <td></td>'
                        .$row_vacia.
                        '<td></td>
                        <td></td>
                    </tr>';
            }

        }


        echo '</tbody>
            </table>';
    }

    // echo $tabla;
?>
