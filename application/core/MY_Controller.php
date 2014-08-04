<?php
class MY_Controller extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		// Grab the website host
		if(!empty($_SERVER['SERVER_NAME'])){
			$host = $_SERVER['SERVER_NAME'];
		} else {
			$host = $_SERVER['HTTP_HOST'];
		}
		if(empty($host)){
			die('Cannot declare host.');
		}
		$this->load->model('websites_model');
		// Get the website
		$website = $this->websites_model->get_website_by_host($host);
		if(empty($website)){
			redirect(MAIN_WEBSITE, 301);
		}
		define('SITE_NAME', $website->website_name);
		define('SITE_EMAIL', $website->website_email);
		define('SITE_STRAPLINE', $website->website_strapline);
		define('SITE_METADESC', SITE_NAME .' - '. SITE_STRAPLINE);
		define('WEBSITE_ID', $website->website_id); // Very important, used throughout

		// Validation
		$this->data = array();
		$this->form_validation->set_error_delimiters('<div class="alert alert-error"><strong>Error!</strong> ', '</div>');
		
		// User credentials, if necessary
		$user = $this->user = $this->data['user'] = $this->session->userdata('user');
		
		if(empty($user)){
			// Check a cookie
			$cookie = $this->input->cookie('user');	
			if(!empty($cookie)){
				$user = $this->user = $this->data['user'] = unserialize($cookie);	
			}
		}
		
        $this->data['message'] = ($this->session->flashdata('message') != '' ? '<div class="alert alert-success"><a data-dismiss="alert" class="close">&times;</a><strong>Success!</strong> '.$this->session->flashdata('message').'</div>' : '');
		$this->data['error'] = ($this->session->flashdata('error') != '' ? '<div class="alert alert-error"><a data-dismiss="alert" class="close">&times;</a><strong>Error!</strong> '.$this->session->flashdata('error').'</div>' : '');
	}
}

class Public_Controller extends MY_Controller 
{
	
}

class Login_Controller extends MY_Controller 
{
	function __construct()
	{
		parent::__construct();

		if(empty($this->user)){
			redirect('login');	
		}
	}
}