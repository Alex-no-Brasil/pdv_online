<?php

function daterange($start, $end) {
    $start = new DateTime($start);
    $end = new DateTime($end);
    $end = $end->modify('+1 day');

    $interval = new DateInterval('P1D');
    $daterange = new DatePeriod($start, $interval, $end);

    $range = [];

    foreach ($daterange as $date) {
        $range[] = $date->format("Y-m-d");
    }

    return $range;
}
