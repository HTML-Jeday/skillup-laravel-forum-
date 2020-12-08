@include('layout.header')
<div class="container">
    <div class='col-12'>
        @if(Auth::check())
        darova
        @endif
        <div class='topic-item'>
            <div class="topic-title bg-warning rounded-top text-white d-flex">
                <div>
                    <div>{{$topic->title}}</div>
                    <div class="topic-description">author: {{$users[$topic->author]->name}}, Subcategory:<a class="text-white subcategory-link" href="/subcategory/{{$subcategory->id}}">{{$subcategory->title}}</a>, Created:{{$topic->created_at}}</div>
                </div>
                <div class="topic-status">Topic status: @if ($topic->opened) Open @else Close @endif</div>
            </div>
            <div class="topic-wrapper">

                <div class="author-content d-flex author-message">
                    <div class="user rounded">
                        <img class="avatar img-fluid" style="height: 100px; width: 100px;" src="{{ URL::asset("images/user.png") }}">
                        <div>
                            <div class="name">{{$users[$topic->author]->name}}</div>
                            <div class="info">
                                @php $date = Carbon\Carbon::now(); @endphp
                                <div class="role">Role: {{$users[$topic->author]->role}}</div>
                                <div class="date">Registered: {{$date->toFormattedDateString($users[$topic->author]->created_at)}}</div>
                                <div class="messages">Messages: {{$users[$topic->author]->messages->count()}}</div>
                                <div class="gender">Gender: {{$users[$topic->author]->gender ?? 'unknow'}}</div>
                            </div>

                        </div>
                    </div>
                    <div class="author-text">
                        <div>{{$topic->text}}</div>
                        <div class="author-text-createdtime">
                            @if ($topic->created_at == $topic->updated_at)
                            @php $carbon = Carbon\Carbon::now() @endphp
                            {{$carbon->diffForHumans($topic->created_at)}}
                            @else
                            @php $carbon = Carbon\Carbon::now() @endphp
                            Changed: {{$carbon->diffForHumans($topic->updated_at)}}
                            @endif
                        </div>
                    </div>
                </div>
                @foreach ($topic->messages as $message)
                <div class="author-content d-flex">
                    <div class="user rounded">
                        <img class="avatar img-fluid" style="height: 100px; width: 100px;" src="{{ URL::asset("images/user.png") }}">
                        <div>
                            <div class="name">{{$users[$message->author]->name}}</div>
                            <div class="info">
                                @php $date = Carbon\Carbon::now(); @endphp
                                <div class="role">Role: {{$users[$message->author]->role}}</div>
                                <div class="date">Registered: {{$date->toFormattedDateString($users[$message->author]->created_at)}}</div>
                                <div class="messages">Messages: {{$users[$message->author]->messages->count()}}</div>
                                <div class="gender">Gender: {{$users[$message->author]->gender ?? 'unknow'}}</div>
                            </div>

                        </div>
                    </div>
                    <div class="author-text">
                        <div>{{$message->text}}</div>
                        <div class="author-text-createdtime">
                            @if ($message->created_at == $message->updated_at)
                            @php $carbon = Carbon\Carbon::now() @endphp
                            {{$carbon->diffForHumans($message->created_at)}}
                            @else
                            @php $carbon = Carbon\Carbon::now() @endphp
                            Changed: {{$carbon->diffForHumans($message->updated_at)}}
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>

</div>
