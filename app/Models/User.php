<?php

require_once __DIR__ . "/../Core/Database.php";
use App\Core\Database;


class User
{
    private PDO $db;
    private $conn;
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }
    public function register(string $fullName, string $email, string $password): bool
    {
        $fullName = trim($fullName);
        $email = trim($email);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        if($this->emailExists($email)){
            return false;
        }
        $pass_hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users(full_name , email , password_hash) VALUES (?,?,?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$fullName, $email, $pass_hash]);
    }
    private function emailExists ($email) :bool {
        $sql = "SELECT email FROM users WHERE email = ?";   
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        if($ok = $stmt->fetch()){
            return true ;
        }
        return false;
    }
    public function login(string $email, string $password): ?array
    {
        $email = trim($email);
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if (!$user) {
            return null;
        }
        if (!password_verify($password, $user['password_hash'])) {
            return null;
        }
        unset($user['password_hash']);
        return $user;
        

    }
    
    private function getById (int $id): ?array {
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        unset($user['password_hash']);
        return $user?:null; 
    }
}