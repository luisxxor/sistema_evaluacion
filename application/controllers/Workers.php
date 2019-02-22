<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Workers extends CI_Controller {
  
	public function __construct() {
		parent::__construct();
		$this->load->model('User_model', 'user');
		$this->load->library('form_validation');
	}
	
	public function index()
	{
		if ($this->session->userdata('is_authenticated') == FALSE) {
			redirect('users/login');
		} else {
			$data['title'] = 'Listado de Trabajadores';
			$data['content'] = 'workers/index';
			$this->load->view('template', $data);
		}
	}
	
	public function create() {
		if ($this->session->userdata('is_authenticated') == FALSE) {
			echo json_encode(['status' => '403','message' => 'Permission Denied']);
			return null;
		}

		$data = json_decode($this->input->post('worker_form'),true);

		$result = $this->worker->form_insert($data);

		if($result > 0)
		{
			echo json_encode(['status' => '201', 'message' => 'Trabajador creado exitosamente']);
		}
		else
		{
			echo json_encode(['status' => '500', 'message' => 'Trabajador no creado, ha ocurrido un error']);
		}
	}

	public function update() {
		if ($this->session->userdata('is_authenticated') == FALSE) {
			echo json_encode(['status' => '403','message' => 'Permission Denied']);
			return null;
		}

		$data = json_decode($this->input->post('worker_form'),true);

		$result = $this->worker->form_update($data);

		if($result > 0)
		{
			echo json_encode(['status' => '200', 'message' => 'Trabajador actualizado exitosamente']);
		}
		else
		{
			echo json_encode(['status' => '500', 'message' => 'Trabajador no actualizado, ha ocurrido un error', 'response' => $result]);
		}
	}

	public function delete() {
		if ($this->session->userdata('is_authenticated') == FALSE) {
			echo json_encode(['status' => '403','message' => 'Permission Denied']);
			return null;
		}

		$id = $this->input->post('id');

		$result = $this->worker->delete($id);

		if($result > 0)
		{
			echo json_encode(['status' => '200', 'message' => 'Trabajador eliminado correctamente']);
		}
		else
		{
			echo json_encode(['status' => '500', 'message' => 'Trabajador no eliminado, ha ocurrido un error', 'response' => $result]);
		}
	}
}
