<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Areas extends CI_Controller {
  
	public function __construct() {
		parent::__construct();
		$this->load->model('Area_model', 'area');
		$this->load->library('form_validation');
	}
	
	public function index()
	{
		if ($this->session->userdata('is_authenticated') == FALSE) {
			redirect('users/login');
		} else {
			$data['title'] = 'Listado de Areas';
			$data['content'] = 'areas/index';
			$this->load->view('template', $data);
		}
	}

	public function read()
	{
		if ($this->session->userdata('is_authenticated') == FALSE) {
			echo json_encode(['status' => '403','message' => 'Permission Denied']);
			return null;
		}

		$data = $this->area->getAll();

		echo json_encode([
			'areas' => $data
		]);
	}
	
	public function create() {
		if ($this->session->userdata('is_authenticated') == FALSE) {
			echo json_encode(['status' => '403','message' => 'Permission Denied']);
			return null;
		}

		$data = json_decode($this->input->post('area_form'),true);

		$result = $this->area->form_insert($data);

		if($result > 0)
		{
			echo json_encode(['status' => '201', 'message' => 'Area creada exitosamente']);
		}
		else
		{
			echo json_encode(['status' => '500', 'message' => 'Area no creada, ha ocurrido un error']);
		}
	}

	public function update() {
		if ($this->session->userdata('is_authenticated') == FALSE) {
			echo json_encode(['status' => '403','message' => 'Permission Denied']);
			return null;
		}

		$data = json_decode($this->input->post('area_form'),true);

		$result = $this->area->form_update($data);

		if($result > 0)
		{
			echo json_encode(['status' => '200', 'message' => 'Area actualizada exitosamente']);
		}
		else
		{
			echo json_encode(['status' => '500', 'message' => 'Area no actualizada, ha ocurrido un error', 'response' => $result]);
		}
	}

	public function delete() {
		if ($this->session->userdata('is_authenticated') == FALSE) {
			echo json_encode(['status' => '403','message' => 'Permission Denied']);
			return null;
		}

		$id = $this->input->post('id');

		$result = $this->area->delete($id);

		if($result > 0)
		{
			echo json_encode(['status' => '200', 'message' => 'Area eliminada correctamente']);
		}
		else
		{
			echo json_encode(['status' => '500', 'message' => 'Area no eliminada, ha ocurrido un error', 'response' => $result]);
		}
	}
}
