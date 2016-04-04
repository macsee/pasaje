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
			$this->db->order_by($order, "desc");


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

	public function add_usuario($array)
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

	public function add_especialista($id)
	{
		$query = "	INSERT INTO especialistas_especialidades (usuario)
					VALUES (?) ON DUPLICATE KEY UPDATE usuario = VALUES(usuario)";

		$this->db->query($query, array('usuario' => $id));
	}

	public function add_especialidad($array)
	{
		$horarios = array();

		foreach ($array['esp_dias'] as $key => $dia)
		{
			$horarios[$dia] = array(
				"desde"	=>	$array[$dia."_desde"],
				"hasta" =>	$array[$dia."_hasta"]
			);
		}

		$data = array(
			'usuario' => $array['esp_usuario'],
		   	'especialidad' => ucwords(strtolower($array['esp_especialidad'])),
			'dias_horarios' => json_encode($horarios),
		   	'duracion'	=> $array['duracion']
		);


		if ($this->first_esp($array['esp_usuario']))
		{
			$this->db->update('especialistas_especialidades', $data, array('usuario' => $array['esp_usuario']));
		}
		else
		{
			$query = "	INSERT INTO especialistas_especialidades (usuario, especialidad, dias_horarios, duracion)
						VALUES (?,?,?,?) ON DUPLICATE KEY UPDATE 	duracion = VALUES(duracion),
																	dias_horarios = VALUES(dias_horarios)";
			$this->db->query($query, $data);
		}
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

	public function get_horarios($id, $especialidad="")
	{
		$turnos = array();
		$array = null;

		if ($id != "todos")
			$array['usuario'] = $id;

		if ($especialidad != "")
			$array['especialidad'] = $especialidad;

		$especialista = $this->get_data("especialistas_especialidades", $array);

		if ($especialista != null) {

			foreach ($especialista as $esp) {

				$dias = json_decode($esp->dias_horarios);
				$duracion = $esp->duracion;

				if ($dias != null) {
					foreach ($dias as $key => $hora) {

						$hora_hasta = strtotime($hora->hasta);
						$hora_desde = strtotime($hora->desde);
						$diff = abs($hora_hasta - $hora_desde)/60;
						$cant_turnos = $diff/$duracion;

						$turnos[$key] = (object) array(
							'cant_turnos' 	=> (isset($turnos[$key]) ? $turnos[$key]->cant_turnos + $cant_turnos : $cant_turnos),
							'desde_hora' 	=> date('H:i', $hora_desde),
							'hasta_hora' 	=> date('H:i', $hora_hasta)
						);

					}
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
}