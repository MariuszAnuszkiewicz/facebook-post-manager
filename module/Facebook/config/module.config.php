<?php
namespace Facebook;

use Zend\Mvc\Router\Http\Literal;
use Zend\Mvc\Router\Http\Segment;

return array(
    'router' => array(
        'routes' => array(
            'facebook' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/facebook[/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => [
                        'controller' => 'Facebook\Controller\Index',
                        'action'     => 'index',
                    ],
                ),
            ),
            /*
            'may_terminate' => true,
            'child_routes' => [
                'get' => [
                    'type' => 'method',
                    'options' => [
                        'verb' => 'get',
                        'defaults' => [
                            'action' => 'index',
                        ],
                    ],
                ],
            ],
           */
            'sendRedirect' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/facebook/send',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'send',
                    ],
                ],
            ],
            'deleteRedirect' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/facebook/delete',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'delete',
                    ],
                ],
            ],
            'editRedirect' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/facebook/edit',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'edit',
                    ],
                ],
            ],
            'delete' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/facebook[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9_]+'
                    ],
                    'defaults' => [
                        'controller' => 'Facebook\Controller\Index',
                        'action'     => 'index',
                    ],
                ],
            ],
            'edit' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/facebook[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9_]+'
                    ],
                    'defaults' => [
                        'controller' => 'Facebook\Controller\Index',
                        'action'     => 'index',
                    ],
                ],
            ],
        ),
    ),

    'controllers' => array(
        'invokables' => [
            'Facebook\Controller\Index' => Controller\IndexController::class,
        ],
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/facebook_layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);