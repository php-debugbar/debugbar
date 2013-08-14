<?php

namespace DebugBar\Tests;

use DebugBar\DebugBar;
use DebugBar\Tests\DataCollector\MockCollector;

class DebugBarTest extends DebugBarTestCase
{
    public function setUp()
    {
        $this->debugbar = new DebugBar();
    }

    public function testAddCollector()
    {
        $this->debugbar->addCollector($c = new MockCollector());
        $this->assertTrue($this->debugbar->hasCollector('mock'));
        $this->assertEquals($c, $this->debugbar->getCollector('mock'));
        $this->assertContains($c, $this->debugbar->getCollectors());
    }

    /**
     * @expectedException \DebugBar\DebugBarException
     */
    public function testAddCollectorWithSameName()
    {
        $this->debugbar->addCollector(new MockCollector());
        $this->debugbar->addCollector(new MockCollector());
    }

    public function testCollect()
    {
        $data = array('foo' => 'bar');
        $this->debugbar->addCollector(new MockCollector($data));
        $datac = $this->debugbar->collect();

        $this->assertArrayHasKey('mock', $datac);
        $this->assertEquals($datac['mock'], $data);
        $this->assertEquals($datac, $this->debugbar->getData());
    }

    public function testArrayAccess()
    {
        $this->debugbar->addCollector($c = new MockCollector());
        $this->assertEquals($c, $this->debugbar['mock']);
        $this->assertTrue(isset($this->debugbar['mock']));
        $this->assertFalse(isset($this->debugbar['foo']));
    }
}