<?php

namespace Esolving\Eschool\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Esolving\Eschool\UserBundle\Entity\User;
use Esolving\Eschool\UserBundle\Entity\Father;

class FatherController extends Controller {

    private function setCodeAndSecurePassword(User $user) {
        $userId = $user->getId();
        $code = date("Y", time()) . str_repeat("0", 6 - strlen($userId)) . $userId;
        $factory = $this->get('security.encoder_factory');
        $encoder = $factory->getEncoder($user);
        $password = $encoder->encodePassword(substr(sha1($code), 0, 6), $user->getSalt());
        $user->setCode($code);
        $user->setPassword($password);
    }

    public function listAction() {
        $serviceCore = $this->get('esolving_eschool_core');
        $sectionId = $serviceCore->getSectionId();
        $headquarterId = $serviceCore->getHeadquarterId();
        $fathers = $this->getDoctrine()->getRepository('EsolvingEschoolUserBundle:Father')->findAllBySectionIdByHeadquarterIdByLanguage($sectionId, $headquarterId, $this->getRequest()->getLocale());
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($fathers, $this->get('request')->query->get('page', 1)/* page number */, 3/* limit per page */);
        $fathersCompactPagination = compact('pagination');
        return $this->render('EsolvingEschoolUserBundle:Father:list.html.twig', array(
                    'fathers' => $fathersCompactPagination
        ));
    }

    public function registerAction(Request $request) {
        $father = new Father();
        $form = $this->createForm($this->get('esolving_eschool_user.form.type.father'), $father);
        $info = "";
        $status = 0;
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                try {
                    $em->getConnection()->beginTransaction();
                    $em->persist($father);
                    $em->flush();
                    $password = $this->setCodeAndSecurePassword($father->getUser());
                    $em->flush();
                    $message = \Swift_Message::newInstance()
                            ->setSubject($this->get('translator')->trans('you_was_registered', array(), 'EsolvingEschoolUserBundle'))
                            ->setFrom($this->get('service_container')->getParameter('email_master'))
                            ->setTo($father->getUser()->getEmail())
                            ->setBody($this->renderView('EsolvingEschoolUserBundle:User:register.txt.twig', array('user' => $father->getUser(), 'password' => $password)), 'text/html')
                    ;
                    $this->get('mailer')->send($message);
                    $em->getConnection()->commit();
                    $status = 1;
                } catch (\Exception $e) {
                    $em->getConnection()->rollback();
                    $status = -1;
                }
                if ($status == 1) {
                    $info = $this->get('translator')->trans('registered', array(), 'EsolvingEschoolUserBundle');
                } else if ($status == 0) {
                    $info = $this->get('translator')->trans('cant_registered', array(), 'EsolvingEschoolUserBundle');
                } else if ($status == -1) {
                    $info = $this->get('translator')->trans('not_registered', array(), 'EsolvingEschoolUserBundle');
                }
            } else {
                $info = $this->get('translator')->trans('not_valid_form', array(), 'EsolvingEschoolUserBundle');
            }
        }
        return $this->render('EsolvingEschoolUserBundle:Father:register.html.twig', array(
                    'form' => $form->createView(),
                    'info' => $info,
                    'status' => $status
                        )
        );
    }

    public function editAction($fatherId) {
        $request = $this->getRequest();
        $father = $this->getDoctrine()->getRepository('EsolvingEschoolUserBundle:Father')->find($fatherId);
        $form = $this->createForm($this->get('esolving_eschool_user.form.type.father'), $father);
        if ($request->isMethod('POST')) {
            $form->bind($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($father);
                $em->flush();
            }
        }
        return $this->render('EsolvingEschoolUserBundle:Father:edit.html.twig', array(
                    'father' => $father,
                    'form' => $form->createView()
        ));
    }

    public function showAction($fatherId) {
        $father = $this->getDoctrine()->getRepository('EsolvingEschoolUserBundle:Father')->findOneByFatherIdByLanguage($fatherId, $this->getRequest()->getLocale());
        return $this->render('EsolvingEschoolUserBundle:Father:show.html.twig', array(
                    'father' => $father,
        ));
    }

    private function createDeleteForm($fatherId) {
        return $this->createFormBuilder(array('fatherId' => $fatherId))
                        ->add('fatherId', 'hidden')
                        ->getForm()
        ;
    }

    public function deleteAction($fatherId) {
        $request = $this->getRequest();
        $father = $this->getDoctrine()->getRepository('EsolvingEschoolUserBundle:Father')->findOneByFatherIdByLanguage($fatherId, $this->getRequest()->getLocale());
//        if($user->getTeachers()){
//            throw $this->createNotFoundException("Can't delete because is a Teacher");
//        }
//        if($user->getStudents()->getRoom()){
//            throw $this->createNotFoundException("Can't delete because is a Teacher");
//        }
//        if($user->getFathers()->getStudents()){
//            throw $this->createNotFoundException("Can't delete because has a Student");
//        }
        $form = $this->createDeleteForm($fatherId);

//        if ($request->isMethod('POST')) {
//            $form->bind($request);
//            if ($form->isValid()) {
//                $em = $this->getDoctrine()->getManager();
//                $em->remove($user);
//                $em->flush();
//                return $this->redirect($this->generateUrl('esolving_eschool_userB_list'));
//            }
//        }
        return $this->render('EsolvingEschoolUserBundle:Father:delete.html.twig', array(
                    'father' => $father,
                    'form' => $form->createView()
        ));
    }

}