@extends('base')
@section('main')
<div class="row">
 <div class="col-sm-8 offset-sm-2">
    <h1 class="display-6">{{ $user->id ? 'Edit User' : 'Tambah User' }}</h1>
  <div>
    @if ($errors->any())
      <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
        </ul>
      </div><br />
    @endif
      <form method="post" enctype="multipart/form-data" action="{{ $user->id ? route('user.update', $user->id) : route('user.store') }}">
          @if($user->id)
          @method('PATCH')
          @endif
          @csrf
          <div class="form-group">    
              <label for="first_name">Nama :</label>
              <input type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}"/>
          </div>
          <div class="form-group">
              <label for="email">Alamat E-Mail :</label>
              <input type="text" class="form-control" name="email"  value="{{ old('email', $user->email) }}"/>
          </div>
          <div class="form-group required">
                <label>Jabatan</label>
                <select name="role_id" class="form-control">
                    <option></option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" @if(old('grup', $user->role_id) == $role->id) selected @endif>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>
          <div class="form-group">
              <label for="password">Password:</label>
              <input type="password" class="form-control" name="password"/>
          </div>
          <div class="form-group">
              <label for="konfirmasi_password">Konfirmasi Password:</label>
              <input type="password" class="form-control" name="password_confirmation"/>
          </div>
          <div class="form-group">
            <label for="photo">Photo</label>
            <input type="file" class="form-control-file" id="photo" name="photo">
        </div> 
        <div class="row" style="margin-top:50px">
          <div class="col-sm-1">
            <a href="{{ route('user.index') }}"  class="btn btn-warning">Kembali</a>
          </div>
          <div class="col-sm-1 offset-sm-10">
            <button type="submit" class="btn btn-primary">Simpan</button>
          </div>
        </div>                      
      </form>
  </div>
</div>
</div>
@endsection