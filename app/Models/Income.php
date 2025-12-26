<?php
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Models/Operation.php';
use App\Core\Database;
class Income extends Operation
{
    protected PDO $db;
    private int $user_id;

    public function __construct(int $user_id)
    {
        $this->db = Database::getInstance()->getConnection();
        $this->user_id = $user_id;
    }

    public function totalIncomes(): float
    {
        $sql = "SELECT SUM(amount) AS total 
                FROM incomes 
                WHERE user_id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$this->user_id]);
        $row = $stmt->fetch();

        return $row['total'] ?? 0;
    }
}