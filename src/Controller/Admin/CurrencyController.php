<?php

namespace App\Controller\Admin;

use App\Entity\Currency;
use App\Form\Admin\Currency\CurrencyType;
use App\Repository\CurrencyRepository;
use App\Services\Common\TranslationRecipient;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Translatable\Entity\Translation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class CurrencyController
 * @package App\Controller\Admin
 * @Route("/admin/currency", name="admin_currency")
 */
class CurrencyController extends AbstractController
{
    /**
     * @Route("", name="_all")
     * @param CurrencyRepository $currencyRepository
     * @param TranslationRecipient $translationRecipient
     * @return Response
     */
    public function currencyAll(CurrencyRepository $currencyRepository, TranslationRecipient $translationRecipient)
    {
        $currencies = [];
        $currenciesAll = $currencyRepository->findAll();
        foreach ($currenciesAll as $currency) {
            $currencies[] = $translationRecipient->getTranslatedEntity($currency);
        }

        return $this->render('admin/currency/all.html.twig', [
            'controller_name' => 'CurrencyController',
            'currencies' => $currencies,
        ]);
    }

    /**
     * @Route("/{id}", name="_single", requirements={"id"="\d+"})
     * @param Currency $currency
     * @param TranslationRecipient $translationRecipient
     * @return Response
     */
    public function currencySingle(Currency $currency, TranslationRecipient $translationRecipient)
    {
        $translation = $translationRecipient->getTranslation($currency);

        return $this->render('admin/currency/single.html.twig', [
            'controller_name' => 'CurrencyController',
            'currency' => $currency,
            'translation' => $translation,
        ]);
    }

    /**
     * @Route("/add", name="_add")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function currencyAdd(Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $form = $this->createForm(CurrencyType::class, null, [
            'action' => $this->generateUrl('admin_currency_add'),
            'method' => 'post',
            'attr' => [
                'id' => 'currency_form',
            ],
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $currency = $form->getData();

            $arrData = $request->request->get('currency');
            $repoTranslation = $entityManager->getRepository(Translation::class);
            $repoTranslation->translate($currency, 'name', 'uk', $arrData['translation_name'])
                ->translate($currency, 'short', 'uk', $arrData['translation_short']);

            $entityManager->persist($currency);
            $entityManager->flush();

            $message = $translator->trans('Валюта успешно добавлена');
            $this->addFlash('success', $message);

            return $this->redirectToRoute('admin_currency_all');
        }

        return $this->render('admin/currency/add.html.twig', [
            'controller_name' => 'CurrencyController',
            'form_add' => $form->createView(),
            'title' => $translator->trans('Добавление валюты'),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="_edit", requirements={"id"="\d+"})
     * @param Currency $currency
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function currencyEdit(Currency $currency, Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $form = $this->createForm(CurrencyType::class, $currency, [
            'action' => $this->generateUrl('admin_currency_edit', ['id' => $currency->getId()]),
            'method' => 'post',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $arrData = $request->request->get('currency');
            $repoTranslation = $entityManager->getRepository(Translation::class);
            $repoTranslation->translate($currency, 'name', 'uk', $arrData['translation_name'])
                ->translate($currency, 'short', 'uk', $arrData['translation_short']);

            $entityManager->persist($currency);
            $entityManager->flush();

            $message = $translator->trans('Валюта успешно изменена');
            $this->addFlash('success', $message);

            return $this->redirectToRoute('admin_currency_single', ['id' => $currency->getId()]);
        }

        return $this->render('admin/currency/add.html.twig', [
            'controller_name' => 'CurrencyController',
            'form_add' => $form->createView(),
            'title' => $translator->trans('Редактирование валюты'),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="_delete", requirements={"id"="\d+"})
     * @param Currency $currency
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function currencyDelete(Currency $currency, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $entityManager->remove($currency);
        $entityManager->flush();

        $message = $translator->trans('Валюта успешно удалена');

        $this->addFlash('success', $message);

        return $this->redirectToRoute('admin_currency_all');
    }
}
