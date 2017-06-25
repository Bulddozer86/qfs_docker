<?php

namespace QFS\BusinessLogicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use QFS\BusinessLogicBundle\Resources\Classes\GridData;

class MainPageController extends Controller
{

    public function listAction()
    {
        $flats = $this->get('doctrine_mongodb')
            ->getManager()
            ->getRepository('DBLogicBundle:Flat')
            ->findLatestItems();

        $grid = new GridData($flats, 4);

        return $this->render(
          'BusinessLogicBundle:Flats:flats.html.twig',
          [
            'data' => $grid->getGridData(),
            'column' => $grid->getColumn(),
          ]
        );
    }

    public function detailAction($alias)
    {
        $flat = $this->get('doctrine_mongodb')
            ->getManager()
            ->getRepository('DBLogicBundle:Flat')
            ->findByAlias($alias);

        return $this->render('BusinessLogicBundle:Flats:detail.html.twig', ['flat' => $flat]);
    }
}
