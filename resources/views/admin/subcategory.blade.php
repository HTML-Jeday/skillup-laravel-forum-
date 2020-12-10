@include('layout.header')

<div class="container admin-page subcategory-page">
    <h1>Subcategory list</h1>
    @foreach ($subcategories as $subcategory)
    <div class='category-item-admin d-flex'>
        <form action="/subcategory/update" method="POST" class="d-flex">
            @method('PUT')
            <input name='id' class='form-control' value='{{$subcategory->id}}' type='hidden'>
            <input name="title" class='form-control' type="text" value="{{$subcategory->title}}">
            <select name="parent_id" class="form-control">
                @foreach ($categories as $category)
                <option @if ($subcategory->parent_id == $category->id) selected @endif value="{{$category->id}}" type="text">{{$category->title}}</option>
                @endforeach
            </select>
            <button class="btn-warning">update</button>
        </form>
        <form action="/subcategory/delete" method="POST" class="d-flex">
            @method('DELETE')
            <input name='id' value='{{$subcategory->id}}' type="hidden">
            <button class="btn-danger">delete</button>
        </form>
    </div>
    @endforeach
    <div class="category-item-admin d-flex category-create">
        <form action="/subcategory/create" method="POST" class="d-flex">
            <input name="title" type="text" class='form-control'>
            <select name="parent_id" class="form-control">
                @foreach ($categories as $category)
                <option value="{{$category->id}}" type="text">{{$category->title}}</option>
                @endforeach
            </select>
            <button class="btn-primary">create</button>
        </form>
    </div>
</div>

