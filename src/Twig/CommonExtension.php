<?php

namespace App\Twig;

use App\Services\Common\TranslationRecipient;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class CommonExtension extends AbstractExtension
{
    private $requestStack;
    private $translationRecipient;

    public function __construct(RequestStack $requestStack, TranslationRecipient $translationRecipient)
    {
        $this->requestStack = $requestStack;
        $this->translationRecipient = $translationRecipient;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('mbCaseTitleSimple', [$this, 'mbCaseTitleSimple']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('isRoutPath', [$this, 'checkRoutPath']),
            new TwigFunction('translate', [$this, 'translate']),
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

    public function mbCaseTitleSimple($string)
    {
        return mb_convert_case($string, MB_CASE_TITLE_SIMPLE, "UTF-8");
    }

    public function translate($entity, $locale = null, $property = null)
    {
        return $this->translationRecipient->getTranslation($entity, $locale, $property);
    }
}
