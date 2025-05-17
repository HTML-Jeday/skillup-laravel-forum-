@include('layout.header')
<div class="container">
    <div class='col-12'>
        <div class="top-line">
            <a class= subcategory-top" href="/subcategory/{{$topic->parent_id}}">Back to subcategory</a>
        </div>
        @if(Auth::check())
        @if($topic->status->isOpened())
        <a href="#" class="addMessage" data-toggle="modal" href="#" data-target="#createMessage">Create message</a>
        @endif
        <!-- Modal -->
        <div class="modal fade" id="createMessage" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Create message</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="/message/create" method="POST">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="exampleInputPassword1">Text</label>
                                <textarea type="text" name="text" class="form-control" id="exampleInputPassword1"></textarea>
                            </div>
                            <input name="parent_id" type='hidden' value="{{$topic->id}}">
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

        @foreach ($users as $u)
        <div class='topic-item'>
            @if ($u->id == $topic->author)
            <div class="topic-title bg-warning rounded-top text-white d-flex">
                <div>
                    <div>{{$topic->title}}</div>
                    <div class="topic-description">author: {{$u->name}}, Subcategory:<a class="text-white subcategory-link" href="/subcategory/{{$subcategory->id}}">{{$subcategory->title}}</a>, Created:{{$topic->created_at}}</div>
                </div>
                <div class="topic-status">Topic status: @if ($topic->status->isOpened()) Open @else Closed @endif</div>
            </div>
            @endif
            <div class="topic-wrapper">
                @if ($u->id == $topic->author)
                <div class="author-content d-flex author-message">
                    <div class="user rounded">
                        <img class="avatar img-fluid" style="height: 100px; width: 100px;" src="{{ URL::asset("images/user.png") }}">
                        <div>
                            <div class="name">{{$u->name}}</div>
                            <div class="info">
                                <div class="role">Role:{{$u->role}}
                                </div>
                                <div class="date">Registered: {{Carbon\Carbon::parse($u->created_at)->diffForHumans()}}</div>
                                <div class="messages">Messages: {{$u->messages->count()}}</div>
                                <div class="gender">Gender:
                                    {{$u->gender->label()}}
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="author-text">
                        <div>{{$topic->text}}</div>
                        <div class="author-text-createdtime">
                            @if ($topic->created_at == $topic->updated_at)
                            {{Carbon\Carbon::parse($topic->created_at)->diffForHumans()}}
                            @else
                            Changed: {{Carbon\Carbon::parse($topic->updated_at)->diffForHumans()}}
                            @endif
                        </div>
                    </div>
                </div>
                @endif

            </div>

        </div>

    </div>
    @endforeach


    <div class="container">
        @if($topic->messages->count() !== 0)
        <h2>Answers</h2>
        @endif
        <div class="messages-wrapper">
            @foreach ($topic->messages as $message)
            @foreach ($users as $u)
            @if ($message->author == $u->id)
            <div class="author-content d-flex author-message">
                <div class="user rounded">
                    <img class="avatar img-fluid" style="height: 100px; width: 100px;" src="{{ URL::asset("images/user.png") }}">
                    <div>
                        <div class="name">{{$u->name}}</div>
                        <div class="info">
                            @php $date = Carbon\Carbon::now(); @endphp
                            <div class="role">Role:
                                {{$u->role}}
                            </div>
                            <div class="date">Registered: {{$date->toFormattedDateString($u->created_at)}}</div>
                            <div class="messages">Messages: {{$u->messages->count()}}</div>
                            <div class="gender">Gender:
                                {{$u->gender->label()}}
                            </div>
                        </div>

                    </div>
                </div>
                <div class="author-text">
                    <div>{{$message->text}}</div>
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
            @endif
            @endforeach
            @endforeach

        </div>
    </div>
</div>
</div>
