@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Two Factor Authentication') }}</div>

                <div class="card-body">
                    @if (session('status') == "two-factor-authentication-disabled")
                        <div class="alert alert-success" role="alert">
                            Two factor Authentication has been disabled.
                        </div>
                    @endif

                    @if (session('status') == "two-factor-authentication-enabled")
                        <div class="alert alert-success" role="alert">
                            Two factor Authentication has been enabled.
                        </div>
                    @endif


                    <!-- 2FA confirmed, we show a 'disable' button to disable it : -->        
                    @if(auth()->user()->two_factor_confirmed)
                        <form action="/user/two-factor-authentication" method="post">
                            @csrf
                            @method('delete')
                            <button type="submit">Disable 2FA</button>
                        </form>
                    <!-- 2FA enabled but not yet confirmed, we show the QRcode and ask for confirmation : -->
                    @elseif(auth()->user()->two_factor_secret)
                        <p>Validate 2FA by scanning the floowing QRcode and entering the TOTP</p>
                        {!! auth()->user()->twoFactorQrCodeSvg() !!}
                        <form action="{{route('two-factor.confirm')}}" method="post">
                            @csrf
                            <input name="code" required/>
                            <button type="submit">Validate 2FA</button>
                        </form>
                    </div>
                    <!-- 2FA not enabled at all, we show an 'enable' button  : -->
                    @else
                        <form action="/user/two-factor-authentication" method="post">
                            @csrf
                            <button type="submit">Activate 2FA</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection