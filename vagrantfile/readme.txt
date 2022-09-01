How to run

1) Install and initialize Homestead as established in the laravel docs:
    - https://laravel.com/docs/9.x/homestead#installation-and-setup

2) Replace the vagrantfile and the homestead yaml with the ones provided in this folder
3) Set up the folder mappings in the homestead yaml with the path where you downloaded this repo
4) Open a terminal or command prompt at the Homestead directory and run the command "vagrant up"
5) Once the vagrant box is up and running use "vagrant ssh" to connect to the box
6) cd into the "ivan-apl-test" directory and run the command "composer install"
7) Once the composer installation is finished run the migrations with the command "php artisan migrate"

The application is commandline based, the available commands are:

- csv:create
- users:import-into-queue
- users:process-queue