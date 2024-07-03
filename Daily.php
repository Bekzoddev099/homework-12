<?php

declare(strict_types=1);

class Daily
{
    const int WORK_DURATION = 540;

    public string $date;
    public string $arrivedAtStr;
    public string $leavedAtStr;

    public function calculate(
        string $date,
        string $arrivedAtStr,
        string $leavedAtStr
        
    ):void 
    {
        $arrivedAtStr = new DateTime($arrivedAtStr);
        $leavedAtStr = new DateTime($leavedAtStr);

        $dailyWorkingHours = $leavedAtStr->diff($arrivedAtStr);
        echo $date . $dailyWorkingHours->format('%H:%i:%s');
    }
}

$today = new Daily();
$today->calculate("26.06.2024 \n", '11:00:00', "16:00:00");