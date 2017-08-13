<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Members extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('person_model','person');
	}

	public function index()
	{
		$this->load->helper('url');
		$this->load->view('templates/header');
		$this->load->view('members/person_view');
		$this->load->view('templates/footer');
	}

	public function ajax_list()
	{
		$list = $this->person->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $person) 
		{
			if($person->disabled == 0){
				$no++;
				$row = array();                                       
				$row[] = $person->member_stallno;
				$row[] = $person->member_first_name;
				$row[] = $person->member_last_name;
				$row[] = $person->member_section;
				if($this->session->userdata('logged_in')){
					$row[] = $person->member_address;
					$row[] = $person->member_age;
					$row[] = $person->member_sex;
					$row[] = $person->member_cstatus;
					$row[] = $person->member_birthdate;
				}
				
				$row[] = $person->member_contactno;

				//add html for action

				if($this->session->userdata('logged_in') and $this->session->userdata('access_lvl') == "Super User")
				{       
		             $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_person('."'".$person->member_id."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
						  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="disable_person('."'".$person->member_id."'".')"><i class="glyphicon glyphicon-trash"></i> Del</a>';
		                
		        }
		        elseif($this->session->userdata('logged_in') and $this->session->userdata('access_lvl') == "Administrator")
		        {
		            $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_person('."'".$person->member_id."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';
		        }
				$data[] = $row;

			}
			
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->person->count_all(),
						"recordsFiltered" => $this->person->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->person->get_by_id($id);
		$data->member_birthdate = ($data->member_birthdate == '0000-00-00') ? '' : $data->member_birthdate; // if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}

	public function ajax_edit_my_data()
	{
		$data = $this->person->get_by_stallno();
		$data->member_birthdate = ($data->member_birthdate == '0000-00-00') ? '' : $data->member_birthdate; // if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
		$data = array(
				'member_stallno' => $this->input->post('member_stallno'),
				'member_first_name' => $this->input->post('member_first_name'),
				'member_last_name' => $this->input->post('member_last_name'),
				'member_section' => $this->input->post('member_section'),
				'member_address' => $this->input->post('member_address'),
				'member_age' => $this->input->post('member_age'),
				'member_sex' => $this->input->post('member_sex'),
				'member_cstatus' => $this->input->post('member_cstatus'),
				'member_birthdate' => $this->input->post('member_birthdate'),
				'member_contactno' => $this->input->post('member_contactno'),
			);
		$insert = $this->person->save($data);
		echo json_encode(array("status" => TRUE));
		$this->session->set_flashdata('member_added', 'Member has been added');
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'member_stallno' => $this->input->post('member_stallno'),
				'member_first_name' => $this->input->post('member_first_name'),
				'member_last_name' => $this->input->post('member_last_name'),
				'member_section' => $this->input->post('member_section'),
				'member_address' => $this->input->post('member_address'),
				'member_age' => $this->input->post('member_age'),
				'member_sex' => $this->input->post('member_sex'),
				'member_cstatus' => $this->input->post('member_cstatus'),
				'member_birthdate' => $this->input->post('member_birthdate'),
				'member_contactno' => $this->input->post('member_contactno'),
			);
		$this->person->update(array('member_id' => $this->input->post('member_id')), $data);
		echo json_encode(array("status" => TRUE));
		$this->session->set_flashdata('member_updated', 'Member data has been updated');
	}

	public function ajax_disable($id)
	{		
		$data = array(
				'disabled' => '1'
			);
		$this->person->update(array('member_id' => ($id)), $data);
		echo json_encode(array("status" => TRUE));
		$this->session->set_flashdata('member_updated', 'Member data has been updated');
	}

	public function ajax_delete($id)
	{
		$this->person->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
		$this->session->set_flashdata('member_deleted', 'Member data has been removed');
	}
	
	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('member_stallno') == '')
		{
			$data['inputerror'][] = 'member_stallno';
			$data['error_string'][] = 'Stall no. is required';
			$data['status'] = FALSE;
		}

		if (!is_numeric($this->input->post('member_stallno')))
		{
			$data['inputerror'][] = 'member_stallno';
			$data['error_string'][] = 'Please input a number';
			$data['status'] = FALSE;
		}

		if (!preg_match("/^[a-zA-Z'-]+$/", $this->input->post('member_first_name'))) 
		{
		    $data['inputerror'][] = 'member_first_name';
			$data['error_string'][] = 'First name must be alpha characters only';
			$data['status'] = FALSE;
		}	

		if($this->input->post('member_first_name') == '')
		{
			$data['inputerror'][] = 'member_first_name';
			$data['error_string'][] = 'First name is required';
			$data['status'] = FALSE;
		}	

		if (!preg_match("/^[a-zA-Z'-]+$/", $this->input->post('member_last_name'))) 
		{
		    $data['inputerror'][] = 'member_last_name';
			$data['error_string'][] = 'Last name must be alpha characters only';
			$data['status'] = FALSE;
		}

		if($this->input->post('member_last_name') == '')
		{
			$data['inputerror'][] = 'member_last_name';
			$data['error_string'][] = 'Last name is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('member_section') == '' or $this->input->post('member_section') == '--Select Section--')
		{
			$data['inputerror'][] = 'member_section';
			$data['error_string'][] = 'Section is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('member_address') == '')
		{
			$data['inputerror'][] = 'member_address';
			$data['error_string'][] = 'Address is required';
			$data['status'] = FALSE;
		}		

		if (!is_numeric($this->input->post('member_age')))
		{
			$data['inputerror'][] = 'member_age';
			$data['error_string'][] = 'Please input a number';
			$data['status'] = FALSE;
		}

		if($this->input->post('member_age') == '')
		{
			$data['inputerror'][] = 'member_age';
			$data['error_string'][] = 'Age is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('member_sex') == '' or $this->input->post('member_sex') == '--Select Gender--')
		{
			$data['inputerror'][] = 'member_sex';
			$data['error_string'][] = 'Gender is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('member_cstatus') == '' or $this->input->post('member_cstatus') == '--Select Status--')
		{
			$data['inputerror'][] = 'member_cstatus';
			$data['error_string'][] = 'Status is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('member_birthdate') == '')
		{
			$data['inputerror'][] = 'member_birthdate';
			$data['error_string'][] = 'Birthdate is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('member_contactno') == '')
		{
			$data['inputerror'][] = 'member_contactno';
			$data['error_string'][] = 'Contact No. is required';
			$data['status'] = FALSE;
		}

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

	// Register member
		public function register(){
			$data['title'] = 'REGISTER MEMBER';

			$this->form_validation->set_rules('member_stallno', 'Stall No.', 'required|callback_check_stallno_exists');
			$this->form_validation->set_rules('member_first_name', 'First Name', 'required');
			$this->form_validation->set_rules('member_last_name', 'Last Name', 'required');
			$this->form_validation->set_rules('member_section', 'Section', 'required');
			$this->form_validation->set_rules('member_address', 'Address', 'required');
			$this->form_validation->set_rules('member_age', 'Age', 'required');
			$this->form_validation->set_rules('member_sex', 'Sex', 'required');
			$this->form_validation->set_rules('member_cstatus', 'Civil Status', 'required');
			$this->form_validation->set_rules('member_birthdate', 'Birthdate', 'required');
			$this->form_validation->set_rules('member_contactno', 'Contact No.', 'required');

			if($this->form_validation->run() === FALSE){
				$this->load->view('templates/header');
				$this->load->view('members/register', $data);
				$this->load->view('templates/footer');
			} else {
				
				$this->person_model->register();

				// Set message
				$this->session->set_flashdata('member_registered', 'Member registered');

				redirect('posts');
			}
		}

		// Check if username exists
		public function check_stallno_exists($member_stallno){
			$this->form_validation->set_message('check_stallno_exists', 'That stall no. is taken. Please choose a different one');
			if($this->person_model->check_stallno_exists($member_stallno)){
				return true;
			} else {
				return false;
			}
		}

		public function excel()
		{
			$this->load->library("excel");
			$object = new PHPExcel();

			$object->setActiveSheetIndex(0);

			$table_columns = array("Stall No.","First Name", "Last Name", "Section", "Address", "Age", "Gender", "Civil Status", "Date of Birth", "Contact No.");

			$column = 0;

			foreach($table_columns as $field)
			{
				$object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
				$column++;
			}

			$member_data = $this->person_model->fetch_data();

			$excel_row = 2;

			foreach($member_data as $row)
			{
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row->member_stallno);
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->member_first_name);
				$object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row->member_last_name);
				$object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->member_section);
				$object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row->member_address);
				$object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row->member_age);
				$object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $row->member_sex);
				$object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $row->member_cstatus);
				$object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, $row->member_birthdate);
				$object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, $row->member_contactno);
				$excel_row++;
			}

			$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="Members.xls"');
			$object_writer->save('php://output');
		}

		Public function word()
		{

			$member_data = $this->person_model->fetch_data();
			header("Content-type: application/vnd.ms-word");
			header("Content-Disposition: attachment;Filename=Members.doc");

			$html="<html>
			<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">
			<body>";



				$data='<table class="table table-bordered" id="table"> 
				<thead>
					<tr>
						<th style="text-align:center;  background-color:#4C9ED9; color: #fff;">Stall No.</th>
						<th style="text-align:center;  background-color:#4C9ED9; color: #fff;">First Name</th>
						<th style="text-align:center;  background-color:#4C9ED9; color: #fff;">Last Name</th>
						<th style="text-align:center;  background-color:#4C9ED9; color: #fff;">Section</th>
						<th style="text-align:center;  background-color:#4C9ED9; color: #fff;">Address</th>
						<th style="text-align:center;  background-color:#4C9ED9; color: #fff;">Age</th>
						<th style="text-align:center;  background-color:#4C9ED9; color: #fff;">Gender</th>
						<th style="text-align:center;  background-color:#4C9ED9; color: #fff;">Civil Status</th>
						<th style="text-align:center;  background-color:#4C9ED9; color: #fff;">Date of Birth</th>
						<th style="text-align:center;  background-color:#4C9ED9; color: #fff;">Contact No.</th>
					</tr>
				</thead>
				<tr>';
					$i=1;
					foreach($member_data as $row):
						$data .='<td style="text-align:center;">'.$row->member_stallno.'</td>
					<td style="text-align:center;">'.$row->member_first_name.'</td>
					<td style="text-align:center;">'.$row->member_last_name.'</td>
					<td style="text-align:center;">'.$row->member_section.'</td>
					<td style="text-align:center;">'.$row->member_address.'</td>
					<td style="text-align:center;">'.$row->member_age.'</td>
					<td style="text-align:center;">'.$row->member_sex.'</td>
					<td style="text-align:center;">'.$row->member_cstatus.'</td>
					<td style="text-align:center;">'.$row->member_birthdate.'</td>
					<td style="text-align:center;">'.$row->member_contactno.'</td>
				</tr>';
				endforeach ;
				$data .='</table>';
				echo  $html.$data;
			}

}
