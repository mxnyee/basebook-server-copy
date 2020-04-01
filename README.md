# Basebook Server

## Setup

hmm

## API

### User

POST /user/signup

POST /user/login

GET /user/{userId}

GET /user?locationName={locationName}&city={city}&state={state}

POST /user/{userId}/follow

DELETE /user/{userId}/follow

POST /user/logout

### Post

POST /post

GET /post/{postId}

GET /post?locationName={locationName}&city={city}&state={state}&hashtag={hashtag}

DELETE /post/{postId}

POST /post/{postId}/react

DELETE /post/{postId}/react

### Comment

POST /post/{postId}/comment

GET /post/{postId}/comment

GET /post/{postId}/comment/{commentId}

DELETE /post/{postId}/comment

POST /post/{postId}/comment/{commentId}/react

DELETE /post/{postId}/comment/{commentId}/react

### Market

GET /market

POST /market/buy/{itemId}

GET /market/inventory/{userId}
