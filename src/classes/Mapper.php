<?php
namespace Mapper;
/**
 * Class Mapper
 * @package Mapper
 *
 * This super-class make json messages uniform
 *
 */
class Mapper {
    protected function Json_Error ($msg){
        return '{"Error":{"text":"' . $msg . '"}}';
    }

    protected function Json_Ok ($msg){
        return '{"Ok":{"text":"' . $msg . '"}}';
    }
}