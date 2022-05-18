<?php

namespace App\Tests\Panther;

use Symfony\Component\Panther\PantherTestCase;

class Test extends PantherTestCase
{
    public function testSomething(): void
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/');

        $client->clickLink('Créer une campagne');

        $this->assertSelectorIsVisible('#campaign-form');
    }
}
