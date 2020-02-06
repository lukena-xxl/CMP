<?php


namespace App\Services\Common;

use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Translatable\Entity\Translation;
use Symfony\Component\HttpFoundation\RequestStack;

class TranslationRecipient
{
    private $requestStack;
    private $entityManager;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager)
    {
        $this->requestStack = $requestStack;
        $this->entityManager = $entityManager;
    }

    public function getTranslatedEntity($entity, $localeSetter = 'setTranslatableLocale')
    {
        $request = $this->requestStack->getCurrentRequest();
        $locale = $request->getLocale();

        if ($request->getDefaultLocale() !== $locale) {
            $entity->$localeSetter($locale);
            $this->entityManager->refresh($entity);
        }

        return $entity;
    }

    public function getTranslation($entity, $locale = null, $property = null)
    {
        if (!$entity || empty($entity)) {
            return '';
        }

        $repository = $this->entityManager->getRepository(Translation::class);
        $translation = $repository->findTranslations($entity);

        if (!is_null($locale) && array_key_exists($locale, $translation)) {
            if (!is_null($property) && array_key_exists($property, $translation[$locale])) {
                return $translation[$locale][$property];
            }

            return $translation[$locale];
        }

        return $translation;
    }

    public function checkAndGetTranslation($entity, $locale = null, $property = null)
    {
        $request = $this->requestStack->getCurrentRequest();
        $current_locale = $request->getLocale();

        if ($request->getDefaultLocale() !== $current_locale) {
            return $this->getTranslation($entity, $locale, $property);
        }

        return false;
    }
}
