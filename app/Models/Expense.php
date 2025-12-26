<?php

require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Models/Operation.php';

use app\Core\Database;

class Expense extends Operation
{
    protected PDO $db;
    private int $user_id;

    public function __construct(int $user_id)
    {
        $this->db = Database::getInstance()->getConnection();
        $this->user_id = $user_id;
    }

    public function totalExpenses(): float
    {
        $sql = "SELECT SUM(amount) AS total 
                FROM expenses 
                WHERE user_id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$this->user_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return (float) ($row['total'] ?? 0);
    }

    public function totalExspensePerDay(?int $user_id = null): array
    {
        $user_id = $user_id ?? $this->user_id;

        $start = date('Y-m-d', strtotime('monday last week'));

        $days = [
            'Mon' => $start,
            'Tue' => date('Y-m-d', strtotime($start . ' +1 day')),
            'Wed' => date('Y-m-d', strtotime($start . ' +2 day')),
            'Thu' => date('Y-m-d', strtotime($start . ' +3 day')),
            'Fri' => date('Y-m-d', strtotime($start . ' +4 day')),
            'Sat' => date('Y-m-d', strtotime($start . ' +5 day')),
            'Sun' => date('Y-m-d', strtotime($start . ' +6 day')),
        ];

        $weekly = [];

        foreach ($days as $label => $date) {
            $stmt = $this->db->prepare("
                SELECT SUM(amount) AS total 
                FROM expenses 
                WHERE user_id = ? 
                  AND DATE(operation_date) = ?
            ");
            $stmt->execute([$user_id, $date]);
            $r = $stmt->fetch(PDO::FETCH_ASSOC);

            $weekly[$label] = ($r && $r['total'] !== null) ? (float) $r['total'] : 0;
        }

        return $weekly;
    }
}
