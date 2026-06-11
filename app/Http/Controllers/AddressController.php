<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AddressController extends Controller
{
    // ── List all saved addresses ──────────────────────────────────

    public function index(): View
    {
        $addresses = auth()->user()
                           ->addresses()
                           ->orderByDesc('is_default')
                           ->orderByDesc('created_at')
                           ->get();

        return view('addresses.index', compact('addresses'));
    }

    // ── Create form ───────────────────────────────────────────────

    public function create(): View
    {
        return view('addresses.create');
    }

    // ── Store new address ─────────────────────────────────────────

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'label'                 => 'required|string|max:50',
            'recipient_name'        => 'required|string|max:100',
            'phone'                 => 'required|string|max:20',
            'address_line1'         => 'required|string|max:255',
            'address_line2'         => 'nullable|string|max:255',
            'city'                  => 'required|string|max:100',
            'state'                 => 'nullable|string|max:100',
            'postal_code'           => 'nullable|string|max:20',
            'country'               => 'nullable|string|max:100',
            'delivery_instructions' => 'nullable|string|max:500',
            'is_default'            => 'nullable|boolean',
        ]);

        $data['user_id']    = auth()->id();
        $data['country']    = $data['country'] ?? 'Kenya';
        $data['is_default'] = $request->boolean('is_default');

        // First ever address is always the default
        if (! auth()->user()->addresses()->exists()) {
            $data['is_default'] = true;
        }

        $address = Address::create($data);

        if ($address->is_default) {
            $address->makeDefault();
        }

        // If came from checkout, return there
        $redirect = $request->query('redirect') === 'checkout'
            ? route('checkout.index')
            : route('addresses.index');

        return redirect($redirect)->with('success', 'Address saved successfully!');
    }

    // ── Edit form ─────────────────────────────────────────────────

    public function edit(Address $address): View
    {
        $this->gate($address);
        return view('addresses.edit', compact('address'));
    }

    // ── Update address ────────────────────────────────────────────

    public function update(Request $request, Address $address): RedirectResponse
    {
        $this->gate($address);

        $data = $request->validate([
            'label'                 => 'required|string|max:50',
            'recipient_name'        => 'required|string|max:100',
            'phone'                 => 'required|string|max:20',
            'address_line1'         => 'required|string|max:255',
            'address_line2'         => 'nullable|string|max:255',
            'city'                  => 'required|string|max:100',
            'state'                 => 'nullable|string|max:100',
            'postal_code'           => 'nullable|string|max:20',
            'country'               => 'nullable|string|max:100',
            'delivery_instructions' => 'nullable|string|max:500',
            'is_default'            => 'nullable|boolean',
        ]);

        $data['is_default'] = $request->boolean('is_default');
        $address->update($data);

        if ($address->is_default) {
            $address->makeDefault();
        }

        return redirect()->route('addresses.index')->with('success', 'Address updated!');
    }

    // ── Delete address ────────────────────────────────────────────

    public function destroy(Address $address): RedirectResponse
    {
        $this->gate($address);

        $wasDefault = $address->is_default;
        $address->delete();

        // Promote next address to default if needed
        if ($wasDefault) {
            auth()->user()->addresses()->latest()->first()?->makeDefault();
        }

        return redirect()->route('addresses.index')->with('success', 'Address removed.');
    }

    // ── Set default (POST, works as both AJAX and normal form) ────

    public function setDefault(Address $address)
    {
        $this->gate($address);
        $address->makeDefault();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('addresses.index')->with('success', 'Default address updated.');
    }

    // ── Private ownership check ───────────────────────────────────

    private function gate(Address $address): void
    {
        abort_if($address->user_id !== auth()->id(), 403);
    }
}