<?php

namespace App\Twig;

use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CommonExtension extends AbstractExtension
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('isRoutPath', [$this, 'checkRoutPath']),
        ];
    }

    public function checkRoutPath($string)
    {
        $request = $this->requestStack->getCurrentRequest();
        $attributes = $request->attributes->all();

        if (strpos($attributes['_route'], $string) !== false) {
            return true;
        }

        return false;
    }
}
