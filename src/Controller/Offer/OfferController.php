<?php

namespace App\Controller\Offer;

use App\Entity\Offer;
use App\Entity\Recruiter;
use App\Entity\User;
use App\Form\OfferType;
use App\UseCase\PublishOffer;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Twig\Environment;

class OfferController
{
    private FormFactoryInterface $formFactory;
    private Security $security;
    private UrlGeneratorInterface $urlGenerator;
    private Environment $twig;
    private PublishOffer $publishOffer;

    /**
     * @param FormFactoryInterface $formFactory
     * @param Security $seccurity
     * @param UrlGeneratorInterface $urlGenerator
     * @param Environment $twig
     * @param PublishOffer $publishOffer
     */
    public function __construct(FormFactoryInterface $formFactory, Security $security, UrlGeneratorInterface $urlGenerator, Environment $twig, PublishOffer $publishOffer)
    {
        $this->formFactory = $formFactory;
        $this->security = $security;
        $this->urlGenerator = $urlGenerator;
        $this->twig = $twig;
        $this->publishOffer = $publishOffer;
    }

    public function __invoke(Request $request): Response
    {
        /** @var User $user */
        $user = $this->security->getUser();
        
        if ($user === null) {
            return new RedirectResponse($this->urlGenerator->generate('app_login'));
        }

        // Safety check: only logged-in recruiters can be here
        if (!$user instanceof Recruiter) {
            throw new AccessDeniedException('Only recruiters can publish offers.');
        }

        $offer = new Offer();

        // 2. Assign the recruiter to the offer BEFORE handling the form or UseCase
        $offer->setRecruiter($user);

        $form = $this->formFactory->create(OfferType::class, $offer);
        $form->handleRequest($request);
        /** @var FlashBagInterface $flashBag */
        $flashBag = $request->getSession()->getBag('flashes');
        if($form->isSubmitted() && $form->isValid()){
            try {
                $this->publishOffer->execute($offer);
                $flashBag->add('success', 'Offer published!');
                return new RedirectResponse($this->urlGenerator->generate('app_offer_index:'));
            }
            catch (\InvalidArgumentException $e) {
                $flashBag->add('error', $e->getMessage());
            }
        }
        return new Response(
            $this->twig->render('UI/Offer/Publish.html.twig', [
                'form' => $form->createView(),
        ])
        );
    }
}