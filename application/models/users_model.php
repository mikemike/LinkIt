<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users_model extends CI_Model{
	
	/**
	* Adds a user to the database
	*
	* @param $data
	*	Array of data to add to the database
	*
	* @return
	*	Int number of rows inserted
	*/
	function add_user($data) {
        $data['created'] = date('YmdHis');
		$data['password'] = md5(SALT.$data['password']);
		$this->db->insert('users', $data);
		return $this->db->insert_id();
	}
	
	/**
	 * Update a user's details
	 * 
	 * @param $user_id int
	 * @param $data array
	 *
	 * @return int
	 */
	function update_user($user_id, $data)
	{
		if(!empty($data['password'])) $data['password'] = md5(SALT.$data['password']);
		$this->db->where('user_id', $user_id);
		$this->db->limit(1);
		$this->db->update('users', $data);
		return $this->db->affected_rows();
	}
	
	/**
	 * Soft delete a user
	 * 
	 * @param $user_id int
	 *
	 * @return int
	 */
	function delete_user($user_id)
	{
		$this->db->where('user_id', $user_id);
		$this->db->limit(1);
		$this->db->update('users', array('deleted' => date('YmdHis')));
		return $this->db->affected_rows();
	}
	
	/**
	 * Get the user's details based on the user id
	 * 
	 * @param $user_id int
	 *
	 * @return mixed
	 */
	function get_user($user_id)
	{
		$this->db->select()
				 ->from('users')
				 ->where('user_id', $user_id)
				 ->limit(1);
		$query = $this->db->get();
		return $query->row();
	}
	
	/**
	 * Get the user's details based on the email address
	 * 
	 * @param $email_address string
	 *
	 * @return mixed
	 */
	function get_user_by_email($email_address)
	{
		$this->db->select()
				 ->from('users')
				 ->where('email', $email_address)
				 ->limit(1);
		$query = $this->db->get();
		return $query->row();
	}
	
	/**
	 * Get the user's details based on the username
	 * 
	 * @param $username string
	 *
	 * @return mixed
	 */
	function get_user_by_username($username)
	{
		$this->db->select()
				 ->from('users')
				 ->where('username', $username)
				 ->limit(1);
		$query = $this->db->get();
		return $query->row();
	}
	
	/**
	 * Get the user's details based on the email token
	 * 
	 * @param $email_token string
	 *
	 * @return mixed
	 */
	function get_user_by_token($email_token)
	{
		$this->db->select()
				 ->from('users')
				 ->where('email_activation_token', $email_token)
				 ->limit(1);
		$query = $this->db->get();
		return $query->row();
	}

	/**
	 * Get the total number of posts made by a user
	 *
	 * @param $user_id int
	 *
	 * @return int
	 */
	function get_user_post_count($user_id)
	{
		$this->db->select('COUNT(p.post_id) AS num_posts')
				 ->from('posts p')
				 ->where('p.user_id', $user_id);
		$query = $this->db->get();
		return $query->row()->num_posts;
	}

	/**
	 * Get the total number of comments made by a user
	 *
	 * @param $user_id int
	 *
	 * @return int
	 */
	function get_user_comment_count($user_id)
	{
		$this->db->select('COUNT(c.post_comment_id) AS num_comments')
				 ->from('post_comments c')
				 ->where('c.user_id', $user_id);
		$query = $this->db->get();
		return $query->row()->num_comments;
	}
	
	/**
	 * Method to check a users login details
	 *
	 * @param $username string the supplied username
	 *
	 * @param $password string the supplied password encrypted using md5 encryption
	 *
	 * @return object or false
	 */
	function login($username, $password) 
	{
		$this->db->select()
				 ->from('users')
				 ->where('username', $username)
				 ->where('password', md5(SALT.$password))
				 ->where('deleted IS NULL', NULL, FALSE)
				 ->where('email_activated', 1)
				 ->where('active', 1);
		$query = $this->db->get();
		
		if($query->num_rows() == 0) {
			return false;
		} else {
			$user = $query->row();
			
			// update the last login
			$this->update_user($user->user_id, array('last_login' => date('Y-m-d H:i:s')));
			return $user;
		}
	}
	
	/**
	 * Grab a random, fake user
	 *
	 * @return object
	 */
	function select_fake_user()
	{
		$this->db->select()
				 ->from('users')
				 ->where('fake', 1)
				 ->order_by('user_id', 'random')
				 ->limit(1);
		$query = $this->db->get();
		return $query->row();
	}
	
}

/* End of file users_model.php */
/* Location: ./application/controllers/users_model.php */
