@if (session()->has('success'))
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive"
            aria-atomic="true" id="liveToastSuccess">
            <div class="d-flex">
                <div class="toast-body">
                    {{ session('success') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>


        <script>
            var toastlive = document.getElementById('liveToastSuccess')
            var toast = new bootstrap.Toast(toastlive)

            toast.show()
        </script>
@endif

@if (session()->has('error'))
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive"
            aria-atomic="true" id="liveToastError">
            <div class="d-flex">
                <div class="toast-body">
                    {{ session('error') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>


        <script>
            var toastlive = document.getElementById('liveToastError')
            var toast = new bootstrap.Toast(toastlive)

            toast.show()
        </script>
@endif

@if ($errors->any())
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive"
            aria-atomic="true" id="liveToastError">
            <div class="d-flex">
                <div class="toast-body">
                    @foreach ($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                    {{ session('error') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>

        <script>
            var toastlive = document.getElementById('liveToastError')
            var toast = new bootstrap.Toast(toastlive)

            toast.show()
        </script>
@endif
