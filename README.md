# Vending Machine

Developed with Laravel and MySQL

## Prerequisites

    * Git
    * Docker

## Installation


Clone the repository

    git clone https://github.com/jonhper/vending_machine.git

Switch to the repo folder

    cd vending_machine

Add permissions to run.sh file

    chmod +x run.sh

Run run.sh script

    ./run.sh

Initialize database

    docker-compose exec php php artisan migrate

You can now access the server at http://127.0.0.1:8000/
