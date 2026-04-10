@extends('layouts/user/layoutmaster')
@section('page_title', 'Đăng Nhập')
@section('content')
<style>
   #submitBtn {
      position: relative;
      overflow: hidden;
      transition: all .5s ease;
   }

   /* Ẩn text khi loading */
   #submitBtn.loading .btn-text {
      opacity: 0;
   }

   /* Spinner */
   #submitBtn.loading::after {
      content: "";
      position: absolute;
      top: 50%;
      left: 50%;
      width: 18px;
      height: 18px;
      border: 3px solid white;
      border-top-color: transparent;
      border-radius: 50%;
      transform: translate(-50%, -50%);
      animation: spin .7s linear infinite;
   }

   @keyframes spin {
      to { transform: translate(-50%, -50%) rotate(360deg); }
   }
</style>
<div>
    <section class="bread-crumb">
        <div class="container">
           <div class="row">
              <div class="col-xs-12 a-left">
                 <ul class="breadcrumb">
                    <li class="home">
                       <a href="/"><span>Trang chủ</span></a>						
                       <span class="br-line">|</span>
                    </li>
                    <li><strong><span>Đăng nhập tài khoản</span></strong></li>
                 </ul>
              </div>
           </div>
        </div>
     </section>
     <div class="container margin-bottom-30">
        <h1 class="title-head page-title"><span>Đăng nhập tài khoản</span></h1>
        <div class="row">
           <div class="col-lg-6">
              <div class="page-login margin-bottom-30">
                 <div id="login">
                    <form id="customer_login" accept-charset="UTF-8">
                        @csrf
                       <input name="FormType" type="hidden" value="customer_login"><input name="utf8" type="hidden" value="true">
                       <div class="form-signup clearfix">
                          <fieldset class="form-group">
                             <label>Email <span class="required">*</span></label>
                             <input type="email" class="form-control form-control-lg" value="" name="email" id="customer_email" placeholder="Email">
                          </fieldset>
                          <fieldset class="form-group">
                             <label>Mật khẩu <span class="required">*</span></label>
                             <input type="password" class="form-control form-control-lg" value="" name="password" id="customer_password" placeholder="Mật khẩu">
                          </fieldset>
                          <div class="pull-xs-left" style="margin-top: 25px;">
                              <!-- Turnstile widget -->
                              {{-- <div class="cf-turnstile"
                                    data-sitekey="{{ config('services.turnstile.site_key') }}">
                              </div> --}}
                              {{-- <input class="btn btn-primary" type="submit" id="submitBtn" value="Đăng nhập"> --}}
                              <button type="submit" class="btn btn-primary" id="submitBtn">
                                 <span class="btn-text">Đăng nhập</span>
                              </button>
                             {{-- <a href="/account/register" class="btn-link-style btn-register" style="margin-left: 20px;text-decoration: underline; ">Đăng ký</a> --}}
                             <div class="block social-login--facebooks">
                                <p class="a-center">
                                   Hoặc đăng nhập bằng
                                </p>
                                <script>function loginFacebook(){var a={client_id:"947410958642584",redirect_uri:"https://store.mysapo.net/account/facebook_account_callback",state:JSON.stringify({redirect_url:window.location.href}),scope:"email",response_type:"code"},b="https://www.facebook.com/v3.2/dialog/oauth"+encodeURIParams(a,!0);window.location.href=b}function loginGoogle(){var a={client_id:"997675985899-pu3vhvc2rngfcuqgh5ddgt7mpibgrasr.apps.googleusercontent.com",redirect_uri:"https://store.mysapo.net/account/google_account_callback",scope:"email profile https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile",access_type:"online",state:JSON.stringify({redirect_url:window.location.href}),response_type:"code"},b="https://accounts.google.com/o/oauth2/v2/auth"+encodeURIParams(a,!0);window.location.href=b}function encodeURIParams(a,b){var c=[];for(var d in a)if(a.hasOwnProperty(d)){var e=a[d];null!=e&&c.push(encodeURIComponent(d)+"="+encodeURIComponent(e))}return 0==c.length?"":(b?"?":"")+c.join("&")}</script>
                                <a href="javascript:void(0)" class="social-login--facebook" onclick="loginFacebook()"><img width="129px" height="37px" alt="facebook-login-button" src="{{ asset('images/fb-btn.svg') }}"></a>
                                <a href="javascript:void(0)" class="social-login--google" onclick="loginGoogle()"><img width="129px" height="37px" alt="google-login-button" src="{{ asset('images/gp-btn.svg') }}"></a>
                             </div>
                          </div>
                       </div>
                    </form>
                 </div>
              </div>
           </div>
           <div class="col-lg-6">
              <div id="recover-password" class="form-signup">
                 <span>
                 Bạn quên mật khẩu? Nhập địa chỉ email để lấy lại mật khẩu qua email.
                 </span>					
                 <form accept-charset="UTF-8" id="reset-pass-form">
                     @csrf
                    <input name="FormType" type="hidden"><input name="utf8" type="hidden" value="true">
                    <div class="form-signup aaaaaaaa">
                    </div>
                    <div class="form-signup clearfix">
                       <fieldset class="form-group">
                          <label>Email <span class="required">*</span></label>
                          <input type="email" class="form-control form-control-lg" value="" name="reset-password-email" id="recover-email" placeholder="Email">
                       </fieldset>
                    </div>
                    <div class="action_bottom">
                       <input class="btn  btn-primary" style="margin-top: 25px;" type="submit" value="Đặt lại mật khẩu">
                    </div>
                 </form>
              </div>
           </div>
        </div>
     </div>
     <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
     <script type="text/javascript">
         function showRecoverPasswordForm() {
               document.getElementById('recover-password').style.display = 'block';
               document.getElementById('login').style.display='none';
         }
         
         function hideRecoverPasswordForm() {
               document.getElementById('recover-password').style.display = 'none';
               document.getElementById('login').style.display = 'block';
         }
        
         $(document).ready(function () {
            $('#reset-pass-form').on('submit', function(e) {
               e.preventDefault();

               let form = $(this);
               let formData = form.serialize();

               const apiResetPass = "{{ route('reset-password' )}}";
               $.ajax({
                  url: apiResetPass,
                  method: 'POST',
                  data: formData,
                  success: function(response) {
                     if(!response.success) {
                        toastr.error(response.message);
                        return;
                     } 
                     form[0].reset();
                     toastr.success(response.message);
                  },
               })

            });
            $('#customer_login').on('submit', function(e) {
               e.preventDefault();
               let form = $(this);
               let formData = form.serialize();
               const btn = document.getElementById('submitBtn');
               btn.classList.add("loading");
               btn.disabled = true;

               const apiLogin = "{{ route('login.index' )}}";
               $.ajax({
                  url: apiLogin,
                  method: 'POST',
                  data: formData,
                  success: function(response) {
                     if(!response.success) {
                        toastr.error(response.message);
                        return;
                     } 
                     form[0].reset();
                     setTimeout(() => {
                        window.location.href = response.redirect;
                     }, 700);
                     toastr.success(response.message);
                  },
                  complete: function() {
                     btn.classList.remove("loading");
                     btn.disabled = false;
                  }
               });

            })
         });
     </script>
     @include('page/user/list_brands');
</div>
@endsection

@section('scripts')

@endsection