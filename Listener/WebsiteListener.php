<?php
/**
 * Created by PhpStorm.
 * User: panos
 * Date: 7/4/14
 * Time: 4:02 PM
 */

namespace Icap\WebsiteBundle\Listener;

use Icap\WebsiteBundle\Entity\Website;
use Icap\WebsiteBundle\Form\WebsiteType;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

use Claroline\CoreBundle\Event\CreateFormResourceEvent;
use Claroline\CoreBundle\Event\CreateResourceEvent;
use Claroline\CoreBundle\Event\DeleteResourceEvent;
use Claroline\CoreBundle\Event\OpenResourceEvent;
use Claroline\CoreBundle\Event\CopyResourceEvent;
use Claroline\CoreBundle\Event\LogCreateDelegateViewEvent;

class WebsiteListener extends ContainerAware{
    public function onCreateForm(CreateFormResourceEvent $event)
    {
        $form = $this->container->get('form.factory')->create(new WebsiteType(), new Website());
        $content = $this->container->get('templating')->render(
            'ClarolineCoreBundle:Resource:createForm.html.twig',
            array(
                'form' => $form->createView(),
                'resourceType' => 'icap_website'
            )
        );
        $event->setResponseContent($content);
        $event->stopPropagation();
    }
    public function onCreate(CreateResourceEvent $event)
    {
        $request = $this->container->get('request');
        $form = $this->container->get('form.factory')->create(new WebsiteType(), new Website());
        $form->handleRequest($request);
        if ($form->isValid()) {
            $website = $form->getData();
            $event->setResources(array($website));
        } else {
            $content = $this->container->get('templating')->render(
                'ClarolineCoreBundle:Resource:createForm.html.twig',
                array(
                    'form' => $form->createView(),
                    'resourceType' => 'icap_website'
                )
            );
            $event->setErrorFormContent($content);
        }
        $event->stopPropagation();
    }
    public function onOpen(OpenResourceEvent $event)
    {
        $route = $this->container
            ->get('router')
            ->generate(
                'icap_website_view',
                array('websiteId' => $event->getResource()->getId())
            );
        $event->setResponse(new RedirectResponse($route));
        $event->stopPropagation();
    }
    public function onDelete(DeleteResourceEvent $event)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $em->remove($event->getResource());
        $em->flush();
        $event->stopPropagation();
    }
    public function onCopy(CopyResourceEvent $event)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        /*$wiki = $event->getResource();
        $oldRoot = $wiki->getRoot();
        $user = $this->container->get('security.context')->getToken()->getUser();
        $sectionRepository = $em->getRepository('IcapWikiBundle:Section');
        $sections = $sectionRepository->children($oldRoot);
        $newSectionsMap = array();
        $newWiki = new Wiki();
        $newWiki->setWikiCreator($user);
        $em->persist($newWiki);
        $em->flush($newWiki);
        $newRoot = $newWiki->getRoot();
        $newRoot->getActiveContribution()->setText($oldRoot->getActiveContribution()->getText());
        $newSectionsMap[$oldRoot->getId()] = $newRoot;
        foreach ($sections as $section) {
            $newSection = new Section();
            $newSection->setWiki($newWiki);
            $newSection->setVisible($section->getVisible());
            $newSectionParent = $newSectionsMap[$section->getParent()->getId()];
            $newSection->setParent($newSectionParent);
            $newSection->setAuthor($user);
            $activeContribution = new Contribution();
            $activeContribution->setTitle($section->getActiveContribution()->getTitle());
            $activeContribution->setText($section->getActiveContribution()->getText());
            $activeContribution->setSection($newSection);
            $activeContribution->setContributor($user);
            $newSection->setActiveContribution($activeContribution);
            $newSectionsMap[$section->getId()] = $newSection;
            $sectionRepository->persistAsLastChildOf($newSection, $newSectionParent);
        }
        $event->setCopy($newWiki);*/
        $event->stopPropagation();
    }
} 