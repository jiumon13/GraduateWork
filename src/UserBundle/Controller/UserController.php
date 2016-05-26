<?php

namespace UserBundle\Controller;

use CommonBundle\Controller\Controller;
use CommonBundle\Form\Handler\ApiRequestHandler;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use UserBundle\Entity\User;
use UserBundle\Event\RegistrationEvent;
use UserBundle\Model\AuthorizationToken;

/**
 * Class UserController
 */
class UserController extends Controller
{
    /**
     * @Route(path="/api/v1/login")
     * @Method({"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function loginAction(Request $request)
    {
        $form = $this->createForm('login');
        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var AuthorizationToken $authorization */
            $authorization = $form->getData();
            $user = $authorization->getUser();
            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->get('security.token_storage')->setToken($token);
            $this->get('session')->set('_security_user', serialize($token));
        }

        return new JsonResponse([
            'success' => $form->isValid(),
            'errors' => $this->getFormErrors($form)
        ]);
    }

    /**
     * @Route(path="/api/v1/users", methods={"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function registerAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm('user', $user);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $password = $user->getPlainPassword();
            $this->get('fos_user.user_manager')->updateUser($user, true);
            $user->setPlainPassword($password);
            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->get('security.token_storage')->setToken($token);
            $this->get('session')->set('_security_user', serialize($token));

            $event = new RegistrationEvent($user);
            $dispatcher = new EventDispatcher();
            $dispatcher->dispatch(RegistrationEvent::NAME, $event);
        }

        return new JsonResponse([
            'success' => $form->isValid(),
            'errors' => $this->getFormErrors($form)
        ]);
    }

    /**
     * @Route(name="user_logout", path="/user/logout", methods={"GET"})
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function logout(Request $request)
    {
        if ($this->isGranted('ROLE_USER')) {
            $this->get('security.token_storage')->setToken(null);
            $request->getSession()->remove('_security_user');
        }

        return $this->redirectToRoute('common_homepage');
    }
}
