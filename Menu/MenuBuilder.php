<?php

namespace Esolving\Eschool\UserBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;

class MenuBuilder {
    
    protected $factory;
    protected $container;
    protected $translationDomain = 'EsolvingEschoolUserBundle';
    
    /**
     * 
     * @param \Knp\Menu\FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory, Container $container) {
        $this->factory = $factory;
        $this->container = $container;
    }

    public function userMenu(Request $request) {
        $menu = $this->factory->createItem('menu_user');
        $menu->setCurrentUri($this->container->get('request')->getRequestUri());

        $menu->addChild('home', array(
            'route' => 'esolving_eschool_userB_index')
        );

        $childMyAccount = $menu->addChild('my_account')->setAttribute('class', 'has-sub')->setExtra('html', '<div class="arrow"></div>');
        $childMyAccount->addChild('profile', array(
            'route' => 'esolving_eschool_userB_profile')
        );
        
        $childMyAccount->addChild('log_out', array(
            'route' => 'logout',
            'routeParameters' => array(
                "_locale" => $request->getLocale()
            )
        ));
        
        $menu->addChild('user', array(
            'route' => 'esolving_eschool_userB_submenu'
        ));
        
        $menu->addChild('room',array(
            'route'=> 'esolving_eschool_roomB_submenu'
        ));
        
        $menu->addChild('assistance',array(
            'route'=> 'esolving_eschool_assistanceB_submenu'
        ));
        
        $menu->addChild('administration',array(
            'route'=> 'sonata_admin_dashboard'
        ));
        
        return $menu;
    }

}