<?php
include 'config.php';
include 'WorkOffEntry.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $arrived_at = $_POST['arrived_at'];
    $leaved_at = $_POST['leaved_at'];
    $required_work_off = $_POST['required_work_off'];
    $worked_off = isset($_POST['worked_off']) ? 1 : 0;

    $entry = new WorkOffEntry(null, $arrived_at, $leaved_at, $required_work_off, $worked_off);
    $entry->save($pdo);
}

$entries = WorkOffEntry::getAll($pdo);
$total_work_off = WorkOffEntry::calculateTotalWorkOff($pdo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PWOT - Personal Work Off Tracker</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>PWOT - Personal Work Off Tracker</h1>
    <form method="post" class="mb-4">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="arrived_at">Arrived at:</label>
                <input type="datetime-local" id="arrived_at" name="arrived_at" class="form-control" required>
            </div>
            <div class="form-group col-md-6">
                <label for="leaved_at">Leaved at:</label>
                <input type="datetime-local" id="leaved_at" name="leaved_at" class="form-control" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="required_work_off">Required work off:</label>
                <input type="time" id="required_work_off" name="required_work_off" class="form-control" required>
            </div>
            <div class="form-group col-md-6 form-check align-self-end">
                <input type="checkbox" class="form-check-input" id="worked_off" name="worked_off">
                <label class="form-check-label" for="worked_off">Worked off</label>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    <table class="table table-striped">
        <thead>
        <tr>
            <th>#</th>
            <th>Arrived at</th>
            <th>Leaved at</th>
            <th>Required work off</th>
            <th>Worked off</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($entries as $entry): ?>
            <tr>
                <td><?= $entry['id'] ?></td>
                <td><?= $entry['arrived_at'] ?></td>
                <td><?= $entry['leaved_at'] ?></td>
                <td><?= $entry['required_work_off'] ?></td>
                <td><?= $entry['worked_off'] ? 'Worked off' : '' ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <p><strong>Total work off hours:</strong> <?= $total_work_off ?></p>
</div>
</body>
</html>
