<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>404 Not Found</title>

	<style type="text/css">

		body {
			height: 100%;
			background: #eee;
			padding: 0;
			margin: 0;
			font-size: 100%;
			color: #333;
			font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
			line-height: 100%;
		}

		a {
			color: #0088cc;
			text-decoration: none;
		}

		a:hover {
			color: #005580;
			text-decoration: underline;
		}

		h1 {
			font-size: 4em;
		}

		small {
			font-size: 0.7em;
			color: #999;
			font-weight: normal;
		}

		hr {
			border: 0;
			border-bottom: 1px #ddd solid;
		}

		#message {
			width: 700px;
			margin: 15% auto;
		}

		#back-home {
			bottom: 0;
			right: 0;
			position: absolute;
			padding: 10px;
		}
	</style>

</head>
<body>

<div id="message">
	<h1>404
		<small>Not Found</small>
	</h1>
	<hr>
	<p>[f:404:text]</p>

	<p>{SELF}</p>
</div>

<div id="back-home">
	<small>[f:404:return] <a href="{PATH}">[f:404:home]</a>?</small>
</div>

</body>
</html>