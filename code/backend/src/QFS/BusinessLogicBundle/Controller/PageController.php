<?php

namespace QFS\BusinessLogicBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use QFS\BusinessLogicBundle\Resources\Classes\GridData;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\RouteRedirectView;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class PageController extends FOSRestController implements ClassResourceInterface
{
    const STEP = 4;

    /**
     *
     * @return mixed
     *
     * @ApiDoc(
     *     statusCodes={
     *         200 = "Returned when successful",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function indexAction()
    {
        $repositoryManager = $this->container->get('fos_elastica.manager');

        /** var FOS\ElasticaBundle\Repository */
        $repository = $repositoryManager->getRepository('DBLogicBundle:Flat');

        /** var array of Acme\UserBundle\Entity\User */
        $users = $repository->find('квартиру');

        $flats = $this->get('doctrine_mongodb')
          ->getManager()
          ->getRepository('DBLogicBundle:Flat')
          ->findLatestItems();

        return $users;
//        $grid = new GridData($flats, self::STEP);
//        $grid->getGridData();
//
//        return $this->render(
//          'BusinessLogicBundle:Flats:flats.html.twig',
//          [
//            'data' => $grid->getGridData(),
//            'column' => $grid->getColumn(),
//          ]
//        );
    }

    public function detailAction($id)
    {
        $flat = $this->get('doctrine_mongodb')
          ->getManager()
          ->getRepository('DBLogicBundle:Flat')
          ->findByHash($id);

        return $this->render('BusinessLogicBundle:Flats:detail.html.twig', ['flat' => $flat]);
    }

}
