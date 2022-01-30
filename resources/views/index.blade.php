<!DOCTYPE html>
<html>
<head>
	<title>SMS Form</title>
	<link rel="stylesheet" type="text/css" href={{ asset('css/styles.css')}} />
<link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
</head>
<body>
	<div class="main">  	
		<input type="checkbox" id="chk" aria-hidden="true">

			<div class="sms">
				<form>
					<label for="chk" aria-hidden="true">Send SMS</label>
					<input type="tel" name="phone" placeholder="Phone Number" required="">
					<input type="text" name="message" placeholder="Message" required="">
					<button>Send SMS</button>
				</form>
			</div>

			<div class="bulk-sms">
				<form>
					<label for="chk" aria-hidden="true">Send Bulk SMS</label>
                    <input type="tel" name="phone" placeholder="Phone Number" required="">
					<input type="text" name="message" placeholder="Message" required="">
					<button>Send Bulk SMS</button>
				</form>
			</div>
	</div>
    @section('js')
</body>
</html>
