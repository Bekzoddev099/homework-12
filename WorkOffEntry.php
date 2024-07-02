<?php
class WorkOffEntry {
    private $id;
    private $arrived_at;
    private $leaved_at;
    private $required_work_off;
    private $worked_off;

    public function __construct($id, $arrived_at, $leaved_at, $required_work_off, $worked_off) {
        $this->id = $id;
        $this->arrived_at = $arrived_at;
        $this->leaved_at = $leaved_at;
        $this->required_work_off = $required_work_off;
        $this->worked_off = $worked_off;
    }

    public function save($pdo) {
        if ($this->id) {
            $stmt = $pdo->prepare("UPDATE work_off_entries SET arrived_at = ?, leaved_at = ?, required_work_off = ?, worked_off = ? WHERE id = ?");
            $stmt->execute([$this->arrived_at, $this->leaved_at, $this->required_work_off, $this->worked_off, $this->id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO work_off_entries (arrived_at, leaved_at, required_work_off, worked_off) VALUES (?, ?, ?, ?)");
            $stmt->execute([$this->arrived_at, $this->leaved_at, $this->required_work_off, $this->worked_off]);
            $this->id = $pdo->lastInsertId();
        }
    }

    public static function getAll($pdo) {
        $stmt = $pdo->query("SELECT * FROM work_off_entries");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function calculateTotalWorkOff($pdo) {
        $stmt = $pdo->query("SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(required_work_off))) AS total_work_off FROM work_off_entries");
        return $stmt->fetch(PDO::FETCH_ASSOC)['total_work_off'];
    }
}
?>
