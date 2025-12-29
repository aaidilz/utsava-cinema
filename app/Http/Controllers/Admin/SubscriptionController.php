<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

final class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::query()
            ->orderBy('price')
            ->get();

        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    public function create()
    {
        return view('admin.subscriptions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration_days' => ['required', 'integer', 'min:1'],
            'is_active' => ['boolean']
        ]);

        $validated['is_active'] = $request->has('is_active');

        Subscription::create($validated);

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Subscription plan created successfully');
    }

    public function edit(Subscription $subscription)
    {
        return view('admin.subscriptions.edit', compact('subscription'));
    }

    public function update(Request $request, Subscription $subscription)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration_days' => ['required', 'integer', 'min:1'],
            'is_active' => ['boolean']
        ]);

        $validated['is_active'] = $request->has('is_active');

        $subscription->update($validated);

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Subscription plan updated successfully');
    }

    public function destroy(Subscription $subscription)
    {
        if ($subscription->transactions()->exists()) {
            // Soft delete or just disable it if it has transactions
            // For now, let's just disable it and warn the user, or strictly allow delete
            // But usually we don't want to break foreign keys.
            // Let's assume for now we can delete, but if it fails (due to DB constraints), we show error.
            // Ideally we should just set is_active to false.
            // But user asked for CRUD. Let's try delete, but maybe safety first?
            // Actually, let's implement delete for now.
        }

        try {
            $subscription->delete();
        } catch (\Exception $e) {
            return back()->with('error', 'Cannot delete subscription plan that has active transactions.');
        }

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Subscription plan deleted successfully');
    }
}
