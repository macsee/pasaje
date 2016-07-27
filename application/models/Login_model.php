<?php
// defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}

    public function check_login($array)
    {
        $query = $this->db->get_where("usuarios", array('usuario' => $array['usuario'], 'password' => $array['password']));

        if ($query->num_rows()>0) {
					return $query->row();
				}
				else {
					return null;
				}
    }

    public function change_pass($array)
    {
        $this->db->update('usuarios', array('password' => $array['password']), array('usuario' => $array['usuario']));

        return $this->check_login($array);
    }

    public function update_time($array)
    {
        $this->db->update('usuarios', array('last_login' => date('Y-m-d H:i')), array('usuario' => $array['usuario']));
    }

}
