<?php

class Main extends CI_Controller
{

	function __construct()
	{

		parent::__construct();
		if ($this->session->userdata('is_loggedin')) {

			redirect('produk');
		}
	}
	function index()
	{

		$this->load->view('main');
	}


	function login()
	{
		$email = $this->input->post('email');
		$password = $this->input->post('password');

		$data_user = $this->User_model->login($email, $password);
		if ($data_user) {
			$user_data = array(
				'photo' => $data_user->photo,
				'fullname' => $data_user->fullname,
				'email' => $data_user->email,
				'posisi' => $data_user->posisi,
				'is_loggedin' => 1
			);

			$this->session->set_userdata($user_data);
			redirect('produk');
		} else {
			redirect('main');
		}
	}
}
