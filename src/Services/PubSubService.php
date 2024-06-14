<?php

namespace App\Services;

use Google\Cloud\PubSub\MessageBuilder;
use Google\Cloud\PubSub\PubSubClient;

class PubSubService
{
    public const PROJECT_ID = 'asobaleu';
    public const VIDEOS_DATA = 'videos-data';

    public function publish(string $data): array
    {
        $pubSub = new PubSubClient([
            'projectId' => self::PROJECT_ID,
        ]);

        $topic = $pubSub->topic(self::VIDEOS_DATA);

        return $topic->publish(
           (new MessageBuilder)
               ->setData($data)
               ->build()
        );
    }
}