<?php

namespace FlatParserBundle\Command;

use FlatParserBundle\Resources\Classes\PageNotFound;
use FlatParserBundle\Resources\Classes\SourceLinks;
use FlatParserBundle\Resources\Services\Downloader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class CleanerCommand extends Command
{
  const NAME = "Remove not available flats";

  public function __construct()
  {
    parent::__construct(self::NAME);
  }

  protected function configure()
  {
    $this->setName('cleaner:run');
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $resources  = $this->getApplication()->getKernel()->getContainer()->getParameter('parser_resource');
    $repository = $this->getApplication()
                       ->getKernel()
                       ->getContainer()->get('doctrine_mongodb')
                       ->getManager()
                       ->getRepository('DBLogicBundle:SourceLink');

    $sources = $repository->findAll();

    //TODO:: Add logs
    if (!$sources) {
      echo 'Empty resource' . PHP_EOL;
      return null;
    }

    $links = [];

    foreach ($sources as $v) {
      //TODO must be limit for $links
      $links[$v->getResource()][$v->getHash()] = $v->getLink();
    }

    foreach ($resources as $name => $value) {
      $contents = Downloader::download($links[$name]);

      if (!$contents || !is_array($contents)) {
        $output->writeln("<error>{$name} - Response is empty, check links to resource</error>");
        continue;
      }

      foreach ($contents as $hash => $content) {
        $element = new PageNotFound($name, $resources[$name]['not_found']);

        if ($element->parsing($content)) {
          $sourceLink = $repository->findOneByHash($hash);

          $dm = $this->getApplication()->getKernel()->getContainer()->get('doctrine_mongodb')->getManager();
          $dm->remove($sourceLink);
          $dm->flush();
        }
      }
    }
  }

}