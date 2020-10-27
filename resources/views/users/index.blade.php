@extends('base')
@section('main')
<nav class="navbar navbar-expand-lg navbar-light bg-light rounded">
  <a class="navbar-brand" href="#">Home</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample09" aria-controls="navbarsExample09" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarsExample09">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <a class="nav-link" href="#">User <span class="sr-only">(current)</span></a>
        </li>        
      </ul>      
    </div>      
</nav>
<div class="row" style="margin-top:20px">
  <div class="col-sm-12">
    <div class="row">
      <div class="col-sm-10">
        <h3 class="display-6">Tabel Rekaman User</h3>   
      </div>
      <div class="col-sm-2">
        <a class="btn btn-sm btn-block btn-success" href="{{ route('user.create') }}" role="button">Tambah</a>   
      </div>
    </div>
      
    <table class="table table-striped">
      <thead>
          <tr>
            <td>Id</td>
            <td>Foto</td>
            <td>Nama</td>
            <td>e-mail</td>
            <td>Jabatan</td>
            <td colspan = 2>Actions</td>
          </tr>
      </thead>
      <tbody>
        @php 
          $no = 1;
        @endphp
        @foreach($users as $user)
          <tr>
              <td>{{ $no++ }}</td>
              <td>
                <img src="{{ url('/images/user/'.$user->photo) }}" alt="profil" class="avatar">
              </td>
              <td>{{ $user->name }}</td>
              <td>{{ $user->email }}</td>
              <td>{{ $user->role->name }}</td>
              <td>
                  <a href="{{ route('user.edit', $user->id)}}" class="btn btn-sm btn-block btn-primary">Edit</a>
              </td>
              <td>
                  <form action="{{ route('user.destroy', $user->id)}}" method="post">
                    @csrf
                    @method('DELETE')

                    <button class="btn btn-sm btn-block btn-danger" onclick="return deleteConfirm()" type="submit">Delete</button>
                  </form>
              </td>
          </tr>
          @endforeach
      </tbody>
    </table>
  <div>
</div>

<style>
  .avatar {
    vertical-align: middle;
    width: 50px;
    height: 50px;
    border-radius: 50%;
  }
</style>
<script>
  function deleteConfirm() {
      if(!confirm("Apakah anda yakin ingin menghapus data ini ?"))
      event.preventDefault();
  }
 </script>
@endsection