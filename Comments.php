<?php
	class Comments extends CI_Controller{
		public function create($post_id){
			$slug = $this->input->post('slug');
			$data['post'] = $this->post_model->get_posts($slug);

			$this->form_validation->set_rules('name', 'Name', 'required');
			$this->form_validation->set_rules('email', 'Email', 'required');

			$this->form_validation->set_rules('body', 'Body', 'required');


			if($this->form_validation->run() === FALSE){
				$this->load->view('templates/header');
				$this->load->view('posts/view', $data);
				$this->load->view('templates/footer');
			} else {
				$this->comment_model->create_comment($post_id);
				$this->session->set_flashdata('comment_posted', 'Comment has been posted');
				redirect('posts/'.$slug);
			}
		}

		public function delete($id){
			// Check login
			if(!$this->session->userdata('logged_in')){
				redirect('users/login');
			}

			$this->comment_model->delete_comment($id);

			// Set message
			$this->session->set_flashdata('comment_deleted', 'Comment has been deleted');
			$slug = $this->input->post('slug');
			$data['post'] = $this->post_model->get_posts($slug);
			redirect('posts/'.$slug);
		}
	}