@include('layout.header')

<div class="container top-container">
    @if(Auth::check())
    @if(Auth::user()->role == 'admin')
    <a href="#" class="bg-warning text-white  " data-toggle="modal" data-target="#categoryModal">Category</a>
    <a href="#" class="bg-warning text-white  " data-toggle="modal" data-target="#subcategoryModal">Subcategory</a>


    <!-- Modal -->
    <div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="/admin/category/create">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Title</label>
                            <input name="title" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <!-- Modal -->
    <div class="modal fade" id="subcategoryModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Subcategory</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="/admin/subcategory/create">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Title</label>
                            <input name="title" type="text" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Choose category</label>
                            <select name="parent_id">
                                @foreach($categories as $category)
                                <option value="{{$category->id}}">{{$category->title}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal -->

    @endif
    @endif
</div>
<div class="container d-flex main-container">

    <div class="col-7">

        @foreach($categories as $category)

        <div class="item-category">
            <div class="category bg-warning text-white rounded-top">{{$category->title}}</div>
            <div class="category-wrapper">
                @foreach($subcategories as $subcategory)
                @if ($subcategory->parent_id == $category->id)
                <div class="subcategory-item d-flex">
                    <div class="left col-7">
                        <a class="subcategory-title" href="/subcategory/{{$subcategory->id}}">{{$subcategory->title}}</a>

                        <div class="subcategory-description d-flex">
                            <div class="subcategory-topics">
                                Topics:
                                {{$subcategory->topics->count()}}
                            </div>

                            <div class="subcategory-messages">Messages:
                                {{$subcategory->messages->count()}}
                            </div>

                        </div>
                    </div>
                    <div class="right col-5 d-flex">
                        <div class="last-topic">
                            <span class="last-topic__title">Latest:</span>
                            <a href="/topic/{{$subcategory->topics->last()->id ?? ''}}" class="last-topic__topic">
                                {{$subcategory->topics->last()->title ?? ''}}
                            </a>
                        </div>
                    </div>
                </div>
                @endif
                @endforeach
            </div>
        </div>
        @endforeach
    </div>



    <div class="col-5">
        <div class="item-statistic">
            <div class="category bg-warning text-white rounded-top">Forum statistic</div>
            <div class="category-wrapper">
                <div>Topics: {{$topics->count()}}</div>
                <div>Messages: {{$messages->count()}}</div>
                <div>Users: {{$users->count()}}</div>
            </div>
        </div>
    </div>

</div>
