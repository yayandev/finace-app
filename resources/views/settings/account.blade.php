@extends('layouts.app')

@section('title', 'Settings Account')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Account Settings /</span> Account</h4>

    <div class="row">
      <div class="col-md-12">
        <ul class="nav nav-pills flex-column flex-md-row mb-3 gap-2 gap-lg-0">
          <li class="nav-item">
            <a class="nav-link active" href="javascript:void(0);"
              ><i class="mdi mdi-account-outline mdi-20px me-1"></i>Account</a
            >
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/settings/security"
              ><i class="mdi mdi-lock-open-outline mdi-20px me-1"></i>Security</a
            >
          </li>
        </ul>
        <div class="card mb-4">
          <h4 class="card-header">Profile Details</h4>
          <!-- Account -->
          <div class="card-body">
            <div class="d-flex align-items-start align-items-sm-center gap-4">
              <img
                src="../../assets/img/avatars/1.png"
                alt="user-avatar"
                class="d-block w-px-120 h-px-120 rounded"
                id="uploadedAvatar" />
              <div class="button-wrapper">
                <label for="upload" class="btn btn-primary me-2 mb-3" tabindex="0">
                  <span class="d-none d-sm-block">Upload new photo</span>
                  <i class="mdi mdi-tray-arrow-up d-block d-sm-none"></i>
                  <input
                    type="file"
                    id="upload"
                    class="account-file-input"
                    hidden
                    accept="image/png, image/jpeg" />
                </label>
                <button type="button" class="btn btn-outline-danger account-image-reset mb-3">
                  <i class="mdi mdi-reload d-block d-sm-none"></i>
                  <span class="d-none d-sm-block">Reset</span>
                </button>

                <div class="small">Allowed JPG, GIF or PNG. Max size of 800K</div>
              </div>
            </div>
          </div>
          <div class="card-body pt-2 mt-1">
            <form id="formAccountSettings" method="GET" onsubmit="return false">
              <div class="row mt-2 gy-4">
                <div class="col-md-6">
                  <div class="form-floating form-floating-outline">
                    <input
                      class="form-control"
                      type="text"
                      id="name"
                      name="name"
                      value="{{ Auth::user()->name }}"
                      autofocus />
                    <label for="name">Name</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating form-floating-outline">
                    <input
                      class="form-control"
                      type="text"
                      id="email"
                      name="email"
                      value="{{Auth::user()->email}}"
                      placeholder="john.doe@example.com" readonly />
                    <label for="email">E-mail</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating form-floating-outline">
                    <input
                      type="date"
                      class="form-control"
                      id="created_at"
                      name="created_at"
                      value="{{ Auth::user()->created_at->format('Y-m-d') }}"
                      readonly />
                    <label for="created_at">Created at</label>
                  </div>
                </div>

                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                      <input
                        type="text"
                        class="form-control"
                        id="role"
                        name="role"
                        value="{{ Auth::user()->getRoleNames()->first() }}"
                        readonly />
                      <label for="role">Role</label>
                    </div>
                  </div>
              </div>
              {{-- <div class="mt-4">
                <button type="submit" class="btn btn-primary me-2">Save changes</button>
                <button type="reset" class="btn btn-outline-secondary">Cancel</button>
              </div> --}}
            </form>
          </div>
          <!-- /Account -->
        </div>

      </div>
    </div>
  </div>
@endsection
