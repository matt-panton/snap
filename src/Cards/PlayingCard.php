<?php 

namespace Snap\Cards;

use Snap\Exceptions\InvalidCardException;
use Snap\Exceptions\InvalidSuitException;

class PlayingCard
{
    const SUITS = ['Spades', 'Hearts', 'Diamonds', 'Clubs'];

    const CARDS = ['Ace', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'Jack', 'Queen', 'King'];

    /**
     * @var string
     */
    protected $suit;

    /**
     * @var string
     */
    protected $card;

    public function __construct($card, $suit)
    {
        if (! $this->isValidSuit($suit)) {
            throw new InvalidSuitException('Invalid suit "'. $suit .'" given. Must be either: "' . join(static::SUITS, '" or "') . '"');
        }

        if (! $this->isValidCard($card)) {
            throw new InvalidCardException('Invalid suit "'. $suit .'" given. Must be either: "' . join(static::SUITS, '" or "') . '"');
        }

        $this->suit = $suit;
        $this->card = $card;
    }

    /**
     * Determine whether given suit is valid.
     * 
     * @param  string  $suit
     * @return boolean
     */
    public function isValidSuit($suit)
    {
        return in_array($suit, static::SUITS);
    }

    /**
     * Determine whether given card is valid.
     * 
     * @param  string  $card
     * @return boolean
     */
    public function isValidCard($card)
    {
        return in_array($card, static::CARDS);
    }

    /**
     * Get the playingCard's card value.
     * 
     * @return string
     */
    public function card()
    {
        return $this->card;
    }

    /**
     * Get the playingCard's suit.
     * 
     * @return string
     */
    public function suit()
    {
        return $this->suit;
    }
}
