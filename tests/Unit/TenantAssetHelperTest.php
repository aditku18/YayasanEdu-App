<?php

namespace Tests\Unit;

use Tests\TestCase;

class TenantAssetHelperTest extends TestCase
{
    public function test_strips_storage_prefix()
    {
        // Ensure the helper removes the leading "storage/" segment and that
        // the final URL corresponds to the tenancy asset route with the
        // cleaned path.
        $url1 = tenant_asset('storage/foo.png');
        $this->assertStringEndsWith('/foo.png', parse_url($url1, PHP_URL_PATH));
        $this->assertStringNotContainsString('storage/storage', $url1);
        $this->assertStringContainsString(url('/tenancy/assets'), $url1);

        $url2 = tenant_asset('/storage/foo.png');
        $this->assertStringEndsWith('/foo.png', parse_url($url2, PHP_URL_PATH));
        $this->assertStringNotContainsString('storage/storage', $url2);
        $this->assertStringContainsString(url('/tenancy/assets'), $url2);
    }

    public function test_leaves_other_paths_intact()
    {
        $url = tenant_asset('bar.png');
        $this->assertStringEndsWith('/bar.png', parse_url($url, PHP_URL_PATH));
        $this->assertStringContainsString(url('/tenancy/assets'), $url);

        $url2 = tenant_asset('baz/qux.jpg');
        $this->assertStringEndsWith('/baz/qux.jpg', parse_url($url2, PHP_URL_PATH));
        $this->assertStringContainsString(url('/tenancy/assets'), $url2);
    }
}
