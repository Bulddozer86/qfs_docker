<?php


namespace FlatParserBundle\Command;

use Doctrine\Common\Inflector\Inflector;
use FlatParserBundle\FlatParserBundle;
use FlatParserBundle\Resources\Classes\Factory\FlatFactory;
use FlatParserBundle\Resources\Classes\FlatContent;
use FlatParserBundle\Resources\Classes\SourceLinks;
use FlatParserBundle\Resources\Services\Downloader;
use FlatParserBundle\Resources\Services\UrlHelper;
use QFS\DBLogicBundle\Document\Flat;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Cocur\Slugify\Slugify;

class FlatParserCommand extends Command
{
    const NAME = "Flat parser";

    public function __construct()
    {
        parent::__construct(self::NAME);
    }

    protected function configure()
    {
        $this->setName('flat:download');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sourceLinks = $this->getApplication()
          ->getKernel()
          ->getContainer()->get('doctrine_mongodb')
          ->getManager()
          ->getRepository('DBLogicBundle:SourceLink');

        $flatRepository = $this->getApplication()
          ->getKernel()
          ->getContainer()->get('doctrine_mongodb')
          ->getManager()
          ->getRepository('DBLogicBundle:Flat');

        $slugify = new Slugify();
        $rootDir = $this->getApplication()->getKernel()->getRootDir().'/../';
        $sources = $sourceLinks->findBy(['is_added' => ['$eq' => false]]);

        if (!$sources) {
            //TODO:: add to logs
            echo 'No data for insert';
            exit();
        }

        foreach ($sources as $v) {
            $data[$v->getResource()][$v->getHash()] = $v->getLink();
        }

        $resources = $this->getApplication()->getKernel()->getContainer()->getParameter('parser_resource');

        foreach ($data as $name => $links) {
            $contents = Downloader::download($links);

            if (!$contents || !is_array($contents)) {
                $output->writeln("<error>Response is empty, check links to resource</error>");
                exit;
            }

            foreach ($contents as $hash => $content) {
                $link = $sourceLinks->findOneBy(['hash' => $hash]);

                if ($link->isAdded()) {
                    continue;
                }

                $element = FlatFactory::factory($name, $resources[$name]['step_one']);
                $object = $element->parsing($content);


                if (!$object) {
                    continue;
                }

                //Downloading images
                $images = [];

                if (isset($object['images'])) {
                    $folder = $rootDir.'web/images/'.$hash;

                    if (!file_exists($folder)) {
                        mkdir($folder, 0777, true);
                    }

                    $images[$hash] = Downloader::images($object['images'], $folder);
                }

                $object['phones'] = $element->getPhone($links[$hash]);

                $flat = new Flat();
                $flat->setPrice($object['price']);
                $flat->setRooms($object['rooms']);
                $flat->setDate($object['date']);
                $flat->setHeadline($object['headline']);
                $flat->setDistrict($object['district']);
                $flat->setResource($object['resource']);
                $flat->setMainData(htmlentities($object['main_data']));
                $flat->setPhones(json_encode($object['phones']));
                $flat->setHash($hash);

                $alias = $slugify->slugify($object['headline']);

                if ($flatRepository->findByAlias($alias)) {
                    $alias = $alias . '-' . substr($hash, 0, 5);
                }

                $flat->setAlias($alias);

                if (isset($images[$hash])) {
                    $flat->setImages(json_encode($images[$hash]));
                }

                $dm = $this->getApplication()->getKernel()->getContainer()->get('doctrine_mongodb')->getManager();
                $dm->persist($flat);
                $dm->flush();

                if ($flat->getId()) {
                    $link->setIsAdded(true);

                    $dm->persist($link);
                    $dm->flush();
                }
            }
        }
    }
}