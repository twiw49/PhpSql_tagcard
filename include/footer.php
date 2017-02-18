<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script>
$('.nav.navbar-nav').on('mouseover', 'li', function() {
	$(this).addClass("active");
});
$('.nav.navbar-nav').on('mouseout', 'li', function() {
	$(this).removeClass("active");
});
</script>
</body>
</html>