<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends Public_Controller {

	function __construct()
	{
		parent::__construct();

		$this->per_page = 15;
		//$this->output->enable_profiler(true);
	}
	
	public function posts($page=1)
	{
		if($page == 0){ show_404(); }

		$this->data['is_search'] = true; // Used to highlight the side nav
		
		$this->template->write('pagetitle', 'Posts');
		$this->load->library('pagination');
		
		$search = array();
		$type = $this->input->get('type');
		$q = $this->input->get('q');
		if(!empty($type) || !empty($q)){
			if(!empty($type)){
				$search['type'] = $type;	
			}
			if(!empty($q)){
				$search['q'] = $q;	
			}
		}
		
		$this->load->model('posts_model');
		$this->data['posts'] = $posts = $this->posts_model->get_latest_posts($this->per_page, (($page-1)*$this->per_page), $search);
		$this->data['posts_total'] = $posts_total = $this->posts_model->get_post_count($search);
		
		$config['base_url'] = site_url('search/posts');
		$config['total_rows'] = $posts_total;
		$config['per_page'] = $this->per_page; 
		$config['uri_segment'] = 3;
		$config['use_page_numbers'] = true;
		$config['display_pages'] = false;
		
		$this->pagination->initialize($config); 
		
		$this->data['pages'] = $this->pagination->create_links();
		
		$this->template->write_view('content', 'search/listings', $this->data);
		$this->template->write_view('_scripts', 'search/js/listings', $this->data);
		$this->template->write_view('sidebar', 'elements/sidebar', $this->data);
		$this->template->render();
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */