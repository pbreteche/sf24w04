<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\CacheItem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Contracts\Cache\CacheInterface;

class MenuController extends AbstractController
{
    #[Cache(expires: '+1 day', public: true)]
    public function menu(
        CacheInterface $cache,
    ): Response
    {
        $data = $cache->get('unique.key.path', function (CacheItem $cacheItem) {
            $cacheItem->expiresAfter(3600);

            return ['la', 'donnée'];
        });

        return $this->render('/menu/menu.html.twig');
    }
}
