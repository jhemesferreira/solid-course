<?php

namespace App\Controller;

use App\Entity\BigFootSighting;
use App\Service\SightingScorer;
use App\Form\BigfootSightingType;
use Doctrine\ORM\EntityManagerInterface;
use App\Model\DebuggableBigFootSightingScore;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BigFootSightingController extends AbstractController
{
    /**
     * @Route("/sighting/upload", name="app_sighting_upload")
     * @IsGranted("ROLE_USER")
     */
    public function upload(Request $request, SightingScorer $sightingScorer, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(BigFootSightingType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var BigFootSighting $sighting */
            $sighting = $form->getData();
            $sighting->setOwner($this->getUser());

            $bfsScore = $sightingScorer->score($sighting);
            $sighting->setScore($bfsScore->getScore());

            $entityManager->persist($sighting);
            $entityManager->flush();

            $this->addFlash('success', 'New BigFoot Sighting created successfully!');

            if ($bfsScore instanceof DebuggableBigFootSightingScore) {
                $this->addFlash('success', sprintf(
                    'Btw, the scoring took %f milliseconds',
                    $bfsScore->getCalculationTime() * 1000
                ));
            }

            return $this->redirectToRoute('app_sighting_show', [
                'id' => $sighting->getId()
            ]);
        }

        return $this->render('main/sighting_new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/sighting/{id}", name="app_sighting_show")
     */
    public function showSighting(BigFootSighting $bigFootSighting)
    {
        return $this->render('main/sighting_show.html.twig', [
            'sighting' => $bigFootSighting
        ]);
    }
}
