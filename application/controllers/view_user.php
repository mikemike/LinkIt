<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class View_user extends Public_Controller {

	/**
	 * View the user
	 */
	public function view($username='')
	{
		if(empty($username)) show_404();
		$this->load->model('users_model');
		$this->load->model('posts_model');
		
		$this->data['user'] = $user = $this->users_model->get_user_by_username($username);
		if(empty($user)) show_404();

		$this->data['num_posts'] = $this->users_model->get_user_post_count($user->user_id);
		$this->data['num_comments'] = $this->users_model->get_user_comment_count($user->user_id);

		$this->data['latest_posts'] = $this->posts_model->get_latest_posts($num=10, null, array('u' => $user->username));
				
		$this->template->write('pagetitle', $user->username);
		$this->template->write_view('content', 'user/view_user', $this->data);
		$this->template->render();
	}
	
}

/* End of file post.php */
/* Location: ./application/controllers/post.php */