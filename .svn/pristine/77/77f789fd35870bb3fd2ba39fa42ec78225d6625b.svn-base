<?php

namespace CM\Bundle\SecurityBundle\Listener;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use CM\Bundle\ModelBundle\Service\CMService;
use CM\Bundle\FrontendBundle\Utils\Filter;

class SecurityListener
{
    private $cm_service;

    public function __construct(CMService $cm_service)
    {
      $this->cm_service = $cm_service;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
      $fan_pages = $this->cm_service->getFanPages();
      $session = $event->getRequest()->getSession();
      $session->set('fan_pages', $fan_pages);
      $session->set('filter', new Filter());
    }

}