<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    /**
     * Display user's addresses.
     */
    public function index()
    {
        $addresses = Auth::user()->addresses()->orderBy('is_default', 'desc')->get();
        return view('account.addresses.index', compact('addresses'));
    }

    /**
     * Show create address form.
     */
    public function create()
    {
        $states = [
            'Andhra Pradesh',
            'Arunachal Pradesh',
            'Assam',
            'Bihar',
            'Chhattisgarh',
            'Goa',
            'Gujarat',
            'Haryana',
            'Himachal Pradesh',
            'Jharkhand',
            'Karnataka',
            'Kerala',
            'Madhya Pradesh',
            'Maharashtra',
            'Manipur',
            'Meghalaya',
            'Mizoram',
            'Nagaland',
            'Odisha',
            'Punjab',
            'Rajasthan',
            'Sikkim',
            'Tamil Nadu',
            'Telangana',
            'Tripura',
            'Uttar Pradesh',
            'Uttarakhand',
            'West Bengal',
            'Delhi',
            'Jammu and Kashmir',
            'Ladakh',
            'Puducherry',
            'Chandigarh',
        ];

        $redirectTo = request('redirect', 'account.addresses');

        return view('account.addresses.create', compact('states', 'redirectTo'));
    }

    /**
     * Store new address.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:100',
            'mobile' => 'required|string|regex:/^[6-9]\d{9}$/',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'landmark' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|string|regex:/^\d{6}$/',
            'address_type' => 'required|in:home,work,other',
            'is_default' => 'nullable|boolean',
        ], [
            'mobile.regex' => 'Please enter a valid 10-digit mobile number.',
            'pincode.regex' => 'Please enter a valid 6-digit pincode.',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['is_default'] = $request->boolean('is_default');

        $address = Address::create($validated);

        // Set as default if requested or if first address
        if ($validated['is_default'] || Auth::user()->addresses()->count() === 1) {
            $address->setAsDefault();
        }

        // Redirect based on context
        $redirectTo = $request->input('redirect', 'account.addresses');

        if ($redirectTo === 'checkout.address') {
            session(['checkout_address_id' => $address->id]);
            return redirect()->route('checkout.review')
                ->with('success', 'Address added successfully!');
        }

        return redirect()->route('account.addresses')
            ->with('success', 'Address added successfully!');
    }

    /**
     * Delete address.
     */
    public function destroy($id)
    {
        $address = Address::where('user_id', Auth::id())->findOrFail($id);

        // Don't allow deleting default address if other addresses exist
        if ($address->is_default && Auth::user()->addresses()->count() > 1) {
            return back()->withErrors(['address' => 'Please set another address as default first.']);
        }

        $address->delete();

        return back()->with('success', 'Address deleted successfully.');
    }

    /**
     * Set address as default.
     */
    public function setDefault($id)
    {
        $address = Address::where('user_id', Auth::id())->findOrFail($id);
        $address->setAsDefault();

        return back()->with('success', 'Default address updated.');
    }
}
