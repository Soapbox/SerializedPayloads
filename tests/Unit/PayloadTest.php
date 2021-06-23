<?php

namespace SoapBox\SerializedPayloads\Tests\Unit;

use Carbon\Carbon;
use Illuminate\Http\Request;
use SoapBox\SerializedPayloads\Payload;
use Ramsey\Uuid\Lazy\LazyUuidFromString;
use SoapBox\SerializedPayloads\Tests\TestCase;

class PayloadTest extends TestCase
{
    /**
     * @test
     */
    public function a_payload_should_automatically_generate_a_uuid_id()
    {
        $payload = Payload::create(['data' => '{}']);

        $this->assertInstanceOf(LazyUuidFromString::class, $payload->getKey());
        $this->assertNotEmpty($payload->getKey());
        $this->assertFalse(is_numeric($payload->getKey()));
    }

    /**
     * @test
     */
    public function process_should_mark_the_processed_at_to_now()
    {
        Carbon::setTestNow(now());

        $payload = Payload::factory()->unprocessed()->create();

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

        Payload::factory()->recentlyProcessed()->create();
        Payload::factory()->unprocessed()->create();
        Payload::factory()->create(['processed_at' => now()->subDay()]);
        $shouldDelete = Payload::factory()->create(['processed_at' => now()->subDay()->subSecond()]);

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
