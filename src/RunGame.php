<?php 

namespace Snap;

use Snap\Player;
use Snap\Cards\Stack;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunGame extends Command
{
    protected static $defaultName = 'play';

    protected $players;

    protected function configure()
    {
        $this->setDescription('Play a game of snap between 2-4 players.');

        $this->addArgument(
            'players',
            InputArgument::IS_ARRAY | InputArgument::REQUIRED,
            'Who\'s playing? (2-4 players, separate with a space)'
        );

        $this->addOption(
            'delay',
            'd',
            InputOption::VALUE_OPTIONAL,
            'How long do you want the delay beween drawing cards? (seconds)',
            0.25
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setupPlayers($input->getArgument('players'));
        
        $this->dealCards(Stack::deck());

        $pile = new Stack();

        while (count($this->players) > 1) {
            foreach ($this->players as $i => $player) {
                $handCard = $player->hand->drawCard();
                $lastPileCard = $pile->lastCard();

                if (is_null($handCard)) {
                    $this->removePlayer($i);

                    continue;
                }

                $output->writeln(sprintf('%s drew %s of %s', $player->name(), $handCard->card(), $handCard->suit()));
                    
                if (is_null($lastPileCard)) {
                    $pile->addCard($handCard);

                    continue;
                }

                if ($handCard->card() === $lastPileCard->card()) {
                    $output->writeln([
                        '<comment>**</comment>',
                        sprintf('<comment>** SNAP - %s gains %d card(s) from the pile.</comment>', $player->name(), $pile->count()),
                        '<comment>**</comment>'
                    ]);

                    $player->hand->addCard($pile->takeAll());

                    continue;
                }

                $pile->addCard($handCard);

                if ($player->hand->isEmpty()) {
                    $output->writeln([
                        '<error>**</error>',
                        sprintf('<error>** OUT - %s is out of cards :(</error>', $player->name()),
                        '<error>**</error>',
                    ]);

                    $this->removePlayer($i);
                    
                    if (count($this->players) === 1) {
                        break 2;
                    }
                }

                usleep( floatval($input->getOption('delay')) * 1000000 );
            }
        }

        $output->writeln([
            '<info>**</info>',
            sprintf('<info>** WINNER - %s won the game!</info>', end($this->players)->name()),
            '<info>**</info>',
        ]);
    }

    protected function setupPlayers($players)
    {
        if (count($players) < 2 || count($players) > 4) {
            throw new \InvalidArgumentException('Please enter 2-4 names.');
        }

        $this->players = array_map(function ($name) {
            return new Player($name);
        }, $players);

        return $this;
    }

    protected function dealCards($deck)
    {
        $stacks = Stack::deck()
            ->shuffle()
            ->splitInto(count($this->players));

        foreach ($this->players as $i => $player) {
            $player->hand = $stacks[$i];
        }

        return $this;
    }

    protected function activePlayers()
    {
        return array_filter($this->players, function ($player) {
            return count($player->hand) > 0;
        });
    }

    protected function removePlayer ($i)
    {
        unset($this->players[$i]);
    }
}
