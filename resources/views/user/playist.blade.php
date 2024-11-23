@extends('user.layout')

@section('title', 'Playist')

@push('styles')
@vite('resources/css/playist.css')
@endpush



@section('content')

<body>
    <div class="user">
        <span>
            User
        </span>
        <img alt="User" height="40" src="images/profile/hinh tao.jpg" width="40" />
    </div>
    <div class="container">
        <div class="left-panel">
            <div class="album-cover">
                <img alt="Album cover 1" height="150" src="https://storage.googleapis.com/a1aa/image/BEmMbvrRne3UcqIsWscmafVu1AyjrGCp9EnYeszIpKdhxJnnA.jpg" width="150" />
                <img alt="Album cover 2" height="150" src="https://storage.googleapis.com/a1aa/image/cvizA7M7ipZbNd2L7StmwNI91LDfWwWzrTsIyD0kUUCbcy5JA.jpg" width="150" />
                <img alt="Album cover 3" height="150" src="https://storage.googleapis.com/a1aa/image/obTGgLJmPwruFRjgfKxMcYdlC7RVM70nPZmJoqctAUSe4kzTA.jpg" width="150" />
                <img alt="Album cover 4" height="150" src="https://storage.googleapis.com/a1aa/image/UD5Qe7PW4zW6HCQRoDXH9zArXZFr9Crhzir4XqV2qWNdcy5JA.jpg" width="150" />
            </div>
            <div class="playlist-info">
                <h2>
                    pock
                </h2>
                <div class="user-info">
                    <i class="fas fa-user-circle">
                    </i>
                    Dat Le
                </div>
                <p>
                    Danh sách phát • Công khai • 2024
                </p>
                <p>
                    3 bản nhạc • 11 phút, 18 giây
                </p>
                <p>
                    pock
                </p>
                <div class="actions">
                    <button>
                        <i class="fas fa-edit">
                        </i>
                    </button>
                    <button>
                        <i class="fas fa-play">
                        </i>
                    </button>
                    <button>
                        <i class="fas fa-ellipsis-h">
                        </i>
                    </button>
                </div>
            </div>
        </div>
        <div class="right-panel">
            <div class="song-list">
                <h3>
                    Sắp xếp
                </h3>
                <div class="song-item">
                    <div class="song-info">
                        <img alt="Song cover 1" height="50" src="https://storage.googleapis.com/a1aa/image/FhKf4cHofBlLPkrexkwfC0AliLDLOyBQk7IBmUMTMWMIjTOPB.jpg" width="50" />
                        <div>
                            <p>
                                Hit Em Up (cùng với Outlawz)
                            </p>
                            <p>
                                2Pac • Death Row Greatest Hits
                            </p>
                        </div>
                    </div>
                    <div class="song-actions">
                        <p>
                            5:16
                        </p>
                    </div>
                </div>
                <div class="song-item">
                    <div class="song-info">
                        <img alt="Song cover 2" height="50" src="https://storage.googleapis.com/a1aa/image/EfZvf2K5ZQlCdkOeZ51UWtSqHzJWjJAUXTvaZICHcfVsjTOPB.jpg" width="50" />
                        <div>
                            <p>
                                CUTE DEPRESSED
                            </p>
                            <p>
                                Dyan Dxddy • CUTE DEPRESSED
                            </p>
                        </div>
                    </div>
                    <div class="song-actions">
                        <p>
                            1:37
                        </p>
                    </div>
                </div>
                <div class="song-item">
                    <div class="song-info">
                        <img alt="Song cover 3" height="50" src="https://storage.googleapis.com/a1aa/image/jh9BtNvfDu16WafJD2eEcVZHbDg2Tc8zh9isqPGTysYqxJnnA.jpg" width="50" />
                        <div>
                            <p>
                                Răng Khôn (cùng với DREAMeR và RIN9)
                            </p>
                            <p>
                                Phí Phương Anh
                            </p>
                        </div>
                    </div>
                    <div class="song-actions">
                        <p>
                            4:25
                        </p>
                        <i class="fas fa-thumbs-up">
                        </i>
                    </div>
                </div>
            </div>
            <div class="song-list">
                <h3>
                    Đề xuất
                </h3>
                <div class="song-item">
                    <div class="song-info">
                        <img alt="Song cover 4" height="50" src="https://storage.googleapis.com/a1aa/image/CDfNMldiBdVuKCSrDHPAxWoTbTxsBazcoRBIsBQmSxkbcy5JA.jpg" width="50" />
                        <div>
                            <p>
                                MASHA ULTRAFUNK
                            </p>
                            <p>
                                HISTED và TXVSTERPLAYA • MASHA ULTRAFUNK
                            </p>
                        </div>
                    </div>
                    <div class="song-actions">
                        <p>
                            1:34
                        </p>
                        <i class="fas fa-plus">
                        </i>
                    </div>
                </div>
                <div class="song-item">
                    <div class="song-info">
                        <img alt="Song cover 5" height="50" src="https://storage.googleapis.com/a1aa/image/rBBbwmoOFh4kF1wQiImo3c1bPwHXoVIibQXz3NBeUnQccy5JA.jpg" width="50" />
                        <div>
                            <p>
                                P.I.M.P.
                            </p>
                            <p>
                                50 Cent • Get Rich Or Die Tryin'
                            </p>
                        </div>
                    </div>
                    <div class="song-actions">
                        <p>
                            4:10
                        </p>
                        <i class="fas fa-plus">
                        </i>
                    </div>
                </div>
                <div class="song-item">
                    <div class="song-info">
                        <img alt="Song cover 6" height="50" src="https://storage.googleapis.com/a1aa/image/Kf8eSjiYevfa2STMROexpnmpmx4KayGajwW4GRiOkBQgGnceE.jpg" width="50" />
                        <div>
                            <p>
                                Điều anh biết (Lofi)
                            </p>
                            <p>
                                Chi Dân
                            </p>
                        </div>
                    </div>
                    <div class="song-actions">
                        <p>
                            5:15
                        </p>
                        <i class="fas fa-plus">
                        </i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>


@endsection