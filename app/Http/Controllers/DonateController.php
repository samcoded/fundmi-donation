<?php

namespace App\Http\Controllers;

use App\Models\DonationListing;
use Illuminate\Http\Request;

class DonateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view("donations.index", ["donations" => DonationListing::latest()->filter(request(['tag', 'search']))->paginate(10)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

        return view("donations.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $form_fields = $request->validate([
            'title' => 'required',
            'tags' => 'required',
            'description' => 'required',
            'target' => 'required',
        ]);

        if ($request->hasFile('image')) {
            $form_fields['image'] = $request->file('image')->store('images', 'public');
        }

        $form_fields['user_id'] = auth()->id();

        DonationListing::create($form_fields);

        return redirect('/')->with('message', 'Donation created successfully!');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(DonationListing $donationlisting)
    {
        //
        // get all donors from the donation listing
        return view('donations/show', ['donation' => $donationlisting]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(DonationListing $donationlisting)
    {
        //
        return view('donations.edit', ['donation' => $donationlisting]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DonationListing $donationlisting)
    {
        //
        $form_fields = $request->validate([
            'title' => 'required',
            'tags' => 'required',
            'description' => 'required',
            'target' => 'required',
        ]);

        if ($request->hasFile('image')) {
            $form_fields['image'] = $request->file('image')->store('images', 'public');
        }

        $donationlisting->update($form_fields);

        return redirect('/')->with('message', 'Donation updated successfully!');

    }

    // Delete Listing
    public function destroy(DonationListing $donationlisting)
    {
        // Make sure logged in user is owner
        if ($donationlisting->user_id != auth()->id()) {
            abort(403, 'Unauthorized Action');
        }

        $donationlisting->delete();
        return redirect('/')->with('message', 'Donation deleted successfully');
    }

    // Manage Listings
    public function manage()
    {
        return view('donations.manage', ['donations' => auth()->user()->donationlisting()->get()]);
    }
}