<div class="modal" id="modal-create-vcat" role="dialog" >
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h1> Categories <span id = 'pop-hdr-lbl'> </span> </h1>
            </div>
            
            <div class="modal-body">
                {{ Form::open(array('url' => 'admin/add-vehicle-cat/'.$vehicle_info->vehicle_id, 'id' => 'create-vcat-form')) }}
                
                <div id = 'vcat-add-body'>
                    <div class = 'col-md-12'>
                        <div id = 'vcat-add-err' class="alert alert-danger error-msg" role="alert" style = 'display: none;'></div>
                    </div>
                    
                    <div class = 'form-group'>
                        <div class = 'col-md-12'>
                            {{ Form::text('category_label', '', array('class' => 'form-control', 'placeholder' => "Category Label")) }}
                        </div>
                    </div>
                    
                    <div class = 'col-md-12'> <br/></div>
                    
                    <div class = 'form-group'>
                        <div class = 'col-md-12'>
                            <input type="submit" class="btn btn-primary vcat-add-btn" value = 'SAVE'>
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
                
                <div class = 'col-md-12'> <hr/></div>
                
                <div id = 'vcat-list' class = 'col-md-12'>
                    @foreach ($vcat_data as $category)
                    <div class = 'reload-vcat-list'>
                        <a class = 'del-vcat-link' href = '{{ URL::To("admin/delete/vcat/".$category->category_id) }}'>
                            <i class = 'fa fa-times'> </i>
                        </a>
                        {{ $category['category_label'] }}
                    </div>
                    @endforeach
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"> Close </button>
            </div>
            
        </div>
    </div>
</div>