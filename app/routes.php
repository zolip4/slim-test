<?php

declare(strict_types=1);

use App\Application\Actions\Title\OptimizeTitlesAction;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->group('/optimize_titles', function (Group $group) {
        $group->post('', OptimizeTitlesAction::class);
    });
};
