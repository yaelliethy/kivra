<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PaginatedRequest;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Exceptions\UserException;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Responses\ApiResponse;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Resources\Users\UserLookupResource;
class UserController extends Controller
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(PaginatedRequest $request)
    {
        try{
            $users = $this->userRepository->filter($request->validated());
            return ApiResponse::success(UserLookupResource::collection($users));
        }
        catch(UserException $e){
            return $e->render($request);
        }
        catch(\Exception $e){
            Log::error($e->getMessage());
            return ApiResponse::error("An error occurred while getting users");
        }
    }

    public function store(StoreUserRequest $request){
        try{
            $user = $this->userRepository->create($request->validated());
            return ApiResponse::success(new UserLookupResource($user));
        }
        catch(UserException $e){
            return $e->render($request);
        }
        catch(\Exception $e){
            Log::error($e->getMessage());
            return ApiResponse::error("An error occurred while creating user");
        }
    }
    public function show(Request $request, String $id)
    {
        try{
            $user = $this->userRepository->getById($id);
            return ApiResponse::success(new UserLookupResource($user));
        }
        catch(UserException $e){
            return $e->render($request);
        }
        catch(\Exception $e){
            Log::error($e->getMessage());
            return ApiResponse::error("An error occurred while getting user");
        }
    }

    public function update(UpdateUserRequest $request, String $id)
    {
        try{
            if($id === null){
                $user = $this->userRepository->update($request->validated(), $request->user());
            }
            else{
                $user = $this->userRepository->update($request->validated(), $this->userRepository->getById($id));
            }
            return ApiResponse::success(new UserLookupResource($user));
        }
        catch(UserException $e){
            return $e->render($request);
        }
        catch(\Exception $e){
            Log::error($e->getMessage());
            return ApiResponse::error("An error occurred while updating user");
        }
    }

    public function updatePassword(UpdatePasswordRequest $request, String $id)
    {
        try{
            $user = $this->userRepository->updatePassword($request->validated(), $id);
            return ApiResponse::success(new UserLookupResource($user));
        }
        catch(UserException $e){
            return $e->render($request);
        }
        catch(\Exception $e){
            Log::error($e->getMessage());
            return ApiResponse::error("An error occurred while updating password");
        }
    }

    public function destroy(Request $request, String $id)
    {
        try{
            $this->userRepository->delete($id);
            return ApiResponse::success([]);
        }
        catch(UserException $e){
            return $e->render($request);
        }
        catch(\Exception $e){
            Log::error($e->getMessage());
            return ApiResponse::error("An error occurred while deleting user");
        }
    }
}
