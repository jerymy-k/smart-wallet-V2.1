<?php

require_once __DIR__ . '/../Core/Database.php';

use App\Core\Database;

class Operation
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }



    public function createOperation(int $user_id, int $categoryId, float $amount, string $description, string $createDate, string $type): bool
    {
        $sql = "INSERT INTO {$type} (user_id, category_id, amount, description, operation_date)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$user_id, $categoryId, $amount, $description, $createDate]);
    }

    public function editOperation(int $id, int $user_id, int $categoryId, float $amount, string $description, string $createDate, string $type): bool
    {

        $sql = "UPDATE {$type}
                SET category_id = ?, amount = ?, description = ?, operation_date = ?
                WHERE id = ? AND user_id = ?";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$categoryId, $amount, $description, $createDate, $id, $user_id]);
    }

    public function getAllOperationPerUser(int $user_id, string $type): array
    {
        $sql = "SELECT 
                    o.id,
                    o.amount,
                    o.description,
                    o.operation_date,
                    c.name AS category_name
                FROM {$type} o
                JOIN categories c ON o.category_id = c.id
                WHERE o.user_id = ?
                ORDER BY o.operation_date DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$user_id]);

        return $stmt->fetchAll();
    }
    public function deleteOperation(int $id, int $user_id, string $type): bool
    {
        $sql = "DELETE FROM {$type} WHERE id = ? AND user_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id, $user_id]);
    }
    public function getOperationById(int $operationID, $type): ?array
    {
        $sql = "SELECT * FROM $type WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$operationID]);
        $oprationResult = $stmt->fetch();
        return $oprationResult;
    }
}
