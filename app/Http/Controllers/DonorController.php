<?php

namespace App\Http\Controllers;

use App\Models\DonationListing;
use App\Models\Donor;
use App\Models\User;
use Illuminate\Http\Request;

class DonorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(DonationListing $donationlisting)
    {
        //
        return view("donor.create", ["donation" => $donationlisting]);
    }

    public function initialize_payment(Request $request, DonationListing $donationlisting)
    {
        //
        $form_fields = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'amount' => 'required',
        ]);

        $form_fields['donation_listing_id'] = $donationlisting->id;

        $curl = curl_init();
        $name = $form_fields['name'];
        $email = $form_fields['email'];
        $amount = $form_fields['amount'] * 100; //the amount in kobo. This value is actually NGN 300

// url to go to after payment
        $callback_url = url('/donate/verify/' . $donationlisting->id);

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/transaction/initialize",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                'amount' => $amount,
                'email' => $email,
                'callback_url' => $callback_url,
                'metadata' => ['donor_name' => $name, 'donor_email' => $email, 'cancel_action' => $callback_url],

            ]),
            CURLOPT_HTTPHEADER => [
                "authorization: Bearer " . env('PAYSTACK_SECRET_KEY'), //replace this with your own test key
                "content-type: application/json",
                "cache-control: no-cache",
            ],
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        if ($err) {
            // there was an error contacting the Paystack API
            die('Curl returned error: ' . $err);
        }

        $tranx = json_decode($response, true);

        if (!$tranx['status']) {
            // there was an error from the API
            print_r('API returned error: ' . $tranx['message']);
        }

        // comment out this line if you want to redirect the user to the payment page
        // print_r($tranx);
        // redirect to page so User can pay
        return redirect($tranx['data']['authorization_url']);

    }

    public function verify_payment(Request $request, DonationListing $donationlisting)
    {
        //
        // http: //127.0.0.1:8000/donate/verify/1?trxref=v6def1u629&reference=v6def1u629
        if (!$request->reference) {
            // there was an error from the API
            return redirect('/donations/' . $donationlisting->id)->with('message', ' Error occured');

        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . $request->reference,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . env('PAYSTACK_SECRET_KEY'),
                "Cache-Control: no-cache",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {

            return redirect('/donations/' . $donationlisting->id)->with('message', 'cURL Error #:' . $err);

        } else {

            $tranx = json_decode($response, true);

            if (!$tranx['status']) {
                // there was an error from the API
                return redirect('/donations/' . $donationlisting->id)->with('message', ' Error: ' . $tranx['message']);

            }

            $form_fields['amount'] = $tranx['data']['amount'] / 100;
            $form_fields['email'] = $tranx['data']['metadata']['donor_email'];
            $form_fields['name'] = $tranx['data']['metadata']['donor_name'];
            $form_fields['donation_listing_id'] = $donationlisting->id;

            Donor::create($form_fields);
            DonationListing::find($donationlisting->id)->update(['amount' => $donationlisting->amount + $form_fields['amount']]);

            // add to user wallet from donation listing
            $user = User::find($donationlisting->user_id);
            $user->wallet = $user->wallet + $form_fields['amount'];
            $user->save();

            return redirect('/donations/' . $donationlisting->id)->with('message', 'Donated successfully!');

        }

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

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}