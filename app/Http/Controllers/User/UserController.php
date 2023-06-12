<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        return view('user.profile', [
            'user' => $user,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('user.editPassword', [
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $user = User::find(auth()->user()->id);

        if ($request->has('matricule'))
        {
            $user->matricule = $request->matricule;
        } else
        {
            $user->matricule = $user->matricule;
        }

        if ($request->has('nom'))
        {
            $user->nom = $request->nom;
        } else
        {
            $user->nom = $user->nom;
        }

        if ($request->has('prenom'))
        {
            $user->prenom = $request->prenom;
        }
        else
        {
            $user->prenom = $user->prenom;
        }

        if ($request->has('email'))
        {
            $user->email = $request->email;
        }
        else
        {
            $user->email = $user->email;
        }

        if ($request->has('image'))
        {
            $image_name = $user->nom . '.' . $request->image->extension();
            $profile_image = $request->image->storeAs('images' , $image_name , 'public');
            $user->profile_image = $profile_image;
        }
        else
        {
            $user->profile_image = $user->profile_image;
        }

        if ($request->has('old_password') && $request->has('new_password') && $request->has('confirm_password'))
        {
            $hashedPassword = $user->password;
            if (Hash::check($request->old_password, $hashedPassword))
            {
                $user->password = Hash::make($request->new_password);
                $user->save();
                Auth::logout();
                return redirect()->route('login')->with('message', 'Mot de passe modifé avec succès.');
            }
            else
            {
                return redirect()->route('user.profile.index')->with('message', 'Mot de passe courrant incorrect !');
            }
        }
            $user->save();
            return redirect()->route('user.profile.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
