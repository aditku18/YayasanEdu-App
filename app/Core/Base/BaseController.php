<?php

namespace App\Core\Base;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;

/**
 * Base Controller - Parent class for all controllers in the application
 * 
 * Provides common methods for:
 * - Standardized JSON responses
 * - Error handling
 * - Resource loading
 */
abstract class BaseController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * The service instance for this controller
     *
     * @var object|null
     */
    protected $service;

    /**
     * Create a new controller instance.
     *
     * @param object|null $service
     */
    public function __construct($service = null)
    {
        $this->service = $service;
        
        // Apply middleware if needed for all controllers
        $this->middleware($this->getMiddleware());
    }

    /**
     * Get middleware to apply to this controller
     * Override in child controllers
     *
     * @return array
     */
    public function getMiddleware(): array
    {
        return [];
    }

    /**
     * Return a JSON response with success structure
     *
     * @param mixed $data
     * @param string|null $message
     * @param int $status
     * @return JsonResponse
     */
    protected function respond($data, ?string $message = null, int $status = 200): JsonResponse
    {
        $response = [
            'success' => in_array($status, [200, 201]),
            'message' => $message,
            'data' => $data,
        ];

        if ($status === 204) {
            return response()->json(null, $status);
        }

        return response()->json($response, $status);
    }

    /**
     * Return a success JSON response
     *
     * @param mixed $data
     * @param string|null $message
     * @param int $status
     * @return JsonResponse
     */
    protected function respondSuccess($data, ?string $message = 'Operation successful', int $status = 200): JsonResponse
    {
        return $this->respond($data, $message, $status);
    }

    /**
     * Return an error JSON response
     *
     * @param string $message
     * @param int $status
     * @param mixed $errors
     * @return JsonResponse
     */
    protected function respondError(string $message, int $status = 400, $errors = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $status);
    }

    /**
     * Return a validation error response
     *
     * @param mixed $errors
     * @param string|null $message
     * @return JsonResponse
     */
    protected function respondValidationError($errors, ?string $message = 'Validation failed'): JsonResponse
    {
        return $this->respondError($message, 422, $errors);
    }

    /**
     * Return a not found error response
     *
     * @param string $message
     * @return JsonResponse
     */
    protected function respondNotFound(string $message = 'Resource not found'): JsonResponse
    {
        return $this->respondError($message, 404);
    }

    /**
     * Return an unauthorized error response
     *
     * @param string $message
     * @return JsonResponse
     */
    protected function respondUnauthorized(string $message = 'Unauthorized'): JsonResponse
    {
        return $this->respondError($message, 401);
    }

    /**
     * Return a forbidden error response
     *
     * @param string $message
     * @return JsonResponse
     */
    protected function respondForbidden(string $message = 'Forbidden'): JsonResponse
    {
        return $this->respondError($message, 403);
    }

    /**
     * Return a created response
     *
     * @param mixed $data
     * @param string|null $message
     * @return JsonResponse
     */
    protected function respondCreated($data, ?string $message = 'Resource created successfully'): JsonResponse
    {
        return $this->respond($data, $message, 201);
    }

    /**
     * Return a deleted response
     *
     * @return JsonResponse
     */
    protected function respondDeleted(): JsonResponse
    {
        return $this->respond(null, 'Resource deleted successfully', 204);
    }

    /**
     * Paginate the results
     *
     * @param mixed $data
     * @return JsonResponse
     */
    protected function respondWithPagination($data): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data->items(),
            'pagination' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
                'has_more' => $data->hasMorePages(),
            ],
        ]);
    }

    /**
     * Load relationships for the model
     *
     * @param object $query
     * @param array|null $relations
     * @return object
     */
    protected function loadRelations($query, ?array $relations = null)
    {
        if ($relations && is_array($relations)) {
            return $query->with($relations);
        }
        
        return $query;
    }

    /**
     * Apply ordering to the query
     *
     * @param object $query
     * @param string|null $orderBy
     * @param string|null $orderDir
     * @return object
     */
    protected function applyOrdering($query, ?string $orderBy = null, ?string $orderDir = null)
    {
        if ($orderBy) {
            $direction = in_array(strtolower($orderDir), ['asc', 'desc']) ? $orderDir : 'asc';
            return $query->orderBy($orderBy, $direction);
        }
        
        return $query;
    }
}
