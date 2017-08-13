<?php
	class User_model extends CI_Model{

		var $table = 'users';	
	var $column_order = array('name','stall_no','access_lvl','email','username','register_date',null); //set column field database for datatable orderable
var $column_search = array('name','stall_no','access_lvl','email','username','register_date');


	
	
	var $order = array('id' => 'asc'); // default order 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();		
	}

	private function _get_datatables_query()
	{
		
		$this->db->from($this->table);

		$i = 0;
	
		foreach ($this->column_search as $item) // loop column 
		{
			if($_POST['search']['value']) // if datatable send POST for search
			{
				
				if($i===0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				}
				else
				{
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if(count($this->column_search) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}
		
		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->db->from($this->table);
		$this->db->where('id',$id);
		$query = $this->db->get();
		return $query->row();
	}

	public function save($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function update($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
		$this->db->where('id', $id);
		$this->db->delete($this->table);
	}
		
		public function register($enc_password){
			// User data array
			$data = array(
				'name' => $this->input->post('name'),
				'email' => $this->input->post('email'),
                'username' => $this->input->post('username'),
                'access_lvl' => $this->input->post('access_lvl'),
                'stall_no' => $this->input->post('stall_no'),
                'password' => $enc_password
			);

			// Insert user
			return $this->db->insert('users', $data);
		}

		// Log user in
		public function login($username, $password){
			// Validate
			$this->db->where('username', $username);
			$this->db->where('password', $password);

			$result = $this->db->get('users');

			if($result->num_rows() == 1){
				return $result->row(0)->id;
			} else {
				return false;
			}
		}

		public function get_access_lvl($id){
			// Validate
			$this->db->where('id', $id);

			$result = $this->db->get('users');

			if($result->num_rows() == 1){
				return $result->row(0)->access_lvl;
			} else {
				return false;
			}
		}

		public function get_stall_no($id){
			// Validate
			$this->db->where('id', $id);

			$result = $this->db->get('users');

			if($result->num_rows() == 1){
				return $result->row(0)->stall_no;
			} else {
				return 0;
			}
		}

		public function get_name($id){
			// Validate
			$this->db->where('id', $id);

			$result = $this->db->get('users');

			if($result->num_rows() == 1){
				return $result->row(0)->name;
			} else {
				return 0;
			}
		}

		// Check username exists
		public function check_username_exists($username){
			$query = $this->db->get_where('users', array('username' => $username));
			if(empty($query->row_array())){
				return true;
			} else {
				return false;
			}
		}

		// Check email exists
		public function check_email_exists($email){
			$query = $this->db->get_where('users', array('email' => $email));
			if(empty($query->row_array())){
				return true;
			} else {
				return false;
			}
		}

		public function check_stall_no_exists($stall_no){
			$query = $this->db->get_where('users', array('stall_no' => $stall_no));
			if(empty($query->row_array())){
				return true;
			} else {
				return false;
			}
		}

		public function fetch_data()
		{
			$this->db->order_by("id", "DESC");
			$query = $this->db->get("users");
			return $query->result();
		}
	}