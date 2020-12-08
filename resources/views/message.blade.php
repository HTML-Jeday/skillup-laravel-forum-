

@foreach ($messages as $message)
<div>{{$message->title}}</div>
@endforeach
<strong>create message</strong>
<form action="/message/create" method="POST">
    <input name="text" type="text" >
    <select name="parent_id">
        @foreach ($topics as $topic)
        <option value="{{$topic->id}}" type="text">{{$topic->title}}</option>
        @endforeach
    </select>
    @foreach ($topics as $topic)
    @if($topic->opened)
    <input type="checkbox" checked disabled>
    @else
    <input  type="checkbox" disabled>
    @endif
    @endforeach
    <button class="btn-success">create</button>
</form>



<strong>delete message</strong>

@foreach ($messages as $message)
<form action="/message/delete" method="POST">
    @method('DELETE')
    <input name='id' value="{{$message->id}}" type='hidden'>
    <div>{{$message->text}}</div>
    <button class="btn-danger">delete</button>
</form>
@endforeach





<strong> update message</strong>

@foreach ($messages as $message)
<form action="/message/update" method="POST">
    @method('PUT')
    <input name='id' value="{{$message->id}}" type='hidden'>
    <input name='text' value='{{$message->text}}' type="text" >
    <select>
        @foreach ($topics as $topic)
        <option value="{{$topic->id}}" type="text">{{$topic->title}}</option>
        @endforeach
    </select>
    <button class="btn-warning">update</button>
</form>
@endforeach





