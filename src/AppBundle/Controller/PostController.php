<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Post;
use AppBundle\Form\PostType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class PostController extends Controller
{
    /**
     * @Route("/posts",name="posts.index")
     * @Method({"GET","POST"})
     */
    public function indexAction(Request $request)
    {
        $rebository = $this->getDoctrine()->getRepository("AppBundle:Post");
        $posts = $rebository->findAll();

        $post = new Post();

        $form = $this->createForm(PostType::class,$post);
        $form->add('Creer',SubmitType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $post->setCreatedAt(new \DateTime());
            $em->persist($post);
            $em->flush();
            return $this->redirectToRoute('posts.index');
        }



        return $this->render('AppBundle:Post:index.html.twig', array(
            'posts' => $posts,
            'form' => $form->createView(),
        ));
    }
    /**
     * @Route("/posts/edit/{id}",name="posts.edit")
     * @Method({"GET","POST"})
    */
    public function editAction(Request $request, Post $post)
    {
        $form = $this->createForm(PostType::class,$post);
        $form->add('Modifier',SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->getDoctrine()->getManager()->flush($post);
            return $this->redirectToRoute('posts.index');
        }

        return $this->render('AppBundle:Post:edit.html.twig',[
            'form' => $form->createView(),
            'post' => $post

        ]);
    }

}
