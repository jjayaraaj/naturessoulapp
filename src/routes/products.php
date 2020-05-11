<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});


$app->get('/api/products/featured', function(Request $request, Response $response){
    $sql = "SELECT id, title, price, stock, measurement, measurement_value, cover_img FROM product WHERE featured =  1 AND status =1 ORDER BY RAND() LIMIT 8";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $featured = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        //echo json_encode($customers);
        return $response->withJson([
            'message' => "successfully Fetched",
            'featured' => $featured
             ]);
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});