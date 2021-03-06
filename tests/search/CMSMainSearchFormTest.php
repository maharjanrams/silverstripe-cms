<?php

use SilverStripe\Dev\FunctionalTest;

class CMSMainSearchFormTest extends FunctionalTest
{

    protected static $fixture_file = '../controller/CMSMainTest.yml';

    public function testTitleFilter()
    {
        $this->session()->inst_set('loggedInAs', $this->idFromFixture('SilverStripe\\Security\\Member', 'admin'));

        $response = $this->get(
            'admin/pages/SearchForm/?' .
            http_build_query(array(
                'q' => array(
                    'Title' => 'Page 10',
                    'FilterClass' => 'SilverStripe\\CMS\\Controllers\\CMSSiteTreeFilter_Search',
                ),
                'action_doSearch' => true
            ))
        );

        $titles = $this->getPageTitles();
        $this->assertEquals(count($titles), 1);
        // For some reason the title gets split into two lines

        $this->assertContains('Page 1', $titles[0]);
    }

    protected function getPageTitles()
    {
        $titles = array();
        $links = $this->cssParser()->getBySelector('li.class-Page a');
        if ($links) {
            foreach ($links as $link) {
                $titles[] = preg_replace('/\n/', ' ', $link->asXML());
            }
        }
        return $titles;
    }
}
