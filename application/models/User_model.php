<?php

class User_model extends CI_Model
{

    function create($data)
    {
        $this->db->insert('users', $data);
    }

    function get_user($email)
    {
        return $this->db->where('email', $email)->get('users')->row();
    }

    function login($email, $password)
    {
        $this->db->where('email', $email);
        $account = $this->db->get('users')->row();
        if ($account != NULL) {
            if (password_verify($password, $account->password)) {
                return $account;
            }
        }
    }

    function check_email($email)
    {
        return $this->db->where('email', $email)->get('users')->num_rows();
    }

    function updated_password($email, $data_password)
    {
        return $this->db->where('email', $email)->update('users', $data_password);
    }
}