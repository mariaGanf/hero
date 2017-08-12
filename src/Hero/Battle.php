<?php
namespace Hero;

use Hero\Printer\Printer;

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
     * @var Fighter
     */
    private $attacker;

    /**
     * Battle constructor.
     * @param Fighter $firstFighter
     * @param Fighter $secondFighter
     * @param Skills $skills
     * @param Printer|null $printer
     * @param $turns
     */
    public function __construct(
            Fighter $firstFighter,
            Fighter $secondFighter,
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
     * @return mixed
     */
    public function getAttacker()
    {
        return $this->attacker;
    }

    /**
     * @param Fighter $attacker
     * @internal param mixed $luck
     */
    public function setAttacker(Fighter $attacker)
    {
        $this->attacker = $attacker;
    }

    /**
     * Method used for the attack
     * @param Fighter $firstFighter
     * @param Fighter $secondFighter
     */
    public function attack(Fighter $firstFighter, Fighter $secondFighter)
    {
        $this->turns--;

        // If the attacker doesn't have the flag set
        if ($this->getAttacker() !== $firstFighter) {

            // This means he is a defender and gets lucky
            if ($firstFighter->getLuck() == mt_rand(1 , 100)) {
                $this->printer->output(sprintf("%s got lucky this turn.", $secondFighter->getName()));

                // Swap roles
                $this->setAttacker($firstFighter);

                return;
            }
        }

        // If the player is Orderus, he can use use magic shield and rapid strike
        if ($firstFighter->getName() == 'Orderus' && $this->getAttacker() == $firstFighter) {

            // Orderus attacks
            $attackerStrength = $this->skills->useRapidStrike(10);
            $damage = ($attackerStrength - $secondFighter->getDefence());
            $newHealth = $secondFighter->getHealth() - $damage;
            $secondFighter->setHealth($newHealth);

            // Printing battle output
            $this->printer->output(sprintf(
                "The damage was %s. %s has a health of %s.",
                    $damage,
                    $secondFighter->getName(),
                    $secondFighter->getHealth()
                )
            );

        } elseif ($secondFighter->getName() == "Orderus" && $this->getAttacker() == $secondFighter) {
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
        $this->getFirstAttacker($this->firstFighter, $this->secondFighter);

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

                if ($this->getAttacker() == $this->firstFighter) {
                    $this->setAttacker($this->secondFighter);
                } else {
                    $this->setAttacker($this->firstFighter);
                }
            }
        }
    }

    /**
     * @param Fighter $firstFighter
     * @param Fighter $secondFighter

     */
    public function getFirstAttacker(Fighter $firstFighter, Fighter $secondFighter)
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


        if ($this->getAttacker() == $firstFighter) {
            $this->setAttacker($secondFighter);
        } else {
            $this->setAttacker($firstFighter);
        }
    }
}
