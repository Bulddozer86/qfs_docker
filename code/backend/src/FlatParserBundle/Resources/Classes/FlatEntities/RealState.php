<?php

namespace FlatParserBundle\Resources\Classes\FlatEntities;

use FlatParserBundle\Resources\Classes\ParserFlatData;
use FlatParserBundle\Resources\Services\Downloader;

class RealState extends ParserFlatData
{

  protected static function getValue($value)
  {
    return !$value ? $value : trim(explode(':', $value)[1]);
  }

  /**
   * Parsing element
   */
  public function parsing($html)
  {
    if (!$html) {
      return null;
    }

    $content  = \phpQuery::newDocumentHTML($html);
    $data     = [
      'price'     => self::getValue($content->find('li.col-sm-6.col-dense-left:nth-child(1)')->text()),
      'rooms'     => self::getValue($content->find('li.col-sm-6.col-dense-left:nth-child(2)')->text()),
      'date'      => strtotime(self::getValue($content->find('li.col-sm-6.col-dense-left:last-child')->prev()->text())),
      'headline'  => pq('h2.h2-under-main-menu')->text(),
      'district'  => trim(pq('div.row.row-dense > div > div.row.row-dense > div > ol > li:nth-child(2)')->text()),
      'resource'  => $this->getName(),
      'main_data' => trim(pq('body > div:nth-child(3) > div.row.row-dense > div > div:nth-child(2) > div.col-md-8 > div:nth-child(4)')->html())
                     . trim(pq('body > div:nth-child(3) > div.row.row-dense > div > div:nth-child(2) > div.col-md-8 > div:nth-child(3) > div')->html())
    ];

    $links = $content->find('div.fotorama a');

    foreach ($links as $link) {
      $data['images'][] =  $this->data[self::KEY_PREFIX] . pq($link)->attr('data-full');
    }

    \phpQuery::unloadDocuments();

    return $data;
  }

  /**
   * Parsing phone number
   */
  public function getPhone($link)
  {
    preg_match_all('/-?\d+(?:\.\d+)?+/', $link, $matches);
    $id = $matches[0][0];

    if (!$id) {
      return null;
    }

    $ajaxLink = strtr($this->phoneLink(), ['_ID_' => $id]);
    $response = Downloader::download([$ajaxLink]);
    $result   = json_decode($response[0], true);

    if (!isset($result['message'])) {
      return null;
    }

    $regex = '/\+?[(0-9)][\d-\()\-\s+]{5,12}[1-9]/';
    preg_match_all($regex, $result['message'], $matches, PREG_PATTERN_ORDER);

    return count($matches[0]) ? $matches[0] : null;
  }

}