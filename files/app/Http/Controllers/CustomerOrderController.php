<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerOrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with(['branch', 'items.food', 'items.extras'])
            ->latest()
            ->paginate(10);

        return Inertia::render('Customer/Orders/Index', [
            'orders' => $orders
        ]);
    }

    public function show(Order $order)
    {
        // Ensure the order belongs to the authenticated user
        // if ($order->user_id !== auth()->id()) {
        //     abort(403);
        // }

        $order->load([
            'branch',
            'items.food',
            'items.extras.extraOption',
        ]);

        return Inertia::render('Customer/Orders/Show', [
            'order' => $order,
            'statusTimeline' => $order->getStatusTimeline(),
        ]);
    }
}
