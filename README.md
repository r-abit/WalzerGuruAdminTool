<p align="center" style="max-height:200px; overflow: hidden; display: flex; align-items: center; justify-content: 
center;">
  <img src="cover.png" alt="drawing" style="height:200px; "/>
</p>

## About Walzer.Guru
Walzer.Guru is an administrator tool that represents the backend of
the app and allows organizers to create events. Users can register,
sign in/out for an event and before an event a job can be triggered
to find a suitable dance partner who has also signed up for the event.
The suitable dance partner is found on the basis of three parameters, age,
height and dance level, which the user specifies when registering for the event.
<br>
The application is written in Laravel, which is a PHP-framework.
### Requirements
- Docker
- PHP & Composer
- npm v8.12.1
- Node v18.4.0
### Installation
To set the application in your environment follow the instructions step by step.

1. ```composer install```
2. ```./vendor/bin/sail up -d```
3. ```./vendor/bin/sail artisan migrate```
4. ```npm run dev```

#### Optional
- To populate the database with fake data<br>```./vendor/bin/sail artisan db:seed```
- To run a queue-worker open the terminal and run<br>```./vendor/bin/sail artisan queue:work```
- To manually trigger the matching-algorithmen run this command.
  <br>```./vendor/bin/sail artisan tinker```
  <br>```MatchDancers::dispatchIf($event_id);```
- The registration form is invisible at the moment. The path to registration is<br>http://localhost/register

### About Frontend
Here we use [Inertia](https://inertiajs.com) which is the bridge between the front- and backend and is written in 
[Vue3](https://vuejs.org/guide/introduction.html).

Since this is a single-page app, the folders are separated so 
that there is a separate folder for each "main container" and the ```Show.vue``` is loaded. Each component that 
contains the ```Show.vue``` are created in Partials. So a better overview is given.

```
|── resources
│   │── js
│   │   │── Jetstream
│   │   └── Layourts
│   └── Pages
│   │   └── Dashboard
│   │   │   │── Partials
│   │   │   │   └── ...
│   │   │   └── Show.vue
│   │   └── Events
│   │   │   │── Partials
│   │   │   │   └── ...
│   │   │   └── Show.vue
│   │   └── ...
│   └── ...
└── ...
```

### Matching Algorithm
The Maching algorithm has the task to form from two groups (men and women), which register for an event under the 
equilibrium distribution of Gale-Shapley.Priority lists are created for each person appendix of the three parameters age, body size and dance level. These 
parameters are scored according to the order. The whole calculation is summarized as one job 
```app/Jobs/MatchingDancers.php```.

### Laravel Documentation
since laravel already provides a very clear and detailed documentation (and I am not a big writer myself) here is 
the [link](https://laravel.com/docs) to it. Learn more about: 
- [Routes](https://laravel.com/docs/routing).
- [Eloqent ORM](https://laravel.com/docs/eloquent).
- [Migrations](https://laravel.com/docs/migrations).
- [Queues](https://laravel.com/docs/queues).

If you are not a big reader, there is also [Laracasts](https://laracasts.com) which will help you to get to know Laravel better.
