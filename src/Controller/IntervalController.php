<?php

namespace App\Controller;

use App\Entity\Interval;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IntervalController extends AbstractController
{
    public function getAction(Request $request): Response
    {
        $intervals = $this
            ->getDoctrine()
            ->getRepository(Interval::class)
            ->findAll();

        return $this->json($intervals);
    }
}