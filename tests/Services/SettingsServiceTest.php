<?php

namespace Tests\Services;

use App\Services\SettingsService;
use Tests\TestCase;

class SettingsServiceTest extends TestCase
{
    public function testGetModelsListsAllModels()
    {
        $expected = [
            'chats',
            'comments',
            'designs',
            'invitations',
            'likes',
            'messages',
            'posts',
            'reactions',
            'teams',
            'trades',
            'likeables',
            'users'
        ];

        $settings = new SettingsService();
        $result = $settings->getModels();

        self::assertEquals($result, $expected);

    }
}
