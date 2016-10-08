<?php

namespace Ens\JobeetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Ens\JobeetBundle\Utils\Jobeet;

/**
* Category
*
* @ORM\Table(name="category")
* @ORM\Entity(repositoryClass="Ens\JobeetBundle\Repository\CategoryRepository")
*/
class Category
{
    /**
    * @var int
    *
    * @ORM\Column(name="id", type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;

    private $more_jobs;

    /**
    * @var string
    *
    * @ORM\Column(name="name", type="string", length=255, unique=true)
    */
    private $name;

    /**
    * @var string
    *
    * @ORM\Column(name="slug", type="string", length=255)
    */
    private $slug;

    /**
    * @var string
    *
    * @ORM\Column(name="active_jobs", type="string", length=255, nullable=true)
    */
    private $activeJobs;


    /**
    * @ORM\OneToMany(targetEntity="Job", mappedBy="category")
    *
    */
    private $jobs;


    /**
    * @ORM\OneToMany(targetEntity="CategoryAffiliate", mappedBy="category")
    */
    private $category_affiliates;


    /**
    * Get id
    *
    * @return integer
    */
    public function getId()
    {
        return $this->id;
    }

    /**
    * Set name
    *
    * @param string $name
    * @return Category
    */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
    * Get name
    *
    * @return string
    */
    public function getName()
    {
        return $this->name;
    }

    public function __construct()
    {
        $this->jobs = new ArrayCollection();
        $this->category_affiliates = new ArrayCollection();
    }

    /**
    * Add jobs
    *
    * @param \Ens\JobeetBundle\Entity\Job $jobs
    * @return Category
    */
    public function addJob(\Ens\JobeetBundle\Entity\Job $jobs)
    {
        $this->jobs[] = $jobs;

        return $this;
    }

    /**
    * Remove jobs
    *
    * @param \Ens\JobeetBundle\Entity\Job $jobs
    */
    public function removeJob(\Ens\JobeetBundle\Entity\Job $jobs)
    {
        $this->jobs->removeElement($jobs);
    }

    /**
    * Get jobs
    *
    * @return \Doctrine\Common\Collections\Collection
    */
    public function getJobs()
    {
        return $this->jobs;
    }

    public function setActiveJobs($jobs)
    {
        $this->active_jobs = $jobs;
    }

    public function getActiveJobs()
    {
        return $this->active_jobs;
    }

    public function setMoreJobs($jobs)
    {
        $this->more_jobs = $jobs >=  0 ? $jobs : 0;
    }

    public function getMoreJobs()
    {
        return $this->more_jobs;
    }

    /**
    * Add category_affiliates
    *
    * @param \Ens\JobeetBundle\Entity\CategoryAffiliate $categoryAffiliates
    * @return Category
    */
    public function addCategoryAffiliate(\Ens\JobeetBundle\Entity\CategoryAffiliate $categoryAffiliates)
    {
        $this->category_affiliates[] = $categoryAffiliates;

        return $this;
    }

    /**
    * Remove category_affiliates
    *
    * @param \Ens\JobeetBundle\Entity\CategoryAffiliate $categoryAffiliates
    */
    public function removeCategoryAffiliate(\Ens\JobeetBundle\Entity\CategoryAffiliate $categoryAffiliates)
    {
        $this->category_affiliates->removeElement($categoryAffiliates);
    }

    /**
    * Get category_affiliates
    *
    * @return \Doctrine\Common\Collections\Collection
    */
    public function getCategoryAffiliates()
    {
        return $this->category_affiliates;
    }

    /**
    * Get slug
    *
    * @return string
    */
    public function getSlug()
    {
        return $this->slug;
    }



    public function __toString()
    {
        return $this->getName();
    }

    /**
    * @ORM/PrePersist
    */
    public function setSlugValue()
    {
        $this->slug = Jobeet::slugify($this->getName());
    }




}
