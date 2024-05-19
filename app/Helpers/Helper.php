<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Request;

function set_active($route, $output = 'active')
{
    if (is_array($route)) {
        return in_array(Request::path(), $route) ? $output : '';
    }
    return Request::path() == $route ? $output : '';
}

function confLocale()
{
    setlocale(LC_TIME, 'id_ID');
    Carbon::setLocale('id');
}

function formatToDBDate($date)
{
    if (!$date) {
        return null;
    }
    $data = strtotime($date);
    return date('Y-m-d', $data);
}

function formatDBDateToNormal($date)
{
    if (!$date) {
        return null;
    }
    $data = strtotime($date);
    return date('d-m-Y', $data);
}

function convertToHoursMins($time, $format = '%02d:%02d') // from minutes (int)
{
    if ($time < 1) {
        return;
    }
    $hours = floor($time / 60);
    $minutes = ($time % 60);
    return sprintf($format, $hours, $minutes);
}

function completeName($nama, $gelarDepan = '', $gelarBelakang = '')
{
    $separator = ' ';
    if ($gelarBelakang != '') {
        $separator = ', ';
    }

    return "" . $gelarDepan . " " . $nama . $separator . $gelarBelakang ?? '';
}

function secondsToHoursMins($time, $format = '%02d:%02d') // from minutes (int)
{
    if ($time < 1) {
        return;
    }
    $hours = floor($time / 3600);
    $minutes = ($time / 60 % 60);
    return sprintf($format, $hours, $minutes);
}

function convertClockToMinutes($time) // from format "H:i:s"
{
    $time = explode(':', $time);
    return ($time[0] * 60) + ($time[1]) + ($time[2] ?? 0 / 60);
}

function convertClockToSeconds($time) // from format "H:i:s
{
    sscanf($time, "%d:%d:%d", $hours, $minutes, $seconds);
    // dd($seconds);
    $time_seconds = isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 3600 + $minutes * 60;
    return $time_seconds;
}
