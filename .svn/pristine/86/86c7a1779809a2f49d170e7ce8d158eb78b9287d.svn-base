<?php

namespace CM\Bundle\SecurityBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

class SecuredController extends Controller
{
	
    /**
     * @Route("/session/check", name="cm_session_check")
     */
    public function checkSession() {
        $isStarted = $this->container->get('session')->isStarted();
        $response = new Response(json_encode($isStarted));

        return $response;
    }

    /**
     * @Route("/login", name="cm_login")
     * @Template()
     */
    public function loginAction(Request $request)
    {
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $request->getSession()->get(SecurityContext::AUTHENTICATION_ERROR);
        }

        return array(
            'last_username' => $request->getSession()->get(SecurityContext::LAST_USERNAME),
            'error'         => $error,
        );
    }
    
    /*
     * @Route("/login_check", name="twitter_security_check")
     */
    /*public function securityCheckAction()
    {
        // The security layer will intercept this request
    }*/

    /*
     * @Route("/logout", name="twitter_logout")
     */
    /*public function logoutAction()
    {
        // The security layer will intercept this request
    }*/

}
