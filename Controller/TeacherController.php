<?php

namespace Esolving\Eschool\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Esolving\Eschool\UserBundle\Entity\User;
use Esolving\Eschool\UserBundle\Entity\Teacher;

class TeacherController extends Controller {

    public function listAction() {
        $serviceCore = $this->get('esolving_eschool_core');
        $sectionId = $serviceCore->getSectionId();
        $headquarterId = $serviceCore->getHeadquarterId();
        $teachers = $this->getDoctrine()->getRepository('EsolvingEschoolUserBundle:Teacher')->findAllBySectionIdByHeadquarterIdByLanguage($sectionId, $headquarterId, $this->getRequest()->getLocale());
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($teachers, $this->get('request')->query->get('page', 1)/* page number */, 3/* limit per page */);
        $teachersCompactPagination = compact('pagination');
        return $this->render('EsolvingEschoolUserBundle:Teacher:list.html.twig', array(
                    'teachers' => $teachersCompactPagination
        ));
    }

    private function setCodeAndSecurePassword(User $user) {
        $userId = $user->getId();
        $code = date("Y", time()) . str_repeat("0", 6 - strlen($userId)) . $userId;
        $factory = $this->get('security.encoder_factory');
        $encoder = $factory->getEncoder($user);
        $password = $encoder->encodePassword(substr(sha1($code), 0, 6), $user->getSalt());
        $user->setCode($code);
        $user->setPassword($password);
    }

    public function registerAction(Request $request) {
        $teacher = new Teacher();
        $form = $this->createForm($this->get('esolving_eschool_user.form.type.teacher'), $teacher);
        $info = "";
        $status = 0;
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                try {
                    $em->getConnection()->beginTransaction();
                    $em->persist($teacher);
                    $em->flush();
                    $password = $this->setCodeAndSecurePassword($teacher->getUser());
                    $em->flush();
                    $message = \Swift_Message::newInstance()
                            ->setSubject($this->get('translator')->trans('you_was_registered', array(), 'EsolvingEschoolUserBundle'))
                            ->setFrom($this->get('service_container')->getParameter('email_master'))
                            ->setTo($teacher->getUser()->getEmail())
                            ->setBody($this->renderView('EsolvingEschoolUserBundle:User:register.txt.twig', array('user' => $teacher->getUser(), 'password' => $password)), 'text/html')
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
        return $this->render('EsolvingEschoolUserBundle:Teacher:register.html.twig', array(
                    'form' => $form->createView(),
                    'info' => $info,
                    'status' => $status
                        )
        );
    }

    public function editAction($teacherId) {
        $request = $this->getRequest();
        $teacher = $this->getDoctrine()->getRepository('EsolvingEschoolUserBundle:Teacher')->find($teacherId);
        $form = $this->createForm($this->get('esolving_eschool_user.form.type.teacher'), $teacher);
        if ($request->isMethod('POST')) {
            $form->bind($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($teacher);
                $em->flush();
            }
        }
        return $this->render('EsolvingEschoolUserBundle:Teacher:edit.html.twig', array(
                    'teacher' => $teacher,
                    'form' => $form->createView()
        ));
    }

    public function showAction($teacherId) {
        $teacher = $this->getDoctrine()->getRepository('EsolvingEschoolUserBundle:Teacher')->findOneByTeacherIdByLanguage($teacherId, $this->getRequest()->getLocale());
        return $this->render('EsolvingEschoolUserBundle:Teacher:show.html.twig', array(
                    'teacher' => $teacher,
        ));
    }

    private function createDeleteForm($fatherId) {
        return $this->createFormBuilder(array('fatherId' => $fatherId))
                        ->add('fatherId', 'hidden')
                        ->getForm()
        ;
    }

    public function deleteAction($teacherId) {
        $request = $this->getRequest();
        $teacher = $this->getDoctrine()->getRepository('EsolvingEschoolUserBundle:Teacher')->findOneByTeacherIdByLanguage($teacherId, $this->getRequest()->getLocale());
//        if($user->getTeachers()){
//            throw $this->createNotFoundException("Can't delete because is a Teacher");
//        }
//        if($user->getStudents()->getRoom()){
//            throw $this->createNotFoundException("Can't delete because is a Teacher");
//        }
//        if($user->getFathers()->getStudents()){
//            throw $this->createNotFoundException("Can't delete because has a Student");
//        }
        $form = $this->createDeleteForm($teacherId);

//        if ($request->isMethod('POST')) {
//            $form->bind($request);
//            if ($form->isValid()) {
//                $em = $this->getDoctrine()->getManager();
//                $em->remove($user);
//                $em->flush();
//                return $this->redirect($this->generateUrl('esolving_eschool_userB_list'));
//            }
//        }
        return $this->render('EsolvingEschoolUserBundle:Teacher:delete.html.twig', array(
                    'teacher' => $teacher,
                    'form' => $form->createView()
        ));
    }

}