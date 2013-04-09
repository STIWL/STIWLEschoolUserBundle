<?php

namespace Esolving\Eschool\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Esolving\Eschool\UserBundle\Entity\Role;
use Esolving\Eschool\UserBundle\Form\RoleType;

/**
 * Role controller.
 *
 */
class RoleController extends Controller {

//    public function checkRoleAction() {
//        $roles_id = $this->getRequest()->get('role');
//        $status = 0;
//        $roleName = "";
//        if (is_array($roles_id)) {
//            foreach ($roles_id as $role_idV) {
//                $role = $this->getDoctrine()->getRepository('EsolvingEschoolUserBundle:Role')->find($role_idV);
//                $roleName[] = $role->getRoleType()->getName();
//            }
//        }
//// create a JSON-response with a 200 status code
//        $response = new \Symfony\Component\HttpFoundation\Response(json_encode($roleName));
//        $response->headers->set('Content-Type', 'application/json');
//        return $response;
//    }

}
