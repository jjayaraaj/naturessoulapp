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


$app->get('/api/products/top', function(Request $request, Response $response){
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


$app->get('/api/products/related/{id}', function(Request $request, Response $response){
    $categoryId = $request->getAttribute('id');
    $sql = "SELECT id, title, price, stock, measurement, measurement_value, cover_img FROM  product WHERE category= :categoryId AND status = 1  ORDER BY RAND() LIMIT 5";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);
			$stmt->bindParam(":categoryId", $categoryId);
			$stmt->execute();
        $related = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        //echo json_encode($customers);
        return $response->withJson([
            'message' => "successfully Fetched",
            'featured' => $related
             ]);
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

$app->get('/api/category/products/{id}', function(Request $request, Response $response){
    $categoryId = $request->getAttribute('id');
    $sql = "SELECT * FROM  product WHERE category= :categoryId AND status = 1";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);
			$stmt->bindParam(":categoryId", $categoryId);
			$stmt->execute();
        $related = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        //echo json_encode($customers);
        return $response->withJson([
            'message' => "successfully Fetched",
            'featured' => $related
             ]);
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

$app->get('/api/category', function(Request $request, Response $response){
    $sql = "SELECT 	id, category FROM  category WHERE status = 1";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $category = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        //echo json_encode($customers);
        return $response->withJson([
            'message' => "successfully Fetched",
            'featured' => $category
             ]);
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});


$app->get('/api/category/sub/{id}', function(Request $request, Response $response){
    $categoryId = $request->getAttribute('id');

    $sql = "SELECT id, category, subcategory FROM subcategory WHERE category = :categoryId";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);
        $stmt->bindParam(":categoryId", $categoryId);
			$stmt->execute();
        $subCategory = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        //echo json_encode($customers);
        return $response->withJson([
            'message' => "successfully Fetched",
            'featured' => $subCategory
             ]);
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});