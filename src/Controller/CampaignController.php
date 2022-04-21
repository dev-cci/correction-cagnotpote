<?php

namespace App\Controller;

use App\Entity\Campaign;
use App\Entity\Participant;
use App\Entity\Payment;
use App\Form\CampaignType;
use App\Form\PaymentType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/campaign')]
class CampaignController extends AbstractController
{
    #[Route('/', name: 'app_campaign_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $campaigns = $entityManager
            ->getRepository(Campaign::class)
            ->findAll();

        return $this->render('campaign/index.html.twig', [
            'campaigns' => $campaigns,
        ]);
    }

    #[Route('/new', name: 'app_campaign_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $campaign = new Campaign();
        $form = $this->createForm(CampaignType::class, $campaign);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($campaign);
            $entityManager->flush();

            return $this->redirectToRoute('app_campaign_show', ['id' => $campaign->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('campaign/new.html.twig', [
            'campaign' => $campaign,
            'form' => $form,
            'name' => $request->request->get('name')
        ]);
    }

    #[Route('/{id}', name: 'app_campaign_show', methods: ['GET'])]
    public function show(Campaign $campaign, ManagerRegistry $doctrine): Response
    {
        // ON RECUPERE ICI L'ARGENT RECOLTE EN ALLANT CHERCHER LES PAIEMENTS DIRECTEMENT GRACE A DOCTRINE
        // Récupérer tous les paiements de chaque participant de la campagne, et l'ajouter dans $payments
        $payments = [];
        foreach ($campaign->getParticipants() as $participant) {
            $participantPayments = $doctrine->getRepository(Payment::class)->findBy(
                ['participant' => $participant]
            );

            array_push($payments, ...$participantPayments);
        }

        // Calculer la somme de tous les paiements
        $sum = array_sum(array_map(function($payment) {
            return $payment->getAmount();
        }, $payments));

        return $this->render('campaign/show.html.twig', [
            'campaign' => $campaign,
            'sum' => $sum,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_campaign_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Campaign $campaign, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CampaignType::class, $campaign);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_campaign_show', ['id' => $campaign->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('campaign/edit.html.twig', [
            'campaign' => $campaign,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_campaign_delete', methods: ['POST'])]
    public function delete(Request $request, Campaign $campaign, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$campaign->getId(), $request->request->get('_token'))) {
            $entityManager->remove($campaign);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_campaign_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/payment', name: 'app_campaign_payment', methods: ['GET', 'POST'])]
    public function payment(Campaign $campaign, Request $request, EntityManagerInterface $entityManager, ManagerRegistry $doctrine): Response
    {
        $payment = new Payment();
        $form = $this->createForm(PaymentType::class, $payment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $participant = new Participant();
            $participant->setEmail($form->get("email")->getData());
            $participant->setCampaign($campaign);

            $savedParticipant = $doctrine->getRepository(Participant::class)->findOneBy(['email' => $participant->getEmail()]);

            if (!$savedParticipant) {
                $entityManager->persist($participant);
                $savedParticipant = $participant;
            }

            $savedParticipant->setHidden($form->get("hidden_participant")->getData());
            $entityManager->persist($savedParticipant);

            $payment->setParticipant($savedParticipant);
            $entityManager->persist($payment);
            $entityManager->flush();


            return $this->redirectToRoute('app_campaign_show', ['id' => $campaign->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('campaign/payment.html.twig', [
            'payment' => $payment,
            'campaign' => $campaign,
            'form' => $form,
            'amount' => $request->request->get('amount'),
        ]);
    }
    //pas compris
}
