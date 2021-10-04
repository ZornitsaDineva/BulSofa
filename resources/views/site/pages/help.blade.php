@extends('site.master')

@section('siteContent')

<section id="main" class="clearfix help-page">
    <div class="container">

        <div class="breadcrumb-section">
            <!-- breadcrumb -->
            <ol class="breadcrumb">
                <li><a href="{{url('/')}}">@lang('Home')</a></li>
                <li>@lang('Help')</li>
            </ol><!-- breadcrumb -->

        </div><!-- banner -->
        <h1>@lang('Order branded T-shirts and business cards to promote your ad')</h1>

        <img src="/images/category/brand3.png" width="150" height="160">
        <img src="/images/category/brand1.png" width="150" height="160">
        <div>
            <form action="{{ route('sendAdminMessage') }}" id="usrform" method="POST">
                {{ csrf_field() }}
                <h1>{{ Auth::user()->name }}, @lang('contact us through this form:')</h1>
                <textarea rows="4" cols="50" name="comment" form="usrform" placeholder="@lang('Enter text here...')"></textarea>
                <div>
                    <input type="submit" value="@lang('Submit')">
                </div>
            </form>
        </div>
        <!-- row -->
    </div><!-- container -->
</section><!-- myads-page -->

@endsection
