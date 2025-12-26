<?php
require_once __DIR__ . "/../Core/Database.php";
use app\Core\Database;

class Category
{
    private PDO $db;
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }
    public function create(int $user_id, string $name, string $type = 'both'): bool
    {
        $sql = "INSERT INTO categories(user_id , name , type ) VALUES (?,?,?)";
        $insert = $this->db->prepare($sql);
        return $insert->execute([$user_id, $name, $type]);
    }
    public function getAllCategoriesByUser(?string $type, int $user_id): ?array
    {
        
        if ($type === NULL) {
            $sql = "SELECT * FROM categories WHERE user_id = ? ORDER BY created_at DESC ";
            $getall = $this->db->prepare($sql);
            $getall->execute([$user_id]);
        } else {
            $sql = "SELECT * FROM categories WHERE user_id = ? AND type = ?";
            $getall = $this->db->prepare($sql);
            $getall->execute([$user_id, $type]);
        }
        $AllCate = $getall->fetchAll();
        return $AllCate;
    }
    public function updateCategorie(int $user_id, int $categorie_id, string $name, string $type = 'both'): bool
    {
        $sql = "UPDATE categories SET name = ?, type = ? WHERE id = ? AND user_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$name, $type, $categorie_id, $user_id]);
    }
    public function deleteCategorie(int $categorie_id, string $user_id): bool
    {
        $sql = "DELETE FROM categories WHERE id = ? AND user_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$categorie_id, $user_id]);
    }
    public function someCategories(?string $type, int $user_id): int
    {
        if ($type === NULL) {
            $sql = "SELECT COUNT(id) AS someCat FROM categories WHERE user_id = ? ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$user_id]);
        } else {
            $sql = "SELECT count(id) AS someCat FROM categories WHERE user_id = ? AND type = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$user_id, $type]);
        }
        $result = $stmt->fetch();
        return $result['someCat'];
    }
}