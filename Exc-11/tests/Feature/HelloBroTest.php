<?php

namespace Tests\Feature;

use Tests\TestCase;

class HelloBroTest extends TestCase
{
    public function test_halo_route_returns_expected_text(): void
    {
        $response = $this->get('/halo');

        $response->assertStatus(200);
        $response->assertSeeText('Halo, bro');
    }

    public function test_halo_juga_route_renders_blade_view(): void
    {
        $response = $this->get('/halo-juga');

        $response->assertStatus(200);
        $response->assertViewIs('halo_juga');
        $response->assertSeeText('halo juga bro');
        $response->assertDontSeeText('Ini adalah halaman Laravel pertamaku.');
    }
}
