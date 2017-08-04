<?php

/**
 * Class Battle
 */
class Battle
{
    const BATTLE_STARTED = 'The battle has started. ';
    const BATTLE_ENDED = 'The battle has ended. ';

    /**
     * @var
     */
    private $firstFighter;

    /**
     * @var
     */
    private $secondFighter;

    /**
     * @var Printer
     */
    private $printer;

    /**
     * @var Skills
     */
    private $skills;

    /**
     * @var
     */
    private $turns;

    /**
     * Battle constructor.
     * @param Fighters $firstFighter
     * @param Fighters $secondFighter
     * @param Skills $skills
     * @param Printer|null $printer
     * @param $turns
     */
    public function __construct(
            Fighters $firstFighter,
            Fighters $secondFighter,
            Skills $skills,
            $turns,
            Printer $printer = null
    )
    {
        $this->firstFighter = $firstFighter;
        $this->secondFighter = $secondFighter;
        $this->printer = new Printer();
        $this->skills = new Skills($firstFighter, $printer);
        $this->turns = $turns;
    }

    /**
     * Method used for the attack
     * @param Fighters $firstFighter
     * @param Fighters $secondFighter
     */
    public function attack(Fighters $firstFighter, Fighters $secondFighter)
    {
        $this->turns--;

        
        // If the attacker doesn't have the flag set
        if ($firstFighter->getAttacker() == false) {

            // This means he is a defender and gets lucky
            if ($firstFighter->getLuck() == mt_rand(1 , 100)) {
                $this->printer->output(sprintf("%s got lucky this turn.", $secondFighter->getName()));

                // Swap roles
                $firstFighter->setAttacker(true);
                $secondFighter->setAttacker(false);
                return;
            }
        }

        // If the player is Orderus, he can use use magic shield and rapid strike
        if ($firstFighter->getName() == 'Orderus' && $firstFighter->getAttacker() == true) {

            // Orderus attacks
            $attackerStrength = $this->skills->useRapidStrike(10);
            $damage = ($attackerStrength - $secondFighter->getDefence());
            $secondFighter->setHealth($damage);

            // Printing battle output
            $this->printer->output(sprintf(
                "The damage was %s. %s has a health of %s.",
                    $damage,
                    $secondFighter->getName(),
                    $secondFighter->getHealth()
                )
            );

        } elseif ($secondFighter->getName() == "Orderus" && $secondFighter->getAttacker() == false) {
            // Orderus defends
            $defenderDefence = $this->skills->useMagicShield(20);
            $damage = $firstFighter->getStrength() - $defenderDefence;
            $firstFighter->setHealth($damage);

            // Printing battle output
            $this->printer->output(sprintf(
                    "The damage was %s. %s has a health of %s.",
                    $damage,
                    $secondFighter->getName(),
                    $secondFighter->getHealth()
                )
            );
        }
    }

    /**
     * Method that handles the battle
     */
    public function battle()
    {
        // Starting battle
        $this->printer->output(self::BATTLE_STARTED);

        $this->turns--;

        // First attack occurs
        $this->firstAttack($this->firstFighter, $this->secondFighter);

        while ($this->turns > 0) {

            if (!$this->firstFighter->isAlive() ||
                !$this->secondFighter->isAlive()
            ) {

                // Calculating who has won the game
                if ($this->firstFighter->isAlive()) {
                    $winner = $this->firstFighter->getName();
                } else {
                    $winner = $this->secondFighter->getName();
                }

              // The battle ends
                $this->printer->output(self::BATTLE_ENDED);
                $this->printer->output(sprintf('The winner is %s', $winner));

                return;
            } else {
                // switch players after every attack
                $this->attack($this->firstFighter, $this->secondFighter);

                if ($this->firstFighter->getAttacker()) {
                    $this->firstFighter->setAttacker(false);
                    $this->secondFighter->setAttacker(true);
                } else {
                    $this->firstFighter->setAttacker(true);
                    $this->secondFighter->setAttacker(false);
                }

            }
        }
    }

    /**
     * @param Fighters $firstFighter
     * @param Fighters $secondFighter

     */
    public function firstAttack(Fighters $firstFighter, Fighters $secondFighter)
    {
        // First attack done by the player with highest speed
        if ($firstFighter->getSpeed() > $secondFighter->getSpeed()) {

            // Printing first attack's output
            $this->printer->output(sprintf("%s started the battle.", $firstFighter->getName()));

            $this->attack($firstFighter, $secondFighter);

        } elseif ($firstFighter->getSpeed() == $secondFighter->getSpeed()) {

            // If both players have the same speed, highest luck will start the attack
            if ($firstFighter->getLuck() > $secondFighter->getLuck()) {

                $this->printer->output(sprintf("%s started the battle.", $firstFighter->getName()));
                $this->attack($firstFighter, $secondFighter);

            } else {
                $this->printer->output(sprintf("%s started the battle.", $secondFighter->getName()));
                $this->attack($firstFighter, $secondFighter);
            }
        } else {
            $this->printer->output(sprintf("%s started the battle.", $secondFighter->getName()));
            $this->attack($firstFighter, $secondFighter);
        }

        if ($firstFighter->getAttacker() == false) {
            $firstFighter->setAttacker(true);
            $secondFighter->setAttacker(false);
        } else {
            $firstFighter->setAttacker(false);
            $secondFighter->setAttacker(true);
        }
    }
}
