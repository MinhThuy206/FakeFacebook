@extends('layout.layout')
@section('title')
    Friend
@endsection
@section('content')
    <style>
        .option1 p,
        .option2 p,
        .option3 p,
        .option4 p {
            display: flex;
            align-items: center;
            padding: 7px;
            cursor: pointer;
        }

        .option1 p i,
        .option2 p i,
        .option3 p i,
        .option4 p i {
            margin-right: 5px;
        }

        .option1 p:hover,
        .option2 p:hover,
        .option3 p:hover,
        .option4 p:hover {
            background-color: #f0f0f0; /* Màu nền khi hover */
        }
    </style>
    <div class="container-fluid" style="margin-top: 56px; display: flex; flex-direction: column;height: 100vh">
        <div class="row" style="flex: 1;">
            <div class="col-md-3" id="session1">
                <h4>Bạn bè</h4>

                <div class="option1">
                    <p><i class="material-icons">people</i> Trang chủ </p>
                </div>

                <div class="option2">
                    <p><i class="material-icons">person_add</i> Lời mời kết bạn</p>
                </div>

                <div class="option3">
                    <p><i class="material-icons">lightbulb</i> Gợi ý</p>
                </div>

                <div class="option4">
                    <p><i class="material-icons">group</i> Tất cả bạn bè</p>
                </div>

            </div>

            <div class="col-md-9" id="session3" style="background-color: #CFD1D5;">
                <div class="list-user">
                    <div class="title">
                        <h4>Những người bạn có thể biết</h4>
                    </div>
                    <br>
                    <div id="add-friend-list">

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>
        let username = "{{ auth()->user()->username }}";
        let profileUrlBase = "{{ route('profile', ['username' => ':username']) }}";
    </script>
    <script src="{{asset('/js/friend.js')}}"></script>
    <script>
        $(document).ready(function () {
            getDataUser({
                'user_id': {{auth()-> user() -> id}},
                'orderBy': 'created_at',
                'order': 'DESC',
                'page': '0',
                'pagesize': '5'
            })
        })
    </script>

@endsection

