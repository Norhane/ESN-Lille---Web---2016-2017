<?php

namespace ChallengesBundle\Controller;

use ChallengesBundle\Entity\SimpleForm;
use ChallengesBundle\Form\SimpleFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SimpleFormController extends Controller {

    public function indexAction(Request $request) {
        
        $simple_form = new SimpleForm();
        $form = $this->get('form.factory')->create(SimpleFormType::class, $simple_form);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($simple_form);
            $em->flush();

            return $this->redirectToRoute('challenges_view', array('id' => $simple_form->getId()));
        }

        return $this->render('ChallengesBundle:SimpleForm:index.html.twig', array(
                'form' => $form->createView()
        ));
    }

    public function viewAction($id) {
       
        $em = $this->getDoctrine()->getManager();
       
        $simple_form = $em->getRepository('ChallengesBundle:SimpleForm')->find($id);
        if (null === $simple_form) {
            throw new NotFoundHttpException("Le pseudo d'id ".$id." n'existe pas.");
        }

        return $this->render('ChallengesBundle:SimpleForm:view.html.twig', array(
                'simple_form' => $simple_form
        ));
    }
}
?>
