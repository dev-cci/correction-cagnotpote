<?php

namespace App\Tests;

use App\Entity\Campaign;
use App\Entity\Participant;
use App\Entity\Payment;
use PHPUnit\Framework\TestCase;

class CampaignTest extends TestCase
{
    public function testGetParticipation(): void
    {
        // ARRANGE
        $campaign = new Campaign();
        $campaign->setEmail('test@gmail.com');

        $participant1 = new Participant();

        $participant2 = new Participant();

        $payment1 = new Payment();
        $payment1->setAmount(10);

        $payment2 = new Payment();
        $payment2->setAmount(5);

        $payment3 = new Payment();
        $payment3->setAmount(3);

        $payment4 = new Payment();
        $payment4->setAmount(7);

        $participant1->addPayment($payment1);
        $participant1->addPayment($payment2);

        $participant2->addPayment($payment3);
        $participant2->addPayment($payment4);

        $campaign->addParticipant($participant1);
        $campaign->addParticipant($participant2);

        // ACT
        $result = $campaign->getRecoltedAmount();

        // ASSERT
        $this->assertEquals(25, $result);
    }

    public function testWithStub(): void
    {
        // ARRANGE
        $campaign = new Campaign();
        $campaign->setEmail('test@gmail.com');

        $participant1 = $this->createStub(Participant::class);
        $participant2 = $this->createStub(Participant::class);

        $campaign->addParticipant($participant1);
        $campaign->addParticipant($participant2);

        $participant1->method('getParticipation')
                     ->willReturn(15);

        $participant2->method('getParticipation')
                     ->willReturn(10);

        // ACT
        $result = $campaign->getRecoltedAmount();

        // ASSERT
        $this->assertEquals(25, $result);
    }

    public function testMethodCallWithMock(): void
    {
        // ARRANGE
        $campaign = new Campaign();
        $campaign->setEmail('test@gmail.com');

        $participant1 = $this->createMock(Participant::class);
        $participant2 = $this->createMock(Participant::class);

        $campaign->addParticipant($participant1);
        $campaign->addParticipant($participant2);

        // ASSERT
        $participant1->expects($this->once())
                     ->method('getParticipation');

        $participant2->expects($this->once())
                     ->method('getParticipation');

        // ACT
        $campaign->getRecoltedAmount();
    }
}
