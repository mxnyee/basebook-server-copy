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
foreach (glob('../routers/*.php') as $filename) { require_once $filename; }

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
  $group->post('/signup', \UserRouter::class . ':signup');
  $group->post('/login', \UserRouter::class . ':login');
  $group->get('/{username}', \UserRouter::class . ':getUser');
  $group->patch('/{username}', \UserRouter::class . ':updateUser');
  $group->get('/{username}/inventory', \UserRouter::class . ':getUserInventory');
  $group->get('/{username}/stats', \UserRouter::class . ':getUserStats');
  $group->get('/{username}/leaderboard', \UserRouter::class . ':getUserLeaderboard');
});

// Post route
$app->group('/post', function (RouteCollectorProxy $group) {
  // Posts
  $group->post('', \PostRouter::class . ':createPost');
  $group->get('', \PostRouter::class . ':getFilteredPosts');
  $group->get('/search', \PostRouter::class . ':searchPosts');
  $group->post('/{postId}/reaction', \PostRouter::class . ':addPostReaction');
  $group->delete('/{postId}/reaction/{username}', \PostRouter::class . ':removePostReaction');
  // Comments
  $group->post('/{postId}/comment', \CommentRouter::class . ':createComment');
  $group->get('/{postId}/comment', \CommentRouter::class . ':getFilteredComments');
  $group->post('/{postId}/comment/{commentId}/reaction', \CommentRouter::class . ':addCommentReaction');
  $group->delete('/{postId}/comment/{commentId}/reaction/{username}', \CommentRouter::class . ':removeCommentReaction');
});

// Market route
$app->group('/market', function (RouteCollectorProxy $group) {
  $group->get('', \MarketRouter::class . ':getAllItems');
  $group->post('/purchase', \MarketRouter::class . ':purchaseItem');
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