<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Http\Resources\Orders\OrderResource;
use App\Http\Requests\PaginatedRequest;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\Request;
use App\Exceptions\UserException;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    protected OrderRepositoryInterface $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(PaginatedRequest $request)
    {
        try{
            $user = $request->user();
            $orders = $this->orderRepository->filter($request->validated(), $user);
            return ApiResponse::success(OrderResource::collection($orders));
        }
        catch(UserException $e){
            return $e->render($request);
        }
        catch(\Exception $e){
            Log::error($e->getMessage());
            return ApiResponse::error("An error occurred while fetching orders");
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        try{
            $order = $this->orderRepository->create($request->validated());
            return ApiResponse::success(new OrderResource($order));
        }
        catch(UserException $e){
            return $e->render($request);
        }
        catch(\Exception $e){
            Log::error($e->getMessage());
            return ApiResponse::error("An error occurred while creating order");
        }
    }
}
