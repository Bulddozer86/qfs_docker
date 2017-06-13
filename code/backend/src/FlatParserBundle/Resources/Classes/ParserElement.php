<?php

namespace FlatParserBundle\Resources\Classes;


abstract class ParserElement
{
  const KEY_LINK     = 'link';
  const KEY_PREFIX   = 'prefix_to_detail';
  const KEY_PARSER   = 'parser';
  const KEY_SELECTOR = 'selector';
  const KEY_ATTR     = 'attr';
  const KEY_PHONE    = 'phone_link';

  /**
   * Name of source(olx, real estate)
   *
   * @var string;
   */
  protected $name;

  /**
   * Data for parsing of element
   *
   * @var array
   */
  protected $data;

  /**
   * List of settings for parsing
   *
   * @var array
   */
  protected $parser;

  /**
   * Initial object
   *
   * @param $name string
   * @param $data array
  */
  public function __construct($name, array $data)
  {
    $this->name = $name;
    $this->data = $data;

    // Not required parameter from config
    if (isset($this->data[self::KEY_PARSER])) {
      $this->parser = $this->data[self::KEY_PARSER];
    }
  }

  /**
   * Getting selector of html elements
   * @return string
  */
  protected function selector()
  {
    return $this->parser[self::KEY_SELECTOR];
  }

  /**
   * Getting attribute name of elements
   * @return string
  */
  protected function attr()
  {
    return $this->parser[self::KEY_ATTR];
  }

  /**
   * Getting link on phone
   * @return string
  */
  public function phoneLink()
  {
    return $this->data[self::KEY_PHONE];
  }

  /**
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }

  /**
   * @return array
   */
  public function getData()
  {
    return $this->data;
  }

  /**
   * Parsing element
  */
  public abstract function parsing($html);

}