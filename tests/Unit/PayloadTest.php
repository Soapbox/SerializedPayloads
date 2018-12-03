<?php

namespace SoapBox\SerializedPayloads\Tests\Unit;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use SoapBox\SerializedPayloads\Payload;
use SoapBox\SerializedPayloads\Tests\TestCase;

class PayloadTest extends TestCase
{
    /**
     * @test
     */
    public function a_payload_should_automatically_generate_a_uuid_id()
    {
        $payload = Payload::create(['data' => '{}']);

        $this->assertInstanceOf(Uuid::class, $payload->getKey());
        $this->assertNotEmpty($payload->getKey());
        $this->assertFalse(is_numeric($payload->getKey()));
    }

    /**
     * @test
     */
    public function process_should_mark_the_processed_at_to_now()
    {
        Carbon::setTestNow(now());

        $payload = factory(Payload::class)->states('unprocessed')->create();

        $this->assertNull($payload->processed_at);

        $payload->process();
        $this->assertSame((string) now(), (string) $payload->processed_at);
    }

    /**
     * @test
     */
    public function scoping_by_should_delete_should_only_returns_payload_that_should_be_deleted()
    {
        Carbon::setTestNow(now());

        factory(Payload::class)->states('recently-processed')->create();
        factory(Payload::class)->states('unprocessed')->create();
        factory(Payload::class)->create(['processed_at' => now()->subDay()]);
        $shouldDelete = factory(Payload::class)->create(['processed_at' => now()->subDay()->subSecond()]);

        $payloads = Payload::shouldDelete()->get();

        $this->assertCount(1, $payloads);
        $this->assertSame((string) $shouldDelete->id, (string) $payloads->first()->id);
    }

    /**
     * @test
     */
    public function it_creates_a_payload_from_a_request()
    {
        $request = new Request(
            $query = [],
            $request = [],
            $attributes = [],
            $cookies = [],
            $files = [],
            $server = [],
            $content = '{"key":"value"}'
        );

        $payload = Payload::createFromRequest($request);

        $this->assertEquals('{"key":"value"}', $payload->fresh()->getData());
    }
}
