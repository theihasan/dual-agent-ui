<?php

namespace Ihasan\DualAgentUI\Tests\Unit;

use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Ihasan\DualAgentUI\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class HandleInertiaRequestsTest extends TestCase
{
    private function getMiddlewareClass(): string
    {
        // Load the middleware class from stub
        $stubContent = file_get_contents(__DIR__ . '/../../stubs/HandleInertiaRequests.php');
        
        // Create a temporary file and eval it to test
        $tempFile = tempnam(sys_get_temp_dir(), 'middleware_test');
        file_put_contents($tempFile, $stubContent);
        
        require_once $tempFile;
        
        return \App\Http\Middleware\HandleInertiaRequests::class;
    }

    #[Test]
    public function middleware_stub_exists(): void
    {
        $stubPath = __DIR__ . '/../../stubs/HandleInertiaRequests.php';
        
        $this->assertFileExists($stubPath);
    }

    #[Test]
    public function middleware_stub_has_correct_namespace(): void
    {
        $stubContent = file_get_contents(__DIR__ . '/../../stubs/HandleInertiaRequests.php');
        
        $this->assertStringContainsString('namespace App\Http\Middleware;', $stubContent);
    }

    #[Test]
    public function middleware_stub_extends_inertia_middleware(): void
    {
        $stubContent = file_get_contents(__DIR__ . '/../../stubs/HandleInertiaRequests.php');
        
        $this->assertStringContainsString('use Inertia\Middleware;', $stubContent);
        $this->assertStringContainsString('extends Middleware', $stubContent);
    }

    #[Test]
    public function middleware_stub_has_root_view_property(): void
    {
        $stubContent = file_get_contents(__DIR__ . '/../../stubs/HandleInertiaRequests.php');
        
        $this->assertStringContainsString('protected $rootView = \'app\';', $stubContent);
    }

    #[Test]
    public function middleware_stub_has_version_method(): void
    {
        $stubContent = file_get_contents(__DIR__ . '/../../stubs/HandleInertiaRequests.php');
        
        $this->assertStringContainsString('public function version(Request $request): ?string', $stubContent);
        $this->assertStringContainsString('return parent::version($request);', $stubContent);
    }

    #[Test]
    public function middleware_stub_has_share_method_with_auth(): void
    {
        $stubContent = file_get_contents(__DIR__ . '/../../stubs/HandleInertiaRequests.php');
        
        $this->assertStringContainsString('public function share(Request $request): array', $stubContent);
        $this->assertStringContainsString('\'auth\' => [', $stubContent);
        $this->assertStringContainsString('\'user\' => $request->user(),', $stubContent);
    }

    #[Test]
    public function middleware_stub_shares_flash_messages(): void
    {
        $stubContent = file_get_contents(__DIR__ . '/../../stubs/HandleInertiaRequests.php');
        
        $this->assertStringContainsString('\'flash\' => [', $stubContent);
        $this->assertStringContainsString('\'success\' => $request->session()->get(\'success\'),', $stubContent);
        $this->assertStringContainsString('\'error\' => $request->session()->get(\'error\'),', $stubContent);
        $this->assertStringContainsString('\'warning\' => $request->session()->get(\'warning\'),', $stubContent);
        $this->assertStringContainsString('\'info\' => $request->session()->get(\'info\'),', $stubContent);
    }

    #[Test]
    public function middleware_stub_merges_with_parent_share(): void
    {
        $stubContent = file_get_contents(__DIR__ . '/../../stubs/HandleInertiaRequests.php');
        
        $this->assertStringContainsString('return array_merge(parent::share($request), [', $stubContent);
    }

    #[Test]
    public function middleware_stub_has_proper_php_opening_tag(): void
    {
        $stubContent = file_get_contents(__DIR__ . '/../../stubs/HandleInertiaRequests.php');
        
        $this->assertStringStartsWith('<?php', $stubContent);
    }

    #[Test]
    public function middleware_stub_has_correct_class_name(): void
    {
        $stubContent = file_get_contents(__DIR__ . '/../../stubs/HandleInertiaRequests.php');
        
        $this->assertStringContainsString('class HandleInertiaRequests extends Middleware', $stubContent);
    }

    #[Test]
    public function middleware_stub_imports_required_classes(): void
    {
        $stubContent = file_get_contents(__DIR__ . '/../../stubs/HandleInertiaRequests.php');
        
        $this->assertStringContainsString('use Illuminate\Http\Request;', $stubContent);
        $this->assertStringContainsString('use Inertia\Middleware;', $stubContent);
    }

    #[Test]
    public function middleware_stub_has_proper_docblocks(): void
    {
        $stubContent = file_get_contents(__DIR__ . '/../../stubs/HandleInertiaRequests.php');
        
        $this->assertStringContainsString('/**', $stubContent);
        $this->assertStringContainsString('* The root template', $stubContent);
        $this->assertStringContainsString('* @see https://inertiajs.com/', $stubContent);
        $this->assertStringContainsString('* @param', $stubContent);
        $this->assertStringContainsString('* @return', $stubContent);
    }

    #[Test]
    public function middleware_stub_has_valid_php_syntax(): void
    {
        $stubPath = __DIR__ . '/../../stubs/HandleInertiaRequests.php';
        
        // Use php -l to check syntax
        $output = shell_exec("php -l {$stubPath} 2>&1");
        
        $this->assertStringContainsString('No syntax errors detected', $output);
    }
}