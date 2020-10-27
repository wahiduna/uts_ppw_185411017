<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();

        return view('users.index', compact('users'));        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create', [
            'user'  => new User(),
            'roles' => Role::All()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) 
    {       
        $data      = $request->all();
        $validator = $this->validateForm($data);
        if ($validator->fails()) {
            Session::flash('message', 'Data gagal disimpan');
            Session::flash('alert-class', 'alert-warning');

            return Redirect::back()->withErrors($validator)->withInput();
        }        

        $user = new User();
        $this->setAttributes($user, $data);
        
        if ($user->save()) {
            return redirect('/user');
        }
    }

    private function setAttributes($user, $data)
    {
        $user->name         = $data['name'];
        $user->email        = $data['email'];
        $user->role_id      = $data['role_id'];
        if (!empty($data['password']))
            $user->password = bcrypt($data['password']);
        
        if (!empty($data['photo']))
            $user->photo = $this->storePhoto($user, $data['photo']);
    }

    private function validateForm($data)
    {
        return Validator::make($data, [
            'name'     => 'required|string|max:40',
            'email'    => 'required', 'email',                
            'password' => 'min:4|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:4',
            'photo'     => 'nullable|image|mimes:jpg,jpeg,png|max:2000',
            'role_id' => 'required'            
        ]);
    }    

    private function storePhoto($user, $photo)
    {
        $this->deletePhoto($user);

        $path = public_path('/images/user/');
        $ext  = $photo->getClientOriginalExtension();
        $name = time() . '_' . mt_rand() . '.' . $ext;

        $photo->move($path, $name);

        return $name;
    }

    private function deletePhoto($user)
    {
        if (!empty($user->photo) and ($user->photo !== "placeholder.png")) {
            if (File::exists(public_path('/images/user/' . $user->photo))) {
                File::delete(public_path('/images/user/' . $user->photo));
            }
        }
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
    public function edit(User $user)
    {
        return view('users.create', [
            'user'  => $user,
            'roles' => Role::All()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $data            = $request->all();
        $data['user_id'] = $user->id;

        $validator = $this->validateForm($data);
        if ($validator->fails()) {
            Session::flash('message', "Data gagal disimpan");
            Session::flash('alert-class', 'alert-warning');

            return Redirect::back()->withErrors($validator)->withInput();
        }

        $this->setAttributes($user, $data);

        if ($user->save()) {
            Session::flash('message', "Data berhasil disimpan");
            Session::flash('alert-class', 'alert-success');
        } else {
            Session::flash('message', "Data gagal disimpan");
            Session::flash('alert-class', 'alert-danger');
        }
        
        return redirect('/user');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if ($user->delete()) {
            $this->deletePhoto($user);
        }

        return Redirect::back();
    }
}
