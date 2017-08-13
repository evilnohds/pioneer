<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Exportusers extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('pusers_model','print_users');
	}

	public function index()
	{
		$this->load->helper('url');
		$this->load->view('users/print_users_view');
		$this->load->view('templates/footer');
	
	}

	public function ajax_list()
	{
		$list = $this->print_users->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $print_users) 
		{
			if($print_users->disabled == 0){
				$no++;
				$row = array();
				$row[] = $print_users->name;
				$row[] = $print_users->stall_no;
				$row[] = $print_users->access_lvl;
				$row[] = $print_users->email;
				$row[] = $print_users->username;
				$data[] = $row;
			}			
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->print_users->count_all(),
						"recordsFiltered" => $this->print_users->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	

}