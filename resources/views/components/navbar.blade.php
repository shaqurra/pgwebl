<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">{{$title}}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#"><i class="fa-solid fa-house"></i>Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('map')}}"><i class="fa-solid fa-map-location-dot"></i>Peta</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('table')}}"><i class="fa-solid fa-table"></i>Table</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Data
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{route('api.points')}}" target="_blank">Points</a></li>
                        <li><a class="dropdown-item" href="{{route('api.polyline')}}" target="_blank">Polyline</a></li>
                        <li><a class="dropdown-item" href="{{route('api.polygon')}}" target="_blank">Polygon</a></li>

                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
