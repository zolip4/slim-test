<?php

declare(strict_types=1);

namespace App\Application\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class SessionMiddleware implements Middleware
{
    public const SECRET_KEY = 'your_secret_key';

    /**
     * {@inheritdoc}
     */
    public function process(Request $request, RequestHandler $handler): Response
    {
        $timestamp = $request->getHeaderLine('Timestamp');
        $checksum = $request->getHeaderLine('Checksum');

        if (!$timestamp || !$checksum) {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode(['error' => 'Missing headers']));

            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        /*if ($apiKey !== API_KEY) {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode(['error' => 'Invalid API Key']));

            return $response->withHeader('Content-Type', 'application/json')->withStatus(403);
        }*/

        $timeDifference = abs(time() - $timestamp);
        if ($timeDifference > 300) {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode(['error' => 'Request expired']));

            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $expectedChecksum = hash_hmac('sha256', $timestamp . self::SECRET_KEY, self::SECRET_KEY);
        if ($checksum !== $expectedChecksum) {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode(['error' => 'Invalid checksum']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(403);
        }

        return $handler->handle($request);
    }
}
