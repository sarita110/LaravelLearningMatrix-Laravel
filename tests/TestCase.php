<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

/**
 * Base test case for all Feature and Integration tests.
 * Unit tests in tests/Unit/ extend PHPUnit\Framework\TestCase directly
 * because they don't need the full Laravel application to be booted.
 */
abstract class TestCase extends BaseTestCase {}
