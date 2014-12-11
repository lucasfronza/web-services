<?php

class Attendance_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    # Insere um usuário
    public function insertUser($obj)
    {
        return $this->db->insert('attendance', $obj);
    }

    # Retorna um usuário
    public function getUser($obj)
    {
        return $this->db->where('key', $obj->key)->where('user', $obj->user)->get('attendance')->row();
    }

    # Deleta um usuário
    public function deleteUser($obj)
    {
        return $this->db->where('key', $obj->key)->where('user', $obj->user)->delete('attendance');
    }

    # Atualiza um usuário
    public function updateUser($obj)
    {
        return $this->db->where('key', $obj->key)->where('user', $obj->user)->update('attendance', $obj);
    }

    # Retorna o Quadro de Presença com todos os usuários
    public function getAllUsers($key)
    {
        return $this->db->where('key', $key)->get('attendance')->result();
    }

    # Deleta um Quadro de Presença
    public function delete($key)
    {
        return $this->db->where('key', $key)->delete('attendance');
    }

}