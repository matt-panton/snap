# Snap

Run a game of snap between 2-4 players.


## Installation
1. Clone or download repo.
2. `cd` into project directory
3. `$ composer install`


## Usage
`$ php snap play <player-1> <player-2> ... <player-4>`  
You must specify 2-4 players  
`$ php snap play matt bob`  

You may specify a delay in seconds between card draws to make the game easier to follow  
`$ php snap play matt bob --delay 0.1`
