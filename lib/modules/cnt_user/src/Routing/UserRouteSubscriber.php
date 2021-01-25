<?php

namespace Drupal\cnt_user\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Drupal\Core\Routing\RoutingEvents;
use Symfony\Component\Routing\RouteCollection;

/**
 * Listens to the dynamic route events.
 */
class UserRouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[RoutingEvents::ALTER] = ['onAlterRoutes', -100];
    return $events;
  }

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {

    // Redirect all users to EU login page.
    if ($route = $collection->get('user.login')) {
      $route->setDefault('_controller', '\Drupal\cnt_user\Controller\RedirectController::login');
      $route->setOption('no_cache', TRUE);
    }

    if ($route = $collection->get('user.register')) {
      $route->setDefault('_controller', '\Drupal\cnt_user\Controller\RedirectController::register');
      $route->setOption('no_cache', TRUE);
    }

    if ($route = $collection->get('entity.user.canonical')) {
      $route->setDefault('_controller', '\Drupal\cnt_user\Controller\RedirectController::postLoginRedirect');
      $route->setOption('no_cache', TRUE);
    }
  }

}
