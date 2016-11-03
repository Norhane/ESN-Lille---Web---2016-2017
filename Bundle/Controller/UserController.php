<?php

namespace ESNInscriptionBundle\Controller;

use ESNInscriptionBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class UserController extends Controller
	{
		public function indexAction()
		{
			$content = $this->get('templating')->render('ESNInscriptionBundle:User:index.html.twig',array('name'=>$name));
			return new Response($content);
		}
		
		public function addAction(Request $request)
		{
			$user = new User();
			
			$formBuilder = $this->get('form.factory')->createBuilder(FormType::class,$user);
			
			$formBuilder
				->add('pseudo', TextType::class)
				->add('pwd', RepeatedType::class, array(
					'type' => PasswordType::class,
					'invalid_message' => 'The password fields must match.',
					'options' => array('attr' => array('class' => 'password-field')),
					'required' => true,
					'first_options'  => array('label' => 'Password'),
					'second_options' => array('label' => 'Repeat Password')))
				->add('mail', EmailType::class)
				->add('country', CountryType::class)
				->add('date', null,array( 'attr'=>array('style'=>'display:none;')))
				->add('save', SubmitType::class)
			;

			$form = $formBuilder->getForm();
			$form->get('date')->setData(new \DateTime());
			if ($request->isMethod('POST')) {
				$form->handleRequest($request);
				
				if ($form->isValid()){
						$em = $this->getDoctrine()->getManager();
						$em->persist($user);
						$em->flush();
						return $this->redirectToRoute('esn_inscription_homepage');
				}
			}
			
			return $this->render('ESNInscriptionBundle:User:add.html.twig', 
			array('form' => $form->createView(),));
		}
	}

?>