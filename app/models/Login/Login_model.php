<?php

class Login_model
{
    private $table = 'login';
    private $db;

    public function __construct()
    {
        $this->db = new Database(DB_USERS);
    }

    // Method untuk login //

    public function login($data)
    {
        $this->db->query(
            "SELECT * FROM {$this->table} 
                WHERE 
            `username` = :username AND
            `password` = :password_field
        "
        );

        $this->db->bind("username", $data['username']);
        $this->db->bind("password_field", $data['password']);

        return $this->db->fetch();
    }

    public function log($id) // untuk membuat status login user menjadi online
    {
        $this->db->query(
            "UPDATE {$this->table}
                SET 
                last_login_at = CURRENT_TIMESTAMP,
                status_login = 1
            WHERE id = :id"
        );

        $this->db->bind("id", $id);

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function logout($id)
    {
        $this->db->query(
            "UPDATE {$this->table}
                SET 
                status_login = 0
            WHERE id = :id"
        );

        $this->db->bind("id", $id);

        $this->db->execute();
        return $this->db->rowCount();
    }

    // Method untuk otentikasi jwt //

    public function getUserData($jwt)
    {

        $this->db->query(
            "SELECT `username`, `password`, `email` FROM {$this->table}
                        WHERE
                    `id` = :id AND
                    `username` = :username"
        ); // sesuaikan

        $this->db->bind('id', $jwt->sub);
        $this->db->bind('username', $jwt->name);

        $result = $this->db->fetch(PDO::FETCH_NUM);

        return [
            'username' => $result[0],
            'password' => $result[1],
            'email' => isset($result[2]) ? $result[2] : '-',
            'role' => $jwt->role,
            'hak_akses' => $jwt->akses
        ];
    }
}
