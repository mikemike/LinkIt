<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends Public_Controller {

	
    function __construct() 
    {
        parent::__construct();
		
		$this->load->model('users_model');
	}

	public function index()
	{
		$this->login();	
	}

	/**
	 * Login page
	 */
	public function login() 
	{
		if(!empty($this->user)) {
			redirect('user/dashboard');
		}
		$this->form_validation->set_rules('username', 'Username', 'required|trim');
		$this->form_validation->set_rules('password', 'Password', 'required|trim');
		if($this->form_validation->run()!==FALSE) {
			// see if we can log them in
			$user = $this->users_model->login($this->input->post('username'), $this->input->post('password'));
			
			if(!$user) // couldnt log them in
			{
				$this->data['error'] = 'Authentication failed. Please try again.';
			} 
			else 
			{
				// log the user in
				$data = array(
					'user_id' => $user->user_id,
					'first_name' => $user->first_name,
					'last_name' => $user->last_name,
					'username' => $this->input->post('username'),
					'email' => $user->email
				);
				$this->session->set_userdata('user', $data);
				
				$this->load->helper('cookie');
				$cookie = array(
					'name'   => 'user',
					'value'  => serialize($data),
					'expire' => 60*60*24*365,
					'path'   => '/'
				);
				set_cookie($cookie);
				
				if(!empty($return)){
					redirect($return);
				} else {
					redirect('user/dashboard');
				}
			}
		}
		
		$this->template->write('pagetitle', 'Login');
		$this->template->write_view('content', 'user/login/login', $this->data);		
		$this->template->render();
	}
	
	/**
	 * Allow the user to sign up
	 */
	public function register()
	{
		$this->template->write('pagetitle', 'Sign Up');
		
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('first_name', 'First Name', 'required|trim|strip_tags');
		$this->form_validation->set_rules('last_name', 'First Name', 'required|trim|strip_tags');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]|trim|strip_tags', 'The email address must be in the correct format and not already registered');
		$this->form_validation->set_rules('username', 'Username', 'required|is_unique[users.username]|min_length[4]|trim|strip_tags|callback__username_check', 'The username must be in the correct format and not already registered');
		$this->form_validation->set_rules('password', 'Password', 'trim|strip_tags|min_length[6]|matches[cpassword]|required');
		$this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|strip_tags|required');

		if ($this->form_validation->run() == FALSE){
			$this->template->write_view('content', 'user/register/index', $this->data);
		} else {
					
			// Generate an email token
			$email_token = $this->data['email_token'] = substr(time().str_replace('.','',uniqid('', true)), 0, 20);
			
			// Save data in the database
			$data = array(
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'email' => $this->input->post('email'),
				'username' => $this->input->post('username'),
				'password' => $this->input->post('password'),
				'email_activation_token' => $email_token
			);
			$this->load->model('users_model');
			$user_id = $this->users_model->add_user($data);
			
			// Email the customer
			$this->_email_token_user($this->input->post('email'));				
			
			redirect('login/register_success');					
		}
		$this->template->render();
	}
	
	/**
	 * This is here for SEO purposes so we can easily track sign ups
	 */
	function register_success()
	{
		$this->template->write('pagetitle', 'Sign Up');
		$this->template->write_view('content', 'user/register/success', $this->data);
		$this->template->render();
	}
	
	/**
	 * User hits this function when they confirm their email address
	 */
	function confirm_email($email_token)
	{
		// Check if the token is valid
		$token = $this->data['user'] = $this->users_model->get_user_by_token($email_token);
		if(empty($token)){
			$this->template->write('pagetitle', 'Uh oh', $this->data);
			$this->template->write_view('content', 'user/register/email_confirm_failed', $this->data);
		} else {
			/** CONFIRMATION **/
			// All found, mark user as confirmed
			$data = array(
				'email_activated' => 1
			);	
			$this->users_model->update_user($token->user_id, $data);
						
			/** LOG IN **/
			// log the user in
			$data = array(
				'user_id' => $token->user_id,
				'first_name' => $token->first_name,
				'last_name' => $token->last_name,
				'email' => $token->email
			);
			$this->session->set_userdata('user', $data);
			
			$this->load->helper('cookie');
			$cookie = array(
				'name'   => 'user',
				'value'  => serialize($data),
				'expire' => 60*60*24*365,
				'path'   => '/'
			);
			set_cookie($cookie);
			
			/** CONTENT **/			
			$this->template->write('pagetitle', 'Email Confirmed', $this->data);
			$this->template->write_view('content', 'user/register/email_confirm_success', $this->data);
		}
		$this->template->render();
	}
	
	/**
	 * Re-send the email token
	 */
	function resend_email_confirmation()
	{
		$this->form_validation->set_rules('email', 'Email', 'trim|strip_tags|required|exists[users.email]');

		if ($this->form_validation->run() == FALSE){
			$this->template->write_view('content', 'user/register/resend_email_confirmation', $this->data);
		} else {
			// Check if the token is valid
			$token = $this->data['user'] = $this->users_model->get_user_by_email($this->input->post('email'));
			
			$this->data['email_token'] = $token->email_activation_token;
			
			// Email the customer
			$this->_email_token_user($this->input->post('email'));	
			$this->template->write_view('content', 'user/register/resend_email_confirmation_success', $this->data);
			
		}
		$this->template->render();
	}
	
	/**
	 * Forgotten password
	 */
	function forgotten_password()
	{
		$this->form_validation->set_rules('email', 'Email', 'trim|strip_tags|required|exists[users.email]');

		if ($this->form_validation->run() == FALSE){
			$this->template->write_view('content', 'user/login/forgotten_password', $this->data);
		} else {
			// Reset user password
			$pass = $this->data['pass'] = uniqid().mt_rand(111,999);
			$data = array('password' => $pass);
			
			$user = $this->data['emailuser'] = $this->users_model->get_user_by_email($this->input->post('email'));
			
			$message = $this->load->view('emails/forgotten_password', $this->data, true);
			$this->load->library('email');
			
			$config['mailtype'] = 'html';					
			$this->email->initialize($config);
			
			$this->email->to($this->input->post('email'));
			$this->email->from(SITE_EMAIL, SITE_NAME);
			$this->email->subject('Your forgotten password - '.SITE_NAME);
			$this->email->message($message);
			$this->email->send();	
			
			$this->users_model->update_user($user->user_id, $data);
			$this->template->write_view('content', 'user/login/forgotten_password_success', $this->data);
			
		}
		$this->template->render();
	}
	
	/**
	 * Method to logout (clear session)
	 */
	public function logout()
	{
		if(empty($this->user)) {
			redirect('login');
		}
		$this->session->sess_destroy();
		// Kill the cookie
		$this->load->helper('cookie');
		delete_cookie('user');
		redirect('login');
	}
	
	/** 
	 * Email confirmation/token
	 */
	function _email_token_user($email)
	{
		$message = $this->load->view('emails/signup_activation', $this->data, true);
		$this->load->library('email');
		
		$config['mailtype'] = 'html';					
		$this->email->initialize($config);
		
		$this->email->to($email);
		$this->email->from(SITE_EMAIL, SITE_NAME);
		$this->email->subject('Please confirm your email address - '.SITE_NAME);
		$this->email->message($message);
		$this->email->send();	
	}
	
	public function _username_check($str)
	{
		if (strpos($str, ' ') === false)
		{
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('username_check', 'The %s field can not have spaces!');
			return FALSE;

		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */