# TIC TAC TOE

> A console based game made in PHP of the tic tac toe classic game

From Wikipedia:
Tic-tac-toe (American English), noughts and crosses (British English) or Xs and Os, is a paper-and-pencil game for two players, X and O, who take turns marking the spaces in a 3×3 grid. The player who succeeds in placing three of their marks in a horizontal, vertical, or diagonal row wins the game.

Requires `PHP 7.3`

## Purpose

This is an exercise for an interview. The most important was to implement best practices with DDD and Hexagonal architecture in mind.

This code is prepared for implementing an api rest with low effort, you only need to create controllers, and use existent commands, queries and handlers to point to the usecases.

The command UI is not tested and only implemented for showing code is working properly.

## Setup

Clone the repository and install the dependencies

```shell
composer install
```

Running the tests

```shell
./bin/phpunit tests
```

## Docker Setup

Clone the repository and install the dependencies

```shell
make refresh-project
```

Running tests from container

```shell
make tests
```

## How to play

It's necessary to run different commands to play the game, we explain in the following lines.
You can run locally if you have installed PHP 7.3.2 or you can enter docker container for playing game.

```shell
docker exec -it tic-tac-toe_dev bash
``` 

The game process is interactive, the cli will ask you for parameters.

 
## Step 1 - Create users

First we need to create two players for being able to play game.
Run next command and enter an id in uuid4 format


```shell
./bin/console tictactoe:user:create
```

### i. Delete user

You can delete user with next command. You will be asked to write id from user you want to delete

```shell
./bin/console tictactoe:user:delete
```

## Step 2 - Create game

After players are created you can start a new game with next command:
You have to pass a geme id in uuid4 format and two user uuid4 previously created.

```shell
./bin/console tictactoe:game:create
```

## Step 3 - Get game actual status

After a game is created, at any moment you can check game status for a given game id. 
This will give you important data about status game. 
If there is a winner, if a game si finished or not and who is next player

```shell
./bin/console tictactoe:game:get
```

## Step 4 - Make a move in the game

You only need to provide game id and x position and y position of the board.
Game will start for player one, and next move will be for player two and so on.

```shell
./bin/console tictactoe:game:move
```

### i. Game is finished

If you want to know if game is finished you have to ask for game status

```shell
./bin/console tictactoe:game:get
```
