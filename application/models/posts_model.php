<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Posts_model extends CI_Model{
	
	/**
	* Adds a post to the database
	*
	* @param $data
	*	Array of data to add to the database
	*
	* @return
	*	Int number of rows inserted
	*/
	function add_post($data, $not_exist=false) {
		if(empty($data['created'])){
	        $data['created'] = date('YmdHis');
		}
		if(empty($data['website_id'])){
        	$data['website_id'] = WEBSITE_ID;
		}
		if($not_exist == true){
			$exists = $this->link_exists($data['link'], $data['website_id']);
			if($exists){
				return true;	
			}
		}
		$this->db->insert('posts', $data);
		return $this->db->insert_id();
	}
	
	/**
	 * Update a post's details
	 * 
	 * @param $post_id int
	 * @param $data array
	 *
	 * @return int
	 */
	function update_post($post_id, $data)
	{
		$this->db->where('post_id', $post_id);
		$this->db->limit(1);
		$this->db->update('posts', $data);
		return $this->db->affected_rows();
	}
	
	/**
	 * Soft delete a post
	 * 
	 * @param $post_id int
	 *
	 * @return int
	 */
	function delete_post($post_id)
	{
		$this->db->where('post_id', $post_id);
		$this->db->limit(1);
		$this->db->update('posts', array('deleted' => date('YmdHis')));
		return $this->db->affected_rows();
	}
	
	/**
	 * Get the post's details based on the post id
	 * 
	 * @param $post_id int
	 *
	 * @return mixed
	 */
	function get_post($post_id)
	{
		$this->db->select('p.*, u.email, u.username, u.first_name, u.last_name, u.points as member_points, u.twitter, u.website')
				 ->select('u.created AS member_since')
				 ->select('(
				 	SELECT COUNT(pi.post_id)
					FROM posts pi
					WHERE u.user_id = pi.user_id
				 ) AS post_count')
				 ->select('(
				 	SELECT COUNT(ci.post_id)
					FROM post_comments ci
					WHERE u.user_id = ci.user_id
				 ) AS comment_count')
				 ->from('posts p')
				 ->join('users u', 'u.user_id = p.user_id', 'inner')
				 ->where('p.post_id', $post_id)
				 ->where('p.deleted IS NULL', null, false)
				 ->where('u.deleted IS NULL', null, false)
				 ->where('u.active', 1)
				 ->where('p.website_id', WEBSITE_ID)
				 ->group_by('p.post_id')
				 ->limit(1);
		$query = $this->db->get();
		return $query->row();
	}
	
	/**
	 * Get the latest n posts
	 *
	 * @param $num
	 *	Number of posts to get
	 *
	 * @param $offset
	 *
	 * @param $search
	 *	Array of various searcable values.  Totes optional.
	 *
	 * @return Object
	 */
	function get_latest_posts($num=10, $offset=null, $search=null) 
	{
		$this->db->select('p.*, u.email, u.username, u.first_name, u.last_name, u.points as member_points, u.twitter, u.website')
			 ->select('u.created AS member_since')
			 ->select('(
				SELECT COUNT(ci.post_id)
				FROM post_comments ci
				WHERE p.post_id = ci.post_id
			 ) AS comment_count')
			 ->from('posts p')
			 ->join('users u', 'u.user_id = p.user_id', 'inner')
			 ->where('p.deleted IS NULL', null, false)
			 ->where('u.deleted IS NULL', null, false)
			 ->where('u.active', 1)
			 ->where('p.website_id', WEBSITE_ID)
			 ->order_by('p.created', 'desc')
			 ->group_by('p.post_id')
			 ->limit($num);
		if(!empty($offset)){
			$this->db->offset($offset);	
		}
		
		if(!empty($search)){
			// Search by type
			if(!empty($search['type'])){
				if($search['type'] == 'links'){
					$this->db->where('p.link !=', '');	
				} else {
					$this->db->where('p.link', '');		
				}
			}
			
			// Search using a string
			if(!empty($search['q'])){
				$this->db->like('p.title', $search['q']);
			}
			
			// Search for a user
			if(!empty($search['u'])){
				$this->db->where('u.username', trim($search['u']));
			}
		}
		
		$query = $this->db->get();
		return $query->result();
	}
	
	/**
	 * Count posts
	 *
	 * @param $offset
	 *
	 * @param $search
	 *	Array of various searcable values.  Totes optional.
	 *
	 * @return int
	 */
	function get_post_count($search=null) 
	{
		$this->db->select('COUNT(p.post_id) AS post_total')
			 ->from('posts p')
			 ->join('users u', 'u.user_id = p.user_id', 'inner')
			 ->where('p.deleted IS NULL', null, false)
			 ->where('u.deleted IS NULL', null, false)
			 ->where('p.website_id', WEBSITE_ID)
			 ->where('u.active', 1);		
		
		if(!empty($search)){
			// Search by type
			if(!empty($search['type'])){
				if($search['type'] == 'links'){
					$this->db->where('p.link !=', '');	
				} else {
					$this->db->where('p.link', '');		
				}
			}
			
			// Search using a string
			if(!empty($search['q'])){
				$this->db->like('p.title', $search['q']);
			}
		}
		
		$query = $this->db->get();
		$num = $query->row();
		return $num->post_total;
	}
	
	/**
	 * Does the user own this post?
	 *
	 * @param $user_id
	 *
	 * @param $post_id
	 *	Int of post
	 *
	 * @return 
	 * 	Boolean
	 */	 
	function user_own_post($user_id, $post_id) {
		$post = $this->get_post($post_id);
		if($user_id == $post->user_id){
			return true;
		} else {
			return false;
		}
	}
	
	/**
	* Adds a comment to a post
	*
	* @param $data
	*	Array of data to add to the database
	*
	* @return
	*	Int number of rows inserted
	*/
	function add_comment($data) {
        $data['created'] = date('YmdHis');
		$this->db->insert('post_comments', $data);
		return $this->db->insert_id();
	}

	/**
	* Get comments from a post
	*
	* @param $post_id
	*	Int of post
	*
	* @return
	*	Object containing comments
	*/
	function get_post_comments($post_id) {
		$this->db->select('c.*, u.email, u.username, u.points as user_points')
				 ->from('post_comments c')
				 ->join('users u', 'u.user_id = c.user_id', 'inner')
				 ->where('c.post_id', $post_id)
				 ->where('c.deleted IS NULL', null, false)
				 ->where('u.deleted IS NULL', null, false)
				 ->where('u.active', 1);
		$query = $this->db->get();
		return $query->result();
	}

	/**
	* Get a comment
	*
	* @param $post_comment_id
	*	Int of post
	*
	* @return
	*	Object 
	*/
	function get_post_comment($post_comment_id) {
		$this->db->select('c.*, u.email, u.username, u.points as user_points')
				 ->from('post_comments c')
				 ->join('users u', 'u.user_id = c.user_id', 'inner')
				 ->where('c.post_comment_id', $post_comment_id)
				 ->where('c.deleted IS NULL', null, false)
				 ->where('u.deleted IS NULL', null, false)
				 ->where('u.active', 1)
				 ->limit(1);
		$query = $this->db->get();
		return $query->row();
	}

	/**
	* Update a comment
	*
	* @param $post_comment_id
	*	Int of post
	*
	* @param $data
	*	Array
	*
	* @return
	*	void 
	*/
	function update_post_comment($post_comment_id, $data) {
		$this->db->where('post_comment_id', $post_comment_id);
		$this->db->update('post_comments', $data);
		return true;
	}
	
	/**
	 * Has the user already voted on a comment?
	 *
	 * @param $user_id
	 *
	 * @param $post_comment_id
	 *
	 * @return 
	 * 	Boolean
	 */
	function user_already_voted_on_comment($user_id, $post_comment_id) {
		$this->db->select('cv.comment_vote_id')
				 ->from('post_comment_votes cv')
				 ->where('cv.user_id', $user_id)
				 ->where('cv.post_comment_id', $post_comment_id)
				 ->limit(1);
		$query = $this->db->get();
		if($query->num_rows() > 0) return true;
		else return false;	
	}
	
	/**
	 * Add a vote to a comment
	 *
	 * @param $post_comment_id
	 *
	 * @param $user_id
	 *
	 * @return int
	 *	Number of points a comment now has
	 */
	function add_comment_vote($post_comment_id, $user_id) {
		$this->db->insert('post_comment_votes', array(
			'user_id' => $user_id,
			'post_comment_id' => $post_comment_id,
		));	
		// Now update a comment's points
		$comment = $this->get_post_comment($post_comment_id);
		$new_points = $comment->points + 1;
		$this->update_post_comment($post_comment_id, array('points' => $new_points));
		return $new_points;
	}
	
	/**
	 * Has the user already voted on a post?
	 *
	 * @param $user_id
	 *
	 * @param $post_id
	 *
	 * @return 
	 * 	Boolean
	 */
	function user_already_voted_on_post($user_id, $post_id) {
		$this->db->select('pv.post_vote_id')
				 ->from('post_votes pv')
				 ->where('pv.user_id', $user_id)
				 ->where('pv.post_id', $post_id)
				 ->limit(1);
		$query = $this->db->get();
		if($query->num_rows() > 0) return true;
		else return false;	
	}
	
	/**
	 * Does the user own this comment?
	 *
	 * @param $user_id
	 *
	 * @param $post_comment_id
	 *	Int of post
	 *
	 * @return 
	 * 	Boolean
	 */	 
	function user_own_comment($user_id, $post_comment_id) {
		$comment = $this->get_post_comment($post_comment_id);
		if($user_id == $comment->user_id){
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Add a vote to a post
	 *
	 * @param $post_id
	 *
	 * @param $user_id
	 *
	 * @param $value
	 *	Up or down, +1 or -1
	 *
	 * @return int
	 *	Number of points a post now has
	 */
	function add_post_vote($post_id, $user_id, $value) {
		$this->db->insert('post_votes', array(
			'user_id' => $user_id,
			'post_id' => $post_id,
			'value' => $value
		));	
		// Now update a comment's points
		$post = $this->get_post($post_id);
		$new_points = $post->points + $value;
		$this->update_post($post_id, array('points' => $new_points));
		return $new_points;
	}
	
	/**
	 * Does a link exist?
	 *
	 * @param $url
	 * @param $website_id
	 *
	 * @return boolean
	 */
	function link_exists($url, $website_id=null) {
		$this->db->select('post_id')
				 ->from('posts')
				 ->where('link', $url)
				 ->limit(1);
		if($website_id){
			$this->db->where('website_id', $website_id);	
		}
		$query = $this->db->get();
		$num = $query->num_rows();
		if($num == 1){
			return true;	
		} else {
			return false;
		}		
	}
	
}

/* End of file posts_model.php */
/* Location: ./application/controllers/posts_model.php */
