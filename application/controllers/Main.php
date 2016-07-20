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
		$data['usuarios'] = $this->main_model->get_data('usuarios');
		$data['is_admin'] = $this->main_model->rol($this->session->userdata('usuario'),"admin") ? 1 : 0;

		if ($data['especialista_sel'] != "todos") {
				$data['especialistas'] = $this->main_model->get_data('agendas', array('usuario' => $data['especialista_sel']));
		}
		else {
				$data['especialistas'] = $this->main_model->get_data('agendas');
		}

		// if ($this->main_model->rol($this->session->userdata('usuario'),"especialista")) {
		// 	$data['especialistas'] = null;
		// 	$data['nom_especialista_sel'] = $this->session->userdata('apellido').', '.$this->session->userdata('nombre')[0];
		// }
		// else
		// 	$data['especialistas'] = $this->main_model->get_data('agendas', array('usuario' => $this->session->userdata('usuario')));
			// $data['especialistas'] = $this->main_model->get_data('usuarios', array('funciones' => 'especialista'));

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
			$this->load->view('modal_eliminar_turno');
			$this->load->view('modal_datos');
			$this->load->view('modal_cambiar_turno');
			$this->load->view('modal_notas', $data);
			$this->load->view('modal_error_notas');
		$this->load->view('footer');
	}

	public function admin()
	{
		if (!$this->main_model->rol($this->session->userdata('usuario'),"admin"))
			redirect('main/agenda');
		else {
			$data['usuarios'] = $this->main_model->get_data("usuarios");
			$data['especialistas'] = $this->main_model->get_data("agendas");

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
				$this->load->view('admin_view', $data);
			$this->load->view('footer');
		}
	}

	public function pacientes()
	{
		$data['usuarios'] = $this->main_model->get_data("usuarios");
		$data['especialistas'] = $this->main_model->get_data("agendas");

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

			$data['usuarios'] = $this->main_model->get_data("usuarios");
			$data['especialistas'] = $this->main_model->get_data("agendas");

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
		if ($this->main_model->rol($_POST['usr_usuario'], "especialista"))
			$this->main_model->am_especialista($_POST['usr_usuario']);

		$this->main_model->am_usuario($_POST);
		redirect('main/admin#usuarios');
	}

	public function del_usuario()
	{
		$this->main_model->del_usuario($_POST['usr_usuario']);
		redirect('main/admin#usuarios');
	}

	public function get_usuario($id)
	{
		return $this->main_model->get_data("usuarios",null,array('usuario' => $id))[0];
	}

	public function get_usuario_json($id)
	{
		echo json_encode($this->get_usuario($id));
	}

/******************************************AGENDAS******************************************/

	public function add_agenda()
	{
		$this->main_model->add_agenda($_POST);
		redirect('main/admin#especialistas');
	}

	public function get_data_agenda($id)
	{
		return $this->main_model->get_data("agendas", null, array('id_agenda' => $id))[0];
	}

	public function get_data_agenda_json($id)
	{
		echo json_encode($this->get_data_agenda($id));
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

	public function get_data_turnos($year, $month, $id_agenda)
	{

		$resultado = [];

		$array_dias = array('do', 'lu', 'ma', 'mi', 'ju', 'vi', 'sa');

		$datos_agenda = $this->main_model->get_datos_agenda($id_agenda);
		$turnos = $this->main_model->get_turnos_mes($year, $month, $datos_agenda); // Turnos del mes para todos o para la agenda seleccionada
		$horarios = $this->main_model->get_horarios($id_agenda);
		$horarios_extra = $this->main_model->get_horarios_extra($year, $month, $id_agenda);

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

	public function get_data_turnos_json($year, $month, $id_agenda)
	{
			echo json_encode($this->get_data_turnos($year, $month, $id_agenda));
	}

/******************************************TURNOS******************************************/

	public function get_turno($id)
	{
		// $especialista = $this->get_usuario($turno->especialista);
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
			'id_turno' 			=> $array['id_turno'],
		  'id_paciente' 	=> $id,
		  'fecha' 				=> $array['fecha'],
		  'hora' 					=> $array['hora'],
			'agenda'				=> $array['id_agenda'],
		  'especialidad' 	=> $array['especialidad'],
			'observaciones' => $array['observaciones_turno'],
			'data_extra'		=> json_encode($extra),
			'estado'				=> $array['estado'],
			'usuario'				=> $usuario
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

		$data_paciente = array(
			'id_paciente' 	=> $data['id_paciente'],
		  'nombre' 				=> ucwords(strtolower($data['nombre'])),
		  'apellido' 			=> ucwords(strtolower($data['apellido'])),
			'dni'						=> isset($data['dni']) ? $data['dni'] : "",
			'direccion'			=> isset($data['direccion']) ? ucwords(strtolower($data['direccion'])) : "",
			'localidad'			=> isset($data['localidad']) ? ucwords(strtolower($data['localidad'])) : "",
		  'tel1' 					=> $tel1,
		  'tel2' 					=> $tel2,
			'observaciones'	=> isset($data['observaciones_paciente']) ? $data['observaciones_paciente'] : "",
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
			$where = "fecha = '".$fecha."' AND (destinatario = 'todos' OR destinatario = '".$usr."' OR usuario = '".$usr."')";
			$notas = $this->main_model->get_data('notas', null, $where, array("last_update","desc"));

			if ($notas != null) {
				foreach ($notas as $key => $value) {
					$usuario = $this->get_usuario($value->usuario);
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

	function crear_agenda()
	{
		$extra = $this->main_model->get_data("agendas", null, array('usuario' => $_POST['crear_agenda_especialistas']))[0];
		$array = (array) json_decode($extra->agenda_extra);

		$agenda = array(
			"hora_desde" => $_POST['crear_agenda_hora_desde'],
			"hora_hasta" => $_POST['crear_agenda_hora_hasta'],
			"duracion" => $_POST['crear_agenda_duracion']
		);

		$fecha = date('Y-m-d', strtotime($_POST['crear_agenda_fecha']));

		$array[$fecha] = $agenda;

		$data = array(
			"usuario" => $_POST['crear_agenda_especialistas'],
			"agenda" => json_encode($array)
		);

		$this->main_model->crear_agenda($data);
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
