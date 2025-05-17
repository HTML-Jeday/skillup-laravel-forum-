<?php

namespace Tests\Unit;

use App\Enums\TopicStatus;
use PHPUnit\Framework\TestCase;

class TopicStatusTest extends TestCase
{
    /**
     * Test that TopicStatus enum has correct values
     *
     * @return void
     */
    public function testTopicStatusValues()
    {
        $this->assertEquals(1, TopicStatus::OPENED->value);
        $this->assertEquals(0, TopicStatus::CLOSED->value);
    }

    /**
     * Test isOpened method
     *
     * @return void
     */
    public function testIsOpenedMethod()
    {
        $this->assertTrue(TopicStatus::OPENED->isOpened());
        $this->assertFalse(TopicStatus::CLOSED->isOpened());
    }

    /**
     * Test isClosed method
     *
     * @return void
     */
    public function testIsClosedMethod()
    {
        $this->assertTrue(TopicStatus::CLOSED->isClosed());
        $this->assertFalse(TopicStatus::OPENED->isClosed());
    }

    /**
     * Test values method returns all enum values
     *
     * @return void
     */
    public function testValuesMethod()
    {
        $values = TopicStatus::values();
        $this->assertCount(2, $values);
        $this->assertContains(1, $values);
        $this->assertContains(0, $values);
    }
}
