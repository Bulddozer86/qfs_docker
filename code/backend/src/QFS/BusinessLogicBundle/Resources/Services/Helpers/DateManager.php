<?php

namespace QFS\BusinessLogicBundle\Resources\Services\Helpers;

class DateManager
{
  protected static $ua_moths = [
    'січень'    => '01',
    'лютий'     => '02',
    'березень'  => '03',
    'квітень'   => '04',
    'еравень'   => '05',
    'червень'   => '06',
    'липень'    => '07',
    'серпень'   => '08',
    'вересень'  => '09',
    'жовтень'   => '10',
    'листопад'  => '11',
    'грудень'   => '12',
    'січня'     => '01',
    'лютого'    => '02',
    'березня'   => '03',
    'квітеня'   => '04',
    'травеня'   => '05',
    'червеня'   => '06',
    'липеня'    => '07',
    'серпеня'   => '08',
    'вересеня'  => '09',
    'жовтеня'   => '10',
    'листопада' => '11',
    'груденя'   => '12',
  ];

  public static function getNumberByName($name)
  {
    return self::$ua_moths[strtolower($name)] ?? null;
  }

  public static function getDateTime()
  {
    return strtotime(date('d.m.Y H:s:i'));
  }
}
