<script src="https://code.jquery.com/jquery-3.7.1.min.js" 
integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" 
crossorigin="anonymous">
</script>

<script type="text/javascript">
    function submitData() {
    $(document).ready(function () {
        var data = {
            username: $('#username').val(),
            email: $('#email').val(),
            password: $('#password').val(),
            action: 'register'
        };

        $.ajax({
            url: 'function.php',
            type: 'post',
            data: data,
            success: function (response) {
                alert(response);
                if (response === "Registration successful!") {
                    window.location.href = "signin.php"; // Redirect to signin.php
                }
            }
        });
    });
}

</script>