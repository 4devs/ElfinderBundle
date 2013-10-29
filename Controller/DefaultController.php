<?php

namespace FDevs\ElfinderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $editor = $this->container->getParameter('f_devs_elfinder.editor');

        return $this->render('FDevsElfinderBundle:Default:' . $editor . '.html.twig');
    }

    public function connectorAction(Request $request)
    {
        $cmd = $request->get('cmd', false);
        if (!$cmd) {
            throw new BadRequestHttpException('cmd is required');
        }
        $request->query->remove('cmd');

        return new JsonResponse($this->get('f_devs_elfinder.connector')->run($cmd, $request->query->all()));
    }
}
