## Recommended Requirements:
* PHP Version: PHP 7.3.*
* Mysql Version: 8.0.*
* NPM Version: 7.*
* Vue Version: 3.*

## How to setup and run?

- Clone this repository (git clone https://github.com/sher-ocs-79/LaraVue-Room-Reservation.git)
- Edit ".env" file and change the DB configuration accordingly.
- Migrate database (php artisan migrate)
- Execute seeder (php artisan db:seed --class=Rooms)
- Install Vue node modules (npm install)
- Run the Vue application (npm run watch)
- Run the Laravel application (php artisan serve)

## How to use the application?

Click Register page from the menu and enter your Email & Password details.
<img width="921" alt="Screen Shot 2021-06-10 at 8 20 28 PM" src="https://user-images.githubusercontent.com/2645314/121523931-598fd380-ca29-11eb-95a8-7a9c0a4b5f3d.png">

Click the Login page from the menu.
<img width="909" alt="Screen Shot 2021-06-10 at 8 19 20 PM" src="https://user-images.githubusercontent.com/2645314/121523795-2f3e1600-ca29-11eb-9e3d-f8841e1849e4.png">

From the dashboard, click My Reservation.
<img width="1156" alt="Screen Shot 2021-06-10 at 8 22 16 PM" src="https://user-images.githubusercontent.com/2645314/121524142-93f97080-ca29-11eb-997a-809d9578ae4e.png">
To edit or delete a reservation just click the button from the Action column accordingly.


To create a new reservation, click the button Create New and select the desired Room, Date, Time & Duration.
<img width="711" alt="Screen Shot 2021-06-10 at 8 25 01 PM" src="https://user-images.githubusercontent.com/2645314/121524510-f7839e00-ca29-11eb-91bc-700b12921c78.png">

To view all the reservations, click Logout and goto the Reservations page.
<img width="1139" alt="Screen Shot 2021-06-10 at 8 30 37 PM" src="https://user-images.githubusercontent.com/2645314/121525461-f606a580-ca2a-11eb-9588-e90166f3aa44.png">

## Booking Consideration
- A booking can be 30 minute or 1 hour long.
- The first booking start time at 8:00 AM, the last one (start time) at 5:00 PM.
- Booking start time and end time has to be on the same day.
- A meeting room can not be booked if already booked for the chosen time.

## The Stack
- Laravel
- MySql
- Vue

## The API
It uses Laravel Sanctum to provides authentication system for SPAs (single page application). Sanctum allows each user of your application to generate multiple API tokens for their account. These tokens may be granted abilities / scopes which specify which actions the tokens are allowed to perform.
