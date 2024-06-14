<?php

declare(strict_types=1);

namespace App\Application\Actions\Title;

use Psr\Http\Message\ResponseInterface as Response;
use App\Application\Actions\Action;
use Psr\Log\LoggerInterface;
use App\Services\PubSubService;

class OptimizeTitlesAction extends Action
{
    /**
     * @param PubSubService $pubSubService
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly PubSubService $pubSubService,
        LoggerInterface $logger
    ) {
        parent::__construct($logger);
    }

    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $data = $this->getFormData();
        $this->pubSubService->publish($data);

        return $this->respondWithData('Data published successfully');
    }
}
