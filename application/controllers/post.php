<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Post extends Public_Controller {

	/**
	 * View the post
	 */
	public function item($slug, $post_id)
	{
		$this->load->model('posts_model');
		$this->load->library('threaded');
		
		$this->data['post'] = $post = $this->posts_model->get_post($post_id);
		$this->data['comments'] = $this->posts_model->get_post_comments($post_id);	
						
		$this->template->write('pagetitle', $post->title);
		$this->template->write_view('content', 'posts/post', $this->data);
		$this->template->write_view('_scripts', 'posts/js/post', $this->data);
		$this->template->write_view('sidebar', 'posts/sidebar', $this->data);
		$this->template->render();
	}
	
	/**
	 * Vote the comment up
	 */
	public function ajax_comment_vote_up($post_comment_id='')
	{
		if(empty($post_comment_id)){
			echo json_encode(array('error' => 'Oops, something went wrong.'));	
		} else {
			if(empty($this->user)){
				echo json_encode(array('error' => 'Oops, you need to be logged in to do that.'));	
			} else {
				$this->load->model('posts_model');
				// User already voted on this?
				$already = $this->posts_model->user_already_voted_on_comment($this->user['user_id'], $post_comment_id);
				if($already){
					echo json_encode(array('error' => 'You can only up-vote once per comment.'));	
				} else {
					// Is this the user's own comment?
					$own = $this->posts_model->user_own_comment($this->user['user_id'], $post_comment_id);
					if($own){
						echo json_encode(array('error' => 'You can\'t vote on your own comment.'));	
					} else {
						$new_points = $this->posts_model->add_comment_vote($post_comment_id, $this->user['user_id']);
						echo json_encode(array('new_points' => $new_points));
					}
				}
			}
		}
	}
	
	/**
	 * Vote the post up
	 */
	public function ajax_post_vote($post_id='')
	{
		if(empty($post_id)){
			echo json_encode(array('error' => 'Oops, something went wrong.'));	
		} else {
			if(empty($this->user)){
				echo json_encode(array('error' => 'Oops, you need to be logged in to do that.'));	
			} else {
				$this->load->model('posts_model');
				// User already voted on this?
				$already = $this->posts_model->user_already_voted_on_post($this->user['user_id'], $post_id);
				if($already){
					echo json_encode(array('error' => 'You can only vote once per post.'));	
				} else {
					// Is this the user's own post?
					$own = $this->posts_model->user_own_post($this->user['user_id'], $post_id);
					if($own){
						echo json_encode(array('error' => 'You can\'t vote on your own post.'));	
					} else {
						$new_points = $this->posts_model->add_post_vote($post_id, $this->user['user_id'], $this->input->post('value'));
						echo json_encode(array('new_points' => $new_points));
					}
				}
			}
		}
	}
	
}

/* End of file post.php */
/* Location: ./application/controllers/post.php */