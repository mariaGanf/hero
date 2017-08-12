<?php
namespace Hero;

use Hero\Printer\Printer;

/**
 * Class Skills
 */
class Skills
{
    /**
     * @var
     */
    private $fighter;

    /**
     * @var Printer
     */
    private $printer;


    /**
     * Skills constructor.
     * @param Fighter $fighter
     * @param Printer|null $printer
     */
    public function __construct(Fighter $fighter, Printer $printer = null)
    {
        $this->fighter = $fighter;
        $this->printer = new Printer();
    }

    /**
     * Method that returns the fighter's defence if the magic shield is used
     * @param int $chance
     * @return float|int|mixed
     */
    public function useMagicShield($chance)
    {
        if (mt_rand(1,100) <= $chance) {

            $damage = $this->fighter->getDefence() / 2;
            $this->printer->output(sprintf("%s used magic shield.", $this->fighter->getName()));
        } else {
            $damage = $this->fighter->getDefence();
            $this->printer->output(sprintf("%s did not use magic shield.", $this->fighter->getName()));
        }

        return $damage;
    }

    /**
     * Method that returns the damage done to the opponent
     * @param integer $chance
     * @return mixed
     */
    public function useRapidStrike($chance)
    {
        if (mt_rand(1,100) <= $chance) {
            $damage = $this->fighter->getStrength() * 2;
            $this->printer->output(sprintf("%s used rapid strike.", $this->fighter->getName()));
        } else {
            $damage = $this->fighter->getStrength();
            $this->printer->output(sprintf("%s did not use rapid strike.", $this->fighter->getName()));
        }

        return $damage;
    }
}