<?php

namespace Mapper;

use PDO;
use PDOException;
use Mapper\Mapper;

class GameMapper extends Mapper{

    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }



    function getGames()
    {
        $sql = "select * FROM game";
        try {
            $stmt = $this->db->query($sql);
            $games = $stmt->fetchAll(PDO::FETCH_OBJ);
            return json_encode($games,JSON_UNESCAPED_UNICODE);
        } catch (\PDOException $e) {
            return $this->Json_Error( $e->getMessage());
        }
    }

    function addGame($request)
    {
        $game = $request->getParsedBody();
        $sql = "INSERT INTO game (name, release_year) VALUES (:name, :release_year)";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("name", $game["name"]);
            $stmt->bindParam("release_year", $game["release_year"]);
            $stmt->execute();
            $game["game_id"] = $this->db->lastInsertId();
            return json_encode($game,JSON_UNESCAPED_UNICODE);
        } catch (\PDOException $e) {
            return $this->Json_Error( $e->getMessage());
        }
    }

}