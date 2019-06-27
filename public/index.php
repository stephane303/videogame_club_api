<?php
if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/../vendor/autoload.php';

session_start();

// Instantiate the app
$settings = require __DIR__ . '/../src/settings.php';
$app = new \Slim\App($settings);

// Set up dependencies
$dependencies = require __DIR__ . '/../src/dependencies.php';
$dependencies($app);

// Register middleware
$middleware = require __DIR__ . '/../src/middleware.php';
$middleware($app);

// Register routes
$routes = require __DIR__ . '/../src/routes.php';
$routes($app);

// Run app
$app->run();

function getMembers()
{
    $sql = "select * FROM member";
    try {
        $db = getConnection();
        $stmt = $db->query($sql);
        $members = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        return json_encode($members,JSON_UNESCAPED_UNICODE);
    } catch (PDOException $e) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}

function getMember($request)
{
    $id = $request->getAttribute('id');
    if (empty($id)) {
        echo '{"error":{"text":"Id is empty"}}';
    }
    try {
        $db = getConnection();
        $stmt = $db->prepare("SELECT * FROM member WHERE member_id=:id");
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $member = $stmt->fetchObject();
        return json_encode($member, JSON_UNESCAPED_UNICODE);
    } catch (PDOException $e) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}

function addMember($request)
{
    $member = $request->getParsedBody();


    $sql = "INSERT INTO member (first_name, last_name, birthdate) VALUES (:first_name, :last_name, :birthdate)";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("first_name", $member["first_name"]);
        $stmt->bindParam("last_name", $member["last_name"]);
        $stmt->bindParam("birthdate", $member["birthdate"]);
        $stmt->execute();
        $member["member_id"] = $db->lastInsertId();
        $db = null;
        echo json_encode($member, JSON_UNESCAPED_UNICODE);
    } catch (PDOException $e) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}

function updateMember($request)
{

    $member = $request->getParsedBody();
    $id = $request->getAttribute('id');
    $sql = "UPDATE member SET first_name=:first_name, last_name=:last_name, birthdate=:birthdate WHERE member_id=:member_id";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("last_name", $member["last_name"]);
        $stmt->bindParam("first_name", $member["first_name"]);
        $stmt->bindParam("birthdate", $member["birthdate"]);
        $stmt->bindParam("member_id", $id);
        $stmt->execute();
        $db = null;
        $member["member_id"] = $id;
        echo json_encode($member,JSON_UNESCAPED_UNICODE);
    } catch (PDOException $e) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}

function deleteMember($request)
{
    $id = $request->getAttribute('id');
    $sql = "DELETE FROM member WHERE member_id=$id";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $db = null;
        echo '{"error":{"text":"successfully! deleted Records"}}';
    } catch (PDOException $e) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}

function getMemberGames ($request)
{
    $member_id = $request->getAttribute('id');
    if (empty($member_id)) {
        echo '{"error":{"text":"Id is empty"}}';
    }
    try {
        $db = getConnection();
        $stmt = $db->prepare("select * from game where game_id in( select game_id FROM member_has_game WHERE member_id=:id)");
        $stmt->bindParam("id", $member_id);
        $stmt->execute();
        $games = $stmt->fetchAll(PDO::FETCH_OBJ);
        return json_encode($games, JSON_UNESCAPED_UNICODE);
    } catch (PDOException $e) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}

function addMemberGame($request)
{

    $member_id = $request->getAttribute('member_id');
    $game_id =   $request->getAttribute('game_id');
    if (empty($member_id) or empty($game_id)) {
        echo '{"error": {"text":"Some ids are empty"}}';
    }

    try {
        $db = getConnection();
        $stmt = $db->prepare("INSERT INTO member_has_game (member_id, game_id) VALUES (:member_id, :game_id)");
        $stmt->bindParam("member_id",$member_id);
        $stmt->bindParam("game_id",$game_id);
        $stmt->execute();
        $db = null;
        echo json_encode([$member_id,$game_id]);
    } catch (PDOException $e) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}

function deleteMemberGame($request)
{
    $member_id = $request->getAttribute('member_id');
    $game_id =   $request->getAttribute('game_id');
    $db = getConnection();
    $stmt = $db->prepare("DELETE FROM member_has_game where member_id = :member_id and game_id = :game_id");
    $stmt->bindParam("member_id",$member_id);
    $stmt->bindParam("game_id",$game_id);
    $stmt->execute();
    $db = null;
    return '{"Ok":"'.$member_id.' '.$game_id.' deleted"}';
}


function addGame($request)
{
    $game = $request->getParsedBody();
    $sql = "INSERT INTO game (name, release_year) VALUES (:name, :release_year)";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("name", $game["name"]);
        $stmt->bindParam("release_year", $game["release_year"]);
        $stmt->execute();
        $game["game_id"] = $db->lastInsertId();
        $db = null;
        echo json_encode($game,JSON_UNESCAPED_UNICODE);
    } catch (PDOException $e) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}

function getGames()
{
    $sql = "select * FROM game";
    try {
        $db = getConnection();
        $stmt = $db->query($sql);
        $games = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        return json_encode($games,JSON_UNESCAPED_UNICODE);
    } catch (PDOException $e) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}

function getConnection()
{
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "";
    $dbname = "club";
    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
}


