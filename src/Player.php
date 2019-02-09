<?php 

namespace Snap;

class Player
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var Stack
     */
    public $hand;

    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Get name of player.
     * 
     * @return string
     */
    public function name()
    {
        return $this->name;
    }
}
