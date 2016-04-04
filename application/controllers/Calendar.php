<?php

class Calendar extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('main_model');
		$this->load->model('calendar_model');
		$this->load->helper('url_helper');
	}

	function make_calendar($especialista, $especialidad="")
	{
		$year = date('Y');
		$month = date('m');

		$this->session->set_userdata('especialista', $especialista);
		$this->session->set_userdata('especialidad', $especialidad);

		$this->show_calendar($year, $month);
	}

	function show_calendar($year, $month)
	{
		$especialista = $this->session->userdata('especialista');
		$especialidad = $this->session->userdata('especialidad');

		$data['calendario'] = $this->calendar_model->create_calendar($especialista, $especialidad, $year, $month);
		$this->load->view('header');
		$this->load->view('calendario_view', $data);
		$this->load->view('footer');
	}
}
?>
