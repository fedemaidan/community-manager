<?php

namespace CM\Bundle\APIBundle\EventListener;

use CM\Bundle\ModelBundle\Entity\User;
use CM\Bundle\APIBundle\Controller\TokenAuthenticatedController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;



class TokenListener
{
    const INVALID_TOKEN = 1;
    const USER_NOT_PASS = 2;
    private $tokens;

    public function __construct($tokens)
    {
        $this->tokens = $tokens;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        /*
         * $controller passed can be either a class or a Closure. This is not usual in Symfony2 but it may happen.
         * If it is a class, it comes in array format
         */
        if (!is_array($controller)) {
            return;
        }

//         if ($controller[0] instanceof TokenAuthenticatedController) {
//             $token = $event->getRequest()->query->get('token');
//             $email = $event->getRequest()->query->get('email');
//             $password = $event->getRequest()->query->get('password');
            
//             $user = $controller[0]->get('doctrine')->getEntityManager()->getRepository('CMModelBundle:User')->findOneByEmail($email);
            
//             if (!in_array($token, $this->tokens)) {
//                 throw new AccessDeniedHttpException('This action needs a valid token!', null, self::INVALID_TOKEN);
//             }
            
//             if ($user != null) {
// 				if(!password_verify($password, $user->getPassword())) {
//             		throw new AccessDeniedHttpException('User or password is wrong!', null, self::USER_NOT_PASS);
//             	}
//             }
//             else {
//             	throw new AccessDeniedHttpException('User or password is wrong!', null, self::USER_NOT_PASS);
//             }

//             // mark the request as having passed token authentication
//             $event->getRequest()->attributes->set('auth_token', $token);
//         }

    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        // check to see if onKernelController marked this as a token "auth'ed" request
        /*if (!$token = $event->getRequest()->attributes->get('auth_token')) {
            return;
        }

        $response = $event->getResponse();

        // create a hash and set it as a response header
        $hash = sha1($response->getContent().$token);
        $response->headers->set('X-CONTENT-HASH', $hash);*/
    }
}