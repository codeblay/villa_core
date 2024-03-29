<?php

namespace Tests\Unit;

use App\Models\Seller;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_that_true_is_true(): void
    {
        dd(null instanceof Seller);
        $this->assertTrue(true);
    }
}
