@extends('admin.master')
@section('adminContent')

<link rel="stylesheet" href="{{asset('site-assets/css/icofont/css/icofont.css')}}">

<div class="row">    
    <!-- left column -->
    <div class="col-md-12">
        <!-- general form elements -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Categories (click one to view Sub Categories)</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>                
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">

                    <div class="col-md-6">
                        <ul class="list-group">
                            @foreach($categories as $aCategory)
                            <li class="list-group-item">
                                <a data-toggle="tab" href="#category{{$aCategory->category_id}}"><i class="{{$aCategory->category_icon}}"></i> 
                                <!--<img src="{{asset($aCategory->category_image)}}" width="20"/>-->
                                &nbsp;&nbsp; {{$aCategory->category_title_bg}} ({{$aCategory->category_title_en}})</a>
                                <a class="pull-right" href="{{url('/admin/category/edit/'.$aCategory->category_id)}}"><i class="fa fa-pencil"></i></a>
                            </li>
                            @endforeach
                        </ul>                                    
                    </div>
                    <div class="col-md-6 tab-content">
                        <?php
                        foreach ($categories as $aCategory) {
                            $subCategories = App\Models\Subcategory::where('parent_category_id', $aCategory->category_id)->get();
                            ?>
                            <div class="list-group tab-pane" id="category{{$aCategory->category_id}}">
                                @foreach($subCategories as $aSubCat)                                
                                <a class="list-group-item" href="{{url('admin/subcategory/edit/'.$aSubCat->subcategory_id)}}">{{$aSubCat->subcategory_title_bg}} ({{$aSubCat->subcategory_title_en}})</a>
                                @endforeach
                            </div>
                        <?php }
                        ?>                        
                    </div>
                </div> 
            </div>            
        </div>
        <!-- /.box -->    
    </div>    
</div>
<!-- /.row -->

@endsection