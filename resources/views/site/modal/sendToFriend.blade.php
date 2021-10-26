@if ($errors->has('email'))
<script type="text/javascript">
    $(window).on('load',function(){
        $('#sendToFriend').modal('show');
    });
</script>
@endif
<div class="modal fade" id="sendToFriend">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body" id="modal-popup-body">
                <div class="row">
                    <div class="col-md-12">
                        @guest
                        <h3>@lang('You need to login to send email')</h3>
                        <a class="btn btn-primary" href="{{url('login')}}">@lang('Login')</a>
                        @else
                        <h3>@lang('Send this ad')</h3>

                        {!! Form::open([ 'url' => LaravelLocalization::localizeUrl('/sendToFriend'),'method' => 'post']) !!}
                        <div class="form-group">
                            <label class="control-label">@lang('Your friend`s email')</label>
                            {!! Form::hidden('post_id', $adDetails->post_id) !!}
                            {!! Form::email('email', null, ['class' => 'form-control', 'required' => true]) !!}
                            @if ($errors->has('email'))
                            <span class="text-danger">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-danger">Submit</button>
                        </div>

                        {!! Form::close()!!}
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
