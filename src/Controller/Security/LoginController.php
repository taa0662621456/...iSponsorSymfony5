<?php


namespace App\Controller\Security;


use App\Form\Vendor\VendorLoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginController extends AbstractController
{
    use TargetPathTrait;

    /**
     * @var RouterInterface
     */
    private RouterInterface $router;

    /**
     * LoginController constructor.
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }


    /**
     * @Route("/signin", defaults={"layout" : "signin"}, name="signin", options={"layout" : "signinFormHomePage"},
     *     methods={"GET", "POST"})
     * @Route("/login", defaults={"layout" : "login"}, name="login", options={"layout" : "loginFormHomePage"},
     *     methods={"GET", "POST"})
     *
     * @param Request $request
     * @param Security $security
     * @param AuthenticationUtils $authenticationUtils
     * @param string $layout
     *
     * @return Response
     */
    public function login(Request $request, Security $security, AuthenticationUtils $authenticationUtils, string $layout = 'login'): Response
    {
        if (!$security->isGranted('IS_AUTHENTICATED_ANONYMOUSLY')) {
            return $this->redirectToRoute('homepage');
        }

        $this->saveTargetPath($request->getSession(), 'main', $this->generateUrl('homepage'));

        $loginType = $this->get('form.factory')->createNamed('', VendorLoginType::class, [
            '_username' => $authenticationUtils->getLastUsername()], [
            'action' => $this->router->generate('login')]);

        return $this->render(
            'security/' . $layout . '.html.twig', [
                'last_username' => $authenticationUtils->getLastUsername(),
                'form'          => $loginType->createView(),
                'error'         => $authenticationUtils->getLastAuthenticationError()
            ]
        );
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(): void
    {
        throw new \RuntimeException('This should never be reached!');
//		throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall!');
    }

    /**
     * @Route("/login/json", name="login_json", methods={"POST"})
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function jsonLogin(Request $request): JsonResponse
    {
        $user = $this->getUser();

        return $this->json(
            array(
                'username' => $user->getUserIdentifier(),
                'roles'    => $user->getRoles(),
            )
        );
    }

}