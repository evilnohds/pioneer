<?php
	class Comment_model extends CI_Model{
		public function __construct(){
			$this->load->database();
		}

		public function create_comment($post_id){

			if($this->session->userdata('logged_in')){
				$data = array(
				'post_id' => $post_id,
				'name' => $this->session->userdata('username'),
				'email' => $this->input->post('email'),
				'body' => $this->input->post('body'),
				'user_id' => $this->session->userdata('user_id'),				
				);
			}
			else{
				$data = array(
				'post_id' => $post_id,
				'name' => $this->input->post('name'),
				'email' => $this->input->post('email'),
				'body' => $this->input->post('body'),
				'user_id' => 0,				
				);
			}
			

			return $this->db->insert('comments', $data);
		}

		public function get_comments($post_id){
			$query = $this->db->get_where('comments', array('post_id' => $post_id));
			return $query->result_array();
		}

		public function delete_comment($id){
			$this->db->where('id', $id);
			$this->db->delete('comments');
			return true;
		}
	}