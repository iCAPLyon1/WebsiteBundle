<?php
/**
 * Created by PhpStorm.
 * User: ptsavdar
 * Date: 16/03/16
 * Time: 16:23
 */

namespace Icap\WebsiteBundle\Tests;


use Claroline\CoreBundle\Library\Testing\TransactionalTestCase;
use Claroline\CoreBundle\Persistence\ObjectManager;
use Icap\WebsiteBundle\Entity\WebsitePageTypeEnum;
use Icap\WebsiteBundle\Manager\WebsitePageManager;
use Icap\WebsiteBundle\Testing\Persister;

class WebsitePageManagerTest extends TransactionalTestCase
{
    /** @var ObjectManager */
    private $om;
    /** @var Persister */
    private $persist;
    /** @var  WebsitePageManager */
    private $manager;

    private $websitePageParams;

    protected function setUp()
    {
        parent::setUp();
        $container = $this->client->getContainer();
        $this->manager = $container->get('icap.website.page.manager');
        $this->om = $container->get('claroline.persistence.object_manager');
        $this->persist = new Persister($this->om);
        $this->websitePageParams = array(
            'title'             => 'Test page',
            'type'              => WebsitePageTypeEnum::BLANK_PAGE,
            'description'       => 'Test description',
            'visible'           => true,
            'isSection'         => false,
            'richText'          => '<div>this is a test page</div>'
        );
    }

    public function testCreateAndDelete()
    {
        $repo = $this->om->getRepository('IcapWebsiteBundle:WebsitePage');
        $user = $this->persist->user('john');
        $website = $this->persist->website('Test Website', $user);
        $websitePage = $this->manager->createEmptyPage($website, $website->getRoot());
        $this->manager->processForm($website, $websitePage, $this->websitePageParams, "POST");
        $pages = $repo->findBy([], ['creationDate' => 'ASC']);
        $this->assertEquals(2, count($pages), 'Test WebsitePage creation');
        $this->assertEquals($websitePage, $pages[1], 'Verify correct WebsitePage was created');
        $this->manager->deletePage($pages[1]);
        $this->assertEquals(1, count($repo->findAll()));
    }

    public function testUpdate()
    {
        $repo = $this->om->getRepository('IcapWebsiteBundle:WebsitePage');
        $user = $this->persist->user('john');
        $website = $this->persist->website('Test Website', $user);
        $websitePage = $this->manager->createEmptyPage($website, $website->getRoot());
        $this->manager->processForm($website, $websitePage, $this->websitePageParams, "POST");
        $this->websitePageParams['title'] = "Test page modified";
        $this->manager->processForm($website, $websitePage, $this->websitePageParams, "PUT");
        $pages = $repo->findBy([], ['creationDate' => 'ASC']);
        $this->assertEquals($this->websitePageParams['title'], $pages[1]->getTitle(), 'Verify correct WebsitePage update');
    }

    public function testMove()
    {
        $repo = $this->om->getRepository('IcapWebsiteBundle:WebsitePage');
        $user = $this->persist->user('john');
        $website = $this->persist->website('Test Website', $user);
        $page1 = $this->manager->createEmptyPage($website, $website->getRoot());
        $page2 = $this->manager->createEmptyPage($website, $website->getRoot());
        $this->manager->processForm($website, $page1, $this->websitePageParams, "POST");
        $this->manager->processForm($website, $page2, $this->websitePageParams, "POST");
        $this->manager->handleMovePage($website, array('pageId' => $page2->getId(), 'newParentId' => $page1->getId()));
        $pages = $repo->findBy([], ['creationDate' => 'ASC']);
        $this->assertEquals($page1->getId(), $pages[2]->getParent()->getId(), 'Verify correct WebsitePage move');
    }

    public function testChangeHomepage()
    {
        $repo = $this->om->getRepository('IcapWebsiteBundle:WebsitePage');
        $websiteRepo = $this->om->getRepository('IcapWebsiteBundle:Website');
        $user = $this->persist->user('john');
        $website = $this->persist->website('Test Website', $user);
        $page1 = $this->manager->createEmptyPage($website, $website->getRoot());
        $page2 = $this->manager->createEmptyPage($website, $website->getRoot());
        $this->manager->processForm($website, $page1, $this->websitePageParams, "POST");
        $this->manager->processForm($website, $page2, $this->websitePageParams, "POST");
        $this->assertEquals($page1->getId(), $websiteRepo->find($website->getId())->getHomepage()->getId(), 'Test Website original homepage');
        $this->assertEquals(true, $repo->find($page1->getId())->getIsHomepage());
        $this->manager->changeHomepage($website, $page2);
        $this->assertEquals($page2->getId(), $websiteRepo->find($website->getId())->getHomePage()->getId(), 'Test Website homepage change');
        $pages = $repo->findBy([], ['creationDate' => 'ASC']);
        $this->assertEquals(false, $pages[1]->getIsHomepage());
        $this->assertEquals(true, $pages[2]->getIsHomepage());
    }
}