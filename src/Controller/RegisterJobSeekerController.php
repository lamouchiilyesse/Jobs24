<?php

namespace App\Controller;


use App\Entity\JobSeeker;
use App\Form\JobSeekerRegistrationType;
use App\UseCase\RegisterJobSeeker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class RegisterJobSeekerController extends  AbstractController
{
    private FormFactoryInterface $formFactory ;

    private RegisterJobSeeker $registerJobSeeker;
    private UrlGeneratorInterface $urlGenerator;

    private Environment $twig;
    public function __construct(
        FormFactoryInterface $formFactory,
        RegisterJobSeeker $registerJobSeeker,
        UrlGeneratorInterface $urlGenerator,
        Environment $twig
    ) {
        $this->formFactory = $formFactory;
        $this->registerJobSeeker = $registerJobSeeker;
        $this->urlGenerator = $urlGenerator;
        $this->twig = $twig;
    }
    /**
     * @param Request $request
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    #[Route('Register/Job-seeker', name: 'register_job_seeker', methods: ['GET','POST'])]
    public function __invoke(Request $request ) : Response
    {
        $jobSeeker = new JobSeeker();
        $form = $this->formFactory->create(JobSeekerRegistrationType::class, $jobSeeker) ;

        if (0 === strpos((string) $request->headers->get('Content-Type'), 'application/json')) {
            $data = json_decode($request->getContent(), true); // decode JSON
            $form->submit($data); // submit data to the form
        } else {
            $form->handleRequest($request); // normal POST form
        }


        if ($form->isSubmitted() && $form->isValid()) {
            $this->registerJobSeeker->execute($jobSeeker);
            // Add a temporary message for the next page
            $request->getSession()->getFlashBag()->set('success', 'Welcome aboard! Your account is ready.');
            return  new RedirectResponse($this->urlGenerator->generate('index'));
        }

        return  new Response($this->twig->render('UI/RegisterJobSeeker.html.twig', [
            'job_seeker_form' => $form->createView(),
        ]));


    }

//    public function success(): Response
//    {
//        return new Response($this->twig->render('register_job_seeker_success.html.twig'));
//    }

}

