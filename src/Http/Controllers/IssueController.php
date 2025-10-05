<?php

namespace Ihasan\DualAgentUI\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Ihasan\DualAgentUI\Services\IssueService;

class IssueController extends Controller
{
    protected IssueService $issueService;

    public function __construct(IssueService $issueService)
    {
        $this->issueService = $issueService;
    }

    /**
     * Display the issues index page
     */
    public function index(Request $request): Response
    {
        $filters = [
            'status' => $request->get('status', 'open'),
            'type' => $request->get('type'),
            'assigned_to' => $request->get('assigned_to'),
            'search' => $request->get('search'),
        ];

        $issues = $this->issueService->getIssues($filters);
        $stats = $this->issueService->getIssueStats();

        // Calculate filter counts for tabs
        $filterCounts = [
            'open' => count($this->issueService->getIssues(['status' => 'open'])),
            'unassigned' => count($this->issueService->getIssues(['assigned_to' => null])),
            'resolved' => count($this->issueService->getIssues(['status' => 'resolved'])),
            'ignored' => count($this->issueService->getIssues(['status' => 'ignored'])),
        ];

        return Inertia::render('Issues', [
            'issues' => $issues,
            'stats' => $stats,
            'filters' => $filters,
            'filterCounts' => $filterCounts,
            'meta' => [
                'total_issues' => count($issues),
                'current_filter' => $filters['status'],
                'types' => array_keys($stats['by_type']),
                'priorities' => array_keys($stats['by_priority']),
            ]
        ]);
    }

    /**
     * Display a specific issue
     */
    public function show(Request $request, int $id): Response
    {
        $issue = $this->issueService->getIssueById($id);

        if (!$issue) {
            abort(404, 'Issue not found');
        }

        return Inertia::render('IssueDetail', [
            'issue' => $issue,
            'breadcrumbs' => [
                ['label' => 'Issues', 'url' => route('dual-agent-ui.issues')],
                ['label' => "Issue #{$id}", 'url' => null]
            ]
        ]);
    }

    /**
     * Get issues data as JSON (for API calls)
     */
    public function data(Request $request)
    {
        $filters = [
            'status' => $request->get('status'),
            'type' => $request->get('type'),
            'assigned_to' => $request->get('assigned_to'),
            'search' => $request->get('search'),
        ];

        $issues = $this->issueService->getIssues($filters);
        $stats = $this->issueService->getIssueStats();

        return response()->json([
            'data' => $issues,
            'stats' => $stats,
            'meta' => [
                'total' => count($issues),
                'filtered' => count($issues),
            ]
        ]);
    }

    /**
     * Create a new issue
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:EXCEPTION,PERFORMANCE,BUG,FEATURE',
            'priority' => 'required|in:low,medium,high,critical',
            'environment' => 'required|string',
            'assigned_to' => 'nullable|email',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50'
        ]);

        // In a real application, this would create the issue in the database
        // For now, we'll just return a success response

        $issueData = [
            'id' => rand(1000, 9999), // Generate random ID for demo
            'title' => $request->get('title'),
            'description' => $request->get('description'),
            'type' => $request->get('type'),
            'priority' => $request->get('priority'),
            'environment' => $request->get('environment'),
            'assigned_to' => $request->get('assigned_to'),
            'status' => 'open',
            'tags' => $request->get('tags', []),
            'created_at' => now()->toISOString(),
            'updated_at' => now()->toISOString(),
        ];

        return response()->json([
            'success' => true,
            'message' => 'Issue created successfully',
            'data' => $issueData
        ], 201);
    }

    /**
     * Update an existing issue
     */
    public function update(Request $request, int $id)
    {
        $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'status' => 'sometimes|in:open,resolved,ignored',
            'priority' => 'sometimes|in:low,medium,high,critical',
            'assigned_to' => 'nullable|email',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50'
        ]);

        // In a real application, this would update the issue in the database
        // For now, we'll just return a success response

        return response()->json([
            'success' => true,
            'message' => 'Issue updated successfully',
            'data' => [
                'id' => $id,
                'updated_at' => now()->toISOString(),
                ...$request->only(['title', 'description', 'status', 'priority', 'assigned_to', 'tags'])
            ]
        ]);
    }

    /**
     * Delete an issue
     */
    public function destroy(int $id)
    {
        // In a real application, this would delete the issue from the database
        // For now, we'll just return a success response

        return response()->json([
            'success' => true,
            'message' => 'Issue deleted successfully'
        ]);
    }

    /**
     * Bulk operations on issues
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:resolve,ignore,assign,delete,change_priority',
            'issue_ids' => 'required|array',
            'issue_ids.*' => 'integer',
            'assigned_to' => 'nullable|email',
            'priority' => 'nullable|in:low,medium,high,critical',
        ]);

        $action = $request->get('action');
        $issueIds = $request->get('issue_ids');

        // In a real application, this would perform bulk operations on the database
        // For now, we'll just return a success response

        return response()->json([
            'success' => true,
            'message' => "Bulk {$action} operation completed successfully",
            'data' => [
                'action' => $action,
                'affected_count' => count($issueIds),
                'issue_ids' => $issueIds
            ]
        ]);
    }
}