<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work Off Tracker</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 20px;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 300px;
            margin: 0 auto;
        }
        label {
            display: block;
            margin-bottom: 10px;
        }
        input[type="datetime-local"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <form action="uy_sql.php" method="post">
        <label>Kelgan vaqti tanlang
            <input type="datetime-local" name="arrived_at" required>
        </label><br>

        <label>Ketgan vaqti tanlang
            <input type="datetime-local" name="leaved_at" required>
        </label>
        <button type="submit">Jo'natish</button>
    </form>
</body>
</html>
<?php

class WorkOffTracker
{

    private $pdo;

    public function __construct($dsn, $username, $password)
    {
        try {
            $this->pdo = new PDO($dsn, $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Xatolik: " . $e->getMessage());
        }
    }

    public function saveTimes($arrived_at, $leaved_at)
    {
        if (!empty($arrived_at) && !empty($leaved_at)) {
            $arrived_at = new DateTime($arrived_at);
            $leaved_at  = new DateTime($leaved_at);
            $interval = $arrived_at->diff($leaved_at);
            $work_duration = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;
            $required_work_off =  540 - $work_duration;

            $arrived_at = $arrived_at->format('Y-m-d H:i:s');
            $leaved_at = $leaved_at->format('Y-m-d H:i:s');

            $query = "INSERT INTO daily (arrived_at, leaved_at, work_duration, required_work_off) VALUES (:arrived_at, :leaved_at, :work_duration, :required_work_off)";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':arrived_at', $arrived_at);
            $stmt->bindParam(':leaved_at', $leaved_at);
            $stmt->bindParam(':work_duration', $work_duration);
            $stmt->bindParam(':required_work_off', $required_work_off);
            $stmt->execute();

            return "Ma'lumotlar muvaffaqiyatli saqlandi.";
        } else {
            return 'Iltimos, barcha maydonlarni to\'ldiring.';
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tracker = new WorkOffTracker(
        'mysql:host=localhost;dbname=work_off_tracker',
        'beko',
        '9999'
    );

    $message = $tracker->saveTimes($_POST['arrived_at'], $_POST['leaved_at']);
    echo $message;
}
?>