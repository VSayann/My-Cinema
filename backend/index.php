<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json; charset=utf-8');

spl_autoload_register(function ($class) {
    $directories = [
        __DIR__ . '/config/',
        __DIR__ . '/models/',
        __DIR__ . '/repositories/',
        __DIR__ . '/controllers/'
    ];
    
    foreach ($directories as $dir) {
        $file = $dir . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = str_replace('/backend', '', $path);
$path = trim($path, '/');

$segments = explode('/', $path);
$resource = $segments[0] ?? '';
$id = $segments[1] ?? null;
$action = $segments[2] ?? null;

switch ($resource) {
    case 'movies':
        $controller = new MovieController();
        handleRequest($controller, $method, $id, $action);
        break;
            
    case 'rooms':
        $controller = new RoomController();
        handleRequest($controller, $method, $id, $action);
        break;
            
    case 'screenings':
        $controller = new ScreeningController();
        handleRequest($controller, $method, $id, $action);
        break;
            
    default:
        break;
    }

function handleRequest($controller, $method, $id, $action) {
    switch ($method) {
        case 'GET':
            if ($id) {
                if ($action) {
                    $methodName = 'getBy' . ucfirst($action);
                    if (method_exists($controller, $methodName)) {
                        $controller->$methodName($id);
                    } 
                } else {
                    $controller->get($id);
                }
            } else {
                if (isset($_GET['q'])) {
                    $controller->search();
                } elseif (isset($_GET['date'])) {
                    $controller->getByDate($_GET['date']);
                } elseif (isset($_GET['movie'])) {
                    $controller->getByMovie($_GET['movie']);
                } else {
                    $controller->list();
                }
            }
            break;
            
        case 'POST':
            $controller->create();
            break;
            
        case 'PUT':
            if ($id) {
                $controller->update($id);
                break;
            }
            
        case 'DELETE':
            if ($id) {
                $controller->delete($id);
                break;
            }
            
        default:
            break;
    }
}
