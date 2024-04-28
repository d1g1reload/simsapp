<?php

class Users extends CI_Controller
{

	function __construct()
	{

		parent::__construct();
		if (!$this->session->userdata('is_loggedin')) {

			$this->session->set_flashdata('failed', 'Silahkan login terlebih dahulu');

			redirect('main');
		}
	}


	function profile()
	{
		$email = $this->session->userdata('email');
		$data['users'] = $this->User_model->get_user($email);
		$data['content'] = "app/profile";
		$this->load->view('layouts/main', $data);
	}



	function process_update_password()
	{
		$email = $this->input->post('email_user');
		$password = $this->input->post('password');
		$options = ['cost' => 12];
		$encrypt_password = password_hash($password, PASSWORD_BCRYPT, $options);
		$data_password = array(
			'password' => $encrypt_password
		);
		if ($data_password) {
			$this->User_model->updated_password($email, $data_password);
			$this->session->set_flashdata('success', 'You Password has been updated.');
			redirect('main');
		}
	}

	function logout()
	{
		$this->session->sess_destroy();
		redirect('main');
	}
}
