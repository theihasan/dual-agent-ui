<?php

namespace Ihasan\DualAgentUI\Services;

class IssueService
{
    /**
     * Get all issues with optional filtering
     */
    public function getIssues(array $filters = []): array
    {
        $issues = $this->getMockIssues();

        // Apply filters
        if (! empty($filters['status'])) {
            $issues = array_filter($issues, function ($issue) use ($filters) {
                return $issue['status'] === $filters['status'];
            });
        }

        if (! empty($filters['type'])) {
            $issues = array_filter($issues, function ($issue) use ($filters) {
                return $issue['type'] === $filters['type'];
            });
        }

        if (! empty($filters['assigned_to'])) {
            $issues = array_filter($issues, function ($issue) use ($filters) {
                return $issue['assigned_to'] === $filters['assigned_to'];
            });
        }

        if (! empty($filters['search'])) {
            $search = strtolower($filters['search']);
            $issues = array_filter($issues, function ($issue) use ($search) {
                return strpos(strtolower($issue['title']), $search) !== false ||
                       strpos(strtolower($issue['description']), $search) !== false;
            });
        }

        // Sort by priority and last updated
        usort($issues, function ($a, $b) {
            $priorityOrder = ['critical' => 4, 'high' => 3, 'medium' => 2, 'low' => 1];
            $aPriority = $priorityOrder[$a['priority']] ?? 0;
            $bPriority = $priorityOrder[$b['priority']] ?? 0;

            if ($aPriority === $bPriority) {
                return strtotime($b['updated_at']) - strtotime($a['updated_at']);
            }

            return $bPriority - $aPriority;
        });

        return array_values($issues);
    }

    /**
     * Get issue by ID
     */
    public function getIssueById(int $id): ?array
    {
        $issues = $this->getMockIssues();

        foreach ($issues as $issue) {
            if ($issue['id'] === $id) {
                return $issue;
            }
        }

        return null;
    }

    /**
     * Get issue statistics
     */
    public function getIssueStats(): array
    {
        $issues = $this->getMockIssues();

        $stats = [
            'total' => count($issues),
            'open' => 0,
            'unassigned' => 0,
            'resolved' => 0,
            'ignored' => 0,
            'by_type' => [],
            'by_priority' => [],
        ];

        foreach ($issues as $issue) {
            $stats[$issue['status']]++;

            if (empty($issue['assigned_to'])) {
                $stats['unassigned']++;
            }

            $stats['by_type'][$issue['type']] = ($stats['by_type'][$issue['type']] ?? 0) + 1;
            $stats['by_priority'][$issue['priority']] = ($stats['by_priority'][$issue['priority']] ?? 0) + 1;
        }

        return $stats;
    }

    /**
     * Mock issue data
     */
    private function getMockIssues(): array
    {
        return [
            [
                'id' => 10,
                'title' => 'Illuminate\Queue\MaxAttemptsExceededException: App\Jobs\ProcessPayment has been attempted too many times',
                'description' => 'Payment processing job is failing repeatedly due to timeout issues. This affects user experience and payment completion rates.',
                'type' => 'EXCEPTION',
                'status' => 'open',
                'priority' => 'critical',
                'environment' => 'production',
                'assigned_to' => null,
                'reporter' => 'system@example.com',
                'first_seen' => '2024-08-27 10:30:00',
                'last_seen' => '2024-10-01 14:22:00',
                'updated_at' => '2024-10-01 14:22:00',
                'created_at' => '2024-08-27 10:30:00',
                'tags' => ['payment', 'queue', 'timeout'],
                'occurrence_count' => 127,
                'affected_users' => 45,
            ],
            [
                'id' => 20,
                'title' => 'Illuminate\Database\UniqueConstraintViolationException: Duplicate email registration',
                'description' => 'Users are encountering duplicate email errors during registration, preventing account creation.',
                'type' => 'EXCEPTION',
                'status' => 'open',
                'priority' => 'high',
                'environment' => 'production',
                'assigned_to' => 'john.doe@example.com',
                'reporter' => 'support@example.com',
                'first_seen' => '2024-09-30 09:15:00',
                'last_seen' => '2024-10-01 13:45:00',
                'updated_at' => '2024-10-01 13:45:00',
                'created_at' => '2024-09-30 09:15:00',
                'tags' => ['registration', 'database', 'email'],
                'occurrence_count' => 45,
                'affected_users' => 23,
            ],
            [
                'id' => 19,
                'title' => 'ErrorException: File not found during cleanup process',
                'description' => 'Temporary files are being deleted before the cleanup process can access them, causing errors.',
                'type' => 'EXCEPTION',
                'status' => 'open',
                'priority' => 'medium',
                'environment' => 'production',
                'assigned_to' => null,
                'reporter' => 'system@example.com',
                'first_seen' => '2024-09-30 11:20:00',
                'last_seen' => '2024-10-01 12:30:00',
                'updated_at' => '2024-10-01 12:30:00',
                'created_at' => '2024-09-30 11:20:00',
                'tags' => ['file-system', 'cleanup', 'storage'],
                'occurrence_count' => 23,
                'affected_users' => 8,
            ],
            [
                'id' => 15,
                'title' => 'Exception: Scheduled backup command failed',
                'description' => 'Daily backup process is failing, potentially causing data loss risk.',
                'type' => 'EXCEPTION',
                'status' => 'open',
                'priority' => 'high',
                'environment' => 'production',
                'assigned_to' => 'ops@example.com',
                'reporter' => 'system@example.com',
                'first_seen' => '2024-09-28 03:00:00',
                'last_seen' => '2024-10-01 03:00:00',
                'updated_at' => '2024-10-01 03:00:00',
                'created_at' => '2024-09-28 03:00:00',
                'tags' => ['backup', 'scheduled-job', 'data-safety'],
                'occurrence_count' => 4,
                'affected_users' => 0,
            ],
            [
                'id' => 9,
                'title' => 'Symfony\Component\Mailer\Exception\TransportException: Email delivery timeout',
                'description' => 'Email notifications are timing out, affecting user communication.',
                'type' => 'EXCEPTION',
                'status' => 'resolved',
                'priority' => 'medium',
                'environment' => 'production',
                'assigned_to' => 'admin@example.com',
                'reporter' => 'monitoring@example.com',
                'first_seen' => '2024-08-15 08:45:00',
                'last_seen' => '2024-09-15 16:20:00',
                'updated_at' => '2024-09-16 10:00:00',
                'created_at' => '2024-08-15 08:45:00',
                'tags' => ['email', 'smtp', 'timeout'],
                'occurrence_count' => 89,
                'affected_users' => 67,
            ],
            [
                'id' => 18,
                'title' => 'RuntimeException: Batch job processing error',
                'description' => 'Notification processing jobs are failing due to missing batch context.',
                'type' => 'EXCEPTION',
                'status' => 'open',
                'priority' => 'medium',
                'environment' => 'production',
                'assigned_to' => null,
                'reporter' => 'system@example.com',
                'first_seen' => '2024-08-28 12:00:00',
                'last_seen' => '2024-09-28 18:45:00',
                'updated_at' => '2024-09-28 18:45:00',
                'created_at' => '2024-08-28 12:00:00',
                'tags' => ['batch-processing', 'notifications', 'queue'],
                'occurrence_count' => 156,
                'affected_users' => 78,
            ],
            [
                'id' => 17,
                'title' => 'Symfony\Component\ErrorHandler\Error\FatalError: Type error in array handling',
                'description' => 'Fatal error occurring in middleware when processing null arrays.',
                'type' => 'EXCEPTION',
                'status' => 'ignored',
                'priority' => 'low',
                'environment' => 'staging',
                'assigned_to' => 'dev@example.com',
                'reporter' => 'qa@example.com',
                'first_seen' => '2024-09-27 14:30:00',
                'last_seen' => '2024-09-27 15:15:00',
                'updated_at' => '2024-09-27 16:00:00',
                'created_at' => '2024-09-27 14:30:00',
                'tags' => ['middleware', 'type-error', 'staging'],
                'occurrence_count' => 12,
                'affected_users' => 3,
            ],
            [
                'id' => 14,
                'title' => 'RuntimeException: Email notification batch processing failed',
                'description' => 'Similar to other batch processing errors but specifically for email notifications.',
                'type' => 'EXCEPTION',
                'status' => 'open',
                'priority' => 'medium',
                'environment' => 'production',
                'assigned_to' => null,
                'reporter' => 'system@example.com',
                'first_seen' => '2024-08-28 12:30:00',
                'last_seen' => '2024-09-28 19:00:00',
                'updated_at' => '2024-09-28 19:00:00',
                'created_at' => '2024-08-28 12:30:00',
                'tags' => ['email', 'batch-processing', 'notifications'],
                'occurrence_count' => 134,
                'affected_users' => 89,
            ],
            [
                'id' => 16,
                'title' => 'Error: Missing model class in job controller',
                'description' => 'JobController is referencing a non-existent model class causing runtime errors.',
                'type' => 'EXCEPTION',
                'status' => 'open',
                'priority' => 'high',
                'environment' => 'production',
                'assigned_to' => 'backend@example.com',
                'reporter' => 'system@example.com',
                'first_seen' => '2024-09-26 10:15:00',
                'last_seen' => '2024-09-26 16:30:00',
                'updated_at' => '2024-09-26 16:30:00',
                'created_at' => '2024-09-26 10:15:00',
                'tags' => ['model', 'controller', 'class-not-found'],
                'occurrence_count' => 67,
                'affected_users' => 34,
            ],
            [
                'id' => 4,
                'title' => 'InvalidArgumentException: Livewire component not found',
                'description' => 'Frontend is trying to load a Livewire component that doesn\'t exist.',
                'type' => 'EXCEPTION',
                'status' => 'resolved',
                'priority' => 'low',
                'environment' => 'production',
                'assigned_to' => 'frontend@example.com',
                'reporter' => 'user@example.com',
                'first_seen' => '2024-08-20 10:15:00',
                'last_seen' => '2024-08-22 09:30:00',
                'updated_at' => '2024-08-23 14:00:00',
                'created_at' => '2024-08-20 10:15:00',
                'tags' => ['livewire', 'frontend', 'component'],
                'occurrence_count' => 8,
                'affected_users' => 5,
            ],
            [
                'id' => 13,
                'title' => 'Exception: Queue worker command timeout',
                'description' => 'Queue workers are timing out during long-running processes.',
                'type' => 'EXCEPTION',
                'status' => 'open',
                'priority' => 'high',
                'environment' => 'production',
                'assigned_to' => 'ops@example.com',
                'reporter' => 'monitoring@example.com',
                'first_seen' => '2024-09-25 08:00:00',
                'last_seen' => '2024-09-30 20:45:00',
                'updated_at' => '2024-09-30 20:45:00',
                'created_at' => '2024-09-25 08:00:00',
                'tags' => ['queue', 'worker', 'timeout'],
                'occurrence_count' => 23,
                'affected_users' => 0,
            ],
        ];
    }
}
