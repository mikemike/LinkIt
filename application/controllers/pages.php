<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pages extends Public_Controller {

	
    function __construct() 
    {
        parent::__construct();
	}

	/**
	 * Guidelines
	 */
	public function guidelines()
	{
		$this->template->write('pagetitle', 'Guidelines');
		$this->template->write_view('content', 'pages/guidelines', $this->data);
		$this->template->render();
	}

	/**
	 * About
	 */
	public function about()
	{
		$this->template->write('pagetitle', 'About');
		$this->template->write_view('content', 'pages/about', $this->data);
		$this->template->render();
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/pages.php */