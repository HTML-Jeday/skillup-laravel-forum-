@include('layout.header')
<div class="container">
    <div class="col-12">
        <div class="top-line">
            <a class=" subcategory-top  ">You are now in subcategory:{{$subcategory->title}}</a>
        </div>
        @if(Auth::check())
        <a href="#" class="addTopic" data-toggle="modal" href="#" data-target="#createTopic">Create topic</a>

        <!-- Modal -->
        <div class="modal fade" id="createTopic" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Create topic</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="/topic/create" method="POST">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Title</label>
                                <input type="text" name="title" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Text</label>
                                <textarea type="text" name="text" class="form-control" id="exampleInputPassword1"></textarea>
                            </div>
                            <div class="form-group form-check">


                                <input value="0" name="opened"  type="hidden">
                                <input type="checkbox" checked  value="1" name="opened" class="form-check-input" id="exampleCheck1">
                                <label class="form-check-label" for="exampleCheck1">is topic open?</label>
                            </div>
                            <input name="parent_id" type='hidden' value="{{$subcategory->id}}">
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



        @if ($subcategory->topics->count() == 0)
        <div>No topic created</div>
        @else
        <div class="topic-top bg-warning d-flex rounded-top">
            <div class="col-8">Heading</div>
            <div class="col-2">Answers</div>
            <div class="col-2">Latest message</div>
        </div>
        @foreach($subcategory->topics as $topic)
        <div class="topic-container d-flex">
            @foreach ($users as $u)
            @if($u->id == $topic->author)
            <div class="topic-title col-8 d-flex">
                <div class="avatar">
                    <img class="rounded img-thumbnail" style="width: 50px; height: 50px" src='{{ URL::asset("images/{$u->avatar}") }}'>
                </div>
                <div class="heading">
                    <div class="title">
                        <a href="/topic/{{$topic->id}}">{{$topic->title}}</a>
                    </div>
                    <div class="author">{{$u->name}},
                        @php $carbon = Carbon\Carbon::now() @endphp
                        {{$carbon->diffForHumans($topic->created_at)}}
                    </div>
                </div>
            </div>
            @if ($topic->messages->count() == 0)
            <div class="answers col-2">Answer: 0</div>
            <div class="last-message col-2">
                <div class="author"></div>
                <div class="time"></div>
            </div>
            @else
            <div class="answers col-2">Answer: {{$topic->messages->count() ?? ''}}</div>
            <div class="last-message col-2">
                <div class="author">{{$u->name ?? ''}}</div>
                <div class="time">{{$carbon->diffForHumans($topic->messages->last()->created_at)}}</div>
            </div>
            @endif
        </div>
        @else

        @endif
        @endforeach
        @endforeach
        @endif
    </div>

</div>














