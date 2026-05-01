<?php

namespace App\Controller;

use App\Repository\MesureConformiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/conformite')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class ConformiteController extends AbstractController
{
    public function __construct(
        private readonly MesureConformiteRepository $mesureRepository,
    ) {}

    #[Route('/score', name: 'app_conformite_score', methods: ['GET'])]
    public function score(): JsonResponse
    {
        $counts = $this->mesureRepository->countByStatut();

        $stats = ['conforme' => 0, 'partiel' => 0, 'non_conforme' => 0, 'na' => 0];
        $total = 0;

        foreach ($counts as $row) {
            $stats[$row['statut']] = (int) $row['total'];
            $total += (int) $row['total'];
        }

        $applicable = $total - $stats['na'];
        $score = $applicable > 0
            ? round((($stats['conforme'] + $stats['partiel'] * 0.5) / $applicable) * 100)
            : 0;

        return $this->json([
            'global' => $score,
            'totalMesures' => $total,
            'conformes' => $stats['conforme'],
            'partiels' => $stats['partiel'],
            'nonConformes' => $stats['non_conforme'],
            'na' => $stats['na'],
        ]);
    }
}
