<?php

namespace Ihasan\DualAgentUI\Services;

class ExceptionService
{
    /**
     * Get all exceptions with optional filtering
     */
    public function getExceptions(array $filters = []): array
    {
        $exceptions = $this->getMockExceptions();

        // Apply filters
        if (!empty($filters['status'])) {
            $exceptions = array_filter($exceptions, function ($exception) use ($filters) {
                return $exception['status'] === $filters['status'];
            });
        }

        if (!empty($filters['environment'])) {
            $exceptions = array_filter($exceptions, function ($exception) use ($filters) {
                return $exception['environment'] === $filters['environment'];
            });
        }

        if (!empty($filters['search'])) {
            $search = strtolower($filters['search']);
            $exceptions = array_filter($exceptions, function ($exception) use ($search) {
                return strpos(strtolower($exception['message']), $search) !== false ||
                       strpos(strtolower($exception['class']), $search) !== false;
            });
        }

        // Sort by most recent first
        usort($exceptions, function ($a, $b) {
            return strtotime($b['last_seen']) - strtotime($a['last_seen']);
        });

        return array_values($exceptions);
    }

    /**
     * Get exception by ID
     */
    public function getExceptionById(int $id): ?array
    {
        $exceptions = $this->getMockExceptions();
        
        foreach ($exceptions as $exception) {
            if ($exception['id'] === $id) {
                return $exception;
            }
        }

        return null;
    }

    /**
     * Get exception statistics
     */
    public function getExceptionStats(): array
    {
        $exceptions = $this->getMockExceptions();
        
        $stats = [
            'total' => count($exceptions),
            'open' => 0,
            'resolved' => 0,
            'ignored' => 0,
            'environments' => [],
            'most_common' => []
        ];

        $classCount = [];
        
        foreach ($exceptions as $exception) {
            $stats[$exception['status']]++;
            
            if (!in_array($exception['environment'], $stats['environments'])) {
                $stats['environments'][] = $exception['environment'];
            }
            
            $class = $exception['class'];
            $classCount[$class] = ($classCount[$class] ?? 0) + 1;
        }

        // Get most common exceptions
        arsort($classCount);
        $stats['most_common'] = array_slice($classCount, 0, 5, true);

        return $stats;
    }

    /**
     * Mock exception data
     */
    private function getMockExceptions(): array
    {
        return [
            [
                'id' => 1,
                'class' => 'Illuminate\Queue\MaxAttemptsExceededException',
                'message' => 'App\Jobs\ProcessPayment has been attempted too many times or run too long. The job may have previously timed out.',
                'file' => '/var/www/html/app/Jobs/ProcessPayment.php',
                'line' => 45,
                'environment' => 'production',
                'status' => 'open',
                'count' => 127,
                'first_seen' => '2024-08-27 10:30:00',
                'last_seen' => '2024-10-01 14:22:00',
                'assigned_to' => null,
                'stack_trace' => 'Full stack trace would be here...',
                'context' => [
                    'user_id' => 1234,
                    'payment_id' => 'pay_abc123',
                    'amount' => 99.99
                ]
            ],
            [
                'id' => 2,
                'class' => 'Illuminate\Database\UniqueConstraintViolationException',
                'message' => 'SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry \'user@example.com\' for key \'users_email_unique\'',
                'file' => '/var/www/html/app/Http/Controllers/UserController.php',
                'line' => 89,
                'environment' => 'production',
                'status' => 'open',
                'count' => 45,
                'first_seen' => '2024-09-30 09:15:00',
                'last_seen' => '2024-10-01 13:45:00',
                'assigned_to' => 'john.doe@example.com',
                'stack_trace' => 'Full stack trace would be here...',
                'context' => [
                    'email' => 'user@example.com',
                    'registration_source' => 'web'
                ]
            ],
            [
                'id' => 3,
                'class' => 'ErrorException',
                'message' => 'unlink(/var/www/html/storage/temp/file_12345.pdf): No such file or directory',
                'file' => '/var/www/html/app/Services/FileService.php',
                'line' => 156,
                'environment' => 'production',
                'status' => 'open',
                'count' => 23,
                'first_seen' => '2024-09-30 11:20:00',
                'last_seen' => '2024-10-01 12:30:00',
                'assigned_to' => null,
                'stack_trace' => 'Full stack trace would be here...',
                'context' => [
                    'file_path' => '/var/www/html/storage/temp/file_12345.pdf',
                    'operation' => 'cleanup'
                ]
            ],
            [
                'id' => 4,
                'class' => 'Symfony\Component\Mailer\Exception\TransportException',
                'message' => 'Unable to send email: cURL error 28: Operation timed out after 30001 milliseconds',
                'file' => '/var/www/html/app/Mail/WelcomeEmail.php',
                'line' => 67,
                'environment' => 'production',
                'status' => 'resolved',
                'count' => 89,
                'first_seen' => '2024-08-15 08:45:00',
                'last_seen' => '2024-09-15 16:20:00',
                'assigned_to' => 'admin@example.com',
                'stack_trace' => 'Full stack trace would be here...',
                'context' => [
                    'recipient' => 'user@example.com',
                    'email_type' => 'welcome'
                ]
            ],
            [
                'id' => 5,
                'class' => 'RuntimeException',
                'message' => 'Attempted to batch job [App\Jobs\ProcessNotification] but no batch was found',
                'file' => '/var/www/html/app/Jobs/ProcessNotification.php',
                'line' => 34,
                'environment' => 'production',
                'status' => 'open',
                'count' => 156,
                'first_seen' => '2024-08-28 12:00:00',
                'last_seen' => '2024-09-28 18:45:00',
                'assigned_to' => null,
                'stack_trace' => 'Full stack trace would be here...',
                'context' => [
                    'batch_id' => 'batch_xyz789',
                    'job_class' => 'App\Jobs\ProcessNotification'
                ]
            ],
            [
                'id' => 6,
                'class' => 'Symfony\Component\ErrorHandler\Error\FatalError',
                'message' => 'Uncaught TypeError: array_merge(): Argument #2 must be of type array, null given',
                'file' => '/var/www/html/app/Http/Middleware/CorsMiddleware.php',
                'line' => 23,
                'environment' => 'staging',
                'status' => 'ignored',
                'count' => 12,
                'first_seen' => '2024-09-27 14:30:00',
                'last_seen' => '2024-09-27 15:15:00',
                'assigned_to' => 'dev@example.com',
                'stack_trace' => 'Full stack trace would be here...',
                'context' => [
                    'headers' => null,
                    'request_path' => '/api/v1/users'
                ]
            ],
            [
                'id' => 7,
                'class' => 'InvalidArgumentException',
                'message' => 'Unable to locate a class or view for component [livewire.user-table]',
                'file' => '/var/www/html/resources/views/dashboard.blade.php',
                'line' => 45,
                'environment' => 'production',
                'status' => 'resolved',
                'count' => 8,
                'first_seen' => '2024-08-20 10:15:00',
                'last_seen' => '2024-08-22 09:30:00',
                'assigned_to' => 'frontend@example.com',
                'stack_trace' => 'Full stack trace would be here...',
                'context' => [
                    'component' => 'livewire.user-table',
                    'view_path' => 'dashboard'
                ]
            ],
            [
                'id' => 8,
                'class' => 'PDOException',
                'message' => 'SQLSTATE[HY000] [2002] Connection refused',
                'file' => '/var/www/html/config/database.php',
                'line' => 67,
                'environment' => 'production',
                'status' => 'open',
                'count' => 234,
                'first_seen' => '2024-09-25 03:20:00',
                'last_seen' => '2024-10-01 07:45:00',
                'assigned_to' => 'ops@example.com',
                'stack_trace' => 'Full stack trace would be here...',
                'context' => [
                    'host' => 'localhost',
                    'database' => 'production_db'
                ]
            ]
        ];
    }
}