<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\Cache;

class MenuController extends AbstractController
{
    #[Cache(expires: '+1 day', public: true)]
    public function menu(): Response
    {
        return $this->render('/menu/menu.html.twig');
    }
}
