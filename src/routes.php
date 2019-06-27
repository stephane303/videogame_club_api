<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {
    $container = $app->getContainer();

    $app->group('/api', function () use ($app,$container) {
        // Version group
        $app->group('/v1', function () use ($app, $container) {

            // Members
            $app->get('/members', 'getMembers');
            $app->get('/member/{id}', 'getMember');
            $app->post('/member', 'addMember');
            $app->put('/member/{id}', 'updateMember');
            $app->delete('/member/{id}', 'deleteMember');

            //Game
            $app->get('/games', 'getGames');
            $app->get( '/member/{id}/games', 'getMemberGames');
            $app->post( '/member/{member_id}/game/{game_id}','addMemberGame');
            $app->post('/game','addGame');
            $app->delete( '/member/{member_id}/game/{game_id}', 'deleteMemberGame');

        });
    });

    $app->get('/[{name}]', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Slim-Skeleton '/' route");

        // Render index view
        return $container->get('renderer')->render($response, 'index.phtml', $args);
    });
};
