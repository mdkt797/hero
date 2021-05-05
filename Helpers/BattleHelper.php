<?php

namespace Helpers;

use Model\SpellModel;
use Services\BattleServices;

/**
 * Class BattleHelper
 * @package Helpers
 */
abstract class BattleHelper
{

    /**
     * @param array $randomAttributes
     * @return array
     */
    public function initializeStats(array $randomAttributes): array
    {
        $activeAttribute = [];

        foreach ($randomAttributes as $key => $value) {
            $activeAttribute[$key] = rand($value[0], $value[1]);
        }

        return $activeAttribute;
    }

    /**
     * Check if champion used
     * rapid strike
     *
     * @return bool
     */
    protected function rapidStrike(int $attackValue , string $attackerType , int $round): int
    {
        $chance = rand(0, 100);
        if (SpellModel::RAPID_STRIKE['chance'] > $chance) {
            $attackValue *= SpellModel::RAPID_STRIKE['value'];
            echo "round: $round | $attackerType use the strike fast ability <br/>";
        }
        return $attackValue;
    }

    /**
     * Check if champion used magick shield
     *
     * @param int $damage
     * @return int
     */
    protected function magicShield(int $damage , int $round , string $attackerType) : int {
        $chance = rand(0, 100);
        if (SpellModel::MAGICK_SHIELD['chance'] > $chance) {
            $damage /= SpellModel::MAGICK_SHIELD['value'];
            echo "round: $round | $attackerType use magic shield ability <br/>";
        }
        return $damage;
    }

    /**
     * Calculate damage
     *
     * @param string $attackerType
     * @param int $attack
     * @param int $defence
     * @return int
     */
    public function calculateDamage (string $attackerType, int $attack , int $defence , int $round) : int {
        switch ($attackerType){
            case 'hero' :
                return $this->rapidStrike($attack- $defence , $attackerType , $round) ;
                break;
            case 'monster':
                return $this->magicShield($attack  - $defence , $round , $attackerType);
                break;
        }
    }

    /**
     * Decide witch champion has first strike
     * the decision is made by the greater luck attribute
     *
     * @param string $firstBy
     * @param array $combatants
     * @return array
     */
    protected function decideFirstCall(string $firstBy, array $combatants): array
    {
        $firstCall = 'byLuck';
        $secondCall = '';
        if ($combatants['hero'][$firstBy] > $combatants['monster'][$firstBy]) {
            $firstCall = 'hero';
            $secondCall = 'monster';
        } else if ($combatants['hero'][$firstBy] < $combatants['monster'][$firstBy]) {
            $firstCall = 'monster';
            $secondCall = 'hero';
        }

        if ($firstCall === 'byLuck') {
            list ($firstCall, $secondCall) = $this->decideFirstCall('Luck', $combatants);
        }

        return [$firstCall, $secondCall];
    }

    /**
     * Check if combatant avoided hit
     *
     * @param int $luck
     * @return bool
     */
    protected function avoid(int $luck): bool
    {
        $chance = rand(0, 100);

        if ($luck >= $chance) {
            return true;
        }
        return false;
    }

    /**
     * @param int $round
     */
    abstract protected function attack(int $round): void;

    /**
     * @param int $rounds
     * @return string
     */
    abstract public function battleBegins(int $rounds = 20): string;

    /**
     * @return BattleServices
     */
    abstract public function setupScene(): BattleServices;
}