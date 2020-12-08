@include('layout.header')


<div class="container d-flex main-container">
    <div class="col-7">
        @if($userRole == 'admin')
        <a href="#" class="bg-warning text-white addCategory rounded">+</a>

        @endif
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
@include('layout.footer')