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

		// $this->output->enable_profiler(TRUE);
		if ($query->num_rows()>0)
		{
			// foreach ($query->result() as $fila)
			// {
			// 	$data[] = $fila;
			// }
			// return $data;
			return $query->result();
		}
		else
		{
			return null;
		}
	}

	public function format_turno($value)
	{

		$id_esp = $this->get_datos_agenda($value->agenda)[0]->usuario;
		$nombre_esp = $this->get_data("usuarios",null,array('usuario' => $id_esp))[0];
		$nombre_pac = $this->get_data("pacientes",null,array('id_paciente' => $value->id_paciente))[0];
		$hora = date('H:i',strtotime($value->hora));

		$obj = (object) array(
							'hora' => $hora,
							'id_turno' => $value->id_turno,
							'id_paciente' => $value->id_paciente,
							'paciente' => $nombre_pac != null ? $nombre_pac->apellido.", ".$nombre_pac->nombre : "",
							'especialidad' => $value->especialidad,
							'especialista' => $nombre_esp->apellido.", ".$nombre_esp->nombre[0],
							'estado' => $value->estado,
							'data_extra' => $value->data_extra
						);

		return $obj;
	}

// Los turnos se obtendran seleccionando un id_agenda, no un especialista. Se podra filtrar agendas de acuerdo a la especialidad en la vista de agenda
// Como los turnos solo registran especialista y especialidad, al seleccionar un id_agenda, debo obtener el especialista y las especialidades relacionadas
// y buscar los turnos del mes que cumplan con esos requisitos.

	public function get_turnos_mes($year, $month, $datos_agenda)
	{
		$data = [];

		$like = null;
		$where = "";

		$where = "(";
		foreach ($datos_agenda as $key => $value) {
			$where .= "agenda = '".$value->id_agenda."'";
			if ($key < count($datos_agenda)-1)
				$where .= " OR ";

		}
		$where .= ")";

		$where .= " AND YEAR(fecha) = '".$year."' AND MONTH(fecha) = '".$month."'";

		$query = $this->main_model->get_data("turnos", $like, $where, array('hora','asc'));


		// if (count($agenda) == 1)
		// 	$this->db->where(array("agenda" => $agenda[0]->id_agenda));
		//
		// $this->db->where(array("YEAR(fecha)" => $year, "MONTH(fecha)" => $month));
		// $this->db->order_by("hora","asc");
		// $query = $this->db->get("turnos");
		//
		if ($query != null)
		{
			foreach ($query as $fila)
			{
				// $data[$fila->fecha][date("H:i",strtotime($fila->hora))] = $this->format_turno($fila);
				$data[$fila->fecha][] = $this->format_turno($fila);
			}
		}

		return $data;
	}

	public function get_datos_especialista($id)
	{
		$like = null;
		$where = null;

		if ($id != "todos")
			$where = array('usuario' => $id);

		// if ($especialidad != "")
		// 	$like = array('especialidad' => $especialidad);

		return $this->main_model->get_data("agendas", $like, $where);
	}

	public function get_datos_agenda($id, $esp ="")
	{
		$like = null;
		$where = null;

		if ($id != "todos") {
			$where = array('id_agenda' => $id);
		}

		if ($id == "todos" && $esp != "" && $esp != "todos") {
			$esp = str_replace("%20"," ",$esp);
			$like = array('especialidad' => $esp);
		}

		// if ($especialidad != "")
		// 	$like = array('especialidad' => $especialidad);

		return $this->main_model->get_data("agendas", $like, $where);
	}

	// public function get_datos_agenda_extra($year, $month, $id, $esp = "")
	public function get_datos_agenda_extra($year, $month, $datos_agenda)
	{
		$like = null;
		$where = "";

		$where = "(";
		foreach ($datos_agenda as $key => $value) {
			$where .= "id_agenda = '".$value->id_agenda."'";
			if ($key < count($datos_agenda)-1)
				$where .= " OR ";

		}
		$where .= ")";

		$where .= " AND YEAR(fecha) = '".$year."' AND MONTH(fecha) = '".$month."'";

		return $this->main_model->get_data("agendas_extras", $like, $where);
	}

	public function am_usuario($array)
	{
		$query = "	INSERT INTO usuarios (usuario, nombre, apellido, password, funciones)
					VALUES (?,?,?,?,?) ON DUPLICATE KEY UPDATE 	nombre = VALUES(nombre),
																apellido = VALUES(apellido),
																funciones = VALUES(funciones)";
		$this->db->query($query, $array);
	}

	public function reset_usuario($usuario)
	{
		$this->db->update('usuarios', array('password' => $usuario), array('usuario' => $usuario));
	}

	public function am_especialista($id)
	{

		$query = "	INSERT INTO agendas (usuario) VALUES (?)";
					// VALUES (?) ON DUPLICATE KEY UPDATE usuario = VALUES(usuario)";

			if ($this->get_datos_especialista($id) == null)
				$this->db->query($query, array('usuario' => $id));
	}

	public function am_agenda($array)
	{
		// $horarios = array();
		// $especialidades = array();
		//
		// foreach ($array['agenda_especialidades'] as $key => $dia)
		// {
		// 	$horarios[$dia] = array(
		// 		"desde"	=>	$array[$dia."_desde"],
		// 		"hasta" =>	$array[$dia."_hasta"]
		// 	);
		// }
		//
		// foreach ($array['esp_especialidad'] as $key =>$esp)
		// {
		// 	$especialidades[] = ucwords(strtolower($esp));
		// }
		//
		// $data = array(
		// 	'id'	  			=> $array['esp_id'],
		// 	'usuario' 			=> $array['esp_usuario'],
		//   	'especialidad' 		=> json_encode($especialidades),
		// 	'dias_horarios' 	=> json_encode($horarios),
		//   	'duracion'			=> $array['duracion']
		// );


		// if ($this->first_esp($array['esp_usuario']))
		// {
		// 	$this->db->update('agendas', $data, array('usuario' => $array['esp_usuario']));
		// }
		// else
		// {
			$query = "	INSERT INTO agendas (id_agenda, nombre_agenda, usuario, especialidad, dias_horarios, duracion)
						VALUES (?,?,?,?,?,?) ON DUPLICATE KEY UPDATE 	especialidad = VALUES(especialidad),
																		nombre_agenda = VALUES(nombre_agenda),
																		usuario = VALUES(usuario),
																		duracion = VALUES(duracion),
																		dias_horarios = VALUES(dias_horarios)";
			$this->db->query($query, $array);
		// }
	}

	function del_agenda($id)
	{
		$this->db->delete("agendas", array('id_agenda' => $id));
	}

	function first_esp($id)
	{
		$query = $this->db->get_where("agendas", array('usuario' => $id));
		if ($query->num_rows() > 0)
			return $query->row(1)->especialidad == "";
	}

	function del_usuario($id)
	{
		// $usuario = $this->get_usuario_by($id);
		$this->db->delete("usuarios", array('usuario' => $id));
		$this->db->delete("agendas", array('usuario' => $id));

	}

	public function am_turno($data)
	{

		$query = "	INSERT INTO turnos (id_turno, id_paciente, fecha, hora, agenda, especialidad, observaciones, data_extra, estado, usuario)
					VALUES (?,?,?,?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE 	id_paciente		= VALUES(id_paciente),
																			fecha 			= VALUES(fecha),
																			hora 			= VALUES(hora),
																			agenda 	= VALUES(agenda),
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

	public function del_facturacion($id)
	{
		$this->db->delete("facturacion", array('id_facturacion' => $id));
	}

	public function am_paciente($data)
	{
		$query = "	INSERT INTO pacientes (id_paciente, nombre, apellido, dni, direccion, localidad, tel1, tel2, observaciones)
					VALUES (?,?,?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE 	nombre = VALUES(nombre),
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

	// public function get_horarios($id, $esp = "")
	public function get_horarios($agenda_esp)
	{
		$turnos = array();

		// $agenda_esp = $this->get_datos_agenda($id, $esp);

		if ($agenda_esp != null) {

			foreach ($agenda_esp as $fila) {

				$dias = json_decode($fila->dias_horarios);
				$duracion = $fila->duracion;
				$usuario = $fila->usuario;

				if ($dias != null) {

					foreach ($dias as $key => $hora) {

						$horarios_esp = array();
						$horarios_man = array();
						$horarios_tar = array();



						$desde_man = $hora->{1}->desde == "" ? 0 : strtotime($hora->{1}->desde);
						$hasta_man = $hora->{1}->hasta == "" ? 0 : strtotime($hora->{1}->hasta);
						$diff = abs($hasta_man - $desde_man)/60;
						$cant_turnos_man = $diff/$duracion;

						$desde_tar = $hora->{2}->desde == "" ? 0 : strtotime($hora->{2}->desde);
						$hasta_tar = $hora->{2}->hasta == "" ? 0 : strtotime($hora->{2}->hasta);
						$diff = abs($hasta_tar - $desde_tar)/60;
						$cant_turnos_tar = $diff/$duracion;

						for ($i=0; $i <= $cant_turnos_man && $cant_turnos_man > 0; $i++) {
							$horarios_esp[] = (object) array('hora' => date('H:i',$desde_man+($i*$duracion*60)), 'id_turno' => "");
						}

						if ($cant_turnos_man > 0 && $cant_turnos_tar > 0)
							$horarios_esp[] = (object) array('hora' => "", 'id_turno' => "");

						for ($i=0; $i <= $cant_turnos_tar && $cant_turnos_tar > 0 ; $i++) {
							$horarios_esp[] = (object) array('hora' => date('H:i',$desde_tar+($i*$duracion*60)), 'id_turno' => "");
						}

						$turnos[$key][] = $horarios_esp;

					}

				}

			}
		}

		return $turnos;
	}

	// public function get_horarios_extra($year, $month, $agendas)
	public function get_horarios_extra($year, $month, $datos_agenda)
	{
		$turnos = array();

		// $agenda_extra = $this->get_datos_agenda_extra($year, $month, $id_agenda, $esp);
		$agenda_extra = $this->get_datos_agenda_extra($year, $month, $datos_agenda);

		// print_r($agenda_extra);

		if ($agenda_extra != null) {

			foreach ($agenda_extra as $fila) {


				$horas = json_decode($fila->horarios);
				$duracion = $fila->duracion;

				// if ($dias != null) {

					$horarios_esp = array();
					$horarios_man = array();
					$horarios_tar = array();

					$desde_man = $horas->{1}->desde == "" ? 0 : strtotime($horas->{1}->desde);
					$hasta_man = $horas->{1}->hasta == "" ? 0 : strtotime($horas->{1}->hasta);
					$diff = abs($hasta_man - $desde_man)/60;
					$cant_turnos_man = $diff/$duracion;

					$desde_tar = $horas->{2}->desde == "" ? 0 : strtotime($horas->{2}->desde);
					$hasta_tar = $horas->{2}->hasta == "" ? 0 : strtotime($horas->{2}->hasta);
					$diff = abs($hasta_tar - $desde_tar)/60;
					$cant_turnos_tar = $diff/$duracion;

					for ($i=0; $i <= $cant_turnos_man && $cant_turnos_man > 0; $i++) {
						$horarios_esp[] = (object) array('hora' => date('H:i',$desde_man+($i*$duracion*60)), 'id_turno' => "");
					}

					if ($cant_turnos_man > 0 && $cant_turnos_tar > 0)
						$horarios_esp[] = (object) array('hora' => "", 'id_turno' => "");

					for ($i=0; $i <= $cant_turnos_tar && $cant_turnos_tar > 0 ; $i++) {
						$horarios_esp[] = (object) array('hora' => date('H:i',$desde_tar+($i*$duracion*60)), 'id_turno' => "");
					}

					$turnos[$fila->fecha][] = $horarios_esp;

				// }

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
								"observaciones" => $fila->observaciones,
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
		return $this->get_data("agendas",null,array('usuario' => $id)) != null;
	}

	public function rol($id, $rol)
	{
		return $this->main_model->get_data('usuarios', array('funciones' => $rol), array('usuario' => $id)) != null;
	}

	public function am_agenda_extra($data)
	{

		$query = "	INSERT INTO agendas_extras (id, id_agenda, fecha, horarios, duracion, usuario)
					VALUES (?,?,?,?,?,?) ON DUPLICATE KEY UPDATE 	horarios 			= VALUES(horarios),
																												usuario 			= VALUES(usuario)";

		$this->db->query($query, $data);

		// $this->db->insert('agendas_extras', $data);
	}

	public function get_agenda_extra($agenda, $fecha)
	{
		return $this->main_model->get_data('agendas_extras', null, array('id_agenda' => $agenda, 'fecha' => $fecha));
	}

	public function del_agenda_extra($data)
	{
		$this->db->delete("agendas_extras", array('id_agenda' => $data['agenda'], 'fecha' => $data['fecha']));
		$this->db->delete("turnos", array('agenda' => $data['agenda'], 'fecha' => $data['fecha']));
	}

}
