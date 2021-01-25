<?php

namespace Drupal\cnt_user\Controller;

use Drupal\cas\Service\CasHelper;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Routing\TrustedRedirectResponse;
use Drupal\Core\Url;
use Drupal\Core\Utility\UnroutedUrlAssemblerInterface;
use Drupal\user\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Redirect controller.
 */
class RedirectController extends ControllerBase {

  /**
   * The unrouted URL assembler service.
   *
   * @var \Drupal\Core\Utility\UnroutedUrlAssemblerInterface
   */
  protected $unroutedUrlAssembler;

  /**
   * The configuration factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * CAS Helper object.
   *
   * @var \Drupal\cas\Service\CasHelper
   */
  protected $casHelper;

  /**
   * Request.
   *
   * @var \Symfony\Component\HttpFoundation\Request
   */
  protected $request;

  /**
   * Redirects to eulogin pages.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The route match.
   * @param \Drupal\cas\Service\CasHelper $cas_helper
   *   The CAS Helper service.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   The configuration factory.
   * @param \Drupal\Core\Utility\UnroutedUrlAssemblerInterface $url_assembler
   *   The unrouted URL assembler service.
   */
  public function __construct(Request $request, CasHelper $cas_helper, ConfigFactoryInterface $configFactory, UnroutedUrlAssemblerInterface $url_assembler) {
    $this->request = $request;
    $this->casHelper = $cas_helper;
    $this->configFactory = $configFactory;
    $this->unroutedUrlAssembler = $url_assembler;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('request_stack')->getCurrentRequest(),
      $container->get('cas.helper'),
      $container->get('config.factory'),
      $container->get('unrouted_url_assembler')
    );
  }

  /**
   * Privacy policy acceptance.
   */
  public function postLoginRedirect(User $user) {
    // Check if the user has just been created.
    // If so, redirect to user edit page.
    $user->load($this->currentUser()->id());
    if ($user->getCreatedTime() == $user->getLastLoginTime()) {
      $response = $this->redirect('entity.user.edit_form', ['user' => $this->currentUser()->id()]);
    }
    else {
      $response = $this->redirect('cnt_user.canonical', ['user' => $this->currentUser()->id()]);
    }
    return $response;
  }

  /**
   * Redirect to eulogin.
   */
  public function login() {
    $parameters = [];
    if (!empty($this->request->query->get('destination'))) {
      $parameters['returnto'] = $this->request->query->get('destination');
    }
    $response = $this->redirect('cas.login', $parameters);
    $response->send();
    return $response;
  }

  /**
   * Redirects a user to the CAS path for registering.
   *
   * @return \Drupal\Core\Routing\TrustedRedirectResponse
   *   The response object.
   */
  public function register(): TrustedRedirectResponse {
    // @todo remove the logic once
    // https://github.com/openeuropa/oe_authentication/issues/117 fixed.
    if ($this->currentUser()->isAuthenticated()) {
      throw new AccessDeniedHttpException();
    }

    $url = $this->getRegisterUrl()->toString();

    $response = new TrustedRedirectResponse($url);

    $cache = new CacheableMetadata();
    $cache->addCacheContexts(['user.roles:anonymous']);
    $response->addCacheableDependency($cache);
    $response->send();
    return $response;
  }

  /**
   * Converts the passed in destination into an absolute URL.
   *
   * @param string $destination
   *   The path for the destination. In case it starts with a slash it should
   *   have the base path included already.
   *
   * @return string
   *   The destination as absolute URL.
   */
  protected function getDestinationAsAbsoluteUrl($destination) {
    if (!UrlHelper::isExternal($destination)) {
      // The destination query parameter can be a relative URL in the sense of
      // not including the scheme and host, but its path is expected to be
      // absolute (start with a '/'). For such a case, prepend the scheme and
      // host, because the 'Location' header must be absolute.
      if (strpos($destination, '/') === 0) {
        $destination = $this->request->getSchemeAndHttpHost() . $destination;
      }
      else {
        // Legacy destination query parameters can be internal paths that have
        // not yet been converted to URLs.
        $destination = UrlHelper::parse($destination);
        $uri = 'base:' . $destination['path'];
        $options = [
          'query' => $destination['query'],
          'fragment' => $destination['fragment'],
          'absolute' => TRUE,
        ];
        // Treat this as if it's user input of a path relative to the site's
        // base URL.
        $destination = $this->unroutedUrlAssembler->assemble($uri, $options);
      }
    }
    return $destination;
  }

  /**
   * Get the register URL.
   *
   * @return \Drupal\Core\Url
   *   The register URL.
   */
  public function getRegisterUrl(): Url {
    $config = $this->configFactory->get('oe_authentication.settings');
    $base_url = $this->casHelper->getServerBaseUrl();
    $path = $config->get('register_path');

    $destination = $this->request->query->get('destination');
    if (!empty($destination)) {
      $destination_path = $this->getDestinationAsAbsoluteUrl($destination);
      // Remove destination parameter to avoid redirect.
      $this->request->query->remove('destination');
    }
    else {
      $service = Url::fromRoute('<front>');
      $service->setAbsolute();
      // We need to ensure we are collecting the cache metadata so it doesn't
      // bubble up to the render context or we get a Logic exception later
      // when we return a Cacheable response.
      $service = $service->toString(TRUE);
      $destination_path = $service->getGeneratedUrl();
    }

    return Url::fromUri($base_url . $path, ['query' => ['service' => $destination_path]]);
  }

}
