<!doctype html>
<html>
	<head>
		<title>Wikipedia Visualization</title>
		<style>
			body {
				margin: 0;
				padding: 0;
				height: 100%;
				width: 100%;
				overflow: hidden;
				background-color: white;
			}
			#a {
				position: absolute;
				top: 0;
				left: 0;
				width: 50%;
				height: 100%;
			}
			#b {
				position: absolute;
				top: 0;
				right: 0;
				width: 50%;
				height: 100%;
			}
		</style>
	</head>
	<body>
		<video id="a" src="a.webm" autoplay loop></video>
		<video id="b" src="b.webm" autoplay loop></video>
	</body>
</html>