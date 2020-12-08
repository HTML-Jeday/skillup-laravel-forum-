@include('layout.header')
<div class="container">
    <div class="col-12">
        <div class="top-line">
            <a class=" subcategory-top  ">You are now in subcategory:{{$subcategory->title}}</a>
        </div>
        @if(Auth::check())
        <a href="#" class="addTopic ">Create topic</a>
        @endif
        @if ($subcategory->topics->count() == 0)
        <div>No topic created</div>
        @else
        <div class="topic-top bg-warning d-flex rounded-top">
            <div class="col-8">Heading
            </div>
            <div class="col-2">Answers</div>
            <div class="col-2">Latest message</div>
        </div>
        @foreach($subcategory->topics as $topic)
        <div class="topic-container d-flex">
            <div class="topic-title col-8 d-flex">
                <div class="avatar">
                    <img class="rounded img-thumbnail" style="width: 50px; height: 50px" src='{{ URL::asset("images/{$users[$topic->author]->avatar}") }}'>
                </div>
                <div class="heading">
                    <div class="title">
                        <a href="/topic/{{$topic->id}}">{{$topic->title}}</a>
                    </div>
                    <div class="author">{{$users[$topic->author]->name}},
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
                <div class="author">{{$users[$topic->messages->last()->author]->name ?? ''}}</div>
                <div class="time">{{$carbon->diffForHumans($topic->messages->last()->created_at)}}</div>
            </div>
            @endif
        </div>
        @endforeach
        @endif
    </div>

</div>














