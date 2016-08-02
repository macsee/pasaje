<?php
// defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('main_model');
		$this->load->helper('url_helper');
		$this->load->library('session');

		$logged = $this->session->userdata('is_logged');

		if (!$logged)
			redirect('login');
	}

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->agenda();
	}

	public function create_navbar($array)
	{
		$navbar = "";

		if ($array['admin_show'])
			$navbar .= '<li class="'.$array['admin_act'].'"><a href="'.$array['admin_url'].'"><span class = "glyphicon glyphicon-dashboard"></span> Admin</a></li>';
		if ($array['agenda_show'])
			$navbar .= '<li class="'.$array['agenda_act'].'"><a href="'.$array['agenda_url'].'"><span class = "glyphicon glyphicon-list-alt"></span> Agenda</a></li>';
		if ($array['pacientes_show'])
			$navbar .= '<li class="'.$array['pacientes_act'].'"><a href="'.$array['pacientes_url'].'"><span class = "glyphicon glyphicon-user"></span> Pacientes</a></li>';
		if ($array['facturacion_show'])
			$navbar .= '<li class="'.$array['facturacion_act'].'"><a href="'.$array['facturacion_url'].'"><span class = "glyphicon glyphicon-flag"></span> Facturación</a></li>';

		$navbar .= '<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">Opciones<span class="caret"></span></a>
			<ul class="dropdown-menu">
			  <li><a href="#"><span class = "glyphicon glyphicon-lock"></span> Bloquear Agenda</a></li>
			  <li><a href="'.base_url('index.php/login/logout').'"><span class = "glyphicon glyphicon-log-out"></span> Salir</a></li>
			</ul>
		</li>';

		return $navbar;
	}

	public function agenda()
	{
		$data['especialista_sel'] = $this->session->userdata('especialista');
		// $data['especialidad_sel'] = $this->session->userdata('especialidad');
		$data['usuario'] = $this->session->userdata('usuario');
		$data['usuarios'] = $this->get_usuarios("todos");//$this->main_model->get_data('usuarios');
		$data['is_admin'] = $this->main_model->rol($this->session->userdata('usuario'),"admin") ? 1 : 0;

		if ($data['especialista_sel'] != "todos") {
				$data['agendas'] = $this->get_agendas($data['especialista_sel']);//$this->main_model->get_data('agendas', array('usuario' => $data['especialista_sel']));
				$data['especialidades'] = $this->get_especialidades($data['especialista_sel']);
		}
		else {
				$data['agendas'] = $this->get_agendas("todos");
				$data['especialidades'] = $this->get_especialidades("todos");
		}

		if ($data['is_admin']) {
			$data["agenda_extra"] = '<h3>Crear Agenda</h3><hr>';
			$data["agenda_extra"] .= $this->load->view('agenda_extra_view', '', true);
		}
		else
			$data["agenda_extra"] = '<div class = "text-muted" style = "font-size:30px;text-align:center;height:150px;padding:50px"><i>No hay agenda abierta para este día</i></div>';

		$arraybar = array (
			'admin_act' 		=> "",
			'admin_url' 		=> base_url('index.php/main/admin'),
			'admin_show' 		=> $this->main_model->rol($this->session->userdata('usuario'),"admin"),
			'agenda_act' 		=> "active",
			'agenda_url' 		=> "#",
			'agenda_show' 		=> true,
			'pacientes_act' 	=> "",
			'pacientes_url' 	=> base_url('index.php/main/pacientes'),
			'pacientes_show' 	=> true,
			'facturacion_act' 	=> "",
			'facturacion_url' 	=> base_url('index.php/main/facturacion'),
			'facturacion_show' 	=> $this->main_model->rol($this->session->userdata('usuario'),"admin")
		);

		$navbar['navbar'] = $this->create_navbar($arraybar);

		$this->load->view('header', array('title' => "Agenda"));
			$this->load->view('navbar', $navbar);
			$this->load->view('agenda_view',$data);
			$this->load->view('modal_turno');
			$this->load->view('modal_confirmacion');
			$this->load->view('modal_datos');
			$this->load->view('modal_cambiar_turno');
			$this->load->view('modal_notas', $data);
			$this->load->view('modal_error');
			$this->load->view('modal_agenda_extra',$data);
		$this->load->view('footer');
	}

	public function admin()
	{
		if (!$this->main_model->rol($this->session->userdata('usuario'),"admin"))
			redirect('main/agenda');
		else {
			$data['usuario'] = $this->session->userdata('usuario');
			$data['usuarios'] = $this->get_usuarios("todos");//$this->main_model->get_data("usuarios");
			$data['agendas'] = $this->get_agendas("todos");
			$data['especialistas'] = $this->get_especialistas();

			$arraybar = array (
				'admin_act' 		=> "active",
				'admin_url' 		=> "#",
				'admin_show' 		=> $this->main_model->rol($this->session->userdata('usuario'),"admin"),
				'agenda_act' 		=> "",
				'agenda_url' 		=> base_url('index.php/main/agenda'),
				'agenda_show' 		=> true,
				'pacientes_act' 	=> "",
				'pacientes_url' 	=> base_url('index.php/main/pacientes'),
				'pacientes_show' 	=> true,
				'facturacion_act' 	=> "",
				'facturacion_url' 	=> base_url('index.php/main/facturacion'),
				'facturacion_show' 	=> $this->main_model->rol($this->session->userdata('usuario'),"admin")
			);

			$navbar['navbar'] = $this->create_navbar($arraybar);

			$this->load->view('header', array('title' => "Admin"));
				$this->load->view('navbar', $navbar);
				$this->load->view('modal_confirmacion');
				$this->load->view('modal_usuario',$data);
				$this->load->view('modal_agenda',$data);
				$this->load->view('admin_view', $data);
			$this->load->view('footer');
		}
	}

	public function pacientes()
	{
		$data['usuarios'] = $this->get_usuarios("todos");//$this->main_model->get_data("usuarios");
		$data['agendas'] = $this->get_agendas("todos");

		$arraybar = array (
			'admin_act' 		=> "",
			'admin_url' 		=> base_url('index.php/main/admin'),
			'admin_show' 		=> $this->main_model->rol($this->session->userdata('usuario'),"admin"),
			'agenda_act' 		=> "",
			'agenda_url' 		=> base_url('index.php/main/agenda'),
			'agenda_show' 		=> true,
			'pacientes_act' 	=> "active",
			'pacientes_url' 	=> "#",
			'pacientes_show' 	=> true,
			'facturacion_act' 	=> "",
			'facturacion_url' 	=> base_url('index.php/main/facturacion'),
			'facturacion_show' 	=> $this->main_model->rol($this->session->userdata('usuario'),"admin")
		);

		$navbar['navbar'] = $this->create_navbar($arraybar);

		$this->load->view('header', array('title' => "Pacientes"));
			$this->load->view('navbar', $navbar);
			$this->load->view('pacientes_view', $data);
		$this->load->view('footer');

	}

	public function facturacion()
	{
		if (!$this->main_model->rol($this->session->userdata('usuario'),"admin"))
			redirect('main/agenda');
		else {

			$data['usuarios'] = $this->get_usuarios("todos");//$this->main_model->get_data("usuarios");
			$data['agendas'] = $this->get_agendas("todos");

			$arraybar = array (
				'admin_act' 		=> "",
				'admin_url' 		=> base_url('index.php/main/admin'),
				'admin_show' 		=> $this->main_model->rol($this->session->userdata('usuario'),"admin"),
				'agenda_act' 		=> "",
				'agenda_url' 		=> base_url('index.php/main/agenda'),
				'agenda_show' 		=> true,
				'pacientes_act' 	=> "",
				'pacientes_url' 	=> base_url('index.php/main/pacientes'),
				'pacientes_show' 	=> true,
				'facturacion_act' 	=> "active",
				'facturacion_url' 	=> "#",
				'facturacion_show' 	=> $this->main_model->rol($this->session->userdata('usuario'),"admin")
			);

			$navbar['navbar'] = $this->create_navbar($arraybar);

			$this->load->view('header', array('title' => "Facturación"));
				$this->load->view('navbar', $navbar);
				$this->load->view('facturacion_view', $data);
			$this->load->view('footer');
		}

	}


/******************************************USUARIOS******************************************/

	public function am_usuario()
	{
		// if ($this->main_model->rol($_POST['usr_usuario'], "especialista"))
		// 	$this->main_model->am_especialista($_POST['usr_usuario']);

		$data = array(
		   	'usuario' 	=> $_POST['usr_usuario'],
		   	'nombre' 	=> ucwords(strtolower($_POST['usr_nombre'])),
		   	'apellido' 	=> ucwords(strtolower($_POST['usr_apellido'])),
			'password'	=> $_POST['usr_usuario'],
		   	'funciones' => json_encode($_POST['usr_funciones'])
		);

		$this->main_model->am_usuario($data);
		// redirect('main/admin#usuarios');
	}

	public function reset_usuario()
	{
		$this->main_model->reset_usuario($_POST['usr_usuario']);
	}

	public function del_usuario()
	{
		$id = $_POST['id_usuario'];
		$this->main_model->del_usuario($id);
		// redirect('main/admin#usuarios');
	}

	// public function get_usuarios("todos")
	// {
	// 	return $this->main_model->get_data('usuarios');
	// }

	// public function get_usuarios_json()
	// {
	// 	echo json_encode($this->get_usuarios("todos"));
	// }

	public function get_usuarios($id)
	{
		if ($id == "todos")
			$agendas = $this->main_model->get_data("usuarios",null, null);
		else
			$agendas = $this->main_model->get_data("usuarios",null, array('usuario' => $id))[0];

		return $agendas;//$this->get_agendas();

		// return $this->main_model->get_data("usuarios",null,array('usuario' => $id))[0];
	}

	public function get_usuarios_json($id)
	{
		echo json_encode($this->get_usuarios($id));
	}

/******************************************AGENDAS******************************************/

	// public function add_agenda()
	// {
	// 	$this->main_model->add_agenda($_POST);
	// 	redirect('main/admin#especialistas');
	// }

	public function am_agenda()
	{
		// error_reporting(E_ALL); ini_set('display_errors', 1);
		$horarios = array();
		$especialidades = array();

		foreach ($_POST['agenda_dias'] as $key => $dia)
		{
			$horarios[$dia][1] = array(
				"desde"	=>	$_POST[$dia."_desde_man"],
				"hasta" =>	$_POST[$dia."_hasta_man"]
			);

			$horarios[$dia][2] = array(
				"desde"	=>	$_POST[$dia."_desde_tar"],
				"hasta" =>	$_POST[$dia."_hasta_tar"]
			);
		}

		$data = array(
			'id_agenda'	  		=> 	$_POST['agenda_id'],
			'nombre_agenda'		=>	ucwords(strtolower($_POST['agenda_nombre'])),
			'usuario' 			=> 	$_POST['agenda_usuario'],
		  	'especialidad' 		=> 	$_POST['agenda_especialidades'],//json_encode($especialidades),
			'dias_horarios' 	=> 	json_encode($horarios),
		  	'duracion'			=> 	$_POST['agenda_duracion']
		);

		// echo json_encode($data);
		$this->main_model->am_agenda($data);
	}

	public function del_agenda($id)
	{
		$id = $_POST['id_agenda'];
		$this->main_model->del_agenda($id);
	}

	public function get_agendas($id)
	{
		if ($id == "todos")
			$agendas = $this->main_model->get_data("agendas",null, null);
		else
			$agendas = $this->main_model->get_data("agendas",null, array('id_agenda' => $id))[0];

		return $agendas;
	}

	public function get_agendas_json($id)
	{
		echo json_encode($this->get_agendas($id));
	}

	// public function get_agenda($id)
	// {
	// 	return $this->main_model->get_data("agendas", null, array('id_agenda' => $id))[0];
	// }

	// public function get_agenda_json($id)
	// {
	// 	echo json_encode($this->get_agenda($id));
	// }

	public function get_especialidades($especialista)
	{
		$data = [];

		if ($especialista == "todos")
			$agendas = $this->main_model->get_data("agendas",null, null);
		else
			$agendas = $this->main_model->get_data("agendas",null, array('usuario' => $especialista));

		foreach ($agendas as $key => $value) {
			$data = array_merge($data, json_decode($value->especialidad));
		}

		return array_unique($data);
	}

	public function get_especialidades_json($especialista)
	{
		echo json_encode($this->get_especialidades($especialista));
	}

	public function get_especialistas()
	{
		$agendas = $this->get_usuarios("todos");//$this->main_model->get_data("usuarios");

		foreach ($agendas as $key => $value) {
			if (stripos($value->funciones,"especialista") !== false)
				$data[] = (object) array('usuario' => $value->usuario,
								'nombre' => $value->apellido.', '.$value->nombre[0]
						);
		}

		return $data;
	}

	public function get_agendas_by_esp($esp)
	{
		if ($esp == "todos")
			$where = null;
		else
			$where = array('especialidad' => $esp);

		return $this->main_model->get_data("agendas", $where, null);
	}

	public function get_agendas_by_esp_json($esp)
	{
		echo json_encode($this->get_agendas_by_esp($esp));
	}
	// Horarios de la agenda de un especialista para la fecha en cuestion. No se muestra la agenda de TODOS

	public function get_horarios($id_agenda)
	{
		return $this->main_model->get_horarios($id_agenda);
	}

	public function get_horarios_json($id_agenda)
	{
			echo json_encode($this->main_model->get_horarios($id_agenda));
	}

/******************************************PRUEBAAAAAAAAAA******************************************/
	public function arrange_turnos($turnos, $horarios)
	{

		$result = $horarios;

		if ($turnos != null) {
			if ($horarios == null) {
				$result = $turnos;
			}
			else {
				foreach ($turnos as $index => $val_turno) {

					foreach ($result as $key => $val_hora) {

						if ($val_turno->hora == $val_hora->hora) {
							$result[$key] = $val_turno;
							break;
						}
						else if ($val_turno->hora < $val_hora->hora) {
							array_splice($result, $key, 0, [$val_turno]);
							break;
						}
						else if ($key == count($result)-1){
							$result[] = $val_turno;
							break;
						}

					}

				}
			}
		}
		else {
			$result = [];
		}

		return $result;

	}

	public function get_data_turnos($year, $month, $id_agenda, $esp="")
	{

		$resultado = [];

		$array_dias = array('do', 'lu', 'ma', 'mi', 'ju', 'vi', 'sa');

		$datos_agenda = $this->main_model->get_datos_agenda($id_agenda, $esp);
		$turnos = $this->main_model->get_turnos_mes($year, $month, $datos_agenda); // Turnos del mes para todos o para la agenda seleccionada
		$horarios = $this->main_model->get_horarios($datos_agenda);
		// $horarios = $this->main_model->get_horarios($id_agenda, $esp);
		// $horarios_extra = $this->main_model->get_horarios_extra($year, $month, $id_agenda, $esp);
		$horarios_extra = $this->main_model->get_horarios_extra($year, $month, $datos_agenda);

		foreach ($turnos as $fecha => $datos) {

			$aux = null;
			$dat = $datos;

			if ($id_agenda != "todos") {
				$dow = $array_dias[date('w', strtotime($fecha))];

				if(isset($horarios[$dow])) {
					$aux = $horarios[$dow][0];
				}
				else if (isset($horarios_extra[$fecha])) {
					$aux = $horarios_extra[$fecha][0];
				}

				$dat = $this->arrange_turnos($datos, $aux);
			}

			$resultado[$fecha] = array (
				'datos' => $dat,
				'cant' => sizeof($datos)
			);

		}

		return array(
			'turnos' => $resultado,
			'horarios' => $horarios,
			'horarios_extra' => $horarios_extra
		);

	}

	public function get_data_turnos_json($year, $month, $id_agenda, $esp="")
	{
			echo json_encode($this->get_data_turnos($year, $month, $id_agenda, $esp));
	}

/******************************************TURNOS******************************************/

	public function get_turno($id)
	{
		// $especialista = $this->get_usuarios($turno->especialista);
		$turno = $this->main_model->get_data("turnos",null,array('id_turno' => $id))[0];
		$agenda = $this->main_model->get_data("agendas",null,array('id_agenda' => $turno->agenda))[0];
		// $agenda = $this->main_model->get_datos_agenda($turno->agenda)[0]->usuario;
		$especialista = $this->main_model->get_data("usuarios",null,array('usuario' => $agenda->usuario))[0];
		$paciente = $this->get_paciente($turno->id_paciente);
		$facturacion = $this->main_model->get_data("facturacion",null,array('id_turno' => $id))[0];

		$turno->name_especialista = $especialista->apellido.', '.$especialista->nombre[0];
		//$turno->especialidades = $this->get_especialidades($turno->especialista);

		$array['datos_paciente'] = $paciente;
		$array['datos_turno'] = $turno;
		$array['datos_facturacion'] = $facturacion;
		return $array;
	}

	public function get_turno_json($id)
	{
		echo json_encode($this->get_turno($id));
	}

	public function nuevo_turno()
	{
		$this->am_turno($_POST);
	}

	public function modificar_datos()
	{
		// $this->am_paciente($_POST);
		$this->am_turno($_POST);
		$this->am_facturacion($_POST);
	}

	public function am_turno($array)
	{
		$id = $this->am_paciente($array);

		$extra = array();

		if (isset($array['primera_vez']))
			array_push($extra,'primera_vez');

		$usuario = $this->session->userdata('usuario');

		$data_turno = array(
			'id_turno' 		=> $array['id_turno'],
		  	'id_paciente' 	=> $id,
		  	'fecha' 		=> $array['fecha'],
		  	'hora' 			=> $array['hora'],
			'agenda'		=> $array['id_agenda'],
		  	'especialidad' 	=> $array['especialidad'],
			'observaciones' => $array['observaciones_turno'],
			'data_extra'	=> json_encode($extra),
			'estado'		=> $array['estado'],
			'usuario'		=> $usuario
		);

		// echo json_encode($data_turno);
		$this->main_model->am_turno($data_turno);
	}

	public function del_turno()
	{
		$this->main_model->del_turno($_POST['id_turno']);
	}

	public function cambiar_turno()
	{
		$data_turno = array(
			'id_turno' 		=> $_POST['id_turno'],
		   	'fecha' 		=> $_POST['fecha'],
		   	'hora' 			=> $_POST['hora'],
		);

		// echo json_encode($data_turno);
		$this->main_model->cambiar_turno($data_turno);
	}

/******************************************PACIENTES******************************************/

	public function am_paciente($data)
	{
		$tel1 = $this->main_model->join_telefono($data['tel1'], $data['tel2']);
		$tel2 = $this->main_model->join_telefono($data['cel1'], $data['cel2']);

		if ($data['id_paciente'] != "") {
			$paciente = $this->get_paciente($data['id_paciente']);
			$data['dni'] = $paciente->dni;
			$data['direccion'] = $paciente->direccion;
			$data['localidad'] = $paciente->localidad;
			$data['observaciones_paciente'] = $paciente->observaciones;
		}

		$data_paciente = array(
			'id_paciente' 	=> $data['id_paciente'],
		  	'nombre' 		=> ucwords(strtolower($data['nombre'])),
		  	'apellido' 		=> ucwords(strtolower($data['apellido'])),
			'dni'			=> isset($data['dni']) ? $data['dni'] : "",
			'direccion'		=> isset($data['direccion']) ? ucwords(strtolower($data['direccion'])) : "",
			'localidad'		=> isset($data['localidad']) ? ucwords(strtolower($data['localidad'])) : "",
		  	'tel1' 			=> $tel1,
		  	'tel2' 			=> $tel2,
			'observaciones'	=> isset($data['observaciones_paciente']) ? $data['observaciones_paciente'] : ""
		);

		return $this->main_model->am_paciente($data_paciente);
	}

	public function get_paciente($id)
	{
		$val = $this->main_model->get_data("pacientes",null,array('id_paciente' => $id));
		if ($val != null)
			return $val[0];
		else
			return null;
	}

	public function get_paciente_json($id)
	{
			// echo json_encode($this->get_paciente($id));
			echo $this->get_paciente($id)->dni;
	}

	public function get_nombre_paciente($id)
	{
		$val = $this->main_model->get_data("pacientes",null,array('id_paciente' => $id));
		if ($val != null)
			return $val[0]->apellido.', '.$val[0]->nombre;
		else
			return null;
	}

	public function autocomplete_pacientes()
	{
		$value = $_POST['query'];

		echo json_encode($this->main_model->get_pacientes_autocomplete(array("apellido" => $value)));
	}

/******************************************NOTAS******************************************/

	public function am_nota()
	{
		$array['id_nota'] = $_POST['id_nota'];
		$array['texto'] = $_POST['texto'];
		$array['usuario'] = $this->session->userdata('usuario');
		$array['destinatario'] = $_POST['destinatario_sel'];
		$array['fecha'] = $_POST['fecha'];

		$this->main_model->am_nota($array);
	}

	public function get_nota($id,$fecha="")
	{
		$usr = $this->session->userdata('usuario');

		if ($id == "todas") {
			// $where = "fecha = '".$fecha."' AND (destinatario = 'todos' OR destinatario = '".$usr."' OR usuario = '".$usr."')";
			$where = array("fecha" => $fecha);
			$notas = $this->main_model->get_data('notas', null, $where, array("last_update","desc"));

			if ($notas != null) {
				foreach ($notas as $key => $value) {
					$usuario = $this->get_usuarios($value->usuario);
					$value->nombre_usuario = $usuario->apellido.', '.$usuario->nombre[0];
				}
			}
		}
		else {
			$notas = $this->main_model->get_data("notas",null,array('id_nota' => $id))[0];
		}

		return $notas;
	}

	public function get_nota_json($id,$fecha="")
	{
		echo json_encode($this->get_nota($id,$fecha));
	}

	public function del_nota()
	{
		$this->main_model->del_nota($_POST['id_nota']);
	}

/******************************************FACTURACION******************************************/
	// public function modificar_datos()
	// {
	// 	$this->am_turno($_POST);
	// 	$this->am_facturacion($_POST);
	//
	// 	$data_turno = array(
	// 		'id_turno' 	=> $_POST['id_turno'],
	// 		'estado' 	=> $_POST['estado']
	// 	);
	//
	// 	$this->main_model->change_turno_estado($data_turno);
	//
	// }

	public function am_facturacion($array)
	{

		// $this->am_paciente($_POST);
		//
		// $data_turno = array(
		// 	'id_turno' 	=> $_POST['id_turno'],
		// 	'estado' 	=> $_POST['estado']
		// );
		//
		// $this->main_model->change_turno_estado($data_turno);

		$usuario = $this->session->userdata('usuario');

		$data_extra = array(
			'total' => $array['total'],
			'pago'	=> $array['total'],
			'debe'	=> ""
		);

		$data_facturacion = array(
			'id_facturacion'	=> $array['id_facturacion'],
			'id_turno' 				=> $array['id_turno'],
			'fecha'						=> $array['fecha'],
			'datos'						=> json_encode($data_extra),
			'usuario'					=> $usuario
		);

		if ($array['estado'] == "OK" && $array['total'] != "")
			$this->main_model->am_facturacion($data_facturacion);
		else if ($array['estado'] != "OK" && $array['id_facturacion'] != "")
			$this->main_model->del_facturacion($array['id_facturacion']);

	}

	function am_agenda_extra()
	{

		// $agenda[1] = array();
		// $agenda[2] = array();

		if ($_POST['crear_agenda_desde_man_hora'] != "" && $_POST['crear_agenda_hasta_man_min'] != "" && $_POST['crear_agenda_hasta_man_hora'] != "" && $_POST['crear_agenda_hasta_man_min'] != "") {
			$agenda[1] = array(
				"desde" => $_POST['crear_agenda_desde_man_hora'].":".$_POST['crear_agenda_desde_man_min'],
				"hasta" => $_POST['crear_agenda_hasta_man_hora'].":".$_POST['crear_agenda_hasta_man_min']
			);
		}
		else {
			$agenda[1] = array(
				"desde" => "",
				"hasta" => ""
			);
		}

		if ($_POST['crear_agenda_desde_tar_hora'] != "" && $_POST['crear_agenda_hasta_tar_min'] != "" && $_POST['crear_agenda_hasta_tar_hora'] != "" && $_POST['crear_agenda_hasta_tar_min'] != "") {
			$agenda[2] = array(
				"desde" => $_POST['crear_agenda_desde_tar_hora'].":".$_POST['crear_agenda_desde_tar_min'],
				"hasta" => $_POST['crear_agenda_hasta_tar_hora'].":".$_POST['crear_agenda_desde_tar_min']
			);
		}
		else {
			$agenda[2] = array(
				"desde" => "",
				"hasta" => ""
			);
		}

		$fecha = date('Y-m-d', strtotime($_POST['crear_agenda_fecha']));

		$data = array(
			"id" 				=> $_POST['crear_id'],
			"id_agenda" => $_POST['crear_agenda_id'],
			"fecha" 		=> $fecha,
			"horarios" 	=> json_encode($agenda),
			"duracion" 	=> $_POST['crear_agenda_duracion'],
			"usuario"		=> $this->session->userdata('usuario')
		);

		$this->main_model->am_agenda_extra($data);
	}

	public function get_agenda_extra($agenda, $fecha)
	{
		return $this->main_model->get_agenda_extra($agenda, $fecha);
	}

	public function get_agenda_extra_json($agenda, $fecha)
	{
		echo json_encode($this->get_agenda_extra($agenda, $fecha));
	}

	public function del_agenda_extra()
	{
		$data['agenda'] = $_POST['agenda'];
		$data['fecha'] = $_POST['fecha'];
		$this->main_model->del_agenda_extra($data);
	}

	function amr_test()
	{
		$this->load->view('header', array('title' => "AMR"));
			$this->load->view('amr_test');
		$this->load->view('footer');
	}

	function convenios() {

		// $url = 'https://www.amr.org.ar/gestion/webServices/autorizador/test/profesiones';
		$login = '38026';
		$password = 'IUUIASUX';
		$url = 'https://www.amr.org.ar/gestion/webServices/autorizador/v3/convenios';
		$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
			$result = curl_exec($ch);
		curl_close($ch);

		echo json_encode($result);
	}

}
