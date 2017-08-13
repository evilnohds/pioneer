<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cashbook extends CI_Controller {


	public function __construct()
	{
		parent::__construct();
		$this->load->model('cashbook_model','entry');
		$this->load->model('ledger_model','entry2');
	}

	public function index()
	{
		$this->load->helper('url');
		$this->load->view('templates/header');
		$this->load->view('cashbook/cashbook_view');
		$this->load->view('templates/footer');
	}

	public function ajax_list()
	{		
		$x = 0;
		$list = $this->entry->get_datatables();
		$data = array();
		$no = $_POST['start'];
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

				if($this->session->userdata('logged_in') and $this->session->userdata('access_lvl') == "Super User")
				{       
		             $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_entry('."'".$entry->id."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
						  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="disable_entry('."'".$entry->id."'".')"><i class="glyphicon glyphicon-trash"></i> Del</a>';
		                
		        }
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
		$nextId = $this->entry->count_all() + 1;
		$data = array(
			    'id' => $nextId,
				'date' => $this->input->post('date'),
				'title' => $this->input->post('title'),
				'details' => $this->input->post('details'),
				'amount' => $this->input->post('amount'),
				'last_edit' => $this->session->userdata('name'),
				'month' => date("F", strtotime($this->input->post('date'))),
				'year' => date("Y", strtotime($this->input->post('date'))),
			);
		$insert = $this->entry->save($data);
		$credit = 0; $debit = 0;
		if($this->input->post('entrybalance') == "Debit")
		{
			$debit = $this->input->post('amount');
		}
		else
			{$credit = $this->input->post('amount');}
		$data = array(
				'id' => $nextId,
				'date' => $this->input->post('date'),
				'particulars' => $this->input->post('title'),
				'credit' => $credit,
				'debit' => $debit,
				'last_edit' => $this->session->userdata('name'),
				'month' => date("F", strtotime($this->input->post('date'))),
				'year' => date("Y", strtotime($this->input->post('date'))),
			);
		$insert = $this->entry2->save($data);
		echo json_encode(array("status" => TRUE));
		$this->session->set_flashdata('entry_added', 'Entry has been added');
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'date' => $this->input->post('date'),
				'title' => $this->input->post('title'),
				'details' => $this->input->post('details'),
				'amount' => $this->input->post('amount'),
				'last_edit' => $this->session->userdata('name'),
				'month' => date("F", strtotime($this->input->post('date'))),
				'year' => date("Y", strtotime($this->input->post('date'))),
			);
		$this->entry->update(array('id' => $this->input->post('id')), $data);

		$credit = 0; $debit = 0;
		if($this->input->post('entrybalance') == "Debit")
		{
			$debit = $this->input->post('amount');
		}
		else
			{$credit = $this->input->post('amount');}
		$data = array(
				'date' => $this->input->post('date'),
				'particulars' => $this->input->post('title'),
				'credit' => $credit,
				'debit' => $debit,
				'last_edit' => $this->session->userdata('name'),
				'month' => date("F", strtotime($this->input->post('date'))),
				'year' => date("Y", strtotime($this->input->post('date'))),
			);
		$this->entry2->update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
		$this->session->set_flashdata('entry_updated', 'entry data has been updated');
	}

	public function ajax_disable($id)
	{		
		$data = array(
				'disabled' => '1',
				'last_edit' => $this->session->userdata('name'),
			);
		$this->entry->update(array('id' => ($id)), $data);
		$this->entry2->update(array('id' => ($id)), $data);
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

		if($this->input->post('title') == '')
		{
			$data['inputerror'][] = 'title';
			$data['error_string'][] = 'title is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('details') == '')
		{
			$data['inputerror'][] = 'details';
			$data['error_string'][] = 'details is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('amount') == '')
		{
			$data['inputerror'][] = 'amount';
			$data['error_string'][] = 'amount is required';
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
			$this->form_validation->set_rules('title', 'title', 'required');

			if($this->form_validation->run() === FALSE){
				$this->load->view('templates/header');
				$this->load->view('cashbook/register', $data);
				$this->load->view('templates/footer');
			} else {
				
				$this->entry_model->register();

				// Set message
				$this->session->set_flashdata('entry_added', 'Entry has been added');

				redirect('cashbook');
			}
		}

		

		public function excel()
		{
			$this->load->library("excel");
			$object = new PHPExcel();

			$object->setActiveSheetIndex(0);

			$table_columns = array("Date","Title", "Details", "Amount", "Last Edit");

			$column = 0;

			foreach($table_columns as $field)
			{
				$object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
				$column++;
			}

			$entry_data = $this->cashbook_model->fetch_data();

			$excel_row = 2;

			foreach($entry_data as $row)
			{
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row->date);
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->title);
				$object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row->details);
				$object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->amount);
				$object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row->last_edit.' on '.$row->edit_date);
				$excel_row++;
			}

			$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="Cashbook.xls"');
			$object_writer->save('php://output');
		}

		Public function word()
		{

			$entry_data = $this->cashbook_model->fetch_data();
			header("Content-type: application/vnd.ms-word");
			header("Content-Disposition: attachment;Filename=Cashbook.doc");

			$html="<html>
			<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">
			<body>";



				$data='<table class="table table-bordered" id="table"> 
				<thead>
					<tr>
						<th style="text-align:center;  background-color:#4C9ED9; color: #fff;">Date</th>
						<th style="text-align:center;  background-color:#4C9ED9; color: #fff;">Title</th>
						<th style="text-align:center;  background-color:#4C9ED9; color: #fff;">Details</th>
						<th style="text-align:center;  background-color:#4C9ED9; color: #fff;">Amount</th>
						<th style="text-align:center;  background-color:#4C9ED9; color: #fff;">Last Edit</th>
					</tr>
				</thead>
				<tr>';
					$i=1;
					foreach($entry_data as $row):
						$data .='<td style="text-align:center;">'.$row->date.'</td>
					<td style="text-align:center;">'.$row->title.'</td>
					<td style="text-align:center;">'.$row->details.'</td>
					<td style="text-align:center;">'.$row->amount.'</td>
					<td style="text-align:center;">'.$row->last_edit.' on '.$row->edit_date.'</td>
				</tr>';
				endforeach ;
				$data .='</table>';
				echo  $html.$data;
			}

}
