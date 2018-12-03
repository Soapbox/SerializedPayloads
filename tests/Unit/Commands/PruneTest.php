<?php

namespace SoapBox\SerializedPayloads\Tests\Commands;

use SoapBox\SerializedPayloads\Payload;
use SoapBox\SerializedPayloads\Tests\TestCase;

class PruneTest extends TestCase
{
    /**
     * @test
     */
    public function it_deletes_only_the_payloads_that_should_be_deleted()
    {
        $payload1 = factory(Payload::class)->states('recently-processed')->create();
        $payload2 = factory(Payload::class)->states('unprocessed')->create();
        $payload3 = factory(Payload::class)->states('should-delete')->create();

        $this->artisan('serialized-payloads:prune');

        $this->assertDatabaseHas('serialized_payloads', ['id' => $payload1->id]);
        $this->assertDatabaseHas('serialized_payloads', ['id' => $payload2->id]);
        $this->assertDatabaseMissing('serialized_payloads', ['id' => $payload3->id]);
    }

    /**
     * @test
     */
    public function it_deletes_all_the_payloads_that_should_be_deleted()
    {
        $payload1 = factory(Payload::class)->states('should-delete')->create();
        $payload2 = factory(Payload::class)->states('should-delete')->create();

        $this->artisan('serialized-payloads:prune');

        $this->assertDatabaseMissing('serialized_payloads', ['id' => $payload1->id]);
        $this->assertDatabaseMissing('serialized_payloads', ['id' => $payload2->id]);
    }
}
