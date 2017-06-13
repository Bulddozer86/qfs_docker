<?php

namespace FlatParserBundle\Resources\Classes;


class PageNotFound extends ParserElement
{

  /**
   * Parsing element
   */
  public function parsing($html)
  {
    if (!$html) {
      return null;
    }

    $content = \phpQuery::newDocumentHTML($html);
    $result  = pq($content)->find($this->selector())->length();

    \phpQuery::unloadDocuments();

    return $result > 0 ? false : true;
  }

}