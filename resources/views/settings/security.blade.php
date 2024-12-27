@extends('layouts.app')

@section('title','Settings Security')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Account Settings /</span> Security</h4>

    <div class="row">
      <div class="col-md-12">
        <ul class="nav nav-pills flex-column flex-md-row mb-3 gap-2 gap-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="/settings/account"
              ><i class="mdi mdi-account-outline mdi-20px me-1"></i> Account</a
            >
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="javascript:void(0);"
              ><i class="mdi mdi-lock-open-outline mdi-20px me-1"></i> Security</a
            >
          </li>
        </ul>
        <!-- Change Password -->
        <div class="card mb-4">
          <h5 class="card-header">Change Password</h5>
         {{-- looping semua error disini --}}
            @if ($errors->any())
            <div class="alert alert-danger m-3">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>
                        {{$error}}
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif
          <div class="card-body">
            <form id="formAccountSettings" method="POST" action="{{route('change.password')}}">
                @csrf
              <div class="row">
                <div class="mb-3 col-md-6 form-password-toggle">
                  <div class="input-group input-group-merge">
                    <div class="form-floating form-floating-outline">
                      <input
                        class="form-control"
                        type="password"
                        name="current_password"
                        id="currentPassword"
                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                      <label for="currentPassword">Current Password</label>
                    </div>
                    <span class="input-group-text cursor-pointer"
                      ><i class="mdi mdi-eye-off-outline"></i
                    ></span>
                  </div>
                </div>
              </div>
              <div class="row g-3 mb-4">
                <div class="col-md-6 form-password-toggle">
                  <div class="input-group input-group-merge">
                    <div class="form-floating form-floating-outline">
                      <input
                        class="form-control"
                        type="password"
                        id="newPassword"
                        name="new_password"
                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                      <label for="newPassword">New Password</label>
                    </div>
                    <span class="input-group-text cursor-pointer"
                      ><i class="mdi mdi-eye-off-outline"></i
                    ></span>
                  </div>
                </div>
                <div class="col-md-6 form-password-toggle">
                  <div class="input-group input-group-merge">
                    <div class="form-floating form-floating-outline">
                      <input
                        class="form-control"
                        type="password"
                        name="password_confirmation"
                        id="confirmPassword"
                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                      <label for="confirmPassword">Confirm New Password</label>
                    </div>
                    <span class="input-group-text cursor-pointer"
                      ><i class="mdi mdi-eye-off-outline"></i
                    ></span>
                  </div>
                </div>
              </div>
              <h6 class="text-body">Password Requirements:</h6>
              <ul class="ps-3 mb-0">
                <li class="mb-1">Minimum 8 characters long - the more, the better</li>
                <li class="mb-1">At least one lowercase character</li>
                <li>At least one number, symbol, or whitespace character</li>
              </ul>
              <div class="mt-4">
                <button type="submit" class="btn btn-primary me-2">Save changes</button>
              </div>
            </form>
          </div>
        </div>
        <!--/ Change Password -->
      </div>
    </div>
  </div>
@endsection
