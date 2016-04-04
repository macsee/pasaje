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
		$data['especialidad_sel'] = $this->session->userdata('especialidad');
		$data['usuario'] = $this->session->userdata('usuario');
		$data['is_admin'] = $this->main_model->rol($this->session->userdata('usuario'),"admin") ? 1 : 0;

		if ($this->main_model->rol($this->session->userdata('usuario'),"especialista")) {
			$data['especialistas'] = null;
			$data['nom_especialista_sel'] = $this->session->userdata('apellido').', '.$this->session->userdata('nombre')[0];
		}
		else
			$data['especialistas'] = $this->main_model->get_data('usuarios', array('funciones' => 'especialista'));

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
			$data['especialistas'] = $this->main_model->get_data("especialistas_especialidades");

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
		$data['especialistas'] = $this->main_model->get_data("especialistas_especialidades");

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
			$data['especialistas'] = $this->main_model->get_data("especialistas_especialidades");

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

	public function add_usuario()
	{
		$this->main_model->add_usuario($_POST);

		if (array_search('especialista', $_POST['usr_funciones']) > 0)
			$this->main_model->add_especialista($_POST['usr_usuario']);

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

	public function del_usuario()
	{
		$this->main_model->del_usuario($_POST['usr_usuario']);
		redirect('main/admin#usuarios');
	}

	public function add_especialidad()
	{
		$this->main_model->add_especialidad($_POST);
		redirect('main/admin#especialistas');
	}

	public function get_especialidad($id, $especialidad="")
	{
		$array['usuario'] = $id;

		if ($especialidad != "")
			$array['especialidad'] = $especialidad;

		return $this->main_model->get_data("especialistas_especialidades", null, $array)[0];
	}

	public function get_especialidad_json($id, $especialidad="")
	{
		echo json_encode($this->get_especialidad($id,$especialidad));
		// echo json_encode($this->main_model->get_especialidad_by($id,$especialidad));
	}

	public function get_especialidades($id)
	{
		return $this->main_model->get_data("especialistas_especialidades", null, array('usuario' => $id));
	}

	public function get_especialidades_json($id)
	{
		echo json_encode($this->get_especialidades($id));
	}

	public function get_turnos_dia_esp($fecha, $especialista, $especialidad="")
	{
		$array['fecha'] = $fecha;
		$horarios = array();

		if ($especialidad != "")
			$array['especialidad'] = $especialidad;

		if ($especialista != "todos") {
			$array['especialista'] = $especialista;
			$horarios = $this->get_dias_horarios($fecha, $especialista);
		}

		$turnos = $this->main_model->get_data("turnos", null, $array);

		if ($turnos != null)
			foreach ($turnos as $key => $value) {
				$nombre_esp = $this->get_usuario($value->especialista);
				$horarios[date('H:i',strtotime($value->hora))] = (object) array(
									'id_turno' => $value->id_turno,
									'id_paciente' => $value->id_paciente,
									'paciente' => $this->get_nombre_paciente($value->id_paciente),
									'especialidad' => $value->especialidad,
									'especialista' => $nombre_esp->apellido.", ".$nombre_esp->nombre,
									'estado' => $value->estado,
									'data_extra' => $value->data_extra
								);
			}

		return $horarios;

	}

	public function get_turnos_dia_esp_json($fecha, $especialista, $especialidad="")
	{
		echo json_encode($this->get_turnos_dia_esp($fecha, $especialista, $especialidad));
	}

	public function get_turno($id)
	{
		$turno = $this->main_model->get_data("turnos",null,array('id_turno' => $id))[0];
		$especialista = $this->get_usuario($turno->especialista);
		$paciente = $this->get_paciente($turno->id_paciente);
		$facturacion = $this->main_model->get_data("facturacion",null,array('id_turno' => $id))[0];

		$turno->name_especialista = $especialista->apellido.', '.$especialista->nombre[0];
		$turno->especialidades = $this->get_especialidades($turno->especialista);

		$array['datos_paciente'] = $paciente;
		$array['datos_turno'] = $turno;
		$array['datos_facturacion'] = $facturacion;
		return $array;
	}

	public function get_turno_json($id)
	{
		echo json_encode($this->get_turno($id));
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

	public function get_dias_horarios($fecha, $id, $especialidad="")
	{
			// $esp = $this->get_especialidad($id,$especialidad)[];
			$array['usuario'] = $id;

			if ($especialidad != "")
				$array['especialidad'] = $especialidad;

			$array_dias = array('do', 'lu', 'ma', 'mi', 'ju', 'vi', 'sa');
			$day = $array_dias[date('w', strtotime($fecha))];

			$esp = $this->main_model->get_data("especialistas_especialidades", null, $array)[0];
			$duracion = $esp->duracion;
			$dias = json_decode($esp->dias_horarios);

			if (isset($dias->$day)) {
				$horarios = $dias->$day;
				$hora_hasta = strtotime($horarios->hasta);
				$hora_desde = strtotime($horarios->desde);
				$diff = abs($hora_hasta - $hora_desde)/60;
				$cant_turnos = $diff/$duracion;

				$horarios_esp = array();
				for ($i=0; $i <= $cant_turnos ; $i++) {
					$horarios_esp[date('H:i',$hora_desde+($i*$duracion*60))] = "";
				}
				return $horarios_esp;
			}
			else {
				return null;
			}
	}

	public function am_turno()
	{
		$id = $this->am_paciente($_POST);

		$extra = array();

		if (isset($_POST['primera_vez']))
			array_push($extra,'primera_vez');

		$usuario = "";

		$data_turno = array(
			'id_turno' 		=> $_POST['id_turno'],
		   	'id_paciente' 	=> $id,
		   	'fecha' 		=> $_POST['fecha'],
		   	'hora' 			=> $_POST['hora'],
		   	'especialista' 	=> $_POST['id_especialista'],
		   	'especialidad' 	=> $_POST['especialidad'],
			'observaciones' => $_POST['observaciones'],
			'data_extra'	=> json_encode($extra),
			'estado'		=> "",
			'usuario'		=> $usuario
		);

		$this->main_model->am_turno($data_turno);

		// echo json_encode($_POST);
		// redirect('main/agenda/');
	}

	public function cambiar_turno()
	{
		$data_turno = array(
			'id_turno' 		=> $_POST['id_turno'],
		   	'fecha' 		=> $_POST['fecha'],
		   	'hora' 			=> $_POST['hora'],
		);

		echo json_encode($data_turno);
		$this->main_model->cambiar_turno($data_turno);
		// $this->session->set_userdata('id_turno',$id_turno);
	}

	public function am_facturacion()
	{

		$this->am_paciente($_POST);

		$data_turno = array(
			'id_turno' 	=> $_POST['id_turno'],
			'estado' 	=> $_POST['estado']
		);

		$this->main_model->change_turno_estado($data_turno);
		$usuario = "";

		$data_extra = array(
			'total' => $_POST['total'],
			'pago'	=> $_POST['total'],
			'debe'	=> ""
		);

		$data_facturacion = array(
			'id_facturacion'	=> $_POST['id_facturacion'],
			'id_turno' 			=> $_POST['id_turno'],
			'fecha'				=> $_POST['fecha'],
			'datos'				=> json_encode($data_extra),
			'usuario'			=> $usuario
		);

		$this->main_model->am_facturacion($data_facturacion);

		// redirect('main/agenda/');
	}

	public function del_turno()
	{
		$this->main_model->del_turno($_POST['id_turno']);
	}

	public function am_paciente($data)
	{
		$tel1 = $this->main_model->join_telefono($data['tel1'], $data['tel2']);
		$tel2 = $this->main_model->join_telefono($data['cel1'], $data['cel2']);

		$data_paciente = array(
			'id_paciente' 	=> $data['id_paciente'],
		   	'nombre' 		=> ucwords(strtolower($data['nombre'])),
		   	'apellido' 		=> ucwords(strtolower($data['apellido'])),
			'dni'			=> isset($data['dni']) ? $data['dni'] : "",
			'direccion'		=> isset($data['direccion']) ? ucwords(strtolower($data['direccion'])) : "",
			'localidad'		=> isset($data['localidad']) ? ucwords(strtolower($data['localidad'])) : "",
		   	'tel1' 			=> $tel1,
		   	'tel2' 			=> $tel2,
			'observaciones'	=> isset($data['observaciones_paciente']) ? $data['observaciones_paciente'] : "",
		);

		return $this->main_model->am_paciente($data_paciente);
	}

	public function autocomplete_pacientes()
	{
		$value = $_POST['query'];

		echo json_encode($this->main_model->get_pacientes_autocomplete(array("apellido" => $value)));
	}

	public function am_nota()
	{
		$array['id_nota'] = $_POST['id_nota'];
		$array['texto'] = $_POST['texto'];
		$array['usuario'] = $this->session->userdata('usuario');
		$array['destinatario'] = $_POST['destinatario'];
		$array['fecha'] = date('Y-m-d',strtotime($_POST['fecha']));

		$this->main_model->am_nota($array);
	}

	public function get_notas($fecha)
	{
		// if ($dest == "todos")
		$notas = $this->main_model->get_data('notas', null, array('fecha' => $fecha), "last_update");
		// else
		// 	$notas = $this->main_model->get_data('notas', null, array('fecha' => $fecha, 'destinatario' => $dest), "last_update");
		if ($notas != null) {
			foreach ($notas as $key => $value) {
				$usuario = $this->get_usuario($value->usuario);
				$value->nombre_usuario = $usuario->apellido.', '.$usuario->nombre[0];
			}
		}

		return $notas;
	}

	public function get_notas_json($fecha)
	{
		echo json_encode($this->get_notas($fecha));
	}

	public function get_nota($id)
	{
		return $this->main_model->get_data("notas",null,array('id_nota' => $id))[0];
	}

	public function get_nota_json($id)
	{
		echo json_encode($this->get_nota($id));
	}

	public function del_nota()
	{
		$this->main_model->del_nota($_POST['id_nota']);
	}

	public function show_notas($fecha, $especialista_sel)
	{
		$notas = $this->get_notas($fecha);
		$usuario = $this->session->userdata('usuario');
		$result = "";

		if ($notas != null) {
	        $result .= '<ul style = "margin-left:-25px">';

	        foreach ($notas as $key => $value) {
	            if ($value->destinatario == $especialista_sel || $value->destinatario == "todos" || $especialista_sel == "todos") {

	                if ($usuario == $value->usuario) {
	                    $onclick = "return editar_nota('".$value->id_nota."')";
	                }
	                else {
	                    $onclick = 'return error_nota()';
	                }

	                $result .=	'<li style = "min-height:40px;margin-bottom:25px">
	                            	<a onclick = "'.$onclick.'">'.$value->texto.'</a>
	                            	<span class = "pull-right text-muted small" style = "width:100%"><i>'.$value->last_update.' - '.$value->nombre_usuario.'</i></span>
	                        	</li>';
	            }
	        }

	        $result .= '</ul>';
	    }
		else {
			$result .= '<i>No hay notas para fecha</i>';
		}

		echo $result;

	}

	function show_turnos($fecha, $especialista_sel, $especialidad_sel="")
	{

		$turnos = $this->get_turnos_dia_esp($fecha,$especialista_sel,$especialidad_sel);
		$tabla = "";
		$header_especialista = "";

		if ($especialista_sel == "todos")
			$header_especialista = '<th>Especialista</th>';

		if ($turnos != null) {

			$tabla = 	'<table class="table">
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
					// if (JSON.parse($value->data_extra).indexOf('primera_vez') >= 0)
					// 	$primera_vez = "color:#d9534f";

					if ($especialidad_sel == "todos")
						$row_especialista = '<td>'.$value->especialista.'</td>';

					if ($value->estado != "")
						$estado = "glyphicon glyphicon-check";

					$tabla .=
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

					$tabla .=
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


			$tabla .=
					'</tbody>
				</table>';
		}

		echo $tabla;
	}

}
