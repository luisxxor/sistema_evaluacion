<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Positions extends CI_Controller {
  
	public function __construct() {
		parent::__construct();
		$this->load->model('Position_model', 'position');
		$this->load->library('form_validation');
	}
	
	public function index()
	{
		if ($this->session->userdata('is_authenticated') == FALSE) {
			redirect('users/login');
		} else {
			$data['title'] = 'Listado de Cargos';
			$data['content'] = 'positions/index';
			$this->load->view('template', $data);
		}
	}

	public function read()
	{
		if ($this->session->userdata('is_authenticated') == FALSE) {
			echo json_encode(['status' => '403','message' => 'Permission Denied']);
			return null;
		}

		$data = $this->position->getAll();

		echo json_encode([
			'positions' => $data
		]);
	}
	
	public function create() {
		if ($this->session->userdata('is_authenticated') == FALSE) {
			echo json_encode(['status' => '403','message' => 'Permission Denied']);
			return null;
		}

		$data = json_decode($this->input->post('position_form'),true);

		$result = $this->position->form_insert($data);

		if($result > 0)
		{
			echo json_encode(['status' => '201', 'message' => 'Cargo creado exitosamente']);
		}
		else
		{
			echo json_encode(['status' => '500', 'message' => 'Cargo no creado, ha ocurrido un error']);
		}
	}

	public function update() {
		if ($this->session->userdata('is_authenticated') == FALSE) {
			echo json_encode(['status' => '403','message' => 'Permission Denied']);
			return null;
		}

		$data = json_decode($this->input->post('position_form'),true);

		$result = $this->position->form_update($data);

		if($result > 0)
		{
			echo json_encode(['status' => '200', 'message' => 'Cargo actualizado exitosamente']);
		}
		else
		{
			echo json_encode(['status' => '500', 'message' => 'Cargo no actualizado, ha ocurrido un error', 'response' => $result]);
		}
	}

	public function delete() {
		if ($this->session->userdata('is_authenticated') == FALSE) {
			echo json_encode(['status' => '403','message' => 'Permission Denied']);
			return null;
		}

		$id = $this->input->post('id');

		$result = $this->position->delete($id);

		if($result > 0)
		{
			echo json_encode(['status' => '200', 'message' => 'Cargo eliminado correctamente']);
		}
		else
		{
			echo json_encode(['status' => '500', 'message' => 'Cargo no eliminado, ha ocurrido un error', 'response' => $result]);
		}
	}
}
