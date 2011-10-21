<!DOCTYPE html>
<html>
<head>
  <script src="http://code.jquery.com/jquery-latest.js"></script>
</head>
<body>
  <button>Toggle</button>
<p>Hello</p>
<div id="lol" style="display: none">Good Bye</div>
<script>

$("button").click(function () {
$('#lol').toggle();
});
</script>

</body>
</html>
