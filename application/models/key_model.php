<?php

class Key_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

	public function _generate_key()
    {
        //$this->load->helper('security');
        
        do
        {
            $salt = do_hash(time().mt_rand());
            $new_key = substr($salt, 0, config_item('rest_key_length'));
        }

        // Already in the DB? Fail. Try again
        while (self::_key_exists($new_key));

        return $new_key;
    }

    // --------------------------------------------------------------------

    /* Private Data Methods */

    public function _get_key($key)
    {
        return $this->db->where(config_item('rest_key_column'), $key)->get(config_item('rest_keys_table'))->row();
    }

    // --------------------------------------------------------------------

    public function _key_exists($key)
    {
        return $this->db->where(config_item('rest_key_column'), $key)->count_all_results(config_item('rest_keys_table')) > 0;
    }

    // --------------------------------------------------------------------

    public function _insert_key($key, $data)
    {
        
        $data[config_item('rest_key_column')] = $key;
        $data['date_created'] = function_exists('now') ? now() : time();

        return $this->db->set($data)->insert(config_item('rest_keys_table'));
    }

    // --------------------------------------------------------------------

    public function _update_key($key, $data)
    {
        return $this->db->where(config_item('rest_key_column'), $key)->update(config_item('rest_keys_table'), $data);
    }

    // --------------------------------------------------------------------

    public function _delete_key($key)
    {
        return $this->db->where(config_item('rest_key_column'), $key)->delete(config_item('rest_keys_table'));
    }
    
}