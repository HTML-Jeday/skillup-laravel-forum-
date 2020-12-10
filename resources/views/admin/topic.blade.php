@include('layout.header')





<div class="container admin-page">
    <h1>Topic list</h1>
    @foreach ($topics as $topic)
    <div class='category-item-admin d-flex'>
        <form action="/topic/update" method="POST" class="d-flex">

            @method('PUT')
            <input name='id' class='form-control' value='{{$topic->id}}' type='hidden'>
            @if (Auth::check())
            @if (Auth::user()->id !== $topic->author)
            <input name="title" class='form-control' disabled type="text" value="{{$topic->title}}">
            <input type="text" name='text' disabled class="form-control" value="{{$topic->text}}">

            <select name="parent_id" class='form-control' disabled="">
                @foreach ($subcategories as $subcategory)
                <option @if ($topic->parent_id == $subcategory->id) selected @endif value="{{$subcategory->id}}" type="text">{{$subcategory->title}}</option>
                @endforeach
            </select>
            @else
            <input name="title" class='form-control' type="text" value="{{$topic->title}}">
            <input type="text" name='text' class="form-control" value="{{$topic->text}}">
            <select name="parent_id" class='form-control'>
                @foreach ($subcategories as $subcategory)
                <option @if ($topic->parent_id == $subcategory->id) selected @endif value="{{$subcategory->id}}" type="text">{{$subcategory->title}}</option>
                @endforeach
            </select>

            <button class="btn-warning">update</button>
            @endif
            @endif

        </form>
        <form action="/topic/delete" method="POST" class="d-flex">
            @method('DELETE')
            <input name='id' value='{{$topic->id}}' type="hidden">
            <button class="btn-danger">delete</button>
        </form>

    </div>
    @endforeach

</div>




