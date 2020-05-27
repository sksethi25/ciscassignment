<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
.circle {
  height: 100px;
  width: 100px;
	border:1px solid black;
  border-radius: 50%;
  margin-left:40px;
}
.parallelogram {
	width: 40px;
	height: 40px;
	transform: rotateZ(45deg);
	border:1px solid black;
    
    position: relative;
    
    margin:0px;
    margin-top: 30px;
    margin-left: 30px;
    z-index:999;
}
.hexagon {
  position: relative;
  width: 200px; 
  height: 115.47px;
  margin: 57.74px 0;
  border-left: solid 5px #333333;
  border-right: solid 5px #333333;
}

.hexagon:before,
.hexagon:after {
  content: "";
  position: absolute;
  z-index: 1;
  width: 141.42px;
  height: 141.42px;
  -webkit-transform: scaleY(0.5774) rotate(-45deg);
  -ms-transform: scaleY(0.5774) rotate(-45deg);
  transform: scaleY(0.5774) rotate(-45deg);
  left: 24.2893px;
}

.hexagon:before {
  top: -70.7107px;
  border-top: solid 7.0711px #333333;
  border-right: solid 7.0711px #333333;
}

.hexagon:after {
  bottom: -70.7107px;
  border-bottom: solid 7.0711px #333333;
  border-left: solid 7.0711px #333333;
}
</style>
</head>
<body>

<h2>Circle CSS</h2>
<div class="hexagon">
<div class="circle">
<div class="parallelogram"></div>
</div>

</div>

</body>
</html> 
