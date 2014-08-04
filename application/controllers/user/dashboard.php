<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends Login_Controller {
	
	/**
	 * Login page
	 */
	public function index() 
	{	
		redirect('');
		$this->template->write('pagetitle', 'Dashboard');
		$this->template->write_view('content', 'user/dashboard', $this->data);					
		$this->template->render();
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */