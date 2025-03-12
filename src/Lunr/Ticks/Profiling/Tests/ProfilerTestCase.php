<?php

/**
 * This file contains the ProfilerTestCase class.
 *
 * SPDX-FileCopyrightText: Copyright 2024 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Ticks\Profiling\Tests;

use Lunr\Halo\LunrBaseTestCase;
use Lunr\Ticks\EventLogging\EventInterface;
use Lunr\Ticks\Profiling\Profiler;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;

/**
 * This class contains common setup routines, providers
 * and shared attributes for testing the Profiler class.
 *
 * @covers Lunr\Ticks\Profiling\Profiler
 */
abstract class ProfilerTestCase extends LunrBaseTestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * Mock instance of an Event
     * @var EventInterface&MockInterface
     */
    protected EventInterface&MockInterface $event;

    /**
     * Instance of the tested class.
     * @var Profiler
     */
    protected Profiler $class;

    /**
     * Mock value of the start timestamp.
     * @var float
     */
    protected float $startTimestamp = 1734352683.3516;

    /**
     * Testcase Constructor.
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->event = Mockery::mock(EventInterface::class);

        $floatval  = 1734352683.3516;
        $stringval = '0.35160200 1734352683';

        $this->mockFunction('microtime', fn(bool $float) => $float ? $floatval : $stringval);

        $this->class = new Profiler($this->event);

        // Unmock here instead of tearDown() because we have another microtime call in the record()
        // method that needs a different mock.
        $this->unmockFunction('microtime');

        parent::baseSetUp($this->class);
    }

    /**
     * Testcase Destructor.
     */
    public function tearDown(): void
    {
        parent::tearDown();

        $this->event->shouldReceive('addFields')
                    ->once();

        $this->event->shouldReceive('addTags')
                    ->once();

        $this->event->shouldReceive('setUuidValue')
                    ->zeroOrMoreTimes();

        $this->event->shouldReceive('recordTimestamp')
                    ->once();

        $this->event->shouldReceive('record')
                    ->once();

        unset($this->event);
        unset($this->class);
    }

}

?>
