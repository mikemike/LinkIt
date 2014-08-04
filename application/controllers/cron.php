<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cron extends Public_Controller {

	
    function __construct() 
    {
        parent::__construct();
	}

	/**
	 * Import some posts from Reddit
	 */
	public function import_reddit($subreddit='')
	{
		if(empty($subreddit)){
			echo 'Select a subreddit.';	
			exit;
		}
		
		require_once(FCPATH."application/libraries/Reddit.php");
		$reddit = new reddit("***", "***");
		
		$this->load->model('posts_model');
		$this->load->model('users_model');
		
		// Grab 50 technology posts
		$response = $reddit->getListing($subreddit, 5);
		foreach($response->data->children as $item){
			// Check if link exists
			$link_exists = $this->posts_model->link_exists($item->data->url);
			if(!$link_exists){
				// Select a random fake user
				$user = $this->users_model->select_fake_user();
				
				// Calculate a random time
				$time = mt_rand(0, 259200);
				$time = time() - $time;
				$time = date('YmdHis', $time);
				
				$data = array(
					'user_id' => $user->user_id,
					'website_id' => 1,
					'type' => 'link',
					'title' => $item->data->title,
					'link' => $item->data->url,
					'points' => mt_rand(3,27),
					'created' => $time // Any time in the last 3 days
				);
				$this->posts_model->add_post($data);
			}
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/pages.php */