<?php

/************************************************************************/
/*  constants v1.2o                                                     */
/************************************************************************/


if (!isset($body))  die ('error: this is a pluggable module and cannot be used standalone.');


$month = array (1 => 'январь', 2 => 'февраль', 3 => 'март', 4 => 'апрель', 5 => 'май', 6 => 'июнь', 7 => 'июль', 8 => 'август', 9 => 'сентябрь', 10 => 'октябрь', 11 => 'ноябрь', 12 => 'декабрь');
$montha = array (1 => 'января', 2 => 'февраля', 3 => 'марта', 4 => 'апреля', 5 => 'мая', 6 => 'июня', 7 => 'июля', 8 => 'августа', 9 => 'сентября', 10 => 'октября', 11 => 'ноября', 12 => 'декабря');
$monthe = array (1 => 'январе', 2 => 'феврале', 3 => 'марте', 4 => 'апреле', 5 => 'мае', 6 => 'июне', 7 => 'июле', 8 => 'августе', 9 => 'сентябре', 10 => 'октябре', 11 => 'ноябре', 12 => 'декабре');
$months = array (1 => 'янв', 2 => 'фев', 3 => 'мар', 4 => 'апр', 5 => 'май', 6 => 'июн', 7 => 'июл', 8 => 'авг', 9 => 'сен', 10 => 'окт', 11 => 'ноя', 12 => 'дек');
$weekdayn = array('воскресенье', 'понедельник', 'вторник', 'среда', 'четверг', 'пятница', 'суббота', 'воскресенье');
//$weekdayns = array('вос', 'пон', 'втр', 'срд', 'чет', 'пят', 'суб', 'вос');
$weekdaynss = array('вс', 'пн', 'вт', 'ср', 'чт', 'пт', 'сб', 'вс');



$curr['time'] = time();
$curr['date'] = date('Y-m-d', $curr['time']);
$curr['datetime'] = date('Y-m-d H:i:s', $curr['time']);

$curr['datea'] = getdate($curr['time']);
$curr['year'] = $curr['datea']['year'];
$curr['mon']  = $curr['datea']['mon'];
$curr['mday'] = $curr['datea']['mday'];
$curr['wday'] = $curr['datea']['wday'];
  if (!$curr['wday'])  $curr['wday'] = 7;
$curr['hours'] = $curr['datea']['hours'];

$curr['lyear'] = $curr['year'] - (($curr['mon'] < 8)?1:0);


?>