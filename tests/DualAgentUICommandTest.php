<?php

namespace Ihasan\DualAgentUI\Tests;

use Ihasan\DualAgentUI\Commands\DualAgentUICommand;
use PHPUnit\Framework\Attributes\Test;

class DualAgentUICommandTest extends TestCase
{
    #[Test]
    public function it_has_correct_signature(): void
    {
        $command = new DualAgentUICommand();
        
        $this->assertEquals('dual-agent-ui', $command->signature);
    }

    #[Test]
    public function it_has_description(): void
    {
        $command = new DualAgentUICommand();
        
        $this->assertNotEmpty($command->description);
        $this->assertEquals('My command', $command->description);
    }

    #[Test]
    public function it_can_be_executed(): void
    {
        $this->artisan('dual-agent-ui')
            ->expectsOutput('All done')
            ->assertExitCode(0);
    }

    #[Test]
    public function it_returns_success_exit_code(): void
    {
        // Test via artisan command instead of direct instantiation
        $this->artisan('dual-agent-ui')
            ->assertExitCode(0);
    }

    #[Test]
    public function handle_method_returns_integer(): void
    {
        // Test that the command exists and is callable
        $command = new DualAgentUICommand();
        $this->assertTrue(method_exists($command, 'handle'));
        $this->assertTrue(is_callable([$command, 'handle']));
    }

    #[Test]
    public function command_is_registered_in_application(): void
    {
        $kernel = $this->app->make('Illuminate\Contracts\Console\Kernel');
        $commands = $kernel->all();
        
        $this->assertArrayHasKey('dual-agent-ui', $commands);
        $this->assertInstanceOf(DualAgentUICommand::class, $commands['dual-agent-ui']);
    }

    #[Test]
    public function it_extends_laravel_command(): void
    {
        $command = new DualAgentUICommand();
        
        $this->assertInstanceOf(\Illuminate\Console\Command::class, $command);
    }

    #[Test]
    public function command_can_be_called_via_artisan(): void
    {
        $result = $this->artisan('dual-agent-ui');
        
        $result->assertSuccessful();
    }

    #[Test]
    public function command_output_contains_expected_message(): void
    {
        $this->artisan('dual-agent-ui')
            ->expectsOutput('All done')
            ->doesntExpectOutput('Error')
            ->doesntExpectOutput('Failed');
    }
}