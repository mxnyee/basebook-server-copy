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

10. `git clone https://github.com/mxnyee/basebook-server`
11. Run `composer install`
12. Use the SQL scripts unders the scripts/ folder to initialize your database
13. Copy `.env.example` into a new file named `.env` and edit the variables to match your setup. Set `BASE_PATH` to the root directory of your basebook-server project (relative to the document root of your webserver, which is usually www/ or htdocs/).
14. Visit `http://localhost/BASE_PATH` to see "Hello, World!"

## API

### Create a new user

`POST /user/signup`

Properties `name`, `city`, and `state` are optional. The propery `accountType` should be one of "Regular", "Premium", "Deluxe".

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

`POST /user/login`

Request:
```
{
  "username": "john",
  "password": "12345"
}
```

### Get a user's profile information

`GET /user/{username}`

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

`PATCH /user/{username}`

All properties are optional.

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

### See a user's inventory

`GET /user/{username}/inventory`

Returns all items this user purchased from the market. Sorted by `itemName`.

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

### See a user's activity

`GET /user/{username}/activity?post&comment&postReaction&commentReaction`

Returns all posts, comments, and/or reactions made by this user. Sorted by most recent.

All query parameters are optional. If none are specified, no results will be returned.

Response:
```
{
  "post": [
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
  ],
  "comment": [
    {
      "commentId": 1, 
      "postId": 32,
      "username": "john",
      "text": "Safe travels!",
      "timestamp": "2020-02-30 14:48:03",
      "numLikes": 1,
      "numDislikes": 0
    }
  ],
  "postReaction:" [
    {
      "postId": 65,
      "username": "john",
      "value": -1
    },
    {
      "postId": 13,
      "username": "john",
      "value": 1
    },
  ],
  "commentReaction:" [
    {
      "postId": 12,
      "username": "john",
      "value": 2
    },
  ]
}
```

### See a user's inbox

`GET /user/{username}/inbox?comment&postReaction`

Returns all comments and/or reactions made on any of this user's posts. Sorted by most recent.

All query parameters are optional. If none are specified, no results will be returned.

Response:
```
{
  "comment": [
    {
      "commentId": 3, 
      "postId": 9,
      "username": "jane",
      "text": "Cute kid",
      "timestamp": "2020-01-30 12:40:15",
      "numLikes": 4,
      "numDislikes": 0
    }
  ],
  "postReaction:" [
    {
      "postId": 31,
      "username": "fred",
      "value": 2
    },
    {
      "postId": 41,
      "username": "jane",
      "value": -1
    },
    {
      "postId": 51,
      "username": "greg",
      "value": 1
    }
  ]
}
```

### See a user's stats

`GET /user/{username}/stats`

Returns the total number of posts and comments made by this user.

Returns an error if the user does not have permissions `canSeeStats`.

Response:
```
{
  "numPosts": 12,
  "numComments": 29
}
```

### See a user's top fans

`GET /user/{username}/top-fans`

Returns the users that have commented on all of this user's posts. Also returns the users that have reacted to all of this user's posts.  

Returns an error if the user does not have permissions `canSeeTopFans`.

Response:
```
{
  "comment": [
    { "username": "greg" },
    { "username": "fred" }
  ],
  "postReaction": [
    { "username": "greg" },
    { "username": "jane" },
    { "username": "fred" }
  ]
}
```

### See a user's ranking

`GET /user/{username}/ranking`

Returns the user's rank by number of posts and comments. Also shows the users who are adjacent to the user in ranking. 

Returns an error if the user does not have permissions `canSeeRanking`.

Response:
```
{
  "postRanking": [
    { "rank": 1, "username": "jane", "numPosts": 34 },
    { "rank": 2, "username": "john", "numPosts": 31 },
    { "rank": 3, "username": "steve", "numPosts": 26 },
    { "rank": 4, "username": "greg", "numPosts": 21 },
    { "rank": 5, "username": "fred", "numPosts": 8 }
  ],
  "commentRanking": [
    { "rank": 3, "username": "greg", "numComments": 45 },
    { "rank": 4, "username": "jane", "numComments": 20 },
    { "rank": 5, "username": "john", "numComments": 4 },
    { "rank": 6, "username": "fred", "numComments": 0 }
    { "rank": 7, "username": "steve", "numComments": 0 }
  ]
}
```

### Create a new post

`POST /post`

Properties `locationName`, `city`, and `state` are optional.

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

### Fetch all posts and filter the details

`GET /post?username&locationName&city&state&country&numLikes&numDislikes&numComments`

Sorted by most recent.

All query parameters are optional. If none are specified, each post will only contain `postId`, `title`, `text`, and `timestamp`. 

Response:
```
[
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
```

### Search for posts

`GET /post/search?username={username}&title={title}&locationName={locationName}&city={city}&state={state}&country={country}`

Sorted by most recent.

All query parameters are optional. If none are specified, all posts will be returned.

Response:
```
[
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
```

### Edit a post

`PATCH /post/{postId}`

All properties are optional.

Request:
```
{
  "title": "Traffic today",
  "text": "The bridge is backed up, be warned",
  "locationName": "Lions Gate Bridge",
  "city": "Vancouver",
  "state": "BC"
}
```
Response:
```
{
  "title": "Traffic today",
  "text": "The bridge is backed up, be warned",
  "locationName": "Lions Gate Bridge",
  "city": "Vancouver",
  "state": "BC",
  "country': "CA"
}
```

### Delete a post

`DELETE /post/{postId}`

### React to a post

`POST /post/{postId}/reaction`

The property `reactionType` should be one of "like", "dislike".
- Adding a positive reaction ("like") removes coins from the sender and gives them to the receiver.
- Adding a negative reaction ("dislike") removes coins from the receiver. The sender does not gain coins.

 Returns an error if the sender does not have enough coins.

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

`DELETE /post/{postId}/reaction/{username}`

- Removing a positive reaction ("like") removes coins from the receiver. The sender's coins are unaffected.
- Removing a negative reaction ("dislike") gives coins to the receiver. The sender's coins are unaffected.


### Create a new comment on a post

`POST /post/{postId}/comment`

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

`GET /post/{postId}/comment?username&numLikes&numDislikes`

Sorted by oldest.

All query parameters are optional. If none are specified, each comment will only contain `commentId`, `text`, and `timestamp`.

Response:
```
[
  {
    "commentId": 70,
    "postId": 11,
    "username": "greg",
    "text": "woah",
    "timestamp": "2020-02-23 01:03:18",
    "numLikes": 0,
    "numDislikes": 3
  },
  {
    "commentId": 35,
    "postId": 11,
    "username": "fred",
    "text": "Let's go again next time",
    "timestamp": "2020-03-06 21:10:32",
    "numLikes": 2,
    "numDislikes": 0
  },
  {
    "commentId": 107,
    "postId": 11,
    "username": "jane",
    "text": "Looks fun!",
    "timestamp": "2020-03-30 19:12:10",
    "numLikes": 0,
    "numDislikes": 0
  }
]
```

### Edit a comment

`PATCH /post/{postId}/comment/{commentId}`

All properties are optional.

Request:
```
{
  "text": "I had the same problem this morning..."
}
```
Response:
```
{
  "text": "I had the same problem this morning..."
}
```

### Delete a comment

`DELETE /post/{postId}/comment/{commentId}`

### React to a comment

`POST /post/{postId}/comment/{commentId}/reaction`

The property `reactionType` should be one of "like", "dislike".
- Adding a positive reaction ("like") removes coins from the sender and gives them to the receiver.
- Adding a negative reaction ("dislike") removes coins from the receiver. The sender does not gain coins.

Returns an error if the sender does not have enough coins.

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

`DELETE /post/{postId}/comment/{commentId}/reaction/{username}`

- Removing a positive reaction ("like") removes coins from the receiver. The sender's coins are unaffected.
- Removing a negative reaction ("dislike") gives coins to the receiver. The sender's coins are unaffected.

### See all items available in the market and sort them

`GET /market?price&itemName`

All query parameters are optional. Order matters. If none are specified, items will by sorted by `itemId`.

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
      "itemId": 5,
      "itemName": "Star",
      "description": "You're a star!",
      "price": 1000
    },
    {
      "itemId": 6,
      "itemName": "Heart",
      "description": "Self care.",
      "price": 2000
    }
  ]
}
```

### Purchase an item from the market

`POST /market/purchase`

Removes coins from the user. Returns an error if the user does not have enough coins.

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
