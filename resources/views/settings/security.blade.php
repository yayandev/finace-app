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
          <div class="card-body">
            <form id="formAccountSettings" method="GET" onsubmit="return false">
              <div class="row">
                <div class="mb-3 col-md-6 form-password-toggle">
                  <div class="input-group input-group-merge">
                    <div class="form-floating form-floating-outline">
                      <input
                        class="form-control"
                        type="password"
                        name="currentPassword"
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
                        name="newPassword"
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
                        name="confirmPassword"
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
                <button type="reset" class="btn btn-outline-secondary">Cancel</button>
              </div>
            </form>
          </div>
        </div>
        <!--/ Change Password -->
      </div>
    </div>
  </div>
@endsection
