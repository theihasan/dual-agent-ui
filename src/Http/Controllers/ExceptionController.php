<?php

namespace Ihasan\DualAgentUI\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Ihasan\DualAgentUI\Services\ExceptionService;

class ExceptionController extends Controller
{
    protected ExceptionService $exceptionService;

    public function __construct(ExceptionService $exceptionService)
    {
        $this->exceptionService = $exceptionService;
    }

    /**
     * Display the exceptions index page
     */
    public function index(Request $request): Response
    {
        $filters = [
            'status' => $request->get('status', 'open'),
            'environment' => $request->get('environment'),
            'search' => $request->get('search'),
        ];

        $exceptions = $this->exceptionService->getExceptions($filters);
        $stats = $this->exceptionService->getExceptionStats();

        return Inertia::render('Exceptions', [
            'exceptions' => $exceptions,
            'stats' => $stats,
            'filters' => $filters,
            'meta' => [
                'total_exceptions' => count($exceptions),
                'current_filter' => $filters['status'],
                'environments' => $stats['environments'],
            ]
        ]);
    }

    /**
     * Display a specific exception
     */
    public function show(Request $request, int $id): Response
    {
        $exception = $this->exceptionService->getExceptionById($id);

        if (!$exception) {
            abort(404, 'Exception not found');
        }

        return Inertia::render('ExceptionDetail', [
            'exception' => $exception,
            'breadcrumbs' => [
                ['label' => 'Exceptions', 'url' => route('dual-agent-ui.exceptions')],
                ['label' => "Exception #{$id}", 'url' => null]
            ]
        ]);
    }

    /**
     * Get exceptions data as JSON (for API calls)
     */
    public function data(Request $request)
    {
        $filters = [
            'status' => $request->get('status'),
            'environment' => $request->get('environment'),
            'search' => $request->get('search'),
        ];

        $exceptions = $this->exceptionService->getExceptions($filters);
        $stats = $this->exceptionService->getExceptionStats();

        return response()->json([
            'data' => $exceptions,
            'stats' => $stats,
            'meta' => [
                'total' => count($exceptions),
                'filtered' => count($exceptions),
            ]
        ]);
    }

    /**
     * Update exception status (assign, resolve, ignore)
     */
    public function updateStatus(Request $request, int $id)
    {
        $request->validate([
            'status' => 'required|in:open,resolved,ignored',
            'assigned_to' => 'nullable|email',
            'notes' => 'nullable|string|max:1000'
        ]);

        // In a real application, this would update the database
        // For now, we'll just return a success response
        
        return response()->json([
            'success' => true,
            'message' => 'Exception status updated successfully',
            'data' => [
                'id' => $id,
                'status' => $request->get('status'),
                'assigned_to' => $request->get('assigned_to'),
                'updated_at' => now()->toISOString()
            ]
        ]);
    }

    /**
     * Bulk operations on exceptions
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:resolve,ignore,assign,delete',
            'exception_ids' => 'required|array',
            'exception_ids.*' => 'integer',
            'assigned_to' => 'nullable|email',
        ]);

        $action = $request->get('action');
        $exceptionIds = $request->get('exception_ids');
        $assignedTo = $request->get('assigned_to');

        // In a real application, this would perform bulk operations on the database
        // For now, we'll just return a success response

        return response()->json([
            'success' => true,
            'message' => "Bulk {$action} operation completed successfully",
            'data' => [
                'action' => $action,
                'affected_count' => count($exceptionIds),
                'exception_ids' => $exceptionIds
            ]
        ]);
    }
}