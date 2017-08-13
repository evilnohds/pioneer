<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Exportledger extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('pledger_model','entry');
	}

	public function index()
	{
		$this->load->helper('url');
		$this->load->view('ledger/print_ledger_view');
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
				$row[] = $entry->particulars;
				$row[] = $entry->debit;
				$row[] = $entry->credit;
				$balance = $balance - $entry->debit + $entry->credit;
				$row[] = $balance;
				$row[] = $entry->last_edit.' on '.$entry->edit_date;
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
