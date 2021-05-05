<?php

namespace Services;

use Model\PersonagesModel;
use Helpers\BattleHelper;

/**
 * Class BattleServices
 * @package Services
 */
class BattleServices extends BattleHelper implements BattleServicesInterface
{
    /** @var */
    public $firstCall;
    /** @var */
    public $secondCall;
    /** @var */
    protected $combatants;

    /**
     * Get combatant health
     *
     * @param string $combatant
     * @return int
     */
    public function getCombatantHealth(string $combatant) : int {
        return $this->combatants[$combatant]['Health'];
    }

    /**
     * Get combatant defence
     *
     * @param string $combatant
     * @return int
     */
    public function getCombatantDefence(string $combatant) : int {
        return $this->combatants[$combatant]['Defence'];
    }

    /**
     * Get combatant strength
     *
     * @param string $combatant
     * @return int
     */
    public function getCombatantStrength(string $combatant) : int {
        return $this->combatants[$combatant]['Strength'];
    }

    public function getCombatantLuck(string $combatant) : int {
        return $this->combatants[$combatant]['Luck'];
    }

    /**
     * Set combatant health
     *
     * @param string $combatant
     * @param int $value
     */
    public function setCombatantHealth(string $combatant , int $value) : void {
        $this->combatants[$combatant]['Health'] = $value;
    }

    /**
     * @param int $round
     */
    protected function attack(int $round): void
    {
        $defenderHealth = $this->getCombatantHealth($this->secondCall);
        $defenderDefence = $this->getCombatantDefence($this->secondCall);
        $aggressorAttack = $this->getCombatantStrength($this->firstCall);

        if (!$this->avoid($this->getCombatantLuck($this->secondCall))) {

            $damage = $this->calculateDamage($this->firstCall,$aggressorAttack , $defenderDefence , $round);;

            $health = $defenderHealth - $damage;
            $this->setCombatantHealth($this->secondCall , $health);

            echo "round: $round | $this->secondCall got a direct hit from $this->firstCall  suffering $damage damage remaining with $health life <br/>";

        }else {
            echo "round: $round | $this->secondCall   avoided a direct hit from $this->firstCall <br/>";
        }

        $pivot = $this->secondCall;
        $this->secondCall = $this->firstCall;
        $this->firstCall = $pivot;
    }

    /**
     * @param int $rounds
     * @return mixed
     * @throws \Exception
     */
    public function battleBegins(int $rounds = 20) : string
    {
        if(!$this->firstCall || !$this->secondCall) throw new \Exception('setUp.the.scene.first');
        for ($a = 1; $a <= $rounds; $a++) {

            if ($this->getCombatantHealth($this->secondCall) <= 0) {
                return $this->firstCall;
                break;
            }

            if ($this->getCombatantHealth($this->firstCall) <= 0) {
                return $this->secondCall;
                break;
            }

            $this->attack($a);
        }
        return 'Both of them got bored';
    }

    /**
     * @param int $rounds
     * @return $this
     */
    public function setupScene(): BattleServices
    {
        $this->combatants['hero'] = $this->initializeStats(PersonagesModel::HERO);
        $this->combatants['monster'] = $this->initializeStats(PersonagesModel::MONSTER);

        list ($this->firstCall, $this->secondCall) = $this->decideFirstCall('Speed' , $this->combatants);

        return $this;
    }
}