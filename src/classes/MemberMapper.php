<?php

namespace Mapper;

use PDO;
use PDOException;
use Mapper\Mapper;

class MemberMapper  extends Mapper {

    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getMembers()
    {
        $sql = "select * FROM member";
        try {
            $stmt = $this->db->query($sql);
            $members = $stmt->fetchAll(\PDO::FETCH_OBJ);
            return json_encode($members , JSON_UNESCAPED_UNICODE);
        } catch (\PDOException $e) {
            return $this->Json_Error( $e->getMessage());
        }
    }

    public function getMember($request)
    {
        $id = $request->getAttribute('id');
        if (empty($id)) {
            return $this->Json_Error( "Id is empty");
        }
        try {
            $stmt =  $this->db->prepare("SELECT * FROM member WHERE member_id=:id");
            $stmt->bindParam("id", $id);
            $stmt->execute();
            $member = $stmt->fetchObject();
            return json_encode($member, JSON_UNESCAPED_UNICODE);
        } catch (\PDOException $e) {
            return $this->Json_Error( $e->getMessage());
        }
    }


    public function addMember($request)
    {
        $member = $request->getParsedBody();   // getParsedBody allow to use application/json AND x-www-form-urlencoded


        $sql = "INSERT INTO member (first_name, last_name, birthdate) VALUES (:first_name, :last_name, :birthdate)";
        try {

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("first_name", $member["first_name"]);
            $stmt->bindParam("last_name", $member["last_name"]);
            $stmt->bindParam("birthdate", $member["birthdate"]);
            $stmt->execute();
            $member["member_id"] = $this->db->lastInsertId();
            return json_encode($member, JSON_UNESCAPED_UNICODE);
        } catch (\PDOException $e) {
            return $this->Json_Error( $e->getMessage());
        }
    }


    public function updateMember($request)
    {

        $member = $request->getParsedBody();  // getParsedBody allow to use application/json AND x-www-form-urlencoded
        $id = $request->getAttribute('id');
        $sql = "UPDATE member SET first_name=:first_name, last_name=:last_name, birthdate=:birthdate WHERE member_id=:member_id";
        try {

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("last_name", $member["last_name"]);
            $stmt->bindParam("first_name", $member["first_name"]);
            $stmt->bindParam("birthdate", $member["birthdate"]);
            $stmt->bindParam("member_id", $id);
            $stmt->execute();
            $db = null;
            $member["member_id"] = $id;
            return json_encode($member,JSON_UNESCAPED_UNICODE);
        } catch (\PDOException $e) {
            return $this->Json_Error( $e->getMessage());
        }
    }

    public function deleteMember($request)
    {
        $id = $request->getAttribute('id');
        $sql = "DELETE FROM member WHERE member_id=$id";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("id", $id);
            $stmt->execute();
            return  $this->Json_Ok("Successfully deleted record");

        } catch (\PDOException $e) {
            return $this->Json_Error( $e->getMessage());
        }
    }

    public function getMemberGames ($request)
    {
        $member_id = $request->getAttribute('id');
        if (empty($member_id)) {
            return $this->Json_Error("Id is empty");
        }
        try {
            $stmt =  $this->db->prepare("select * from game where game_id in( select game_id FROM member_has_game WHERE member_id=:id)");
            $stmt->bindParam("id", $member_id);
            $stmt->execute();
            $games = $stmt->fetchAll(PDO::FETCH_OBJ);
            return json_encode($games, JSON_UNESCAPED_UNICODE);
        } catch (\PDOException $e) {
            return $this->Json_Error( $e->getMessage());
        }
    }

    public function addMemberGame($request)
    {

        $member_id = $request->getAttribute('member_id');
        $game_id =   $request->getAttribute('game_id');
        if (empty($member_id) or empty($game_id)) {
            return $this->Json_Error("Some ids are empty");
        }

        try {
            $stmt = $this->db->prepare("INSERT INTO member_has_game (member_id, game_id) VALUES (:member_id, :game_id)");
            $stmt->bindParam("member_id",$member_id);
            $stmt->bindParam("game_id",$game_id);
            $stmt->execute();
            return  $this->Json_Ok('member_id:' . $member_id . ' game_id:' . $game_id . ' added');
        } catch (\PDOException $e) {
            return $this->Json_Error( $e->getMessage());
        }

    }

    public function deleteMemberGame($request)
    {
        $member_id = $request->getAttribute('member_id');
        $game_id =   $request->getAttribute('game_id');
        try {
            $stmt = $this->db->prepare("DELETE FROM member_has_game where member_id = :member_id and game_id = :game_id");
            $stmt->bindParam("member_id", $member_id);
            $stmt->bindParam("game_id", $game_id);
            $stmt->execute();
            return $this->Json_Ok('member_id:' . $member_id . ' game_id:' . $game_id . ' deleted');
        } catch (\PDOException $e){
            return $this->Json_Error( $e->getMessage());
        }
    }


}