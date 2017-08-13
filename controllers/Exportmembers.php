<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Exportmembers extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('pmembers_model','print_members');
	}

	public function index()
	{
		$this->load->helper('url');
		$this->load->view('members/print_members_view');
		$this->load->view('templates/footer');
	}

	public function ajax_list()
	{
		$list = $this->print_members->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $print_members) 
		{
			if($print_members->disabled == 0){

				$no++;
				$row = array();
				$row[] = $print_members->member_stallno;
				$row[] = $print_members->member_first_name;
				$row[] = $print_members->member_last_name;
				$row[] = $print_members->member_section;
				$row[] = $print_members->member_address;
					$row[] = $print_members->member_age;
					$row[] = $print_members->member_sex;
					$row[] = $print_members->member_cstatus;
					$row[] = $print_members->member_birthdate;
				
				$row[] = $print_members->member_contactno;
				$data[] = $row;

			}
			
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->print_members->count_all(),
						"recordsFiltered" => $this->print_members->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	

}