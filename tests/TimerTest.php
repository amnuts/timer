<?php

namespace Amnuts\Tests;

use Amnuts\Datetime\Timer;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class TimerTest extends TestCase
{
    protected $dateTimePattern = '\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}';
    protected $durationPattern = '\d+d \d+h \d+m \d+\.\d+s';

    /**
     * @testdox Test that instantiating the class is OK
     */
    public function testConstruct()
    {
        $timer = new Timer();
        $this->assertInstanceOf(Timer::class, $timer);
    }

    /**
     * @testdox Check that the `stop` method returns object
     */
    public function testStopWorks()
    {
        $timer = (new Timer())->stop();
        $this->assertInstanceOf(Timer::class, $timer);
    }

    /**
     * @testdox Check that the `stop` method throws exception on failure
     */
    public function testStopThrowsException()
    {
        $timer = new Timer();

        // Cheating a little here!
        $class = new ReflectionClass($timer);
        $prop = $class->getProperty('started');
        $prop->setAccessible(true);
        $prop->setValue($timer, false);

        $this->expectException(\RuntimeException::class);
        $timer->stop();
    }

    /**
     * @testdox Check that the `mark` method returns object
     */
    public function testMarkWorks()
    {
        $timer = (new Timer())->mark();
        $this->assertInstanceOf(Timer::class, $timer);
    }

    /**
     * @testdox Check that the `mark` method throws exception on failure
     */
    public function testMarkThrowsException()
    {
        $timer = new Timer();

        // Cheating a little here, too!
        $class = new ReflectionClass($timer);
        $prop = $class->getProperty('started');
        $prop->setAccessible(true);
        $prop->setValue($timer, false);

        $this->expectException(\RuntimeException::class);
        $timer->mark();
    }

    /**
     * @testdox Test that __toString shows right thing while running
     */
    public function testToStringWhileRunning()
    {
        $timer = new Timer();
        $this->assertMatchesRegularExpression(
            "/^Started {$this->dateTimePattern}, current delta {$this->durationPattern}\$/",
            (string)$timer
        );

        // make sure markers bare no relevance
        $timer->mark()->mark()->mark();
        $this->assertMatchesRegularExpression(
            "/^Started {$this->dateTimePattern}, current delta {$this->durationPattern}\$/",
            (string)$timer
        );
    }

    /**
     * @testdox Test that __toString shows right thing when stopped
     */
    public function testToStringStopped()
    {
        $timer = (new Timer())->stop();
        $this->assertMatchesRegularExpression(
            "/^Started {$this->dateTimePattern}\nEnded {$this->dateTimePattern}, total time {$this->durationPattern}\$/",
            (string)$timer
        );
    }

    /**
     * @testdox Test that __toString shows right thing when using markers
     */
    public function testToStringMarker()
    {
        // without marker text
        $timer = (new Timer())->mark()->stop();
        $this->assertMatchesRegularExpression(
            "/^Started {$this->dateTimePattern}\n"
                . "	Δ {$this->durationPattern}\n"
                . "Ended {$this->dateTimePattern}, total time {$this->durationPattern}\$/m",
            (string)$timer
        );

        // with marker text
        $timer = (new Timer())->mark('test marker')->stop();
        $this->assertMatchesRegularExpression(
            "/^Started {$this->dateTimePattern}\n"
                . "	Δ {$this->durationPattern} \(test marker\)\n"
                . "Ended {$this->dateTimePattern}, total time {$this->durationPattern}\$/m",
            (string)$timer
        );

        // multiple markers without marker text
        $timer = (new Timer())->mark()->mark()->mark()->stop();
        $this->assertMatchesRegularExpression(
            "/^Started {$this->dateTimePattern}\n"
                . "	Δ {$this->durationPattern}\n"
                . "	Δ {$this->durationPattern}\n"
                . "	Δ {$this->durationPattern}\n"
                . "Ended {$this->dateTimePattern}, total time {$this->durationPattern}\$/m",
            (string)$timer
        );

        // with marker text
        $timer = (new Timer())
            ->mark('test marker 1')
            ->mark('test marker 2')
            ->mark('test marker 3')
            ->stop();
        $this->assertMatchesRegularExpression(
            "/^Started {$this->dateTimePattern}\n"
                . "	Δ {$this->durationPattern} \(test marker 1\)\n"
                . "	Δ {$this->durationPattern} \(test marker 2\)\n"
                . "	Δ {$this->durationPattern} \(test marker 3\)\n"
                . "Ended {$this->dateTimePattern}, total time {$this->durationPattern}\$/m",
            (string)$timer
        );
    }
}
