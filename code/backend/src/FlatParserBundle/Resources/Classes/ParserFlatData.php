<?php

namespace FlatParserBundle\Resources\Classes;


abstract class ParserFlatData extends ParserElement
{
  /**
   * Initial object
   *
   * @param $name string
   * @param $data array
  */
  public function __construct($name, array $data)
  {
    parent::__construct($name, $data);
  }

  /**
   * Parsing phone number
   */
  public abstract function getPhone($link);

}