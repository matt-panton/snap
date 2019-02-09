<?php 

namespace Snap\Cards;

class Stack implements \Countable
{
    /**
     * @var array
     */
    protected $cards = [];

    public function __construct($cards = [])
    {
        $this->setCards($cards);
    }

    /**
     * Build a deck of cards.
     * 
     * @return self
     */
    public static function deck()
    {
        $deck = [];

        foreach (PlayingCard::SUITS as $suit) {
            foreach (PlayingCard::CARDS as $card) {
                $deck[] = new PlayingCard($card, $suit);
            }
        }

        return new static($deck);
    }

    /**
     * Draw the first card in cards array and return it.
     * 
     * @return PlayingCard
     */
    public function drawCard()
    {
        return array_shift($this->cards);
    }

    /**
     * Remove all cards from the cards array and return them.
     * 
     * @return array
     */
    public function takeAll()
    {
        $cards = $this->cards;

        $this->cards = [];

        return $cards;
    }

    /**
     * Add a card or multiple cards to end of cards array.
     * 
     * @param array|PlayingCard  $card
     */
    public function addCard($cards)
    {
        $cards = is_array($cards) ? $cards : [$cards];

        foreach ($cards as $card) {
            array_push($this->cards, $card);
        }

        return $this;
    }

    /**
     * Get the number of cards in the stack.
     * 
     * @return int
     */
    public function count()
    {
        return count($this->cards);
    }

    /**
     * Determine whether stack is empty.
     * 
     * @return boolean
     */
    public function isEmpty()
    {
        return empty($this->cards);
    }

    /**
     * Suffle the cards.
     *
     * @return  $this
     */
    public function shuffle()
    {
        shuffle($this->cards);

        return $this;
    }

    /**
     * Set cards in the stack.
     * 
     * @param array
     */
    public function setCards($cards)
    {
        $this->cards = $cards;

        return $this;
    }

    /**
     * Get cards array.
     * 
     * @return array
     */
    public function cards()
    {
        return $this->cards;
    }

    /**
     * Get the last card in cards array.
     * 
     * @return PlayingCard|null
     */
    public function lastCard()
    {
        return end($this->cards) ?: null;
    }

    /**
     * Split current stack evenly into X amount of new stacks
     * 
     * @param  integer $numberOfStacks
     * @return array
     */
    public function splitInto($numberOfStacks)
    {
        $newStackCount = floor($this->count() / $numberOfStacks);
        $newStacks = array_map(function () { return new static; }, range(1, $numberOfStacks));

        while ($this->count() >= $numberOfStacks) {
            foreach ($newStacks as $stack) {
                $stack->addCard($this->drawCard());
            }
        }

        return $newStacks;
    }
}
