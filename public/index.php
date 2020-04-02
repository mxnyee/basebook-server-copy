<?php
use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

require_once __DIR__ . '/../vendor/autoload.php';
require_once '../database/connect.php';
require_once '../schemas/validator.php';
// Import all controllers
foreach (glob('../controllers/*.php') as $filename) {
  require_once $filename;
}

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Global database connection and validator object
$conn = openConnection();
$validator = createValidator();

$container = new Container();
$container->set('conn', $conn);
$container->set('validator', $validator);

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
  $group->post('/signup', '\UserController:signup');
  $group->post('/login', '\UserController:login');
  $group->get('/{username}', '\'UserController:getUser');
  $group->patch('/{username}', '\UserController:editUser');
  $group->get('/{username}/inventory', '\UserController:getUserInventory');
  $group->get('/{username}/stats', '\UserController:getUserStats');
  $group->get('/{username}/leaderboard', '\UserController:getUserLeaderboard');
});

// Post route
$app->group('/post', function (RouteCollectorProxy $group) {
  // Posts
  $group->post('', '\PostController:createPost');
  $group->get('', '\PostController:getAllPosts');
  $group->get('/search', '\PostController:searchPosts');
  $group->post('/{postId}/reaction', '\PostController:addPostReaction');
  $group->delete('/{postId}/reaction/{username}', '\PostController:removePostReaction');
  // Comments
  $group->post('/{postId}/comment', '\CommentController:createComment');
  $group->get('/{postId}/comment', '\CommentController:getAllCommentsOnPost');
  $group->post('/{postId}/comment/{commentId}/reaction', '\CommentController:addCommentReaction');
  $group->delete('/{postId}/comment/{commentId}/reaction/{username}', '\CommentController:removeCommentReaction');
});

// Market route
$app->group('/market', function (RouteCollectorProxy $group) {
  $group->get('', '\MarketController:getAllItems');
  $group->post('/purchase', '\MarketController:purchaseItem');
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