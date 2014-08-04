<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Details extends Login_Controller {
	
	function __construct() {
        parent::__construct();
		$this->load->model('users_model');
    }
	
	/**
	 * User can edit their account
	 */
	public function index() 
	{			
		$this->template->write('pagetitle', 'Your Details');
		$this->load->library('form_validation');
		$this->data['user'] = $this->users_model->get_user($this->user['user_id']);

		$this->form_validation->set_rules('first_name', 'First Name', 'required|trim|strip_tags');
		$this->form_validation->set_rules('last_name', 'Surname', 'required|trim|strip_tags');
		$this->form_validation->set_rules('website', 'Website', 'trim|strip_tags|prep_url');
		$this->form_validation->set_rules('twitter', 'Twitter', 'trim|strip_tags');
		$this->form_validation->set_rules('password', 'Password', 'trim|strip_tags|min_length[6]|matches[cpassword]');
		$this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|strip_tags');

		if ($this->form_validation->run() == FALSE){			
			$this->template->write_view('content', 'user/details', $this->data);
		} else {
			
			// Save data in the database
			$data = array(
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'website' => $this->input->post('website'),
				'twitter' => $this->input->post('twitter'),
			);
			
			$pass = $this->input->post('password');
			if(!empty($pass)){
				$data['password'] = $this->input->post('password');	
			}
			
			$this->load->model('users_model');
			$user_id = $this->users_model->update_user($this->user['user_id'], $data);			
			
			$this->session->set_flashdata('message', 'Your details have been updated successfully.');
			
			redirect(current_url());						
			
		}
		$this->template->render();
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */