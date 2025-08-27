<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class AdminOrderController extends Controller
{
    public function index(Request $request)
{
    $rest_id = $request->user()->rest_id;

    $query = Order::with(['user', 'branch'])
        ->when($request->search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('delivery_address', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        })
        ->when($request->order_type, fn($q, $order_type) => $q->where('order_type', $order_type))
        ->when($request->status, fn($q, $status) => $q->where('status', $status))
        ->when($request->payment_status, fn($q, $payment_status) => $q->where('payment_status', $payment_status))
        ->when($request->date_range, function ($q, $date_range) {
            [$start, $end] = explode(' to ', $date_range);
            $q->whereBetween('created_at', [
                \Carbon\Carbon::parse($start)->startOfDay(),
                \Carbon\Carbon::parse($end)->endOfDay()
            ]);
        })
        ->when($rest_id, fn($q, $rest_id) => $q->where('branch_id', $rest_id))
        ->latest();

    $orders = $query->paginate(10)
        ->withQueryString()
        ->through(fn($order) => [
            'id' => $order->id,
            'user' => [
                'name' => $order->user->name,
                'email' => $order->user->email
            ],
            'branch' => [
                'name' => optional($order->branch)->name
            ],
            'total_amount' => $order->total_amount,
            'status' => $order->status,
            'payment_status' => $order->payment_status,
            'payment_method' => $order->payment_method,
            'created_at' => $order->created_at,
            'estimated_delivery_time' => $order->estimated_delivery_time,
            'order_type' => $order->order_type,
            'delivery_fee' => $order->delivery_fee,
        ]);

    return Inertia::render('Admin/Orders/Index', [
        'orders' => $orders,
        'filters' => $request->only(['search', 'order_type', 'status', 'payment_status', 'date_range']),
        'statuses' => Order::getStatuses(),
        'payment_statuses' => Order::getPaymentStatuses(),
        'order_types' => ['delivery', 'collection'],
    ]);
}

    public function show(Order $order)
    {
        $order->load([
            'branch',
            'items.package',
            'items.extras.extraOption',
        ]);

        $orderData = [
                'id' => $order->id,
                'user' => [
                    'name' => $order->user->name,
                    'email' => $order->user->email,
                    'phone' => $order->user->phone
                ],
                'branch' => [
                    'name' => $order->branch->name
                ],
                'items' => $order->items->map(fn($item) => [
                    'id' => $item->id,
                    'food' => [
                        'name' => $item->package->name,
                        'image' => $item->package->image_path ? Storage::url($item->package->image_path) : null
                    ],
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'subtotal' => $item->subtotal,
                    'order_type'=>$item->order_type,
                    'special_instructions' => $item->special_instructions,
                    'extras' => $item->extras->map(fn($extra) => [
                        'name' => $extra->extraOption->name,
                        'price' => $extra->extraOption->base_price
                    ])
                ]),
                'subtotal' => $order->subtotal,
                'delivery_fee' => $order->delivery_fee,
                'tax' => $order->tax,
                'total_amount' => $order->total_amount,
                'delivery_address' => $order->delivery_address,
                'delivery_notes' => $order->delivery_notes,
                'status' => $order->status,
                'payment_status' => $order->payment_status,
                'payment_method' => $order->payment_method,
                'estimated_delivery_time' => $order->estimated_delivery_time,
                'created_at' => $order->created_at
            ];

        return Inertia::render('Admin/Orders/Show', [
            'order' => $orderData
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => ['required', 'in:' . implode(',', Order::getStatuses())]
        ]);

        $order->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Order status updated successfully');
    }

    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => ['required', 'in:' . implode(',', Order::getPaymentStatuses())]
        ]);

        $order->update([
            'payment_status' => $request->payment_status
        ]);

        return back()->with('success', 'Payment status updated successfully');
    }
}
