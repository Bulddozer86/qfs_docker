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
        $grid->getGridData();

        return $this->render(
          'BusinessLogicBundle:Flats:flats.html.twig',
          [
            'data' => $grid->getGridData(),
            'column' => $grid->getColumn(),
          ]
        );

    }
}
