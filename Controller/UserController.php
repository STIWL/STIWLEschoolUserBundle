<?php

namespace Esolving\Eschool\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Esolving\Eschool\UserBundle\Entity\User;
use Esolving\Eschool\UserBundle\Entity\Student;
use Esolving\Eschool\UserBundle\Entity\Teacher;
use Esolving\Eschool\UserBundle\Entity\Father;

/**
 * User controller.
 *
 */
class UserController extends Controller {

//            // create a JSON-response with a 200 status code
//            $response = new \Symfony\Component\HttpFoundation\JsonResponse(array('algo'=>'1'));
//            
//            $response = new \Symfony\Component\HttpFoundation\Response(json_encode(array('algo'=>'1')));
//            $response->headers->set('Content-Type', 'application/json');
//            return $response;

    public function submenuAction() {

        return $this->render('EsolvingEschoolUserBundle:User:submenu.html.twig', array(
        ));
    }

    public function setSectionHeadquarterAction($route, $routeParams = null) {
        $session = $this->get('session');
        $session->set('section', $this->getRequest()->get('ddlbSection'));
        $session->set('headquarter', $this->getRequest()->get('ddlbHeadquarter'));

        $parameters = array();
        $requestQuery = $this->getRequest()->query;
        foreach ($requestQuery as $key => $value) {
            $parameters[$key] = $value;
        }

        return $this->redirect($this->generateUrl($route, $parameters));
    }

    public function homeAction() {

        return $this->render('EsolvingEschoolUserBundle:User:home.html.twig', array());
    }

    public function listAction() {
        $serviceCore = $this->get('esolving_eschool_core');
        $sectionId = $serviceCore->getSectionId();
        $headquarterId = $serviceCore->getHeadquarterId();
        $users = $this->getDoctrine()->getRepository('EsolvingEschoolUserBundle:User')->findAllExceptAdminBySectionIdByHeadquarterIdByLanguage($sectionId, $headquarterId, $this->getRequest()->getLocale());
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($users, $this->get('request')->query->get('page', 1)/* page number */, 3/* limit per page */);
        $usersCompactPagination = compact('pagination');
        return $this->render('EsolvingEschoolUserBundle:User:list.html.twig', array(
                    'users' => $usersCompactPagination
        ));
    }

    private function createDeleteForm($userId) {
        return $this->createFormBuilder(array('userId' => $userId))
                        ->add('userId', 'hidden')
                        ->getForm()
        ;
    }

    public function deleteAction($userId) {
        $request = $this->getRequest();
        $user = $this->getDoctrine()->getRepository('EsolvingEschoolUserBundle:User')->findOneByUserIdByLanguage($userId, $request->getLocale());
//        if($user->getTeachers()){
//            throw $this->createNotFoundException("Can't delete because is a Teacher");
//        }
//        if($user->getStudents()->getRoom()){
//            throw $this->createNotFoundException("Can't delete because is a Teacher");
//        }
//        if($user->getFathers()->getStudents()){
//            throw $this->createNotFoundException("Can't delete because has a Student");
//        }
        $form = $this->createDeleteForm($userId);

//        if ($request->isMethod('POST')) {
//            $form->bind($request);
//            if ($form->isValid()) {
//                $em = $this->getDoctrine()->getManager();
//                $em->remove($user);
//                $em->flush();
//                return $this->redirect($this->generateUrl('esolving_eschool_userB_list'));
//            }
//        }
        return $this->render('EsolvingEschoolUserBundle:User:delete.html.twig', array(
                    'user' => $user,
                    'form' => $form->createView()
        ));
    }

//    public function choiceFathersAction() {
//        $userId = $this->getRequest()->get('userId');
//        $user = $this->getDoctrine()->getRepository('EsolvingEschoolUserBundle:User')->find($userId);
//        $formGeneral = $this->get('esolving_eschool_user.form.type.general');
//        $formGeneral->setOptions(array('userId' => $userId));
//        $student = $this->getDoctrine()->getRepository('EsolvingEschoolUserBundle:Student')->findOneBy(array('user' => $userId));
//        if ($this->getRequest()->get('role') == 'ROLE_STUDENT') {
//            if ($student) {
//                $formGeneral->setOptions(array('role' => 'ROLE_STUDENT'));
//                if (count($student->getFathers()) > 0) {
//                    foreach ($student->getFathers() as $fatherV) {
//                        $user->getFathers()->add($fatherV);
//                    }
//                }
//            }
//        } else if ($this->getRequest()->get('role') == 'ROLE_FATHER') {
//            $formGeneral->setOptions(array('role' => 'ROLE_FATHER'));
//        }
//        $user->getRolesAccess()->clear();
//        $requestTypeGeneral = $this->getRequest()->get($formGeneral->getName());
//        foreach ($requestTypeGeneral['rolesAccess'] as $roleAccessV) {
//            $role = $this->getDoctrine()->getRepository('EsolvingEschoolUserBundle:Role')->find($roleAccessV);
//            $user->getRolesAccess()->add($role);
//        }
//        $form = $this->createForm($formGeneral, $user);
//        return $this->render('EsolvingEschoolUserBundle:User:choiceFathers.html.twig', array(
//                    'form' => $form->createView()
//        ));
//    }

    public function editAction($userId) {
        $user = $this->getDoctrine()->getRepository('EsolvingEschoolUserBundle:User')->find($userId);
        $request = $this->getRequest();
        $status = 0;
        $info = null;
        if (!$user) {
            throw $this->createNotFoundException('No user found');
        }

        $formGeneral = $this->get('esolving_eschool_user.form.type.general');
        $formGeneral->setOptions(array('userId' => $userId));
        $student = $this->getDoctrine()->getRepository('EsolvingEschoolUserBundle:Student')->findOneBy(array('user' => $userId));
        if ($student) {
            $formGeneral->setOptions(array('role' => 'ROLE_STUDENT'));
            if (count($student->getFathers()) > 0) {
                foreach ($student->getFathers() as $fatherV) {
                    $user->getFathers()->add($fatherV);
                }
            }
        }

        $form = $this->createForm($formGeneral, $user, array('user' => $user));

        if ($request->isXmlHttpRequest()) {
//        if ($request->isMethod('POST')) {
            $rolesArr = array();
            $rolesTypeArr = array();
            $requestFormGeneral = $this->getRequest()->get($form->getName());
            if (isset($requestFormGeneral['rolesAccess'])) {
                if (count($requestFormGeneral['rolesAccess']) > 0) {
                    foreach ($requestFormGeneral['rolesAccess'] as $roleAccessV) {
                        $role = $this->getDoctrine()->getRepository('EsolvingEschoolUserBundle:Role')->find($roleAccessV);
                        $rolesTypeArr[] = $role->getRoleType();
                        $rolesArr[] = $role;
                    }
                }
            }

            $formGeneral->setOptions(array('roles' => $rolesTypeArr));

            $form = $this->createForm($formGeneral, $user, array('roles' => $rolesArr, 'user' => $user));

            if ($request->get('send')) {
                $form->bind($request);
                if ($form->isValid()) {
                    $em = $this->getDoctrine()->getManager();
                    try {
                        $em->getConnection()->beginTransaction();
                        $teacher = $this->getDoctrine()->getRepository('EsolvingEschoolUserBundle:Teacher')->findOneBy(array(
                            'user' => $userId
                        ));
                        $student = $this->getDoctrine()->getRepository('EsolvingEschoolUserBundle:Student')->findOneBy(array(
                            'user' => $userId
                        ));
                        $father = $this->getDoctrine()->getRepository('EsolvingEschoolUserBundle:Father')->findOneBy(array(
                            'user' => $userId
                        ));
                        $deleteStudent = true;
                        $deleteFather = true;
                        $deleteTeacher = true;
                        if (in_array('ROLE_STUDENT', $rolesTypeArr)) {
                            $deleteStudent = false;
                            if (!$student) {
                                $student = new Student();
                                $student->setUser($user);
                                $user->getStudents()->add($student);
                            } else {
                                $fathers = $this->getDoctrine()->getRepository('EsolvingEschoolUserBundle:Father')->findAll();
                                foreach ($fathers as $fatherV) {
                                    $student->getFathers()->removeElement($fatherV);
                                }
                            }

                            foreach ($form->get('fathers')->getData() as $fatherV) {
                                $student->getFathers()->add($fatherV);
                            }
                        }
                        if (in_array('ROLE_TEACHER', $rolesTypeArr)) {
                            $deleteTeacher = false;
                            if (!$teacher) {
                                $teacher = new Teacher();
                                $teacher->setUser($user);
                                $em->persist($teacher);
                                $user->addTeacher($teacher);
                            }
                        }
                        if (in_array('ROLE_FATHER', $rolesTypeArr)) {
                            $deleteFather = false;
                            if (!$father) {
                                $father = new Father();
                                $father->setUser($user);
                                $em->persist($father);
                                $user->addFather($father);
                            }
                        }
                        if ($deleteFather) {
                            if ($father) {
                                $em->remove($father);
                            }
                        }
                        if ($deleteStudent) {
                            if ($student) {
                                $em->remove($student);
                            }
                        }
                        if ($deleteTeacher) {
                            if ($teacher) {
                                $em->remove($teacher);
                            }
                        }
                        $em->persist($user);
                        $em->flush();
                        $status = 1;
                        $em->getConnection()->commit();
                        return $this->redirect($this->generateUrl('esolving_eschool_userB_edit', array('userId' => $userId)));
                    } catch (\Exception $e) {
                        $em->getConnection()->rollback();
                        $status = -1;
                    }
                    if ($status == 1) {
                        $info[] = $this->get('translator')->trans('updated', array(), 'EsolvingEschoolUserBundle');
//                    $this->get('session')->getFlashBag()->add('notice', $info);
                    } else if ($status == 0) {
                        $info[] = $this->get('translator')->trans('cant_update', array(), 'EsolvingEschoolUserBundle');
                    } else if ($status == -1) {
                        $info[] = $this->get('translator')->trans('not_updated', array(), 'EsolvingEschoolUserBundle');
                    }
                } else {
                    $info[] = $this->get('translator')->trans('please_complete_the_fields_correctly', array(), 'EsolvingEschoolUserBundle');
                }
                return $this->render('EsolvingEschoolUserBundle:User:edit_errors.json.twig', array(
                            'form' => $form->createView(),
                            'infos' => $info,
                            'status' => $status
                ));
            }
            return $this->render('EsolvingEschoolUserBundle:User:choiceFathers.html.twig', array(
                        'form' => $form->createView()
            ));
        }
        return $this->render('EsolvingEschoolUserBundle:User:edit.html.twig', array(
                    'form' => $form->createView(),
                    'user' => $user,
                    'info' => $info
        ));
    }

    public function showAction($userId) {
        $user = $this->getDoctrine()->getRepository('EsolvingEschoolUserBundle:User')->findOneByUserIdByLanguage($userId, $this->getRequest()->getLocale());

        $formDelete = $this->createDeleteForm($userId);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
        return $this->render('EsolvingEschoolUserBundle:User:show.html.twig', array(
                    'user' => $user,
                    'formDelete' => $formDelete
        ));
    }

    public function registerAction(Request $request) {
        $user = new User();
        $student = new Student();
        $teacher = new Teacher();
        $formGeneral = $this->get('esolving_eschool_user.form.type.general');
        $form = $this->createForm($formGeneral, $user);
        $status = 0;
        $info = "";
//        if ($this->getRequest()->getMethod() === 'POST') {
        if ($request->isXmlHttpRequest()) {
//        if ($request->isMethod('POST')) {
            $rolesArr = array();
            $rolesTypeArr = array();
            $requestFormGeneral = $this->getRequest()->get($form->getName());
            if (isset($requestFormGeneral['rolesAccess'])) {
                if (count($requestFormGeneral['rolesAccess']) > 0) {
                    foreach ($requestFormGeneral['rolesAccess'] as $roleAccessV) {
                        $role = $this->getDoctrine()->getRepository('EsolvingEschoolUserBundle:Role')->find($roleAccessV);
                        $rolesTypeArr[] = $role->getRoleType();
                        $rolesArr[] = $role;
                    }
                }
            }

            $formGeneral->setOptions(array('roles' => $rolesTypeArr));

            $form = $this->createForm($formGeneral, $user, array('roles' => $rolesArr, 'user' => $user));

            if ($request->get('send')) {
                $form->bind($request);
                if ($form->isValid()) {
                    $em = $this->getDoctrine()->getManager();
                    try {
                        $em->getConnection()->beginTransaction();
                        if (in_array('ROLE_STUDENT', $rolesTypeArr)) {

                            $student = new Student();
                            $student->setUser($user);
                            $user->getStudents()->add($student);

                            foreach ($form->get('fathers')->getData() as $fatherV) {
                                $student->getFathers()->add($fatherV);
                            }
                        }
                        if (in_array('ROLE_TEACHER', $rolesTypeArr)) {

                            $teacher = new Teacher();
                            $teacher->setUser($user);
                            $em->persist($teacher);
                            $user->addTeacher($teacher);
                        }
                        if (in_array('ROLE_FATHER', $rolesTypeArr)) {
                            $father = new Father();
                            $father->setUser($user);
                            $em->persist($father);
                            $user->addFather($father);
                        }
                        $em->persist($user);
                        $em->flush();
                        $password = $this->setCodeAndSecurePassword($user);
                        $em->flush();
                        $message = \Swift_Message::newInstance()
                                ->setSubject($this->get('translator')->trans('you_was_registered', array(), 'EsolvingEschoolUserBundle'))
                                ->setFrom($this->get('service_container')->getParameter('email_master'))
                                ->setTo($user->getEmail())
                                ->setBody($this->renderView('EsolvingEschoolUserBundle:User:register.txt.twig', array('user' => $user, 'password' => $password)), 'text/html')
                        ;
                        $this->get('mailer')->send($message);
                        $status = 1;
                        $em->getConnection()->commit();
//                return $this->redirect($this->generateUrl('esolving_eschool_userB_edit', array('userId' => $userId)));
                    } catch (\Exception $e) {
                        $em->getConnection()->rollback();
                        $status = -1;
                    }
                    if ($status == 1) {
                        $info[] = $this->get('translator')->trans('registered', array(), 'EsolvingEschoolUserBundle');
//                    $this->get('session')->getFlashBag()->add('notice', $info);
                    } else if ($status == 0) {
                        $info[] = $this->get('translator')->trans('cant_register', array(), 'EsolvingEschoolUserBundle');
                    } else if ($status == -1) {
                        $info[] = $this->get('translator')->trans('not_registered', array(), 'EsolvingEschoolUserBundle');
                    }
                } else {
                    $info[] = $this->get('translator')->trans('please_complete_the_fields_correctly', array(), 'EsolvingEschoolUserBundle');
                }
                return $this->render('EsolvingEschoolUserBundle:User:edit_errors.json.twig', array(
                            'form' => $form->createView(),
                            'infos' => $info,
                            'status' => $status
                ));
            }
            return $this->render('EsolvingEschoolUserBundle:User:choiceFathers.html.twig', array(
                        'form' => $form->createView(),
//                        'print' => print_r($request)
            ));
        }
//        $userId = ($userId) ? $userId : $user->getId();
        return $this->render('EsolvingEschoolUserBundle:User:register.html.twig', array(
                    'form' => $form->createView(),
                    'info' => $info,
                    'status' => $status,
                    'userId' => $user->getId()
                        )
        );
    }

    private function setCodeAndSecurePassword(User $user) {
        $userId = $user->getId();
        $code = date("Y", time()) . str_repeat("0", 6 - strlen($userId)) . $userId;
        $password = substr(sha1($code), 0, 6);
        $factory = $this->get('security.encoder_factory');
        $encoder = $factory->getEncoder($user);
        $encodePassword = $encoder->encodePassword($password, $user->getSalt());
        $user->setCode($code);
        $user->setPassword($encodePassword);
        return $password;
    }

    public function profileAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        if (!$user) {
            throw $this->createNotFoundException('Not user found for id ' . $user->getId());
        }
//        $form = $this->createForm(new UserUpdateType(), $user);
        $form = $this->createForm($this->get('esolving_eschool_user.form.type.user_update'), $user);
        $info = "";
        $status = 0;
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            $actualPassword = $form->get("actualPassword")->getData();
            $passwordRepeated = $form->get('passwordRepeated')->getData();
//            $sexType = $form->get('sexType')->getData();
            if ($form->isValid()) {
                try {
                    $em->getConnection()->beginTransaction();
                    if (!empty($actualPassword) && !empty($passwordRepeated)) {
                        $factory = $this->get('security.encoder_factory');
                        $encoder = $factory->getEncoder($user);
                        $password = $encoder->encodePassword($passwordRepeated, $this->getUser()->getSalt());
                        $user->setPassword($password);
                    }
                    $em->persist($user);
                    $em->flush();
                    $em->getConnection()->commit();
                    $status = 1;
                } catch (\Exception $e) {
                    $em->getConnection()->rollback();
                    $status = -1;
                }
                if ($status == 1) {
                    $info = $this->get('translator')->trans('updated', array(), 'EsolvingEschoolUserBundle');
                } else if ($status == 0) {
                    $info = $this->get('translator')->trans('cant_updated', array(), 'EsolvingEschoolUserBundle');
                } else if ($status == -1) {
                    $info = $this->get('translator')->trans('not_updated', array(), 'EsolvingEschoolUserBundle');
                }
            } else {
                $info = $this->get('translator')->trans('not_valid_form', array(), 'EsolvingEschoolUserBundle');
            }
        }
        return $this->render('EsolvingEschoolUserBundle:User:profile.html.twig', array('form' => $form->createView(), 'info' => $info, 'status' => $status));
    }

}