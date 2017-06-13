<?php

namespace FlatParserBundle\Resources\Classes\Factory;

use FlatParserBundle\Resources\Classes\FlatEntities\Olx;
use FlatParserBundle\Resources\Classes\FlatEntities\RealState;

class FlatFactory
{
  /**
   * @param string $type
   *
   * @return FormatterInterface
   */
  public static function factory($name, array $data)
  {
    switch ($name) {
      case 'olx':
        return new Olx($name, $data);
        break;
      case 'real_state':
        return new RealState($name, $data);
        break;
    }

    throw new \InvalidArgumentException('Unknown format given');
  }
}