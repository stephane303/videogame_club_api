<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use \Mapper\MemberMapper;
use \Mapper\GameMapper;

return function (App $app) {
    $container = $app->getContainer();

    $app->group('/api', function () use ($app,$container) {
        // Version group
        $app->group('/v1', function () use ($app, $container) {

            // Members
            $app->get('/members', function (Request $request, Response $response, array $args) use ($container) {
                $memberMapper = new MemberMapper($container->db);
                return $memberMapper->getMembers();
            });
            $app->get('/member/{id}', function (Request $request, Response $response, array $args) use ($container) {
                $memberMapper = new MemberMapper($container->db);
                return $memberMapper->getMember($request);
            });
            $app->post('/member', function (Request $request, Response $response, array $args) use ($container) {
                $memberMapper = new MemberMapper($container->db);
                return $memberMapper->addMember($request);
            });
            $app->put('/member/{id}', function (Request $request, Response $response, array $args) use ($container) {
                $memberMapper = new MemberMapper($container->db);
                return $memberMapper->updateMember($request);
            });
            $app->delete('/member/{id}', function (Request $request, Response $response, array $args) use ($container) {
                $memberMapper = new MemberMapper($container->db);
                return $memberMapper->deleteMember($request);
            });
            $app->get( '/member/{id}/games', function (Request $request, Response $response, array $args) use ($container) {
                $memberMapper = new MemberMapper($container->db);
                return $memberMapper->getMemberGames($request);
            });
            $app->post( '/member/{member_id}/game/{game_id}',function (Request $request, Response $response, array $args) use ($container) {
                $memberMapper = new MemberMapper($container->db);
                return $memberMapper->addMemberGame($request);
            });
            $app->delete( '/member/{member_id}/game/{game_id}', function (Request $request, Response $response, array $args) use ($container) {
                $memberMapper = new MemberMapper($container->db);
                return $memberMapper->deleteMemberGame($request);
            });



            //Game
            $app->get('/games', function (Request $request, Response $response, array $args) use ($container) {
                $gameMapper = new GameMapper($container->db);
                return $gameMapper->getGames();
            });

            $app->post('/game',function (Request $request, Response $response, array $args) use ($container) {
                $gameMapper = new GameMapper($container->db);
                return $gameMapper->addGame($request);
            });


        });
    });

    $app->get('/[{name}]', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Slim-Skeleton '/' route");

        // Render index view
        return $container->get('renderer')->render($response, 'index.phtml', $args);
    });
};
