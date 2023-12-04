<!-- Libs cdn -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
    integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
    integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
</script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
    $(document).ready(function() {
        $("#submit-btn").click(submitForm);

        function submitForm() {
            $("#loading-indicator").show();
            $('#leads-form :input').prop('disabled', true);

            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });

            $.ajax({
                method: "POST",
                url: "/submit-form-api",
                data: $('#leads-form').serialize(),
                success: function(response) {
                    $("#loading-indicator").hide();
                    $('#leads-form :input').prop('disabled', false);
                    const status = response.success;
                    if (status) {
                        swal("Thành công", "Chúng tôi sẽ sớm liên hệ với bạn", "success");

                        document.getElementById('leads-form').reset();
                    } else {
                        swal(
                        "Thất bại",
                        "Không thể gửi form, hãy thử lại sau",
                        "error"
                    );
                    }
                },
                error: function(error) {
                    $("#loading-indicator").hide();
                    $('#leads-form :input').prop('disabled', false);

                    swal(
                        "Thất bại",
                        "Không thể gửi form, hãy thử lại sau",
                        "error"
                    );
                },
            });
        };
    });
</script>

<!-- My Scripts -->

<!-- Page Scripts -->
@yield('page-script')
