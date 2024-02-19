<nav class="navbar navbar-expand-lg bg-body-tertiary fixed-top">
    <div class="container-fluid">
        <a class="fa fa-facebook-square" style="font-size:40px;" href="{{ route('formpost') }}"></a>
        <form class="d-flex me-auto">
            <input class="form-control me-2" type="search" placeholder="Search in Facebook" aria-label="Search">
        </form>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-center  align-items-center" id="navbarSupportedContent">
            <a href="{{ route('formpost') }}" class="nav-link">
                <i class="material-icons" style="font-size:36px; margin-right: 100px;">home</i>
            </a>
            <a href="{{ route('formfriend') }}" class="nav-link">
                <i class="material-icons" style="font-size:36px; margin-right: 100px;">people</i>
            </a>
            <a href="#" class="nav-link">
                <i class="material-icons" style="font-size:36px; margin-right: 100px;">account_circle</i>
            </a>
            <a href="#" class="nav-link">
                <i class="material-icons" style="font-size:36px; margin-right: 100px;">airplay</i>
            </a>
        </div>
        <div class="navbar-collapse collapse justify-content-end" id="navbarSupportedContent">
            <a href="#" class="nav-link">
                <i class="material-icons" style="font-size:36px; margin-right: 50px;">message</i>
            </a>
            <a href="#" class="nav-link">
                <i class="material-icons" style="font-size:36px">notifications</i>
            </a>
        </div>
    </div>
</nav>
