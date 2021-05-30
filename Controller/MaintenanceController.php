<?php
class MaintenanceController extends PiController {

    function StatisticsGET() {
        $CheckAuth = $this->CheckAuth(true);
        if (!isset($CheckAuth['maintenance']))
        {
            throw new Exception("You don't have permission");
        }
        $rustart = getrusage();
        $time_start = microtime(true);

        $Model = $this->CallModel("Maintenance");
        $Model->BatchMoveBotsVisits();

        $ru = getrusage();
        $time_end = microtime(true);

        $this->ReturnJson([
            'Task Status' => 'run_to_completion',
            'Computations' => $this->rutime($ru, $rustart, 'utime').'ms',
            'System Calls' => $this->rutime($ru, $rustart, 'stime').'ms',
            'Time' => (($time_end - $time_start)*1000).'ms'
        ]);
    }
}
