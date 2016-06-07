<?php

class Calendar_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function create_calendar($especialista, $especialidad, $year, $month)
	{

		$array_dias = array('do', 'lu', 'ma', 'mi', 'ju', 'vi', 'sa');

		$conf = array (
			'show_next_prev' => true,
			'next_prev_url' => base_url().'index.php/calendar/show_calendar/'
		);

		$conf['template'] = '

   		{table_open}<table border="0" cellpadding="0" cellspacing="0" class = "calendar">{/table_open}

   		{heading_row_start}<tr class = "cabecera">{/heading_row_start}

   		{heading_previous_cell}<th class = "previous"><a href="{previous_url}"><span class = "glyphicon glyphicon-chevron-left"></span></a></th>{/heading_previous_cell}
   		{heading_title_cell}<th colspan="{colspan}">{heading}</th>{/heading_title_cell}
   		{heading_next_cell}<th class = "next"><a href="{next_url}"><span class = "glyphicon glyphicon-chevron-right"></span></a></th>{/heading_next_cell}

		{heading_row_end}</tr>{/heading_row_end}

 		{week_row_start}<tr class = "semana">{/week_row_start}
   		{week_day_cell}<td class = "dia_semana">{week_day}</td>{/week_day_cell}
   		{week_row_end}</tr>{/week_row_end}

   		{cal_row_start}<tr class ="days">{/cal_row_start}
   		{cal_cell_start}<td>{/cal_cell_start}

   		{cal_cell_content}{content}{/cal_cell_content}
   		{cal_cell_content_today}<div class="highlight">{content}</div>{/cal_cell_content_today}

   		{cal_cell_no_content}{day}{/cal_cell_no_content}
   		{cal_cell_no_content_today}<div class="highlight">{day}</div>{/cal_cell_no_content_today}

		{cal_cell_blank}&nbsp;{/cal_cell_blank}

	   	{cal_cell_end}</div></td>{/cal_cell_end}
   		{cal_row_end}</tr>{/cal_row_end}

   		{table_close}</table>{/table_close}';

		// {cal_cell_no_content}<a href="{day}">{day}</a>{/cal_cell_no_content}
		 // Cantidad de turnos que puede dar un especialista para cada dia de la semana
		// $especialista = "eguercio";
		// $especialidad = "";

		$horarios = $this->main_model->get_horarios($especialista, $especialidad);

   		for ($dia=1; $dia <= 31; $dia++)
   		{
			$fecha = $year.'-'.$month.'-'.$dia;
			$day_name = $array_dias[date('w', strtotime($fecha))];

			$horarios_extra = $this->main_model->get_agenda_extra_dia($fecha, $especialista, $especialidad="");

			// Cantidad de turnos dados para una fecha indicada para un especialista
			// $cant = count($this->main_model->get_turnos($fecha, $especialista));
			$cant_turnos = 0;
			$factor = -1;

			if ($especialista == "todos") {

				$cant = count($this->main_model->get_data("turnos", null, array('fecha' => $fecha)));

				if (isset($horarios[$day_name])) {
					foreach ($horarios[$day_name] as $key => $value) {
						$cant_turnos += $value->cant_turnos;
					}
					$factor = $cant/$cant_turnos;
				}
				else if (isset($horarios_extra)) {
					foreach ($horarios_extra as $key => $value) {
						$cant_turnos += $value->cant_turnos;
					}
					$factor = $cant/$cant_turnos;
				}

			}
			else {

				$cant = count($this->main_model->get_data("turnos", null, array('fecha' => $fecha, 'especialista' => $especialista)));

				if (isset($horarios[$day_name][$especialista])) {
					$cant_turnos += $horarios[$day_name][$especialista]->cant_turnos;
					$factor = $cant/$cant_turnos;
				}
				else if (isset($horarios_extra[$especialista])) {
					$cant_turnos += $horarios_extra[$especialista]->cant_turnos;
					$factor = $cant/$cant_turnos;
				}

			}

			// $factor = $cant/$cant_turnos;

			// if (isset($horarios[$day_name])) {
			// 	if ($especialista == "todos")
			//
			// 	$cant_turnos = $horarios[$day_name]->cant_turnos;
			// 	$factor = $cant/$cant_turnos;
			// }

			switch (true) {
				case ($factor == 0):
					$tipo_celda = "celda_vacia";
					break;
				case ($factor > 0 && $factor < 0.30):
					$tipo_celda = "celda_low";
					break;
				case ($factor >= 0.30 && $factor < 0.75):
					$tipo_celda = "celda_medium";
					break;
				case ($factor >= 0.75 && $factor < 1):
					$tipo_celda = "celda_high";
					break;
				case ($factor >= 1):
					$tipo_celda = "celda_full";
					break;
				default:
					$tipo_celda = "";

			}

			$cal_data[$dia] = '<div class = "celda '.$tipo_celda.'" onclick = "return parent.set_fecha(\''.$fecha.'\')">'.$dia.'</div>';
		}

		$this->load->library('calendar', $conf);
		return $this->calendar->generate($year, $month, $cal_data);
	}

}

?>
