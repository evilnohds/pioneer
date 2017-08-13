<?php
	class Users extends CI_Controller{

		public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model','person');
	}

	public function index()
	{
		$this->load->helper('url');
		$this->load->view('templates/header');
		$this->load->view('users/user_view');
		$this->load->view('templates/footer');
	}

	public function ajax_list()
	{
		$list = $this->person->get_datatables();
		$data = array();
		$no = $_POST['start'];

		if($this->session->userdata('logged_in') and $this->session->userdata('access_lvl') == "Member")
			{
				foreach ($list as $person) {
					if($this->session->userdata('stall_no') == $person->stall_no and $person->disabled == 0 and $person->access_lvl == 'Member'){
						$no++;
								$row = array();
								$row[] = $person->name;
								$row[] = $person->stall_no;
								$row[] = $person->access_lvl;
								$row[] = $person->username;	
								$row[] = $person->email;						
								$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_my_person('."'".$person->id."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';
								$data[] = $row;					

						}
					}
				
			}


		else{

				foreach ($list as $person) {
					if($person->disabled == 0 and $person->access_lvl != 'Super User'){
						
						$no++;
						$row = array();
						$row[] = $person->name;
						$row[] = $person->stall_no;
						$row[] = $person->access_lvl;
						$row[] = $person->username;	
						$row[] = $person->email;
		            
				

						if($this->session->userdata('logged_in') and $this->session->userdata('access_lvl') == "Super User")
						{
			                $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_person('."'".$person->id."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
							  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="disable_person('."'".$person->id."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
			            }
			            elseif($this->session->userdata('logged_in') and $this->session->userdata('access_lvl') == "Administrator")
						{
			                $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_person('."'".$person->id."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
							  ';
			            }

						$data[] = $row;
					}	
					elseif($person->disabled == 0 and $person->access_lvl == 'Super User' and $this->session->userdata('username') == $person->username)
					{
						
						$no++;
						$row = array();
						$row[] = $person->name;
						$row[] = $person->stall_no;
						$row[] = $person->access_lvl;
						$row[] = $person->username;	
						$row[] = $person->email;
		            
				

						if($this->session->userdata('logged_in') and $this->session->userdata('access_lvl') == "Super User")
						{
			                $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_person('."'".$person->id."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
							  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_person('."'".$person->id."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
			            }
			            elseif($this->session->userdata('logged_in') and $this->session->userdata('access_lvl') == "Administrator")
						{
			                $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_person('."'".$person->id."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
							  ';
			            }

						$data[] = $row;
					}					
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
		$data->register_date = ($data->register_date == '0000-00-00') ? '' : $data->register_date; // if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}

	
	public function ajax_add()
	{
		$this->_validate();
		$data = array(
				'name' => $this->input->post('name'),
				'stall_no' => $this->input->post('stall_no'),
				'access_lvl' => $this->input->post('access_lvl'),
				'email' => $this->input->post('email'),
				'username' => $this->input->post('username'),
				'password' => md5($this->input->post('password')),
				'register_date' => $this->input->post('register_date'),
			);
		$insert = $this->person->save($data);
		echo json_encode(array("status" => TRUE));
		$this->session->set_flashdata('user_registered', 'User has been added');
	}

	public function ajax_update()
	{
		$this->_validate();
		if ($this->input->post('changepw') == "True"){
			$data = array(
				'name' => $this->input->post('name'),
				'stall_no' => $this->input->post('stall_no'),
				'access_lvl' => $this->input->post('access_lvl'),
				'email' => $this->input->post('email'),
				'username' => $this->input->post('username'),
				'password' => md5($this->input->post('password')),
				'register_date' => $this->input->post('register_date'),
			);
		}
		else{
			$data = array(
				'name' => $this->input->post('name'),
				'stall_no' => $this->input->post('stall_no'),
				'access_lvl' => $this->input->post('access_lvl'),
				'email' => $this->input->post('email'),
				'username' => $this->input->post('username'),
				'register_date' => $this->input->post('register_date'),
			);
		}
		
		$this->person->update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
		$this->session->set_flashdata('user_updated', 'User data updated');
	}

	public function ajax_disable($id)
	{		
		$data = array(
				'disabled' => '1'
			);
		$this->person->update(array('id' => $id), $data);
		echo json_encode(array("status" => TRUE));
		$this->session->set_flashdata('entry_updated', 'entry data has been updated');
	}


	public function ajax_update_my_account()
	{
		$this->_validate2();
		if ($this->input->post('changepw') == "True"){
			$data = array(
				'name' => $this->input->post('name'),
				'email' => $this->input->post('email'),
				'username' => $this->input->post('username'),
				'password' => md5($this->input->post('password')),
			);
		}
		else{
			$data = array(
				'name' => $this->input->post('name'),
				'email' => $this->input->post('email'),
				'username' => $this->input->post('username'),
			);
		}
		
		$this->person->update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
		$this->session->set_flashdata('user_updated', 'User data updated');
	}

	public function ajax_delete($id)
	{
		$this->person->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
		$this->session->set_flashdata('user_deleted', 'User data deleted');		
	}


	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('stall_no') == '')
		{
			$data['inputerror'][] = 'stall_no';
			$data['error_string'][] = 'Stall no. is required';
			$data['status'] = FALSE;
		}

		if (!is_numeric($this->input->post('stall_no')))
		{
			$data['inputerror'][] = 'stall_no';
			$data['error_string'][] = 'Please input a number';
			$data['status'] = FALSE;
		}

		if (!preg_match("/^[a-zA-Z '-]+$/", $this->input->post('name'))) 
		{
		    $data['inputerror'][] = 'name';
			$data['error_string'][] = 'Name must be alpha characters only';
			$data['status'] = FALSE;
		}	

		if($this->input->post('name') == '')
		{
			$data['inputerror'][] = 'name';
			$data['error_string'][] = 'Name is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('access_lvl') == '' or $this->input->post('access_lvl') == '--Select Access Level--')
		{
			$data['inputerror'][] = 'access_lvl';
			$data['error_string'][] = 'Access Level is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('username') == '')
		{
			$data['inputerror'][] = 'username';
			$data['error_string'][] = 'Username is required';
			$data['status'] = FALSE;
		}		

		if (!filter_var($this->input->post('email'), FILTER_VALIDATE_EMAIL)) {
		  $data['inputerror'][] = 'email';
			$data['error_string'][] = 'Invalid email format';
			$data['status'] = FALSE;
		}

		if ($this->input->post('changepw') == "True"){
			if (!($this->input->post('password') == $this->input->post('cpassword'))) {
		  $data['inputerror'][] = 'cpassword';
			$data['error_string'][] = 'Passwords do not match';
			$data['status'] = FALSE;
			}
		}
		

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

	private function _validate2()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;


		if (!preg_match("/^[a-zA-Z '-]+$/", $this->input->post('name'))) 
		{
		    $data['inputerror'][] = 'name';
			$data['error_string'][] = 'Name must be alpha characters only';
			$data['status'] = FALSE;
		}	

		if($this->input->post('name') == '')
		{
			$data['inputerror'][] = 'name';
			$data['error_string'][] = 'Name is required';
			$data['status'] = FALSE;
		}

		
		if($this->input->post('username') == '')
		{
			$data['inputerror'][] = 'username';
			$data['error_string'][] = 'Username is required';
			$data['status'] = FALSE;
		}		

		if (!filter_var($this->input->post('email'), FILTER_VALIDATE_EMAIL)) {
		  $data['inputerror'][] = 'email';
			$data['error_string'][] = 'Invalid email format';
			$data['status'] = FALSE;
		}

		if ($this->input->post('changepw') == "True"){
			if (!($this->input->post('password') == $this->input->post('cpassword'))) {
		  $data['inputerror'][] = 'cpassword';
			$data['error_string'][] = 'Passwords do not match';
			$data['status'] = FALSE;
			}
		}
		

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}


	
		// Register user
		public function register(){
			$data['title'] = 'REGISTER USER';

			$this->form_validation->set_rules('name', 'Name', 'required');
			$this->form_validation->set_rules('username', 'Username', 'required|callback_check_username_exists');
			$this->form_validation->set_rules('email', 'Email', 'callback_check_email_exists');
			$this->form_validation->set_rules('stall_no', 'Stall No.', 'callback_check_stall_no_exists');
			$this->form_validation->set_rules('password', 'Password', 'required');
			$this->form_validation->set_rules('password2', 'Confirm Password', 'matches[password]');

			if($this->form_validation->run() === FALSE){
				$this->load->view('templates/header');
				$this->load->view('users/register', $data);
				$this->load->view('templates/footer');
			} else {
				// Encrypt password
				$enc_password = md5($this->input->post('password'));

				$this->user_model->register($enc_password);

				// Set message
				$this->session->set_flashdata('user_registered', 'User registered');

				redirect('posts');
			}
		}

		// Log in user
		public function login(){
			$data['title'] = 'SIGN IN';

			$this->form_validation->set_rules('username', 'Username', 'required');
			$this->form_validation->set_rules('password', 'Password', 'required');

			if($this->form_validation->run() === FALSE){
				$this->load->view('templates/header');
				$this->load->view('users/login', $data);
				$this->load->view('templates/footer');
			} else {
				
				// Get username
				$username = $this->input->post('username');
				// Get and encrypt the password
				$password = md5($this->input->post('password'));

				// Login user
				$user_id = $this->user_model->login($username, $password);

				if($user_id){
					// Create session
					$access_lvl = $this->user_model->get_access_lvl($user_id);
					$stall_no = $this->user_model->get_stall_no($user_id);
					$name = $this->user_model->get_name($user_id);
					$user_data = array(
						'user_id' => $user_id,
						'name' => $name,
						'username' => $username,
						'access_lvl' => $access_lvl,
						'stall_no' => $stall_no,
						'logged_in' => true
					);

					$this->session->set_userdata($user_data);

					// Set message
					$this->session->set_flashdata('user_loggedin', 'You are now logged in. Welcome '.$username);

					redirect('posts');
				} else {
					// Set message
					$this->session->set_flashdata('login_failed', 'Login is invalid');

					redirect('users/login');
				}		
			}
		}

		// Log user out
		public function logout(){
			// Unset user data
			$this->session->unset_userdata('logged_in');
			$this->session->unset_userdata('user_id');
			$this->session->unset_userdata('username');
			$this->session->unset_userdata('access_lvl');
			$this->session->unset_userdata('stall_no');
			// Set message
			$this->session->set_flashdata('user_loggedout', 'You are now logged out');

			redirect('users/login');
		}

		// Check if username exists
		public function check_username_exists($username){
			$this->form_validation->set_message('check_username_exists', 'That username is taken. Please choose a different one');
			if($this->user_model->check_username_exists($username)){
				return true;
			} else {
				return false;
			}
		}

		// Check if email exists
		public function check_email_exists($email){
			$this->form_validation->set_message('check_email_exists', 'That email is taken. Please choose a different one');
			if($this->user_model->check_email_exists($email)){
				return true;
			} else {
				return false;
			}
		}

		public function check_stall_no_exists($stall_no){
			$this->form_validation->set_message('check_stall_no_exists', 'That stall number is owned by another user. Please check your input');
			if($this->user_model->check_stall_no_exists($stall_no)){
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

			$table_columns = array("Name", "Access Level", "Email", "Username", "Stall No.");

			$column = 0;

			foreach($table_columns as $field)
			{
				$object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
				$column++;
			}

			$user_data = $this->user_model->fetch_data();

			$excel_row = 2;

			foreach($user_data as $row)
			{
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row->name);
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->access_lvl);
				$object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row->email);
				$object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->username);
				$object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row->stall_no);
				$excel_row++;
			}

			$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="Users.xls"');
			$object_writer->save('php://output');
		}

		public function action2(){
			//load our new PHPExcel library
			$this->load->library('excel');
			//activate worksheet number 1
			$this->excel->setActiveSheetIndex(0);
			//name the worksheet
			$this->excel->getActiveSheet()->setTitle('test worksheet');
			//set cell A1 content with some text
			$this->excel->getActiveSheet()->setCellValue('A1', 'This is just some text value');
			//change the font size
			$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
			//make the font become bold
			$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
			//merge cell A1 until D1
			$this->excel->getActiveSheet()->mergeCells('A1:D1');
			//set aligment to center for that merged cell (A1 to D1)
			$this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			$filename='just_some_random_name.xls'; //save our workbook as this file name
			header('Content-Type: application/vnd.ms-excel'); //mime type
			header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
			header('Cache-Control: max-age=0'); //no cache
			             
			//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
			//if you want to save it as .XLSX Excel 2007 format
			$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
			//force user to download the Excel file without writing it to server's HD
			$objWriter->save('php://output');
		}

		Public function word()
		{

			$user_data = $this->user_model->fetch_data();
			header("Content-type: application/vnd.ms-word");
			header("Content-Disposition: attachment;Filename=Users.doc");

			$html="<html>
			<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">
			<body>";



				$data='<table class="table table-bordered" id="table"> 
				<thead>
					<tr>
						<th style="text-align:center;  background-color:#4C9ED9; color: #fff;">Name</th>
						<th style="text-align:center;  background-color:#4C9ED9; color: #fff;">Access Level</th>
						<th style="text-align:center;  background-color:#4C9ED9; color: #fff;">Email</th>  
						<th style="text-align:center;  background-color:#4C9ED9; color: #fff;">Username</th> 
						<th style="text-align:center;  background-color:#4C9ED9; color: #fff;">Stall No.</th>  

					</tr>
				</thead>
				<tr>';
					$i=1;
					foreach($user_data as $row):
						$data .='<td style="text-align:center;">'.$row->name.'</td>
					<td style="text-align:center;">'.$row->access_lvl.'</td>
					<td style="text-align:center;">'.$row->email.'</td>
					<td style="text-align:center;">'.$row->username.'</td>
					<td style="text-align:center;">'.$row->stall_no.'</td>
				</tr>';
				endforeach ;
				$data .='</table>';
				echo  $html.$data;
			}
	}