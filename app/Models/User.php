<?php

require_once __DIR__ . "/../Core/Database.php";

use App\Core\Database;

class User
{
    private PDO $db;

    public string $fullName;
    public string $email;
    private string $password;

    public function __construct(?string $name, string $eml, string $pass)
    {
        $this->fullName = $name;
        $this->email = $eml;
        $this->password = $pass;

        $this->db = Database::getInstance()->getConnection(); // singleton
    }

    public function register(): bool
    {
        $fullName = trim($this->fullName);
        $email = trim($this->email);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        if (self::emailExists($email)) {
            return false;
        }

        $pass_hash = password_hash($this->password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users(full_name, email, password_hash) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([$fullName, $email, $pass_hash]);
    }

    private static function emailExists(string $email): bool
    {
        $conn = Database::getInstance()->getConnection();

        $sql = "SELECT id FROM users WHERE email = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$email]);

        return (bool) $stmt->fetch();
    }

    public function login(): ?array
    {
        $email = trim($this->email);
        $password = $this->password;

        $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return null;
        }

        if (!password_verify($password, $user['password_hash'])) {
            return null;
        }

        unset($user['password_hash']);
        return $user;
    }

    private function getById(int $id): ?array
    {
        $sql = "SELECT * FROM users WHERE id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user)
            return null;

        unset($user['password_hash']);
        return $user;
    }
}
