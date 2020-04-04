<?php

use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

require_once __DIR__ . '/../vendor/autoload.php';
require_once '../database/DatabaseConnection.php';
require_once '../validation/Validator.php';
foreach (glob('../controllers/*.php') as $filename) { require_once $filename; }

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$container = new Container();
AppFactory::setContainer($container);
$app = AppFactory::create();
$app->setBasePath(getenv('BASE_PATH'));
$app->addBodyParsingMiddleware();
$app->addErrorMiddleware(true, true, true);

// Root
$app->get('/', function (Request $request, Response $response) {
  $response->getBody()->write('Hello, World!');
  return $response;
});

// User route
$app->group('/user', function (RouteCollectorProxy $group) {
  $group->post('/signup', \UserController::class . ':signup');
  $group->post('/login', \UserController::class . ':login');
  $group->get('/{username}', \UserController::class . ':getUser');
  $group->patch('/{username}', \UserController::class . ':updateUser');
  $group->get('/{username}/inventory', \UserController::class . ':getUserInventory');
  $group->get('/{username}/stats', \UserController::class . ':getUserStats');
  $group->get('/{username}/leaderboard', \UserController::class . ':getUserLeaderboard');
});

// Post route
$app->group('/post', function (RouteCollectorProxy $group) {
  // Posts
  $group->post('', \PostController::class . ':createPost');
  $group->get('', \PostController::class . ':getFilteredPosts');
  $group->get('/search', \PostController::class . ':searchPosts');
  $group->post('/{postId}/reaction', \PostController::class . ':addPostReaction');
  $group->delete('/{postId}/reaction/{username}', \PostController::class . ':removePostReaction');
  // Comments
  $group->post('/{postId}/comment', \CommentController::class . ':createComment');
  $group->get('/{postId}/comment', \CommentController::class . ':getFilteredComments');
  $group->post('/{postId}/comment/{commentId}/reaction', \CommentController::class . ':addCommentReaction');
  $group->delete('/{postId}/comment/{commentId}/reaction/{username}', \CommentController::class . ':removeCommentReaction');
});

// Market route
$app->group('/market', function (RouteCollectorProxy $group) {
  $group->get('', \MarketController::class . ':getAllItems');
  $group->post('/purchase', \MarketController::class . ':purchaseItem');
});

// For testing!!
$app->post('/test', function (Request $request, Response $response) {
  $data = $request->getParsedBody();
  $response->getBody()->write('POST /test ' . PHP_EOL . 'Request received:' . PHP_EOL . var_export($data, true));
  return $response;
});
$app->get('/test', function (Request $request, Response $response) {
  $response->getBody()->write('GET /test ' . PHP_EOL . 'Request received!');
  return $response;
});
$app->put('/test', function (Request $request, Response $response) {
  $data = $request->getParsedBody();
  $response->getBody()->write('PUT /test ' . PHP_EOL . 'Request received:' . PHP_EOL . var_export($data, true));
  return $response;
});
$app->patch('/test', function (Request $request, Response $response) {
  $data = $request->getParsedBody();
  $response->getBody()->write('PATCH /test ' . PHP_EOL . 'Request received:' . PHP_EOL . var_export($data, true));
  return $response;
});
$app->delete('/test', function (Request $request, Response $response) {
  $response->getBody()->write('DELETE /test ' . PHP_EOL . 'Request received!');
  return $response;
});

$app->run();