<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Show Register/Create Form
    public function create()
    {
        return view('users.register');
    }

    // Create New User
    public function store(Request $request)
    {
        $form_fields = $request->validate([
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => 'required|confirmed|min:6',
        ]);

        // Hash Password
        $form_fields['password'] = bcrypt($form_fields['password']);

        // Create User
        $user = User::create($form_fields);

        // Login
        auth()->login($user);

        return redirect('/')->with('message', 'User created and logged in');
    }

    // Logout User
    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('message', 'You have been logged out!');

    }

    // Show Login Form
    public function login()
    {
        return view('users.login');
    }

    // Authenticate User
    public function authenticate(Request $request)
    {
        $form_fields = $request->validate([
            'email' => ['required', 'email'],
            'password' => 'required',
        ]);

        if (auth()->attempt($form_fields)) {
            $request->session()->regenerate();

            return redirect('/')->with('message', 'You are now logged in!');
        }

        return back()->withErrors(['email' => 'Invalid Credentials'])->onlyInput('email');
    }

    // Show withdraw Form
    public function create_withdraw()
    {

        //get all withdrawals
        $withdrawals = Withdrawal::all();

        return view('users.withdraw', ['withdrawals' => $withdrawals]);
    }

    // Withdraw User
    public function withdraw(Request $request)
    {
        $form_fields = $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $user = auth()->user();

        if ($user->wallet < $form_fields['amount']) {
            return back()->withErrors(['amount' => 'Insufficient funds']);
        }

        $user->wallet = $user->wallet - $form_fields['amount'];
        $user->save();
        //record to withdrawals
        $form_fields['user_id'] = $user->id;
        $user->withdrawals()->create($form_fields);

        return redirect('/')->with('message', 'Withdrawal successful!');

    }

    public function show()
    {
        // get user
        $user = auth()->user();
        return view('users.profile', ['user' => $user]);
    }

    // public function edit(User $user)
    // {
    //     return view('users.edit', ['user' => $user]);
    // }

    public function update(Request $request)
    {

        $user = auth()->user();

        $form_fields = $request->validate([
            'name' => ['required', 'min:3'],
            'password' => 'nullable|confirmed|min:6',
        ]);

        //check if there is a change in email
        if ($request->email != $user->email) {
            $form_fields = $request->validate([
                'email' => ['required', 'email', Rule::unique('users', 'email')],
                'name' => ['required', 'min:3'],
                'password' => 'nullable|confirmed|min:6',
            ]);

        }

        if ($form_fields['password']) {
            $form_fields['password'] = bcrypt($form_fields['password']);
        } else {
            unset($form_fields['password']);
        }

        $user->update($form_fields);

        return redirect('/profile')->with('message', 'User updated!');

    }

}