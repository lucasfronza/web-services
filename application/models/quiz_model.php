<?php

class Quiz_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    # Insere um Quiz
    public function insert($obj)
    {
        $this->db->insert('quiz', $obj);
        return $this->db->insert_id();
    }

    # Atualiza um Quiz
    public function update($obj)
    {
        $this->db->where('key', $obj->key);
        return $this->db->update('quiz', $obj);
    }

    # Retorna um Quiz
    public function get($key)
    {
        return $this->db->where('key', $key)->get('quiz')->row();
    }

    # Deleta um Quiz
    public function delete($key)
    {
        return $this->db->where('key', $key)->delete('quiz');
    }

}