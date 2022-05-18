<?php

namespace App\Tests;

use App\Entity\Campaign;
use App\Entity\Participant;
use App\Entity\Payment;
use PHPUnit\Framework\TestCase;

class ParticipantTest extends TestCase
{
    public function testGetParticipation(): void
    {
        // ARRANGE
        $campaign = new Campaign();
        $campaign->setEmail('test@gmail.com');

        $participant = new Participant();
        $participant->setCampaign($campaign);

        $payment1 = new Payment();
        $payment1->setAmount(10);

        $payment2 = new Payment();
        $payment2->setAmount(5);

        $participant->addPayment($payment1);
        $participant->addPayment($payment2);

        // ACT
        $result = $participant->getParticipation();

        // ASSERT
        $this->assertEquals(15, $result);
    }
}
