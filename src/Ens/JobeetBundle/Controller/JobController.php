<?php

namespace Ens\JobeetBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Ens\JobeetBundle\Entity\Job;
use Ens\JobeetBundle\Form\JobType;

/**
* Job controller.
*
*/
class JobController extends Controller
{
    /**
    * Lists all Job entities.
    *
    */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $categories = $em->getRepository('EnsJobeetBundle:Category')->getWithJobs();

        foreach($categories as $category)
        {
            $category->setActiveJobs($em->getRepository('EnsJobeetBundle:Job')->findByActiveJobs($category->getId(), $this->container->getParameter('max_jobs_on_homepage')));
            $category->setMoreJobs($em->getRepository('EnsJobeetBundle:Job')->countActiveJobs($category->getId()) - $this->container->getParameter('max_jobs_on_homepage'));
        }

        return $this->render(':Job:index.html.twig', array(
            'categories' => $categories
        ));
    }

    /**
    * Creates a new Job entity.
    *
    */
    public function newAction()
    {
      $entity  = new Job();
      $request = $this->getRequest();
      $form    = $this->createForm(new JobType(), $entity);
      $form->bind($request);

      if ($form->isValid()) {
        $em = $this->getDoctrine()->getEntityManager();

        $entity->file->move(__DIR__.'/../../../../web/uploads/jobs', $entity->file->getClientOriginalName());
        $entity->setLogo($entity->file->getClientOriginalName());

        $em->persist($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('ens_job_show', array(
          'company' => $entity->getCompanySlug(),
          'location' => $entity->getLocationSlug(),
          'id' => $entity->getId(),
          'position' => $entity->getPositionSlug()
        )));
      }

      return $this->render(':Job:new.html.twig', array(
        'job' => $entity,
        'form'   => $form->createView()
      ));
    }

    /**
    * Finds and displays a Job entity.
    *
    */
    public function showAction(Job $job)
    {
        $deleteForm = $this->createDeleteForm($job);

        return $this->render('job/show.html.twig', array(
            'job' => $job,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Displays a form to edit an existing Job entity.
    *
    */
    public function editAction(Request $request, Job $job)
    {
        $deleteForm = $this->createDeleteForm($job);
        $editForm = $this->createForm('Ens\JobeetBundle\Form\JobType', $job);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($job);
            $em->flush();

            return $this->redirectToRoute('ens_job_edit', array('id' => $job->getId()));
        }

        return $this->render('job/edit.html.twig', array(
            'job' => $job,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Deletes a Job entity.
    *
    */
    public function deleteAction(Request $request, Job $job)
    {
        $form = $this->createDeleteForm($job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($job);
            $em->flush();
        }

        return $this->redirectToRoute('ens_job_index');
    }

    /**
    * Creates a form to delete a Job entity.
    *
    * @param Job $job The Job entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createDeleteForm(Job $job)
    {
        return $this->createFormBuilder()
        ->setAction($this->generateUrl('ens_job_delete', array('id' => $job->getId())))
        ->setMethod('DELETE')
        ->getForm()
        ;
    }
}
