<?php

namespace App\Modules\User\Http\Controllers;

use App\Core\Base\BaseController;
use App\Modules\User\Http\Requests\UserRequest;
use App\Modules\User\Services\UserService;
use Illuminate\Http\Request;

/**
 * User Controller - Example Module Controller
 * 
 * Demonstrates how to use the modular architecture
 * with BaseController and Service pattern.
 */
class UserController extends BaseController
{
    /**
     * The service instance.
     *
     * @var UserService
     */
    protected $service;

    /**
     * Create a new controller instance.
     *
     * @param UserService $service
     */
    public function __construct(UserService $service)
    {
        parent::__construct($service);
        
        $this->middleware('auth');
        $this->middleware('permission:user.view')->only(['index', 'show']);
        $this->middleware('permission:user.create')->only(['store', 'create']);
        $this->middleware('permission:user.update')->only(['update', 'edit']);
        $this->middleware('permission:user.delete')->only(['destroy']);
    }

    /**
     * Get middleware to apply.
     *
     * @return array
     */
    public function getMiddleware(): array
    {
        return [
            ['middleware' => 'auth', 'options' => []],
            ['middleware' => 'verified', 'options' => []],
        ];
    }

    /**
     * Display a listing of the users.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $users = $this->service->paginate(
            relations: ['roles', 'schoolUnit'],
            perPage: $request->get('per_page', 15)
        );

        return $this->respondWithPagination($users);
    }

    /**
     * Store a newly created user.
     *
     * @param UserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UserRequest $request)
    {
        $user = $this->service->create($request->validated());

        return $this->respondCreated($user, 'User created successfully');
    }

    /**
     * Display the specified user.
     *
     * @param int|string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $user = $this->service->findWith($id, ['roles', 'schoolUnit']);

        if (!$user) {
            return $this->respondNotFound('User not found');
        }

        return $this->respond($user);
    }

    /**
     * Update the specified user.
     *
     * @param UserRequest $request
     * @param int|string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UserRequest $request, $id)
    {
        $user = $this->service->update($id, $request->validated());

        return $this->respondSuccess($user, 'User updated successfully');
    }

    /**
     * Remove the specified user.
     *
     * @param int|string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->service->delete($id);

        return $this->respondDeleted();
    }
}
