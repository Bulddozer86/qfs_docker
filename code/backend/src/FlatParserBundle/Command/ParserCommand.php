<?php

namespace FlatParserBundle\Command;

use FlatParserBundle\Resources\Classes\SourceLinks;
use FlatParserBundle\Resources\Services\Downloader;
use QFS\BusinessLogicBundle\Resources\Services\Helpers\DateManager;
use QFS\DBLogicBundle\Document\SourceLink;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class ParserCommand extends Command
{
  const NAME = "Source links parser";

  public function __construct()
  {
    parent::__construct(self::NAME);
  }

  protected function configure()
  {
    $this->setName('parser:run');
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $resources = $this->getApplication()->getKernel()->getContainer()->getParameter('parser_resource');

    if (!$resources || !is_array($resources)) {
      $output->writeln("<error>Wrong config data, please check your config.yml</error>");
      exit;
    }

    $links = [];

    foreach ($resources as $resource => $data) {

      if (isset($data['step_one']['post'])) {
        $links[$resource]['post'] = $data['step_one']['post'];
      }

      $links[$resource]['link'] = $data['step_one']['link'];
    }

    if (!$links) {
      $output->writeln("<error>Wrong config data, please check your resource link in config.yml</error>");
      exit;
    }

    $contents = Downloader::download($links);

    if (!$contents || !is_array($contents)) {
      $output->writeln("<error>Response is empty, check links to resource</error>");
      exit;
    }

    $repository = $this->getApplication()
                       ->getKernel()
                       ->getContainer()->get('doctrine_mongodb')
                       ->getManager()
                       ->getRepository('DBLogicBundle:SourceLink');

    foreach ($contents as $name => $html) {
      $element = new SourceLinks($name, $resources[$name]['step_one']);
      $links   = $element->parsing($html);

      if (!$links) {
        continue;
      }

      foreach ($links as $hash => $link) {
        if ($repository->findOneBy(['hash' => ['$eq' => $hash]])) {
          continue;
        }

        $sourceLink = new SourceLink();

        $sourceLink->setResource($element->getName());
        $sourceLink->setHash($hash);
        $sourceLink->setLink($link);
        $sourceLink->setDate(DateManager::getDateTime());
        $sourceLink->setIsAdded(false);

        $dm = $this->getApplication()->getKernel()->getContainer()->get('doctrine_mongodb')->getManager();
        $dm->persist($sourceLink);
        $dm->flush();
      }

    }
  }
}