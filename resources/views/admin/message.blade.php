
@include('layout.header')





<div class="container admin-page">
    <h1>Message list</h1>
    @foreach ($messages as $message)
    @foreach ($users as $u)
    @if ($u->id == $message->author)
    <div class='category-item-admin d-flex'>
        <div class="author-item"> Author: {{$u->name}}</div>
        <form action="/message/update" method="POST" class="d-flex">
            @method('PUT')
            @if(Auth::user()->id !== $message->author)
            <input name='text' value='{{$message->text}}' disabled type="text" class="form-control">
            @else
            <input name='id' value="{{$message->id}}" class="form-control" type='hidden'>
            <input name='text' value='{{$message->text}}' type="text" class="form-control">

            <button class="btn-warning">update</button>
            @endif


        </form>
        <form action="/message/delete" method="POST" class="d-flex">
            @method('DELETE')
            <input name='id' value="{{$message->id}}" type='hidden'>
            <button class="btn-danger">delete</button>
        </form>

    </div>
    @endif
    @endforeach
    @endforeach

</div>





