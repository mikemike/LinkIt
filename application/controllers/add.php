<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Add extends Login_Controller {
	
	function __construct() {
        parent::__construct();
		$this->load->model('users_model');
		$this->load->model('posts_model');
    }
	
	/**
	 * Add a post
	 */
	public function index() 
	{			
		$this->template->write('pagetitle', 'Add a Post');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('type', 'Type', 'required|trim|strip_tags');
		if($this->input->post('type') == 'link'){
			$this->form_validation->set_rules('link_title', 'Title', 'required|trim|strip_tags');
			$this->form_validation->set_rules('link', 'Link', 'required|trim|strip_tags|prep_url');			
		} else {
			$this->form_validation->set_rules('desc_title', 'Title', 'required|trim|strip_tags');
			$this->form_validation->set_rules('comment', 'Comment', 'required|trim|strip_tags');
		}
		
		if ($this->form_validation->run() == FALSE){			
			$this->template->write_view('content', 'user/add/post', $this->data);
			$this->template->write_view('sidebar', 'elements/guidelines', $this->data);
		} else {
			
			// Save data in the database
			if($this->input->post('type') == 'link'){
				$data = array(
					'type' => $this->input->post('type'),
					'title' => $this->input->post('link_title'),
					'link' => $this->input->post('link'),
					'user_id' => $this->user['user_id'],
				);
				$title = $this->input->post('link_title');
			} else {
				$data = array(
					'type' => $this->input->post('type'),
					'title' => $this->input->post('desc_title'),
					'user_id' => $this->user['user_id'],
				);
				$title = $this->input->post('desc_title');
			}
			$post_id = $this->posts_model->add_post($data);		
			
			$comment = $this->input->post('comment');
			if(!empty($comment)){
				$this->posts_model->add_comment(
					array(
						'user_id' => $this->user['user_id'],
						'post_id' => $post_id, 
						'comment' => $comment
					)
				);	
			}
			
			redirect('l/'. url_title($title, '-', true) .'/'. $post_id );
			
		}
		$this->template->render();
	}

	/**
	 * Add a comment to a post
	 */
	public function comment($post_id='', $comment_id=null)
	{
		$post = $this->posts_model->get_post($post_id);
		if(empty($post)) show_404();

		$this->form_validation->set_rules('comment', 'Comment', 'required|trim|strip_tags');
		if ($this->form_validation->run() == FALSE){
			$this->data['error'] = 'You didn\'t enter anything!';
			redirect('l/'.url_title($post->title, '-', true).'/'.$post_id);
		} else {
			$data = array(
				'user_id' => $this->user['user_id'],
				'post_id' => $post_id, 
				'comment' => $this->input->post('comment')
			);
			if($comment_id != null){
				$data['parent_comment_id'] = $comment_id;	
			}
			$comment_id = $this->posts_model->add_comment($data);
			redirect('l/'.url_title($post->title, '-', true).'/'.$post_id.'/#'.$comment_id);
		}
		
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */