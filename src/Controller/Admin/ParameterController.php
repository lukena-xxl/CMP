<?php

namespace App\Controller\Admin;

use App\Entity\Parameter;
use App\Form\Admin\Parameter\ParameterType;
use App\Repository\ParameterRepository;
use App\Services\Common\TranslationRecipient;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Translatable\Entity\Translation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ParameterController
 * @package App\Controller\Admin
 * @Route("/admin/parameter", name="admin_parameter")
 */
class ParameterController extends AbstractController
{
    /**
     * @Route("", name="_all")
     * @param ParameterRepository $parameterRepository
     * @param TranslationRecipient $translationRecipient
     * @return Response
     */
    public function parameterAll(ParameterRepository $parameterRepository, TranslationRecipient $translationRecipient)
    {
        $parameters = [];
        $parametersAll = $parameterRepository->findAll();
        foreach ($parametersAll as $parameter) {
            $parameters[] = $translationRecipient->getTranslatedEntity($parameter);
        }

        return $this->render('admin/parameter/all.html.twig', [
            'controller_name' => 'ParameterController',
            'parameters' => $parameters,
        ]);
    }

    /**
     * @Route("/{id}", name="_single", requirements={"id"="\d+"})
     * @param Parameter $parameter
     * @param TranslationRecipient $translationRecipient
     * @return Response
     */
    public function parameterSingle(Parameter $parameter, TranslationRecipient $translationRecipient)
    {
        $translation = $translationRecipient->getTranslation($parameter);

        return $this->render('admin/parameter/single.html.twig', [
            'controller_name' => 'ParameterController',
            'parameter' => $parameter,
            'translation' => $translation,
        ]);
    }

    /**
     * @Route("/add", name="_add")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @return RedirectResponse|Response
     */
    public function parameterAdd(Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $form = $this->createForm(ParameterType::class, null, [
            'action' => $this->generateUrl('admin_parameter_add'),
            'method' => 'post',
            'attr' => [
                'id' => 'parameter_form',
            ],
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $parameter = $form->getData();

            $arrData = $request->request->get('parameter');
            $repoTranslation = $entityManager->getRepository(Translation::class);
            $repoTranslation->translate($parameter, 'name', 'uk', $arrData['translation_name']);

            $entityManager->persist($parameter);
            $entityManager->flush();

            $message = $translator->trans('Параметр успешно добавлен');
            $this->addFlash('success', $message);

            return $this->redirectToRoute('admin_parameter_all');
        }

        return $this->render('admin/parameter/add.html.twig', [
            'controller_name' => 'ParameterController',
            'form_add' => $form->createView(),
            'title' => $translator->trans('Добавление параметра'),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="_edit", requirements={"id"="\d+"})
     * @param Parameter $parameter
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @return RedirectResponse|Response
     */
    public function parameterEdit(Parameter $parameter, Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $form = $this->createForm(ParameterType::class, $parameter, [
            'action' => $this->generateUrl('admin_parameter_edit', ['id' => $parameter->getId()]),
            'method' => 'post',
            'attr' => [
                'id' => 'parameter_form',
            ],
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $arrData = $request->request->get('parameter');
            $repoTranslation = $entityManager->getRepository(Translation::class);
            $repoTranslation->translate($parameter, 'name', 'uk', $arrData['translation_name']);

            $entityManager->persist($parameter);
            $entityManager->flush();

            $message = $translator->trans('Параметр успешно изменен');
            $this->addFlash('success', $message);

            return $this->redirectToRoute('admin_parameter_single', ['id' => $parameter->getId()]);
        }

        return $this->render('admin/parameter/add.html.twig', [
            'controller_name' => 'ParameterController',
            'form_add' => $form->createView(),
            'title' => $translator->trans('Редактирование параметра'),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="_delete", requirements={"id"="\d+"})
     * @param Parameter $parameter
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @return RedirectResponse
     */
    public function parameterDelete(Parameter $parameter, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $entityManager->remove($parameter);
        $entityManager->flush();

        $message = $translator->trans('Параметр успешно удален');

        $this->addFlash('success', $message);

        return $this->redirectToRoute('admin_parameter_all');
    }
}
