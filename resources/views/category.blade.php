
<strong>category create</strong>
@foreach ($categories as $category)
<div>{{$category->title}}</div>
@endforeach

<form action="/category/create" method="POST">
    <input name="title" type="text">
    <button class="btn-success" type="submit">create</button>
</form>



<strong>category delete</strong>

@foreach ($categories as $category)
<form action="/category/delete" method="POST">
    @method('DELETE')
    <input name='id' value="{{$category->id}}" type='hidden'>
    <div>{{$category->title}}</div>
    <button class="btn-danger">delete</button>
</form>
@endforeach





<strong>category update</strong>

@foreach ($categories as $category)
<form action="/category/update" method="POST">
    @method('PUT')
    <input name='id' value="{{$category->id}}" type='hidden'>
    <input name='title' value='{{$category->title}}' type="text" >
    <button class="btn-warning">update</button>
</form>
@endforeach



