@include('layout.header')
<div class="container user-page">



    <div class="user-profile">
        <div class='bg-warning user-top rounded-top text-white'>User profile</div>
        <div class='user-container'>

            <form action="/user/update" method="POST">
                @method('PUT')
                <input type="hidden" name='id' value="{{$user->id}}">
                <div class="d-flex">Nickname:<input class='form-control' type='text' name="name" value={{$user->name}}></div>
                <button class="btn btn-warning" type='submit'>Update</button>
            </form>
            <div>Email:{{$user->email}}</div>
            <form action="/user/update" method="POST">
                @method('PUT')
                <div class="d-flex">
                    <input type="hidden" name='id' value="{{$user->id}}">
                    First Name:<input class='form-control' type='text' name="FirstName" value={{$user->FirstName}}>
                </div>
                <button class="btn btn-warning" type='submit'>Update</button>
            </form>
            <form action="/user/update" method="POST">
                @method('PUT')
                <div class="d-flex">
                    <input type="hidden" name='id' value="{{$user->id}}">
                    Last Name:<input class='form-control' type='text' name="LastName" value={{$user->LastName}}>
                </div>
                <button class="btn btn-warning" type='submit'>Update</button>
            </form>
            <form action="/user/update" method="POST">
                @method('PUT')
                <div class="d-flex">
                    <input type="hidden" name='id' value="{{$user->id}}">
                    New password:<input class='form-control' type='text' name="password">
                </div>
                <button class="btn btn-warning" type='submit'>Update</button>
            </form>

            <form action="/user/update" method="POST">
                @method('PUT')
                <input type="hidden" name='id' value="{{$user->id}}">
                <div>Gender:
                    <select name="gender">
                        <option value="{{ \App\Enums\Gender::UNKNOWN->value }}" {{ $user->gender === \App\Enums\Gender::UNKNOWN ? 'selected' : '' }}>Unknown</option>
                        <option value="{{ \App\Enums\Gender::FEMALE->value }}" {{ $user->gender === \App\Enums\Gender::FEMALE ? 'selected' : '' }}>Female</option>
                        <option value="{{ \App\Enums\Gender::MALE->value }}" {{ $user->gender === \App\Enums\Gender::MALE ? 'selected' : '' }}>Male</option>
                    </select>
                </div>
                <button class="btn btn-warning" type='submit'>Update</button>
            </form>
            <div>Role:{{$user->role}}</div>
            <div>Registered:{{$user->created_at}}</div>
            <div>Verified: @if ($user->verified == '0') No
                <form action="/user/verification" method="POST">
                    <input name="user_id" value="{{$user->id}}" type="hidden">
                    <button type="submit">send verification email</button>
                </form>
                @else ($user->verified == '1')
                Yes @endif
            </div>
        </div>
    </div>
    <div class="user-wrapper">
        @if($user->role == 'admin')
        <div class='heading'>User list</div>
        @foreach ($users as $u)
        <div class="d-flex user-item">
            <div class='user-name'>{{$u->id}}.{{$u->name}}</div>
            @if($user->id !== $u->id)
            <form method='post' action='/user/delete'>
                @method('DELETE')
                <input name='id' value='{{$u->id}}' type='hidden'>
                <button class='btn btn-danger'>delete</button>
            </form>
            @endif
        </div>
        @endforeach
        @endif
    </div>
</div>