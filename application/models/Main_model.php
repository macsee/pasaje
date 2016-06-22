<?php
// defined('BASEPATH') OR exit('No direct script access allowed');

class Main_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
		$this->load->library('session');
	}

	public function get_data($tabla, $like=null, $equal=null, $order=null)
	{
		if ($like != null)
			$this->db->like($like);

		if ($equal != null)
			$this->db->where($equal);

		if ($order != null)
			$this->db->order_by($order[0],$order[1]);


			$query = $this->db->get($tabla);

		if ($query->num_rows()>0)
		{
			foreach ($query->result() as $fila)
			{
				$data[] = $fila;
			}
			return $data;
		}
		else
		{
			return null;
		}
	}

	public function get_turnos_mes($year, $month, $id_agenda)
	{

		$this->db->where(array("MONTH(fecha)" => $year, "MONTH(fecha)" => $month, "especialista" => $id_agenda));
		$this->db->order_by("hora","desc");
		$query = $this->db->get("turnos");

		if ($query->num_rows()>0)
		{
			foreach ($query->result() as $fila)
			{
				$data[$fila->especialista][$fila->fecha][] = $fila;
			}
			return $data;
		}
		else
		{
			return null;
		}

	}

	public function get_datos_especialista($id)
	{
		$like = null;
		$where = null;

		if ($id != "todos")
			$where = array('usuario' => $id);

		// if ($especialidad != "")
		// 	$like = array('especialidad' => $especialidad);

		return $this->main_model->get_data("especialistas_especialidades", $like, $where);
	}

	public function am_usuario($array)
	{
		$data = array(
		   	'usuario' => $array['usr_usuario'],
		   	'nombre' => ucwords(strtolower($array['usr_nombre'])),
		   	'apellido' => ucwords(strtolower($array['usr_apellido'])),
		   	'password' => $array['usr_usuario'],
		   	'funciones' => json_encode($array['usr_funciones'])
		);

		$query = "	INSERT INTO usuarios (usuario, nombre, apellido, password, funciones)
					VALUES (?,?,?,?,?) ON DUPLICATE KEY UPDATE 	nombre = VALUES(nombre),
																apellido = VALUES(apellido),
																funciones = VALUES(funciones)";
		$this->db->query($query, $data);
	}

	public function am_especialista($id)
	{

		$query = "	INSERT INTO especialistas_especialidades (usuario) VALUES (?)";
					// VALUES (?) ON DUPLICATE KEY UPDATE usuario = VALUES(usuario)";

			if ($this->get_datos_especialista($id,"") == null)
				$this->db->query($query, array('usuario' => $id));
	}

	public function add_agenda($array)
	{
		$horarios = array();
		$especialidades = array();

		foreach ($array['esp_dias'] as $key => $dia)
		{
			$horarios[$dia] = array(
				"desde"	=>	$array[$dia."_desde"],
				"hasta" =>	$array[$dia."_hasta"]
			);
		}

		foreach ($array['esp_especialidad'] as $key =>$esp)
		{
			$especialidades[] = ucwords(strtolower($esp));
		}

		$data = array(
			'id'	  		=> $array['esp_id'],
			'usuario' 		=> $array['esp_usuario'],
		   	'especialidad' 	=> json_encode($especialidades),
			'dias_horarios' => json_encode($horarios),
		   	'duracion'		=> $array['duracion']
		);


		// if ($this->first_esp($array['esp_usuario']))
		// {
		// 	$this->db->update('especialistas_especialidades', $data, array('usuario' => $array['esp_usuario']));
		// }
		// else
		// {
			$query = "	INSERT INTO especialistas_especialidades (id, usuario, especialidad, dias_horarios, duracion)
						VALUES (?,?,?,?,?) ON DUPLICATE KEY UPDATE 	especialidad = VALUES(especialidad),
																	usuario = VALUES(usuario),
																	duracion = VALUES(duracion),
																	dias_horarios = VALUES(dias_horarios)";
			$this->db->query($query, $data);
		// }
	}

	function first_esp($id)
	{
		$query = $this->db->get_where("especialistas_especialidades", array('usuario' => $id));
		if ($query->num_rows() > 0)
			return $query->row(1)->especialidad == "";
	}

	function del_usuario($id)
	{
		// $usuario = $this->get_usuario_by($id);
		$this->db->delete("usuarios", array('usuario' => $id));
		$this->db->delete("especialistas_especialidades", array('usuario' => $id));

	}

	public function am_turno($data)
	{

		$query = "	INSERT INTO turnos (id_turno, id_paciente, fecha, hora, especialista, especialidad, observaciones, data_extra, estado, usuario)
					VALUES (?,?,?,?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE 	id_paciente		= VALUES(id_paciente),
																			fecha 			= VALUES(fecha),
																			hora 			= VALUES(hora),
																			especialista 	= VALUES(especialista),
																			especialidad 	= VALUES(especialidad),
																			observaciones 	= VALUES(observaciones),
																			estado 			= VALUES(estado),
																			usuario 		= VALUES(usuario),
																			data_extra		= VALUES(data_extra)";
		$this->db->query($query, $data);
	}

	public function del_turno($id)
	{
		$this->db->delete("turnos", array('id_turno' => $id));
	}

	public function cambiar_turno($data)
	{
		$this->db->update('turnos', array('fecha' => $data['fecha'], 'hora' => $data['hora']), array('id_turno' => $data['id_turno']));
	}

	public function change_turno_estado($data)
	{
		$this->db->update('turnos', array('estado' => $data['estado']), array('id_turno' => $data['id_turno']));
	}

	public function am_facturacion($data)
	{

		$query = "	INSERT INTO facturacion (id_facturacion, id_turno, fecha, datos, usuario)
					VALUES (?,?,?,?,?) ON DUPLICATE KEY UPDATE 	id_facturacion	= VALUES(id_facturacion),
																id_turno		= VALUES(id_turno),
																fecha	 		= VALUES(fecha),
																datos 			= VALUES(datos),
																usuario	 		= VALUES(usuario)";
		$this->db->query($query, $data);
	}

	public function am_paciente($data)
	{
		$query = "	INSERT INTO pacientes (id_paciente, nombre, apellido, dni, direccion, localidad, tel1, tel2, observaciones)
					VALUES (?,?,?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE 	nombre 			= VALUES(nombre),
																			apellido 		= VALUES(apellido),
																			dni 			= VALUES(dni),
																			direccion 		= VALUES(direccion),
																			localidad 		= VALUES(localidad),
																			tel1 			= VALUES(tel1),
																			tel2 			= VALUES(tel2),
																			observaciones	= VALUES(observaciones)";

		$this->db->query($query, $data);

		if ($data['id_paciente'] != "")
			return $data['id_paciente'];
		else
			return $this->db->insert_id();
	}

	public function get_horarios($id)
	{
		$turnos = array();

		$agenda_esp = $this->get_datos_especialista($id);

		if ($agenda_esp != null) {

			foreach ($agenda_esp as $fila) {

				$dias = json_decode($fila->dias_horarios);
				$duracion = $fila->duracion;
				$usuario = $fila->usuario;

				if ($dias != null) {

					foreach ($dias as $key => $hora) {

						//desde_man
						//hasta_man

						//desde_tar
						//hasta_tar

						$horarios_esp = array();
						$horarios_man = array();
						$horarios_tar = array();

						$desde_man = !isset($hora->{1}->desde) ? 0 : strtotime($hora->{1}->desde);
						$hasta_man = !isset($hora->{1}->hasta) ? 0 : strtotime($hora->{1}->hasta);
						$diff = abs($hasta_man - $desde_man)/60;
						$cant_turnos_man = $diff/$duracion;

						$desde_tar = !isset($hora->{2}->desde) ? 0 : strtotime($hora->{2}->desde);
						$hasta_tar = !isset($hora->{2}->hasta) ? 0 : strtotime($hora->{2}->hasta);
						$diff = abs($hasta_tar - $desde_tar)/60;
						$cant_turnos_tar = $diff/$duracion;

						for ($i=0; $i <= $cant_turnos_man && $cant_turnos_man > 0; $i++) {
							$horarios_esp[] = date('H:i',$desde_man+($i*$duracion*60));
							// $horarios_esp[date('H:i',$hora_desde+($i*$duracion*60))] = "";
						}

						if ($cant_turnos_man > 0 && $cant_turnos_tar > 0)
							$horarios_esp[] = "";

						for ($i=0; $i <= $cant_turnos_tar && $cant_turnos_tar > 0 ; $i++) {
							$horarios_esp[] = date('H:i',$desde_tar+($i*$duracion*60));
						}

						// Agrego la clave usuario para poder sumar la cantidad de turnos de cada usuario en un mismo dia
						// y asi no contar dos veces un mismo usuario con mas de una especialidad

						// $turnos[$key][$usuario] = (object) array( // La parte de usuarios esta manejada por la funcion que llama a esta funcion

						// $turnos[$key] = (object) array(
						// 	'horarios'		=>	$horarios_esp,
						// 	'cant_turnos' 	=> 	$cant_turnos
						// );
						$turnos[$key] = (object) $horarios_esp;

					}

				}

			}
		}

		return $turnos;
	}


	public function get_agenda_extra_dia($fecha, $id, $especialidad="")
	{
		$fecha = date('Y-m-d',strtotime($fecha));
		$datos = $this->get_datos_especialista($id);
		$turnos = null;
		$horarios_esp = null;

		if ($datos != null) {
			foreach ($datos as $key => $value) {

					$usuario = $value->usuario;
					$agenda_extra = json_decode($value->agenda_extra);

					if (isset($agenda_extra->$fecha)) {

						$extra = $agenda_extra->$fecha;
						$hora_hasta = strtotime($extra->hora_hasta);
						$hora_desde = strtotime($extra->hora_desde);
						$diff = abs($hora_hasta - $hora_desde)/60;
						$cant_turnos = $diff/$extra->duracion;

						for ($i=0; $i <= $cant_turnos ; $i++) {
							$horarios_esp[date('H:i',$hora_desde+($i*$extra->duracion*60))] = "";
						}

						// Horarios extra para la fecha $fecha y para el especialista $id
						//
						$turnos[$usuario] = (object) array(
							'horarios'		=>	$horarios_esp,
							'cant_turnos' 	=> 	$cant_turnos
						);
					}

			}

		}

		return $turnos;
	}

	public function get_pacientes_autocomplete($like)
	{
		$this->db->like($like);
		$query = $this->db->get("pacientes");

		if ($query->num_rows()>0)
		{
			foreach ($query->result() as $fila)
			{
				$data[] = array(
								// "label" => $fila->apellido,
								"value" 	=> $fila->apellido,
								"nombre" 	=> $fila->nombre,
								"id" 		=> $fila->id_paciente,
								"dni" 		=> $fila->dni,
								"direccion" => $fila->direccion,
								"localidad" => $fila->localidad,
								"tel"		=> $fila->tel1,
								"cel"		=> $fila->tel2,
 				);

			}
			return $data;
		}
		else
		{
			return null;
		}
	}

	public function join_telefono($t1, $t2)
	{
		return ($t1 != "" ? $t1.'-'.$t2 : $t2);
	}

	public function explode_telefono($t1)
	{
		if ($t1 != "")
			if (stripos($t1,"-") !== false) {
				$t11 = explode("-", $t1)[0];
				$t12 = explode("-", $t1)[1];
			}
			else {
				$t11 = "";
				$t12 = $t1;
			}
		else {
			$t11 = "";
			$t12 = "";
		}

		return array('prefijo' => $t11, 'telefono' => $t12);
	}

	public function am_nota($data)
	{
		$query = "	INSERT INTO notas (id_nota, texto, usuario, destinatario, fecha)
					VALUES (?,?,?,?,?) ON DUPLICATE KEY UPDATE 	texto 			= VALUES(texto),
																usuario 		= VALUES(usuario),
																destinatario 	= VALUES(destinatario)";

		$this->db->query($query, $data);
	}

	public function del_nota($id)
	{
		$this->db->delete("notas", array('id_nota' => $id));
	}

	public function is_especialista($id)
	{
		return $this->get_data("especialistas_especialidades",null,array('usuario' => $id)) != null;
	}

	public function rol($id, $rol)
	{
		return $this->main_model->get_data('usuarios', array('funciones' => $rol), array('usuario' => $id)) != null;
	}

	public function crear_agenda($data)
	{
		$this->db->update('especialistas_especialidades', array('agenda_extra' => $data['agenda']), array('usuario' => $data['usuario']));
	}
}
