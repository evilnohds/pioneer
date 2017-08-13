<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ledger extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('ledger_model','entry');
	}

	public function index()
	{
		$this->load->helper('url');
		$this->load->view('templates/header');
		$this->load->view('ledger/ledger_view');
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
				$row[] = number_format((float)$balance, 2, '.', '');
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

	public function ajax_edit($id)
	{
		$data = $this->entry->get_by_id($id);
		$data->date = ($data->date == '0000-00-00') ? '' : $data->date; // if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}

	
	public function ajax_add()
	{
		$this->_validate();
		if($this->input->post('particulars2') == 'Others'){
			$particulars = $this->input->post('particulars2');
		}
		else{
			$particulars = $this->input->post('particulars');
		}
		$data = array(
				'date' => $this->input->post('date'),
				'particulars' => $particulars,
				'debit' => $this->input->post('debit'),
				'credit' => $this->input->post('credit'),
				'last_edit' => $this->session->userdata('name'),
				'month' => date("F", strtotime($this->input->post('date'))),
				'year' => date("Y", strtotime($this->input->post('date'))),
			);
		$insert = $this->entry->save($data);
		echo json_encode(array("status" => TRUE));
		$this->session->set_flashdata('entry_added', 'Entry has been added');
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'date' => $this->input->post('date'),
				'particulars' => $this->input->post('particulars'),
				'debit' => $this->input->post('debit'),
				'credit' => $this->input->post('credit'),
				'last_edit' => $this->session->userdata('name'),
				'month' => date("F", strtotime($this->input->post('date'))),
				'year' => date("Y", strtotime($this->input->post('date'))),
			);
		$this->entry->update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
		$this->session->set_flashdata('entry_updated', 'entry data has been updated');
	}

	public function ajax_delete($id)
	{
		$this->entry->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
		$this->session->set_flashdata('entry_deleted', 'entry data has been removed');
	}
	
	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('date') == '')
		{
			$data['inputerror'][] = 'date';
			$data['error_string'][] = 'Date is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('particulars') == '')
		{
			$data['inputerror'][] = 'particulars';
			$data['error_string'][] = 'Particulars is required';
			$data['status'] = FALSE;
		}	

		if($this->input->post('debit') == '' and !($this->input->post('credit') == '')) {
			if (!is_numeric($this->input->post('credit')))
				{
					$data['inputerror'][] = 'credit';
					$data['error_string'][] = 'Please input a number';
					$data['status'] = FALSE;
				}
		}

		if($this->input->post('credit') == '' and !($this->input->post('debit') == '')){
			if (!is_numeric($this->input->post('debit')))
				{
					$data['inputerror'][] = 'debit';
					$data['error_string'][] = 'Please input a number';
					$data['status'] = FALSE;
				}
		}

		if($this->input->post('debit') == '' and $this->input->post('credit') == '')
		{
			$data['inputerror'][] = 'debit';
			$data['error_string'][] = 'Debit and Credit cannot be empty';
			$data['inputerror'][] = 'credit';
			$data['error_string'][] = 'Debit and Credit cannot be empty';
			$data['status'] = FALSE;
		}

	

		
		
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

	// Register entry
		public function register(){
			$data['title'] = 'ADD ENTRY';

			$this->form_validation->set_rules('date', 'Date', 'required');
			$this->form_validation->set_rules('particulars', 'Particulars', 'required');

			if($this->form_validation->run() === FALSE){
				$this->load->view('templates/header');
				$this->load->view('ledger/register', $data);
				$this->load->view('templates/footer');
			} else {
				
				$this->entry_model->register();

				// Set message
				$this->session->set_flashdata('entry_added', 'Entry has been added');

				redirect('ledger');
			}
		}

		

		public function excel()
		{
			$this->load->library("excel");
			$object = new PHPExcel();

			$object->setActiveSheetIndex(0);

			$table_columns = array("Date","Particulars", "Debit", "Credit", "Balance", "Last Edit");

			$column = 0;

			foreach($table_columns as $field)
			{
				$object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
				$column++;
			}

			$entry_data = $this->ledger_model->fetch_data();

			$excel_row = 2;

			foreach($entry_data as $row)
			{
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row->date);
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->particulars);
				$object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row->debit);
				$object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->credit);
				$object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row->balance);
				$object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row->last_edit);
				$excel_row++;
			}

			$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="General_Ledger.xls"');
			$object_writer->save('php://output');
		}

		Public function word()
		{

			$entry_data = $this->ledger_model->fetch_data();
			header("Content-type: application/vnd.ms-word");
			header("Content-Disposition: attachment;Filename=General_Ledger.doc");

			$html="<html>
			<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">
			<body>";



				$data='<table class="table table-bordered" id="table"> 
				<thead>
					<tr>
						<th style="text-align:center;  background-color:#4C9ED9; color: #fff;">Date</th>
						<th style="text-align:center;  background-color:#4C9ED9; color: #fff;">Particulars</th>
						<th style="text-align:center;  background-color:#4C9ED9; color: #fff;">Debit</th>
						<th style="text-align:center;  background-color:#4C9ED9; color: #fff;">Credit</th>
						<th style="text-align:center;  background-color:#4C9ED9; color: #fff;">Balance</th>
						<th style="text-align:center;  background-color:#4C9ED9; color: #fff;">Last Edit</th>
					</tr>
				</thead>
				<tr>';
					$i=1;
					foreach($entry_data as $row):
						$data .='<td style="text-align:center;">'.$row->date.'</td>
					<td style="text-align:center;">'.$row->particulars.'</td>
					<td style="text-align:center;">'.$row->debit.'</td>
					<td style="text-align:center;">'.$row->credit.'</td>
					<td style="text-align:center;">'.$row->balance.'</td>
					<td style="text-align:center;">'.$row->last_edit.' on '.$row->edit_date.'</td>
				</tr>';
				endforeach ;
				$data .='</table>';
				echo  $html.$data;
			}

}
