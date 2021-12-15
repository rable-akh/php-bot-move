<?php

namespace App\Core;

use phpDocumentor\Reflection\Types\Integer;

class Bot
{

    /*
     * @var Position
     */
    protected $position;

    public function __construct()
    {
        $this->position = new Position();
    }

    /**
     * The main entry point to the Bot object.
     * @param $commandString
     * @return string
     */
    public function move($commandString)
    {
        $commands = $this->getMoveCommands($commandString);
        foreach ($commands as $command) {
            $methodName = $command['method'];
            $param = $command['param'];
            $this->position->$methodName($param);
        }
        return $this->getPosition();
    }

    /**
     * Turn the bot clockwise.
     * @return $this
     */
    public function turnRight()
    {
        $this->position->turn('right');
        return $this;
    }

    /**
     * Turn the bot anticlockwise.
     * @return $this
     */
    public function turnLeft()
    {
        $this->position->turn('left');
        return $this;
    }

    /**
     * Move the bot the number of the provided @steps in the direction
     * that it is currently facing.
     *
     * @param int $steps
     * @return $this
     */
    public function walk($steps = 1)
    {
        $this->position->walk($steps);
        return $this;
    }

    /**
     * Return a string representation of the bot position.
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position->__toString();
    }

    /**
     * Get the current direction the bot is facing.
     *
     * @return string
     */
    public function getDirection()
    {
        return $this->position->getDirection();
    }

    /**
     * Parse the command string and extracts the turning and moving commands that should be called.
     *
     * @param string $command
     * @return array
     * @throws \Exception
     */
    protected function getMoveCommands($command)
    {
        $methods = [];
        /*
         * Split the command string into characters and loop over them.
         */
        foreach (str_split($command) as $char) {
            switch ($char) {
                case 'R':
                case 'L':
                    $currentMethod = [];
                    $currentMethod['method'] = 'turn';
                    $currentMethod['param'] = $char == 'R' ? 'right' : 'left';
                    $methods[] = $currentMethod;
                    break;
                case 'W':
                    $methods[]['method'] = 'walk';
                    break;
                default:
                    $lastMethodIndex = count($methods) - 1;
                    if (!is_numeric($char) || count($methods) == 0 || $methods[$lastMethodIndex]['method'] !== 'walk') {
                        throw new \Exception('The move command is not in a valid format');
                    }
                    $methods[$lastMethodIndex]['param'] = isset($methods[$lastMethodIndex]['param']) ? $methods[$lastMethodIndex]['param'] . $char : $char;
                    break;
            }
        }
        return $methods;
    }
}