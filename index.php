<?php
// Redirect to this month's report
//header('Location: ' . date('Y/m/'));

require __DIR__ . '/includes/core.php';


$tz = date_default_timezone_get(); //get timezone.

//unix time for midnight on first day of the month (in UTC)
$start_date = mktime(0,0,0, date('n'), 1, date('Y'));



$data = Data_Summary::month($start_date);


View::factory('month')
    ->set('date', $start_date)
    ->set('data', $data)
    ->render();