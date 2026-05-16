<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Recruiter;
use App\Form\RecruiterRegistrationType;
use App\UseCase\RegisterRecruiter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class RegisterRecruiterController extends AbstractController
{
    private  FormFactoryInterface $formFactory;

    private UrlGeneratorInterface $urlGenerator;

    private Environment $twig;

    private RegisterRecruiter $RegisterRecruiter;

    public function __construct(
        FormFactoryInterface $formFactory,
        UrlGeneratorInterface $urlGenerator,
        Environment $twig,
        RegisterRecruiter $RegisterRecruiter
    ) {
        $this->formFactory = $formFactory;
        $this->urlGenerator = $urlGenerator;
        $this->twig = $twig;
        $this->RegisterRecruiter = $RegisterRecruiter;
    }
    /**
     * @return Response
     */
    #[Route('/Register/Recruiter', name: 'register_recruiter', methods: ['GET','POST'])]
    public function __invoke(Request $request) : Response
    {
        $recruiter = new Recruiter();
        $form = $this->formFactory->create(RecruiterRegistrationType::class, $recruiter);

        if (0 === strpos((string) $request->headers->get('Content-Type'), 'application/json')) {
            $data = json_decode($request->getContent(), true); // decode JSON
            $form->submit($data); // submit data to the form
        } else {
                $form->handleRequest($request); // normal POST form
                }

            if ($form->isSubmitted() && $form->isValid()) {
               $this->RegisterRecruiter->execute($recruiter);
                $request->getSession()->getFlashBag()->set('success', 'Welcome aboard! Your account is ready.');
                return  new RedirectResponse($this->urlGenerator->generate('index'));
            }
            return new Response($this->twig->render(   "UI/RegisterRecruiter.html.twig", [
                'recruiter_form' => $form->createView(),
            ]));
    }
}
