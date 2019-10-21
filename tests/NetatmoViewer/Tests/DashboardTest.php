<?php

namespace NetatmoViewer\Tests;

use Silex\WebTestCase;

class DashboardTest extends WebTestCase
{
    public function createApplication()
    {
        return require __DIR__ . '/../../../src/app.php';
    }

    public function testErroringDashboard()
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertEquals(500, $client->getResponse()->getStatusCode());
        $this->assertContains('¯\_(ツ)_/¯', $client->getResponse()->getContent());
    }
}
