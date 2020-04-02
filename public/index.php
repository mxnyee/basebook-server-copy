<?php
use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

require_once __DIR__ . '/../vendor/autoload.php';
require_once '../database/connect.php';
// Import all controllers
foreach (glob('../controllers/*.php') as $filename) {
  require_once $filename;
}

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$conn = openConnection();

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

$app->run();