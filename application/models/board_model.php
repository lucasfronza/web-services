<?php

class Board_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    # Insere um usuario
    public function insertUser($obj)
    {
        $this->db->insert('board', $obj);
        return $this->db->insert_id();
    }

    # Retorna todas os usuarios de um Quadro de Notas
    public function getAllUsers($key)
    {
        return $this->db->where('key', $key)->get('board')->result();
    }

    # Deleta todos usuarios de um QN
    public function deleteBoard($key)
    {
        return $this->db->where('key', $key)->delete('board');
    }

    # Atualiza um usuario
    public function updateUser($obj)
    {
        $this->db->where('id', $obj->id);
        $this->db->where('key', $obj->key);
        return $this->db->update('board', $obj);
    }

    # Seleciona um usuario
    public function getUser($obj)
    {
        $this->db->where('id', $obj->id);
        $this->db->where('key', $obj->key);
        return $this->db->get('board')->row();
    }

    # Delete um usuario
    public function deleteUser($obj)
    {
        $this->db->where('id', $obj->id);
        $this->db->where('key', $obj->key);
        return $this->db->delete('board');
    }

}