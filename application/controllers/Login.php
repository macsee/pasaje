<?php

class Login extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('login_model');
		$this->load->model('main_model');
		$this->load->helper('url_helper');
		$this->load->library('session');

		// $this->session->sess_destroy();

		// $logged = $this->session->userdata('is_logged');
		//
		// if ($logged)
		// 	redirect('main');
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
		$this->session->sess_destroy();
		$this->load->view("header", array("title" => "Log In"));
		$this->load->view("login_view");
		$this->load->view("footer");
	}

	public function check_login()
	{
		$result = $this->login_model->check_login($_POST);

		echo json_encode($result);
	}

	public function set_credentials()
	{
		// echo json_encode($_POST);

		$loginArray = array(
			'usuario' 			=> $_POST['usuario'],
			'nombre' 				=> $_POST['nombre'],
			'apellido' 			=> $_POST['apellido'],
			'is_logged' 		=> true,
			'especialista'	=> ($this->main_model->rol($_POST['usuario'],"especialista") ? $_POST['usuario'] : "todos"),
			'especialidad' 	=> "",
			'last_session' 	=> date('Y-m-d H:i')
		);

		$this->session->set_userdata($loginArray);
		$this->login_model->update_time($loginArray);
		if ($this->login_model->check_function($_POST['usuario'],"grupos"))
			echo "agenda_grupos";
		else
			echo "agenda_grupos";
	}

	public function change_pass()
	{
		echo json_encode($this->login_model->change_pass($_POST));
	}

	public function logout()
	{
		$this->index();
	}

}
