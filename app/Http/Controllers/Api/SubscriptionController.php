<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{

    public function subscribe($id)
    {
        try {
            // Get the authenticated user
            $user = Auth::user();

            // If the user already has an active subscription
            if ($user->hasActiveSubscription()) {
                return response()->json([
                    'status' => 400,
                    'error' => 'User already has an active subscription'
                ], 400);
            }

            // Find the plan by ID
            $plan = Plan::findOrFail($id);

            // Create a new subscription
            $subscription = new Subscription();
            $subscription->user_id = $user->id;
            $subscription->plan_id = $plan->id;
            $subscription->start_date = now();
            $subscription->end_date = now()->addDays($plan->duration); // Assuming duration is in days
            $subscription->save();

            return response()->json([
                'status' => 200,
                'message' => 'User subscribed to plan successfully',
                'subscription' => $subscription
            ], 200);
        } catch (\Exception $e) {

            \Log::error('Error occurred while subscribing to the plan: ' . $e->getMessage());

            return response()->json([
                'status' => 500,
                'error' => 'An error occurred while subscribing to the plan'
            ], 500);
        }
    }

    public function activeSubscriptions(Request $request)
{
    try {
        // Get the authenticated user
        $user = Auth::user();

        $activeSubscription = Subscription::where('user_id', $user->id)
            ->where('end_date', '>=', now())
            ->with('plan')
            ->orderBy('created_at', 'desc')
            ->first();

        return response()->json([
            'status' => 200,
            'active_subscription' => $activeSubscription
        ], 200);
    } catch (Exception $e) {
        return response()->json([
            'status' => 500,
            'error' => 'An error occurred while fetching active subscription'
        ], 500);
    }
}



    public function subscriptionHistory()
    {
        try {
            $user = Auth::user();
            $subscriptionHistory = $user->subscriptionHistory();

            return response()->json([
                'status' => 200,
                'subscription_history' => $subscriptionHistory
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'error' => 'An error occurred while fetching subscription history'
            ], 500);
        }
    }
}
