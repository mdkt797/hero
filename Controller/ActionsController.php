<?php

namespace Controller;

use Services\BattleServices;
use Model\PersonagesModel;

/**
 * Class ActionsController
 * @package Controller
 */
class ActionsController
{
    /** @var BattleServices  */
    protected $battleService;

    /**
     * ActionsController constructor.
     */
    public function __construct()
    {
        $this->battleService = new BattleServices();
    }

    /**
     * @return mixed|string
     * @throws \Exception
     */
    public function battleBegins()
    {
        return $this->battleService->setupScene()->battleBegins(20);
    }
}
