var scrollY = 0;
var distance = 40;
var speed = 24;
function autoScrollTo(e1) {
	var currentY = window.pageYOffset;
	var targetY = document.getElementById(e1).offsetTop;
	var bodyHeight = document.body.offsetHeight;
	var yPos = currentY + window.innerHeight;
	var animator = setTimeout('autoScrollTo(\''+e1+'\')',24);
	if(yPos > bodyHeight) {
		clearTimeout(animator);
	} else {
		if(currentY < targetY-distance) {
			scrollY = currentY+distance;
			window.scroll(0, scrollY);
		} else {
			clearTimeout(animator);
		}
	}
}
function resetScroller(e1){
	var currentY = window.pageYOffset;
	var targetY = document.getElementById(e1).offsetTop;
	var animator = setTimeOut('resetScroller(\''+e1+'\')',speed);
	if(currentY > targetY){
		scrollY = currentY-distance;
		window.scroll(0, scrollY);
	} else {
		clearTimeout(animator);
	}
}