<?php

namespace Esolving\Eschool\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Esolving\Eschool\UserBundle\Entity\User;
use Esolving\Eschool\UserBundle\Entity\Student;

class StudentController extends Controller {

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
        $students = $this->getDoctrine()->getRepository('EsolvingEschoolUserBundle:Student')->findAllBySectionIdByHeadquarterIdByLanguage($sectionId, $headquarterId, $this->getRequest()->getLocale());
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($students, $this->get('request')->query->get('page', 1)/* page number */, 3/* limit per page */);
        $studentsCompactPagination = compact('pagination');
        return $this->render('EsolvingEschoolUserBundle:Student:list.html.twig', array(
                    'students' => $studentsCompactPagination
        ));
    }

    public function editAction($studentId) {
        $request = $this->getRequest();
        $student = $this->getDoctrine()->getRepository('EsolvingEschoolUserBundle:Student')->findOneByStudentIdByLanguage($studentId, $request->getLocale());
        $form = $this->createForm($this->get('esolving_eschool_user.form.type.student'), $student);
        if ($request->isMethod('POST')) {
            $form->bind($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($student);
                $em->flush();
            }
        }
        return $this->render('EsolvingEschoolUserBundle:Student:edit.html.twig', array(
                    'student' => $student,
                    'form' => $form->createView()
        ));
    }

    public function showAction($studentId) {
        $serviceCore = $this->get('esolving_eschool_core');
        $sectionId = $serviceCore->getSectionId();
        $headquarterId = $serviceCore->getHeadquarterId();
        $student = $this->getDoctrine()->getRepository('EsolvingEschoolUserBundle:Student')->findOneByStudentIdByLanguage($studentId, $this->getRequest()->getLocale());
        return $this->render('EsolvingEschoolUserBundle:Student:show.html.twig', array(
                    'student' => $student,
        ));
    }

    private function createDeleteForm($studentId) {
        return $this->createFormBuilder(array('studentId' => $studentId))
                        ->add('studentId', 'hidden')
                        ->getForm()
        ;
    }

    public function deleteAction($studentId) {
        $request = $this->getRequest();
        $student = $this->getDoctrine()->getRepository('EsolvingEschoolUserBundle:Student')->findOneByStudentIdByLanguage($studentId, $request->getLocale());
//        if($user->getTeachers()){
//            throw $this->createNotFoundException("Can't delete because is a Teacher");
//        }
//        if($user->getStudents()->getRoom()){
//            throw $this->createNotFoundException("Can't delete because is a Teacher");
//        }
//        if($user->getFathers()->getStudents()){
//            throw $this->createNotFoundException("Can't delete because has a Student");
//        }
        $form = $this->createDeleteForm($studentId);

//        if ($request->isMethod('POST')) {
//            $form->bind($request);
//            if ($form->isValid()) {
//                $em = $this->getDoctrine()->getManager();
//                $em->remove($user);
//                $em->flush();
//                return $this->redirect($this->generateUrl('esolving_eschool_userB_list'));
//            }
//        }
        return $this->render('EsolvingEschoolUserBundle:Student:delete.html.twig', array(
                    'student' => $student,
                    'form' => $form->createView()
        ));
    }

    public function registerAction(Request $request) {
        $student = new Student();
        $form = $this->createForm($this->get('esolving_eschool_user.form.type.student'), $student);
        $info = "";
        $status = 0;
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                try {
                    $em->getConnection()->beginTransaction();
                    $em->persist($student);
                    $em->flush();
                    $password = $this->setCodeAndSecurePassword($student->getUser());
                    $em->flush();
                    $message = \Swift_Message::newInstance()
                            ->setSubject($this->get('translator')->trans('you_was_registered', array(), 'EsolvingEschoolUserBundle'))
                            ->setFrom($this->get('service_container')->getParameter('email_master'))
                            ->setTo($student->getUser()->getEmail())
                            ->setBody($this->renderView('EsolvingEschoolUserBundle:User:register.txt.twig', array('user' => $student->getUser(), 'password' => $password)), 'text/html')
                    ;
                    $this->get('mailer')->send($message);
                    $em->getConnection()->commit();
                    return $this->redirect($this->generateUrl('esolving_eschool_userB_Student_show', array(
                                        'id' => $student->getId()
                    )));
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
        return $this->render('EsolvingEschoolUserBundle:Student:register.html.twig', array(
                    'form' => $form->createView(),
                    'info' => $info,
                    'status' => $status
                        )
        );
    }

}