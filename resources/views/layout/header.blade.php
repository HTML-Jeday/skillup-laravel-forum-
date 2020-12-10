<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Forum</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <style>
            /*! normalize.css v8.0.1 | MIT License | github.com/necolas/normalize.css */html{line-height:1.15;-webkit-text-size-adjust:100%}body{margin:0}a{background-color:transparent}[hidden]{display:none}html{font-family:system-ui,-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Arial,Noto Sans,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol,Noto Color Emoji;line-height:1.5}*,:after,:before{box-sizing:border-box;border:0 solid #e2e8f0}a{color:inherit;text-decoration:inherit}svg,video{display:block;vertical-align:middle}video{max-width:100%;height:auto}.bg-white{--bg-opacity:1;background-color:#fff;background-color:rgba(255,255,255,var(--bg-opacity))}.bg-gray-100{--bg-opacity:1;background-color:#f7fafc;background-color:rgba(247,250,252,var(--bg-opacity))}.border-gray-200{--border-opacity:1;border-color:#edf2f7;border-color:rgba(237,242,247,var(--border-opacity))}.border-t{border-top-width:1px}.flex{display:flex}.grid{display:grid}.hidden{display:none}.items-center{align-items:center}.justify-center{justify-content:center}.font-semibold{font-weight:600}.h-5{height:1.25rem}.h-8{height:2rem}.h-16{height:4rem}.text-sm{font-size:.875rem}.text-lg{font-size:1.125rem}.leading-7{line-height:1.75rem}.mx-auto{margin-left:auto;margin-right:auto}.ml-1{margin-left:.25rem}.mt-2{margin-top:.5rem}.mr-2{margin-right:.5rem}.ml-2{margin-left:.5rem}.mt-4{margin-top:1rem}.ml-4{margin-left:1rem}.mt-8{margin-top:2rem}.ml-12{margin-left:3rem}.-mt-px{margin-top:-1px}.max-w-6xl{max-width:72rem}.min-h-screen{min-height:100vh}.overflow-hidden{overflow:hidden}.p-6{padding:1.5rem}.py-4{padding-top:1rem;padding-bottom:1rem}.px-6{padding-left:1.5rem;padding-right:1.5rem}.pt-8{padding-top:2rem}.fixed{position:fixed}.relative{position:relative}.top-0{top:0}.right-0{right:0}.shadow{box-shadow:0 1px 3px 0 rgba(0,0,0,.1),0 1px 2px 0 rgba(0,0,0,.06)}.text-center{text-align:center}.text-gray-200{--text-opacity:1;color:#edf2f7;color:rgba(237,242,247,var(--text-opacity))}.text-gray-300{--text-opacity:1;color:#e2e8f0;color:rgba(226,232,240,var(--text-opacity))}.text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}.text-gray-500{--text-opacity:1;color:#a0aec0;color:rgba(160,174,192,var(--text-opacity))}.text-gray-600{--text-opacity:1;color:#718096;color:rgba(113,128,150,var(--text-opacity))}.text-gray-700{--text-opacity:1;color:#4a5568;color:rgba(74,85,104,var(--text-opacity))}.text-gray-900{--text-opacity:1;color:#1a202c;color:rgba(26,32,44,var(--text-opacity))}.underline{text-decoration:underline}.antialiased{-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}.w-5{width:1.25rem}.w-8{width:2rem}.w-auto{width:auto}.grid-cols-1{grid-template-columns:repeat(1,minmax(0,1fr))}@media (min-width:640px){.sm\:rounded-lg{border-radius:.5rem}.sm\:block{display:block}.sm\:items-center{align-items:center}.sm\:justify-start{justify-content:flex-start}.sm\:justify-between{justify-content:space-between}.sm\:h-20{height:5rem}.sm\:ml-0{margin-left:0}.sm\:px-6{padding-left:1.5rem;padding-right:1.5rem}.sm\:pt-0{padding-top:0}.sm\:text-left{text-align:left}.sm\:text-right{text-align:right}}@media (min-width:768px){.md\:border-t-0{border-top-width:0}.md\:border-l{border-left-width:1px}.md\:grid-cols-2{grid-template-columns:repeat(2,minmax(0,1fr))}}@media (min-width:1024px){.lg\:px-8{padding-left:2rem;padding-right:2rem}}@media (prefers-color-scheme:dark){.dark\:bg-gray-800{--bg-opacity:1;background-color:#2d3748;background-color:rgba(45,55,72,var(--bg-opacity))}.dark\:bg-gray-900{--bg-opacity:1;background-color:#1a202c;background-color:rgba(26,32,44,var(--bg-opacity))}.dark\:border-gray-700{--border-opacity:1;border-color:#4a5568;border-color:rgba(74,85,104,var(--border-opacity))}.dark\:text-white{--text-opacity:1;color:#fff;color:rgba(255,255,255,var(--text-opacity))}.dark\:text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}}
        </style>
        <!-- CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

        <!-- jQuery and JS bundle w/ Popper.js -->
        <script
            src="https://code.jquery.com/jquery-3.5.1.min.js"
            integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
        crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

        <script src="{{ URL::asset("js/app.js") }}"></script>
        <style>
            body{
                font-family: 'Roboto', sans-serif;
                background-color: var(--light);
            }


            nav{
                font-weight: bold;
            }
            .nav-item.active{
                margin-right: 20px;
            }
            .nav-link{
                color: white;
                transition: 0.5s;
            }
            nav a.nav-link:hover{
                color: black;
            }


            .category{
                padding: 10px 40px;
                font-weight: bold;
                text-transform: uppercase;
            }

            .subcategory-item{
                padding: 10px 40px;
                background-color: #fff;
            }
            .subcategory-title{
                color: var(--orange);
            }
            .subcategory-title:hover{
                color: var(--orange);
            }
            .subcategory-description div + div{
                margin-left: 20px;
            }
            .subcategory-description div{
                font-size: 14px;
            }
            .subcategory-item .left{
                padding: 0;
            }

            .main-container .col-7{
                padding: 0;
            }

            .last-topic__topic{
                font-weight: bold;
                color: #000;
            }
            .last-topic__topic:hover{
                color: #000;
            }


            .item-category{
                margin-bottom: 20px;
            }
            .item-statistic{
                background-color: #fff;
            }
            .item-statistic .category-wrapper{
                padding: 10px 40px;
            }


            .topic-top{
                padding: 10px 40px;
                color: #fff;
                font-weight: bold;
            }
            .topic-container{
                padding: 10px 40px;
                color: var(--orange);
                background-color: #fff;
            }
            .avatar{
                margin-right: 20px;
            }
            .heading .title a{
                color: var(--orange);
            }
            .heading .title a:hover{
                color: var(--orange);

            }
            .topic-container + .topic-container{
                border-top: 1px solid #ccc;
            }
            .addMessage{
                margin-top: 100px;
                text-align: right;
                display: block;
                color: var(--orange);
            }
            .addMessage:hover{
                color: var(--orange);
            }
            .topic-item .topic-title{
                padding: 10px 40px;
                font-size: 25px;
                font-weight: bold;
            }
            .topic-wrapper{
                background-color: #fff;
            }
            .author-content{
                padding: 30px 40px;

            }
            .author-content + .author-content{
                border-top: 1px solid #ccc;
                margin-top: 10px;
            }
            .author-content .user{
                background-color: #ffc107;
                padding: 10px;
                color: #fff;
                width: 200px;

                margin-right: 50px;
            }
            .author-content .user .name{
                text-align: center;
                margin-bottom: 20px;
            }
            .author-content .user img{
                margin: 0 auto;
                display: block;
            }
            .author-content .info{
                font-size: 10px;
            }
            .author-text{
                display: flex;
                flex-direction: column;
                justify-content: space-between;
            }
            .author-text-createdtime{
                font-size: 10px;
            }
            .topic-status{
                padding: 5px 40px;
                text-align: right;
                font-size: 12px;
            }
            .topic-item .topic-title{
                justify-content: space-between;
            }
            .topic-item .topic-description{
                font-size: 12px;
            }
            .author-message{
                margin-bottom: 80px;
            }
            .subcategory-link{
                text-decoration: underline;
            }
            .top-line{
                margin-bottom: 100px;
            }
            .top-line a{
                text-decoration: underline;
                display: block;
                margin: 10px 0;
                color: #ffc107;
                cursor: pointer;
            }
            .author-message .author-text{
                max-width: 700px;
            }
            .addCategory{
                font-size: 20px;
                margin-bottom: 20px;
                margin-top: 100px;
                display: block;
                width: 23px;
                height: 31px;
                text-align: center;

            }
            .addTopic{
                text-align: right;
                display: block;
                color: var(--orange);
            }
            .addTopic:hover{
                color: var(--orange);
            }
            .top-container{
                margin-top: 100px;
            }

            .admin-page h1{
                margin-top: 100px;
                text-align: center;
            }
            .category-create{
                margin-top: 30px;
                justify-content: center;
            }
            .admin-page .author-item{
                max-width: 250px;
                width: 100%;
            }
            .admin-page .topic-name{
                width: 100px;
                margin: 0 20px;
            }
            .category-page .category-item-admin, .subcategory-page .category-item-admin{
                justify-content: center;
            }
            .user-item .user-name{
                width: 100%;
                max-width: 200px;
            }

            .user-wrapper{
                max-width: 500px;
                margin: 0 auto;
            }

            .user-wrapper .heading{
                font-weight: bold;
                margin-bottom: 20px;
            }
            .user-page{
                margin-top: 100px;
            }

            .user-profile{
                max-width: 400px;
                margin: 50px auto;
                background-color: #fff;

            }
            .user-profile .user-top{
                padding: 10px 40px;
                text-align: center;
            }
            .user-profile .user-container{
                padding: 10px 40px;
            }
            .user-container div{
                margin: 10px 0;
            }
            .messages-wrapper{
                background-color: #fff;
            }

        </style>
    </head>


    <nav class="navbar navbar-expand-lg bg-warning">
        <div class="container">
            <a class="navbar-brand text-white" href="/">Forum</a>
            @if(Auth::check())
            @if(Auth::user()->role == 'admin')
            <a class="text-white nav-link" href="/category">All categories</a>
            <a class="text-white nav-link" href="/subcategory">All subcategories</a>
            @endif
            @endif
            @if(Auth::check())
            @if(Auth::user()->role == 'admin' || (Auth::user()->role == 'moderator'))
            <a class="text-white nav-link" href="/topic">All topics</a>
            <a class="text-white nav-link" href="/message">All messages</a>
            @endif
            @endif
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    @if( Auth::user()->name ?? '' )
                    <li class="nav-item active">
                        <a class="nav-link text-white" href="/user/profile">Hi, {{ Auth::user()->name }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/logout">Logout</a>
                    </li>
                    @else
                    <div class="d-flex">
                        <li class="nav-item active">
                            <a class="nav-link text-white" href="#">Hi, Guest</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="modal" href="#" data-target="#loginModal">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="modal" href="#" data-target="#registerModal">Register</a>
                        </li>
                    </div>
                    @endif
                </ul>
            </div>
        </div>
    </nav>





    <!-- Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Login</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="/login">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Email</label>
                            <input name="email" type="email" class="form-control" placeholder="user@google.com">
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input name="password" type="password" class="form-control" placeholder="******">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal -->

    <!-- Modal -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Register</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form method="POST" action="/register">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Email</label>
                            <input name="email" type="email" class="form-control" placeholder="user@google.com">
                        </div>
                        <div class="form-group">
                            <label>Username</label>
                            <input name="name" type="text"  class="form-control" placeholder="superuser">
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input name="password" type="password" class="form-control" placeholder="dwadaw">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary">Register</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal -->

    <div class="container">


        @if (\Session::has('success'))
        <div class="alert alert-success">
            {!! \Session::get('success') !!}
        </div>
        @endif
        @if (\Session::has('error'))
        <div class="alert alert-danger">
            {!! \Session::get('error') !!}
        </div>
        @endif
        @if (\Session::has('ok'))
        <div class="alert alert-warning">
            {!! \Session::get('ok') !!}
        </div>
        @endif
    </div>


