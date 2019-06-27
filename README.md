# API for managing members and games of a Club about old-fashioned video games.

## Requirement

    php 7.2
    Composer
    MySql or MariaDb
## Install the Application

* Run this command from the root directory of the application.

      composer install
* MySql dump is in the file club.sql and has to be imported in MySql
## Authentication
HttpBasicAuthentication is possible to use but has been commented for this exercise.
## Api endpoints and payload

### GET $WEBROOT/api/v1/members
Get the full list of the club members in json format.

Output:
```json
[
    {
        "member_id": "1",
        "first_name": " Albert",
        "last_name": " Levert",
        "birthdate": "1972-03-02"
    },
    {
        "member_id": "9",
        "first_name": " Stéphane",
        "last_name": " Gerber",
        "birthdate": "1968-03-15"
    }
]
```
### POST $WEBROOT/api/v1/member
Add a member to the club

Input format : json or x-www-form-urlencoded

Payload for application/json format: 
```json
{
	"first_name": "Aline",
	"last_name" : "Müller",
	"birthdate" : "1978-03-15"
}
```
Output:
```json
{
    "last_name": "Müller",
    "first_name": "Aline",
    "birthdate": "978-03-15",
    "member_id": "14"
}
```

### PUT $WEBROOT/api/v1/member/{member_id}
Update a member data

Input format : json or x-www-form-urlencoded

Payload for application/json format: 
```json
{
	"first_name": "Aline",
	"last_name" : "Miller",
	"birthdate" : "1978-03-15"
}
```
Output:
```json
{
    "last_name": "Miller",
    "first_name": "Aline",
    "birthdate": "1978-03-15",
    "member_id": "10"
}
```
### GET $WEBROOT/api/v1/member/{member_id}
Retrieve member data in json format.
Output:
```json
{
    "member_id": "10",
    "first_name": "Valérie",
    "last_name": "Noirat",
    "birthdate": "1968-03-03"
}
```

### DELETE $WEBROOT/api/v1/member/{member_id}
Delete member

Output:
```json
{
    "Ok": {
        "text": "Successfully deleted record"
    }
}
```
### GET $WEBROOT/api/v1/games
Get the full list of the club members in json format.

Output:
```json
[
    {
        "game_id": "1",
        "name": " Donkey Kong",
        "release_year": "1984"
    },
    {
        "game_id": "2",
        "name": "Super Mario Bros.",
        "release_year": "1983"
    },
    {
        "game_id": "3",
        "name": "The Legend of Zelda",
        "release_year": "1987"
    }
]
```

### POST $WEBROOT/api/v1/game
Add a game to the club

Input format : json or x-www-form-urlencoded

Payload for application/json format: 
```json
{
	"name": "Centipede",
	"release_year" : "1980"
}
```
Output:


### GET $WEBROOT/api/v1/member/{member_id}/games
Retrieve the list of games in json format owned by a member

Output:
```json
[
    {
        "game_id": "1",
        "name": " Donkey Kong",
        "release_year": "1984"
    },
    {
        "game_id": "2",
        "name": "Super Mario Bros.",
        "release_year": "1983"
    },
    {
        "game_id": "3",
        "name": "The Legend of Zelda",
        "release_year": "1987"
    }
]
```

### POST $WEBROOT/api/v1/member/{member_id}/game/{game_id}
Add a game to the collection of a member

Output:
```json{
{
    "Ok": {
        "text": "member_id:11 game_id:4 added"
    }
}
```
### DELETE $WEBROOT/api/v1/member/{member_id}/game/{game_id}
Delete a game from the collection of a member

Output:
```json
{
    "Ok": {
        "text": "member_id:11 game_id:4 deleted"
    }
}
```

## Error Management

If an error occurs the following json message will the sent:
```json
{
    "Error": {
        "text": "<Error message>"
    }
}
```

HTTP 200 OK success status will be sent, should be improved by sending a more appropriate  status





