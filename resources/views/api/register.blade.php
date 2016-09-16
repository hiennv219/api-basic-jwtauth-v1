<form action="" id="form-name">
	<!-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> -->
	<input type="submit" value="Submit">
</form>


<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>

<script>
// $.ajaxSetup({
//     headers: {
//         'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
//     }
// });


$(document).ready(function(){

	$("#form-name").on('submit',(function(e) {
		e.preventDefault();
		$.ajax({
			url: "http://localhost:8000/api/register",
			dataType: "json",
			type: "POST",
			data: {"name":"H11s12D11","email":"te11s2211st@gmail.com","password":"123456"},
			success: function (data) {
				alert("user created successfully")
			}
		});
	}));

});
</script>