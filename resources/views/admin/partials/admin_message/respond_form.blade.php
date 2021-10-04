@extends('admin.master')
@section('adminContent')



<div class="box">

    <div class="container-fluid">
        <form action="{{ route('adminMessageRespond') }}" id="response" method="POST" >
            {{ csrf_field() }}
            <input type="hidden" value="{{$admin_message->admin_message_id}}" name="id">
            <!--<h4> @lang('Enter response to') {{ $admin_message->name }}@lang(', through this form:')</h4>-->
            <h4> @lang('Enter response to :name, through this form:', ['name' => $admin_message->name])</h4>
            <textarea rows="4" cols="50" disabled>{{$admin_message->comment}}</textarea>
            <textarea rows="4" cols="50" name="response" placeholder="@lang('Enter text here...')"></textarea>
            <div ><input type="submit"></div>
            

        </form>
    </div>

</div>



<!-- /.row -->

@endsection