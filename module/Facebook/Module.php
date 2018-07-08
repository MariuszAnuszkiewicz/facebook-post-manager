<?php
namespace Facebook;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\Mvc\MvcEvent;
use Facebook\Listener\SendListener;
use Facebook\Facebook\Model\FacebookAccess;
use Facebook\Facebook\Model\AccessTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;


class Module implements ConfigProviderInterface, ServiceProviderInterface, AutoloaderProviderInterface
{

    /**
     * Return an array for passing to Zend\Loader\AutoloaderFactory.
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                    'Facebook\Model' => __DIR__ . '/../../vendor/Facebook/Model',
                ),
            ),
        );
    }

    /**
     * Expected to return \Zend\ServiceManager\Config object or array to
     * seed such an object.
     * @return array|\Zend\ServiceManager\Config
     */
    public function getServiceConfig()
    {
        return include  __DIR__ . '/config/service.config.php';
    }

    /**
     * Returns configuration to merge with application configuration
     * @return array|\Traversable
     */
    public function getConfig()
    {
        return include  __DIR__ . '/config/module.config.php';
    }

    /**
     * @param MvcEvent $event
     */

    public function onBootstrap(MvcEvent $event)
    {
        $eventManager = $event->getApplication()->getEventManager();
        $eventManager->attach(new SendListener());
    }

}