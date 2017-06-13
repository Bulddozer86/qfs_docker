<?php

namespace FlatParserBundle\Resources\Services;


class Downloader
{
  /**
   * Multi curl downloader
   * @param $data array
   * @return array
  */
  public static function download(array $data)
  {
    // array of curl handles
    $curly = array();
    // data to be returned
    $result = array();

    // multi handle
    $mh = curl_multi_init();

    // loop through $data and create curl handles
    // then add them to the multi-handle
    foreach ($data as $id => $d) {

      $curly[$id] = curl_init();

      $url = (is_array($d) && !empty($d['link'])) ? $d['link'] : $d;

      curl_setopt($curly[$id], CURLOPT_URL, $url);
      curl_setopt($curly[$id], CURLOPT_HEADER, 0);
      curl_setopt($curly[$id], CURLOPT_RETURNTRANSFER, 1);

      // post?
      if (is_array($d)) {
        if (!empty($d['post'])) {
          curl_setopt($curly[$id], CURLOPT_POST, 1);
          curl_setopt($curly[$id], CURLOPT_POSTFIELDS, $d['post']['data']);
        }
      }

      // extra options?
      if (!empty($options)) {
        curl_setopt_array($curly[$id], $options);
      }

      curl_multi_add_handle($mh, $curly[$id]);
    }

    // execute the handles
    $running = null;

    do {
      curl_multi_exec($mh, $running);
    } while($running > 0);


    // get content and remove handles
    foreach($curly as $id => $c) {
      $result[$id] = curl_multi_getcontent($c);
      curl_multi_remove_handle($mh, $c);
    }

    // all done
    curl_multi_close($mh);

    return $result;
  }

  public static function images($data, $folder, $options = array()) {

    // array of curl handles
    $curly = array();
    // data to be returned
    $result = array();

    // multi handle
    $mh = curl_multi_init();

    // loop through $data and create curl handles
    // then add them to the multi-handle
    foreach ($data as $id => $d) {

      $fileName = '/'. md5($d) . '.jpg';
      $path     = $folder . $fileName;

      if(file_exists($path)) {
        unlink($path);
      }

      $url = $d;
      $curly[$id] = curl_init($url);
      curl_setopt($curly[$id], CURLOPT_HEADER, 0);
      curl_setopt($curly[$id], CURLOPT_FILE, fopen($path, 'x'));

      curl_multi_add_handle($mh, $curly[$id]);

      $result[] = $fileName;
    }

    // execute the handles
    $running = null;
    do {
      curl_multi_exec($mh, $running);
    } while($running > 0);


    // get content and remove handles
    foreach($curly as $id => $c) {
      curl_multi_remove_handle($mh, $c);
    }

    // all done
    curl_multi_close($mh);

    return $result;
  }
}