<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemExtra;
use App\Models\ExtraOption;
use App\Models\UserAddress;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    public function checkout(Branch $branch)
    {
        // Validate that the branch exists and is active
        if (!$branch || !$branch->is_active) {
            return redirect()->route('home')->with('error', 'Invalid branch selected.');
        }

        // Store branch in session
        session(['selected_branch' => $branch]);

        return Inertia::render('Customer/Checkout', [
            'addresses' => auth()->user()->addresses()->latest()->get(),
            'branch' => $branch,
            'previous_orders' => auth()->user()->orders()
                ->with('items.food') // Change orderItems to items
                ->latest()
                ->take(5)
                ->get(),
        ]);
    }


    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Create order data array
            $orderData = [
                'user_id' => auth()->id(),
                'branch_id' => $request->branch_id,
                'order_type' => $request->order_type,
                'subtotal' => $request->subtotal,
                'tax' => $request->tax,
                'total_amount' => $request->total_amount,
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => $request->payment_method,
                'paymentRef' => $request->paymentRef,
                'estimated_delivery_time' => Carbon::now()->addMinutes($request->order_type === 'delivery' ? 45 : 20),
            ];

            // dd($request->order_type === 'delivery');
            // Add delivery-specific fields only for delivery orders
            if ($request->order_type === 'delivery') {
                $orderData = array_merge($orderData, [
                    'delivery_fee' => $request->delivery_fee,
                    'delivery_address' => $request->delivery_address,
                    'delivery_notes' => $request->delivery_notes,
                ]);
            } else {
                // For collection orders, set delivery-related fields to null
                $orderData = array_merge($orderData, [
                    'delivery_fee' => null,
                    'delivery_address' => null,
                    'delivery_latitude' => null,
                    'delivery_longitude' => null,
                    'delivery_notes' => $request->delivery_notes, // Optional notes for collection
                ]);
            }

            // Debug the order data
            \Log::info('Order Data:', $orderData);

            // Create the order
            $order = Order::create($orderData);

            // Debug the created order
            \Log::info('Created Order:', $order->toArray());

            // Process each item in the order
            foreach ($request->items as $item) {
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'food_id' => $item['food_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['subtotal'],
                    'special_instructions' => $item['special_instructions'] ?? null,
                ]);
            }

            // Process payment based on payment method
            $paymentResult = $this->processPayment($order);

            if ($paymentResult['success']) {
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'confirmed'
                ]);
            }

            DB::commit();

            // Return a redirect response with the order
            return redirect()->route('customer.orders.show', [
                'order' => $order->id
            ])->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Order Creation Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Failed to place order. Please try again.');
        }
    }

    private function processPayment($order)
    {
        // Implement your payment processing logic here based on $order->payment_method
        // This is a simplified example
        switch ($order->payment_method) {
            case 'cash':
                return ['success' => true];
            case 'card':
                // Add your card processing logic here
                return ['success' => true];
            case 'upi':
                // Add your UPI processing logic here
                return ['success' => true];
            case 'wallet':
                // Add your wallet processing logic here
                return ['success' => true];
            default:
                return ['success' => false];
        }
    }


    private function processCardPayment(Order $order)
    {
        // Implement your card payment gateway integration here
        // This is a placeholder implementation
        return ['success' => true];
    }

    private function processWalletPayment(Order $order)
    {
        // Implement wallet payment logic
        $user = auth()->user();
        if ($user->wallet_balance >= $order->total_amount) {
            $user->decrement('wallet_balance', $order->total_amount);
            return ['success' => true];
        }
        return ['success' => false, 'message' => 'Insufficient wallet balance'];
    }

    private function processUPIPayment(Order $order)
    {
        // Implement UPI payment gateway integration here
        // This is a placeholder implementation
        return ['success' => true];
    }


    public function cancel(Order $order)
    {
        if ($order->status !== 'pending') {
            return back()->with('error', 'This order cannot be cancelled.');
        }

        $order->update([
            'status' => 'cancelled',
            'payment_status' => 'refunded'
        ]);

        // Process refund if payment was made
        if ($order->payment_status === 'paid') {
            $this->processRefund($order);
        }

        return back()->with('success', 'Order cancelled successfully.');
    }

    private function processRefund(Order $order)
    {
        // Implement refund logic based on payment method
        switch ($order->payment_method) {
            case 'card':
                // Process card refund
                break;
            case 'wallet':
                // Refund to wallet
                $order->user->increment('wallet_balance', $order->total_amount);
                break;
            case 'upi':
                // Process UPI refund
                break;
        }
    }

    /**
     * Store a new delivery address for the authenticated user.
     */
    public function storeAddress(Request $request)
    {
        try {
            $validated = $request->validate([
                'address_label' => 'required|string|max:50',
                'address' => 'required|string|max:255',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
                'is_default' => 'boolean'
            ]);

            // Begin transaction
            \DB::beginTransaction();

            // If this is set as default, remove default from other addresses
            if ($validated['is_default']) {
                auth()->user()->addresses()
                    ->where('is_default', true)
                    ->update(['is_default' => false]);
            }
            // If this is the first address, make it default regardless
            else if (auth()->user()->addresses()->count() === 0) {
                $validated['is_default'] = true;
            }

            // Create new address
            $address = auth()->user()->addresses()->create($validated);

            \DB::commit();

            return back()->with('success', 'Address saved successfully');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Failed to save address');
        }
    }

    /**
     * Update an existing address.
     */
    public function updateAddress(Request $request, UserAddress $address)
    {
        try {
            // First verify the address belongs to the authenticated user
            if ($address->user_id !== auth()->id()) {
                throw new \Exception('Unauthorized access');
            }

            $validated = $request->validate([
                'address_label' => 'required|string|max:50',
                'address' => 'required|string|max:255',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
                'is_default' => 'boolean'
            ]);

            \DB::beginTransaction();

            // Handle default address logic
            if ($validated['is_default']) {
                auth()->user()->addresses()
                    ->where('id', '!=', $address->id)
                    ->where('is_default', true)
                    ->update(['is_default' => false]);
            }

            // Update the address
            $address->update($validated);

            \DB::commit();

            return response()->json([
                'message' => 'Address updated successfully',
                'address' => $address
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \DB::rollBack();

            return response()->json([
                'message' => 'Failed to update address',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete an address.
     */
    public function deleteAddress(UserAddress $address)
    {
        try {
            // Verify ownership
            if ($address->user_id !== auth()->id()) {
                throw new \Exception('Unauthorized access');
            }

            \DB::beginTransaction();

            // If this was the default address, make another address default
            if ($address->is_default) {
                $newDefault = auth()->user()->addresses()
                    ->where('id', '!=', $address->id)
                    ->first();

                if ($newDefault) {
                    $newDefault->update(['is_default' => true]);
                }
            }

            $address->delete();

            \DB::commit();

            return response()->json([
                'message' => 'Address deleted successfully'
            ]);
        } catch (\Exception $e) {
            \DB::rollBack();

            return response()->json([
                'message' => 'Failed to delete address',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Set an address as default.
     */
    public function setDefaultAddress(UserAddress $address)
    {
        try {
            // Verify ownership
            if ($address->user_id !== auth()->id()) {
                throw new \Exception('Unauthorized access');
            }

            \DB::beginTransaction();

            // Remove default from other addresses
            auth()->user()->addresses()
                ->where('id', '!=', $address->id)
                ->update(['is_default' => false]);

            // Set this address as default
            $address->update(['is_default' => true]);

            \DB::commit();

            return response()->json([
                'message' => 'Default address updated successfully',
                'address' => $address
            ]);
        } catch (\Exception $e) {
            \DB::rollBack();

            return response()->json([
                'message' => 'Failed to update default address',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
