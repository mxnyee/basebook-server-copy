# Basebook Server

## Setup

### Installations

1. Install [XAMPP](https://www.apachefriends.org/download.html)
2. Install [Composer](https://getcomposer.org/download/)

### Configuring XAMPP

3. Open the XAMPP control panel and start the Apache server
4. Start the MySQL server and click "Admin" under MySQL
5. Go to the "User Accounts" tab
6. Under the user "root" with host name "localhost", click "Edit privileges"
7. Click "Change password", type in a new superuser password, and click "Go"
8. In the XAMPP installation folder (default is C:/xampp), navigate to phpMyAdmin/config.inc.php
9. Add your new password to the `$cfg['Servers'][$i]['password']` field

### Setting up Basebook

10. In the XAMPP installation folder, navigate to htdocs/
11. `git clone https://github.com/mxnyee/basebook-server` into that folder
12. Run `composer install`
13. Copy `.env.example` into a new file named `.env` and edit the variables to match your setup
14. Make sure the XAMPP Apache and MySQL servers are running
15. Visit http://localhost or, if you defined the `BASE_PATH` environment variable in your `.env` file, `http://localhost/BASE_PATH`

## API

### Create a new user

POST /user/signup

Request:
```
{
  "username": "john",
  "email": "johnsmith@example.com",
  "password": "12345",
  "name": "John Smith",
  "city": "Vancouver",
  "state": "BC",
  "accountType": "Regular"
}
```

Response:
```
{
  "username": "john",
  "email": "johnsmith@example.com",
  "password": "12345",
  "name": "John Smith",
  "city": "Vancouver",
  "state": "BC",
  "country": "CA",
  "numCoins": 0,
  "accountType": "Regular"
}
```

### Login as an existing user

POST /user/login

Request:
```
{
  "username": "john",
  "password": "12345"
}
```

### Get a user's profile information

GET /user/{username}

Response:
```
{
  "email": "johnsmith@example.com",
  "name": "John Smith",
  "city": "Vancouver",
  "state": "BC",
  "country": "CA",
  "numCoins": 645,
  "accountType": "Regular"
}
```

### Edit a user's profile information

PATCH /user/{username}

Request:
```
{
  "email": "johnnysmith@example.com",
  "password": "67890",
  "name": "Johnny Smith",
  "city": "Seattle",
  "state": "WA"
}
```
Response:
```
{
  "email": "johnnysmith@example.com",
  "password": "67890",
  "name": "Johnny Smith",
  "city": "Seattle",
  "state": "WA",
  "country": "US",
}
```

### Get a user's inventory

GET /user/{username}/inventory

Response:
```
{
  "superpowers": [
    {
      "itemId": "111",
      "itemName": "Double Like",
      "description": "Gives 2 coins every time you like a post or comment.",
      "expiryDate": "2020-04-10"
    }
  ],
  "accessories": [
    {
      "itemId": "333",
      "itemName": "Badge",
      "description": "A shiny badge to put on your profile.",
      "color": "#00B7EB"
    }
  ]
}
```

### See a user's stats

GET /user/{username}/stats

Response:
```
{
  "numPosts": 12,
  "numComments": 29
}
```

### See a user's ranking

GET /user/{username}/ranking

Response:
```
{
  "postRanking": [
    { "username": "jane", "numPosts": 34 },
    { "username": "john", "numPosts": 31 },
    { "username": "greg", "numPosts": 21 },
    { "username": "tom", "numPosts": 8 }
  ],
  "commentRanking": [
    { "username": "greg", "numPosts": 45 },
    { "username": "john", "numPosts": 20 },
    { "username": "jane", "numPosts": 4 },
    { "username": "tom", "numPosts": 0 }
  ]
}
```

### Create a new post

POST /post

Request:
```
{
  "username": "john",
  "title": "The best cinnamon buns",
  "text": "Visited the bakery today. Delicious! Their coffee is good too.",
  "locationName": "Grounds for Coffee",
  "city": "Vancouver",
  "state": "BC"
}
```
Response:
```
{
  "postId": 45,
  "username": "john",
  "title": "The best cinnamon buns",
  "text": "Visited the bakery today. Delicious! Their coffee is good too.",
  "locationName": "Grounds for Coffee",
  "city": "Vancouver",
  "state": "BC",
  "country": "CA",
  "timestamp": "2020-01-22 12:20:05",
  "numLikes": 0,
  "numDislikes": 0,
  "numComments": 0
}
```

### Fetch a feed of posts and filter the details

GET /post?username&locationName&city&state&country&numLikes&numDislikes&numComments

All query parameters are optional. If none are specified, each post will only contain `postId`, `title`, `text`, and `timestamp`.

Response:
```
{
  "result": [
    {
      "postId": 45,
      "username": "john",
      "title": "The best cinnamon buns",
      "text": "Visited the bakery today. Delicious! Their coffee is good too.",
      "locationName": "Grounds for Coffee",
      "city": "Vancouver",
      "state": "BC",
      "country": "CA",
      "timestamp": "2020-01-22 12:20:05",
      "numLikes": 15,
      "numDislikes": 3,
      "numComments": 6
    },
    {
      "postId": 14,
      "username": "jane",
      "title": "My new puppy",
      "text": "Golden retriever :D reminds me of my childhood buddy",
      "city": "Richmond",
      "state": "BC",
      "country": "CA",
      "timestamp": "2020-01-10 15:51:40",
      "numLikes": 21,
      "numDislikes": 0,
      "numComments": 2
    }
  ]
}
```

### Search for posts

GET /post/search?username={username}&title={title}&locationName={locationName}&city={city}&state={state}&country={country}

All query parameters are optional. If none are specified, all posts will be returned.

Response:
```
{
  "result": [
    {
      "postId": 45,
      "username": "john",
      "title": "The best cinnamon buns",
      "text": "Visited the bakery today. Delicious! Their coffee is good too.",
      "locationName": "Grounds for Coffee",
      "city": "Vancouver",
      "state": "BC",
      "country": "CA",
      "timestamp": "2020-01-22 12:20:05",
      "numLikes": 15,
      "numDislikes": 3,
      "numComments": 6
    },
    {
      "postId": 14,
      "username": "jane",
      "title": "My new puppy",
      "text": "Golden retriever :D reminds me of my childhood buddy",
      "city": "Richmond",
      "state": "BC",
      "country": "CA",
      "timestamp": "2020-01-10 15:51:40",
      "numLikes": 21,
      "numDislikes": 0,
      "numComments": 2
    }
  ]
}
```

## Delete a post

DELETE /post/{postId}

### React to a post

POST /post/{postId}/reaction

Request:
```
{
  "username": "jane",
  "reactionType": "like"
}
```
Response:
```
{
  "username": "jane",
  "postId": 3,
  "value": 1
}
```

### Undo a reaction to a post

DELETE /post/{postId}/reaction/{username}


### Create a new comment on a post

POST /post/{postId}/comment

Request:
```
{
  "username": "jane",
  "text": "Looks fun!"
}
```
Response:
```
{
  "commentId": 107,
  "postId": 45,
  "username": "jane",
  "text": "Looks fun!",
  "timestamp": "2020-03-30 19:12:10",
  "numLikes": 0,
  "numDislikes": 0
}
```

### Get the comments on a post and filter the details

GET /post/{postId}/comment?username&numLikes&numDislikes

All query parameters are optional. If none are specified, each comment will only contain `commentId`, `text`, and `timestamp`.

Response:
```
{
  "result": [
    {
      "commentId": 107,
      "username": "jane",
      "text": "Looks fun!",
      "timestamp": "2020-03-30 19:12:10",
      "numLikes": 0,
      "numDislikes": 0
    },
    {
      "commentId": 35,
      "username": "tom",
      "text": "Let's go again next time",
      "timestamp": "2020-02-26 21:10:32",
      "numLikes": 2,
      "numDislikes": 0
    },
    {
      "commentId": 70,
      "username": "greg",
      "text": "woah",
      "timestamp": "2020-02-23 01:03:18",
      "numLikes": 0,
      "numDislikes": 3
    },
  ]
}
```

## Delete a comment

DELETE /post/{postId}/comment/{commentId}

### React to a comment

POST /post/{postId}/comment/{commentId}/reaction

Request:
```
{
  "username": "jane",
  "reactionType": "dislike"
}
```
Response:
```
{
  "username": "jane",
  "commentId": 70,
  "postId": 35,
  "value": -1
}
```

### Undo a reaction to a comment

DELETE /post/{postId}/comment/{commentId}/reaction/{username}

### See all items available in the market

GET /market

Response:
```
{
  "superpowers": [
    {
      "itemId": 2,
      "itemName": "Double Like",
      "description": "Gives 2 coins every time you like a post or comment.",
      "price": 200,
      "duration": 3
    },
    {
      "itemId": 6,
      "itemName": "Triple Like",
      "description": "Gives 3 coins every time you like a post or comment.",
      "price": 400,
      "duration": 1
    }
  ],
  "accessories": [
    {
      "itemId": 40,
      "itemName": "Badge",
      "description": "A shiny badge to put on your profile.",
      "price": 500
    }
  ]
}
```

### Purchase an item from the market

POST /market/purchase

Request:
```
{
  "username": "john",
  "itemId": 3
}
```
Response:
```
{
  "username": "john",
  "itemId": 3,
  "expiryDate": "2020-04-10"
}
```
