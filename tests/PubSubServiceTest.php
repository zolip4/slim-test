<?php

use App\Services\PubSubService;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface as ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Response;

class PubSubServiceTest extends TestCase
{
    protected PubSubService $pubSubServiceMock;
    protected $app;

    protected function setUp(): void
    {
        parent::setUp();

        $this->pubSubServiceMock = $this->createMock(PubSubService::class);
        $this->pubSubServiceMock->expects($this->once())
            ->method('publish')
            ->with($this->equalTo('{"videos":[{"title":"Sample Title"}]}'));

        $this->app = AppFactory::create();
    }

    public function testOptimizeTitle()
    {
        $this->app->post('/optimize_titles', function (Request $request, ResponseInterface $response) {
            $data = $request->getBody()->getContents();
            $this->pubSubServiceMock->publish($data);
            return $response->withHeader('Content-Type', 'application/json');
        });

        $streamFactory = new StreamFactory();
        $body = $streamFactory->createStream(json_encode(['videos' => [['title' => 'Sample Title']]]));
        $request = (new ServerRequestFactory())->createServerRequest('POST', '/optimize_titles')
            ->withBody($body)
            ->withHeader('Content-Type', 'application/json');

        $response = new Response();

        $response = $this->app->handle($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
    }
}
