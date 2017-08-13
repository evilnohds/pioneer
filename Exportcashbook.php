<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Exportcashbook extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('pcashbook_model','entry');
	}

	public function index()
	{
		$this->load->helper('url');
		$this->load->view('cashbook/print_cashbook_view');
		$this->load->view('templates/footer');
	}

	public function ajax_list()
	{
		$list = $this->entry->get_datatables();
		$data = array();
		$no = $_POST['start'];
		$balance = 0;
		foreach ($list as $entry) 
		{
			if($entry->disabled == 0){
				$no++;
				$row = array();
				$row[] = $entry->date;
				$row[] = $entry->title;
				$row[] = $entry->details;
				$row[] = $entry->amount;
				$row[] = $entry->last_edit.' on '.$entry->edit_date;
							//add html for action
				$data[] = $row;
			}
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->entry->count_all(),
						"recordsFiltered" => $this->entry->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

		

}
