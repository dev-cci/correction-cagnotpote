<?php

namespace App\Tests\E2E;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ViewCampaignTest extends WebTestCase
{
    public function testCampaignView(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/campaign/1ecd68ff-ebab-6d8e-ac92-7565b044ca5b');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Campito');
    }
}
