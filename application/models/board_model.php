<?php

class Board_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    # Insere uma matéria
    public function insertSubject($obj)
    {
        $this->db->insert('board', $obj);
        return $this->db->insert_id();
    }

    # Retorna todas as matérias de um Quadro de Notas
    public function getAllSubjects($key)
    {
        return $this->db->where('key', $key)->get('board')->result();
    }

    # Deleta todas as matérias de um QN
    public function deleteBoard($key)
    {
        return $this->db->where('key', $key)->delete('board');
    }

    # Atualiza uma matéria
    public function updateSubject($obj)
    {
        $this->db->where('subject_id', $obj->subject_id);
        $this->db->where('key', $obj->key);
        return $this->db->update('board', $obj);
    }

    # Seleciona uma matéria
    public function getSubject($obj)
    {
        $this->db->where('subject_id', $obj->subject_id);
        $this->db->where('key', $obj->key);
        return $this->db->get('board')->row();
    }

    # Delete uma matéria
    public function deleteSubject($obj)
    {
        $this->db->where('subject_id', $obj->subject_id);
        $this->db->where('key', $obj->key);
        return $this->db->delete('board');
    }

}