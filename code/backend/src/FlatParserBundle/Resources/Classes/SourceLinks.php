<?php

namespace FlatParserBundle\Resources\Classes;

class SourceLinks extends ParserElement
{
  /**
   * Parsing element
   * @param $html string
   * @return array
   */
  public function parsing($html)
  {
    if (!$html) {
      return null;
    }

    $content = \phpQuery::newDocumentHTML($html);
    $prefix  = $this->data[self::KEY_PREFIX];
    $data    = [];

    foreach ($content->find($this->selector()) as $a) {
      $link = pq($a)->attr($this->attr());
      $link = !$prefix ? $link : $prefix . $link;

      $data[md5($link)] = $link;
    };

    \phpQuery::unloadDocuments();

    return $data;
  }
}