<?php

namespace Services;


/**
 * Class BattleServices
 * @package Services
 */
interface BattleServicesInterface
{
    /**
     * @param int $rounds
     * @return mixed
     * @throws \Exception
     */
    public function battleBegins(int $rounds = 20) : string;

    /**
     * @param int $rounds
     * @return $this
     */
    public function setupScene(): BattleServices;
}