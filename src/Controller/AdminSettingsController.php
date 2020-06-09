<?php

namespace App\Controller;

use App\Form\AdminSettingsType;
use SWP\Bundle\SettingsBundle\Manager\SettingsManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminSettingsController extends AbstractController
{
    public function index(Request $request, SettingsManagerInterface $settingsManager): Response
    {
        $form = $this->createForm(AdminSettingsType::class, [
            'new_user_email_template' => $settingsManager->get('new_user_email_template'),
            'new_user_email_title' => $settingsManager->get('new_user_email_title'),
            'new_course_email_template' => $settingsManager->get('new_course_email_template'),
            'new_course_email_title' => $settingsManager->get('new_course_email_title'),
            'password_reset_email_template' => $settingsManager->get('password_reset_email_template'),
            'password_reset_email_title' => $settingsManager->get('password_reset_email_title'),
            'email_from_address' => $settingsManager->get('email_from_address'),
            'email_from_name' => $settingsManager->get('email_from_name'),
        ]);

        if (Request::METHOD_POST === $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $data = $form->getData();
                $settingsManager->set('new_user_email_template', $data['new_user_email_template']);
                $settingsManager->set('new_user_email_title', $data['new_user_email_title']);
                $settingsManager->set('new_course_email_template', $data['new_course_email_template']);
                $settingsManager->set('new_course_email_title', $data['new_course_email_title']);
                $settingsManager->set('password_reset_email_template', $data['password_reset_email_template']);
                $settingsManager->set('password_reset_email_title', $data['password_reset_email_title']);
                $settingsManager->set('email_from_address', $data['email_from_address']);
                $settingsManager->set('email_from_name', $data['email_from_name']);

                return new RedirectResponse($this->generateUrl('admin_settings'));
            }
        }

        return $this->render('settings/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
