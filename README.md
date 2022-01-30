# PHP Hackathon
This document has the purpose of summarizing the main functionalities your application managed to achieve from a technical perspective. Feel free to extend this template to meet your needs and also choose any approach you want for documenting your solution.

## Problem statement
*Congratulations, you have been chosen to handle the new client that has just signed up with us.  You are part of the software engineering team that has to build a solution for the new client’s business.
Now let’s see what this business is about: the client’s idea is to build a health center platform (the building is already there) that allows the booking of sport programmes (pilates, kangoo jumps), from here referred to simply as programmes. The main difference from her competitors is that she wants to make them accessible through other applications that already have a user base, such as maybe Facebook, Strava, Suunto or any custom application that wants to encourage their users to practice sport. This means they need to be able to integrate our client’s product into their own.
The team has decided that the best solution would be a REST API that could be integrated by those other platforms and that the application does not need a dedicated frontend (no html, css, yeeey!). After an initial discussion with the client, you know that the main responsibility of the API is to allow users to register to an existing programme and allow admins to create and delete programmes.
When creating programmes, admins need to provide a time interval (starting date and time and ending date and time), a maximum number of allowed participants (users that have registered to the programme) and a room in which the programme will take place.
Programmes need to be assigned a room within the health center. Each room can facilitate one or more programme types. The list of rooms and programme types can be fixed, with no possibility to add rooms or new types in the system. The api does not need to support CRUD operations on them.
All the programmes in the health center need to fully fit inside the daily schedule. This means that the same room cannot be used at the same time for separate programmes (a.k.a two programmes cannot use the same room at the same time). Also the same user cannot register to more than one programme in the same time interval (if kangoo jumps takes place from 10 to 12, she cannot participate in pilates from 11 to 13) even if the programmes are in different rooms. You also need to make sure that a user does not register to programmes that exceed the number of allowed maximum users.
Authentication is not an issue. It’s not required for users, as they can be registered into the system only with the (valid!) CNP. A list of admins can be hardcoded in the system and each can have a random string token that they would need to send as a request header in order for the application to know that specific request was made by an admin and the api was not abused by a bad actor. (for the purpose of this exercise, we won’t focus on security, but be aware this is a bad solution, do not try in production!)
You have estimated it takes 4 weeks to build this solution. You have 3 days. Good luck!*

## Technical documentation
### Data and Domain model
We have 3 entities: 

Interval
- This entity has a start and end date, it will be used by a foreign key in the Program entity to identify a specific interval for the sport program.
- id - PK int; start_datetime - datetime; end_datetime - datetime;

Program 
- This entity describes the sport program as requested in the brief. It has a maximum number of participants, a room in which it will happen, the name of the sport
activity and a time interval.
- id - PK int; time_interval_id - FK (Interval) int; max_participants - int; room - int; sport - varchar(255);

Bookings
- This entity logs the participation of a user, identified by CNP into a specific Program identified with a foreign key.
- id - PK int; program_id FK (Program) - int; user_cnp - int;

### Application architecture
In this section, please provide a brief overview of the design of your application and highlight the main components and the interaction between them.

We have 3 main areas of interest:
-Entity
    This part of the program makes the connection between a PHP class with specific variables that are annotated in order for the Doctrine ORM 
    to interpret as a Table. This also has getters, setters and constructors.
-Controller
    This part handles all requests received on the specified routes in the config/routes.yaml file and interacts with the Entity and Repository 
    parts of the system.
-Repository
    This part of the system, handles the DB interaction, when files should be saved/searched on specific criteria.

In terms of application flow it goes like this:
- Request received
- Matched route from routes.yaml
- Send to specific controller method
- Validate access/input data
- Execute controller method
- Return response either message or the created entity.

Lastly there is a helper class called Utility.php where some validation logic is implemented. 

###  Implementation
##### Functionalities
For each of the following functionalities, please tick the box if you implemented it and describe its input and output in your application:

[x] Brew coffee \
[x] Create programme \
[x] Delete programme \
[x] Book a programme 

##### Business rules
Please highlight all the validations and mechanisms you identified as necessary in order to avoid inconsistent states and apply the business logic in your application.

The following validations were done:
- check that the starting + ending time of a program is within the opening hours of the sports center
- check that the starting time is before the ending time
- check that the times provided for program creations are valid datetime strings
- check if interval exists before creating again
- check room is not occupied by another program in the same time interval (not just same interval)
- check if program doesn't already exist
- check admin header for create/delete operations on program
- check sports from program is valid (only some sports are allowed)
- check room key is valid (only rooms from 1-10 exist)
- check user CNP is a valid CNP (13 integers)
- check program exist on bookings
- check user doesn't already have a booking on that program
- check program booking is not full
- check user is not already part of a class in the same interval

##### 3rd party libraries (if applicable)
Please give a brief review of the 3rd party libraries you used and how/ why you've integrated them into your project.

Doctrine ORM - used annotations for describing DB entities as well as composing queries in repository
Symfony dependencies (Request, Response, AbstractController, Repository) - access to the request parameters and creating easy responses

##### Environment
Please fill in the following table with the technologies you used in order to work at your application. Feel free to add more rows if you want us to know about anything else you used.

| Name                  | Choice                      |
|-----------------------|-----------------------------|
| Operating system (OS) | Windows 10                  |
| Database              | MySql 5.7                   |
| Web server            | symfony server / Apache 2.4 |
| PHP                   | 7.4                         |
| IDE                   | PhpStorm                    |
| API CLIENT            | Postman                     | 
| Symfony               | 5.3                         |

### Testing
In this section, please list the steps and/ or tools you've used in order to test the behaviour of your solution.

##
Program Create test:

URL: http://127.0.0.1:8000/api/programs
Method: POST
Header: admin-key:3FdpSrH93Z
Payload:
{
"max_participants": 10,
"room": 7,
"sport": "PILATES",
"interval": 
    {
    "start_time": "01/25/2022 06:00",
    "end_time": "01/25/2022 07:00"
    }
}

##
Program Delete test:
URL: http://127.0.0.1:8000/api/programs
Method: DELETE
Header: admin-key:3FdpSrH93Z
Payload:
{
    "id":1
}

##
Program Get test:
URL: http://127.0.0.1:8000/api/programs
Method: GET

##
Booking Create test:
URL: http://127.0.0.1:8000/api/bookings
Method: POST
Payload:
{
    "program_id":11,
    "CNP": 4932942394111
}

## Feedback
In this section, please let us know what is your opinion about this experience and how we can improve it:

1. Have you ever been involved in a similar experience? If so, how was this one different? 
   No, this is the first hackathon I attend. The only thing similar to this task were university projects.
2. Do you think this type of selection process is suitable for you?
   Yes, I appreciate the practical aspect and that it was not just theoretical questions (so far) and you can access external information sources like google/youtube and you don't have to re-invent the wheel while being
   under pressure.
3. What's your opinion about the complexity of the requirements?
    I think the project was complex, it required some specific knowledge in a framework, probably easier than pure php, I think most requirements have been completed, but I'm sure there are some that are unaddressed 
    and would take some more time and experience to make it a "good solution".
4. What did you enjoy the most?
    I think that the fact it was over 3 days.
5. What was the most challenging part of this anti hackathon?
    Setting up the framework + tools (I used a new computer).
6. Do you think the time limit was suitable for the requirements?
    Yes, I think there was enough time for a good but not perfect solution.
7. Did you find the resources you were sent on your email* *useful?
    Yes they were useful but since you suggested Laravel/Symfony or just php, I think the Symfony documentation + Symfony set-up guide would help, maybe some information about APIs
8. Is there anything you would like to improve to your current implementation?
    The payload that is sent + the response that is give might be a bit more user-friendly (even if it's API so computer to computer)
9. What would you change regarding this anti hackathon?
    Maybe if there was a code skeleton like symfony/laravel skeleton implementation that can be run to have a working server and then that you can build on.
