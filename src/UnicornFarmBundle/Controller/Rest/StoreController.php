<?php

namespace UnicornFarmBundle\Controller\Rest;

use Swagger\Annotations as SWG;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use UnicornFarmBundle\Service\UnicornService;

class StoreController extends Controller
{
    /**
     * @var UnicornService
     */
    private $unicornService;

    /**
     * UnicornController constructor.
     * @param UnicornService $unicornService
     */
    public function __construct(UnicornService $unicornService)
    {
        $this->unicornService = $unicornService;
    }
}
