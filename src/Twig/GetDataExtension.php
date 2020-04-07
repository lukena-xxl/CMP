<?php

namespace App\Twig;

use App\Entity\Filter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class GetDataExtension extends AbstractExtension
{
    private $entityManager;
    private $requestStack;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
    }

    public function getFilters(): array
    {
        return [];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('findFilterProducts', [$this, 'findFilterProducts']),
        ];
    }

    public function findFilterProducts($filter_id)
    {
        return $this->entityManager->getRepository(Filter::class)->findFilterProducts($filter_id);
    }
}
