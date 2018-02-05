@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
          <h1>Edit Profile</h1>
          <hr>
          @if($id == "id")
            <form action="{{ action('HomeController@profileeditstore', $user->id) }}" method="POST" class="">
          @else
            <form action="store/{{ $user->id }}" method="POST" class="">
          @endif
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
              <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" placeholder="Name">
                @if ($errors->has('name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
              </div>
            </div>

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
              <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" placeholder="Email">
                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
              </div>
            </div>

            <div class="form-group{{ $errors->has('role') ? ' has-error' : '' }}">
              <label for="menu">Roles</label>
                <select class="form-control" name="role">
                    <option value="">Please Select</option>
                    <option value="9">Admin</option>
                    <option value="8">Editor</option>
                    <option value="7">Contributor</option>
                    <option value="1">Subscribe</option>
                </select>
                <span id="helpBlock" class="help-block">
                @if ($user->role == 9)
                  Your current role is <b>Admin</b>.
                @elseif ($user->role == 8)
                  Your current role is <b>Editor</b>.
                @elseif ($user->role == 7)
                  Your current role is <b>Contributor</b>.
                @elseif ($user->role == 1)
                  Your current role is <b>Subscribe</b>.
                @endif
                </span>
                @if ($errors->has('role'))
                    <span class="help-block">
                        <strong>{{ $errors->first('role') }}</strong>
                    </span>
                @endif
            </div>
            @if($id !== "id")
            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
              <div class="form-group">
                <label for="password">New Password</label>
                <input type="password" class="form-control" id="password" name="password">
                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
              </div>
            </div>

            <div class="form-group">
                <label for="password-confirm">Confirm Password</label>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
            </div>
            @endif

            {{ csrf_field() }}
            <input type="hidden" name="_method" value="put">
            <input type="submit" name="submit" value="Save" class="btn btn-primary">
            <a href="{{action('HomeController@user')}}"><input type="button" value="Cancel" class="btn btn-default"></a>
          </form>
        </div>
    </div>
</div>
@endsection
