<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Websites_model extends CI_Model{
	
	/**
	 * Get the website
	 * 
	 * @param $host string
	 *
	 * @return mixed
	 */
	function get_website_by_host($host)
	{
		$this->db->select()
				 ->from('websites')
				 ->where('local', $host)
				 ->or_where('live', $host)
				 ->limit(1);
		$query = $this->db->get();
		return $query->row();
	}
}

/* End of file websites_model.php */
/* Location: ./application/controllers/websites_model.php */
