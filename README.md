# API for managing members and games of a Club about old-fashioned video games.

## Requirement

    php 7.2
    Composer
    MySql or MariaDb
## Install the Application

* Run this command from the root directory of the application.

      composer install
* MySql dump is in the file club.sql and has to be imported in MySql

## Api endpoints and payload

### GET $WEBROOT/api/v1/members
Get the full list of the club members in json format.

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

### GET $WEBROOT/api/v1/member/{member_id}
Retrieve member data in json format.

### DELETE $WEBROOT/api/v1/member/{member_id}
Delete member

### GET $WEBROOT/api/v1/games
Get the full list of the club members in json format.
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



### GET $WEBROOT/api/v1/member/{member_id}/games
Retrieve the list of games in json format owned by a member
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

### DELETE $WEBROOT/api/v1/member/{member_id}/game/{game_id}
Delete a game from the collection of a member




