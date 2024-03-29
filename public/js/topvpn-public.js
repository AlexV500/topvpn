(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	$(window).load(function() {
	$(window).scroll(function(){
		var $sections = $('section');
		$sections.each(function(i,el){
			var top  = $(el).offset().top-200;
			var bottom = top +$(el).height();
			var scroll = $(window).scrollTop();
			var id = $(el).attr('id');
			if( scroll > top && scroll < bottom){
				$('a.active').removeClass('active');
				$('a[href="#'+id+'"]').addClass('active');

			}
		})
	});

	$(".widget-context").on("click","a", function (event) {
		// исключаем стандартную реакцию браузера

		event.preventDefault();

		// получем идентификатор блока из атрибута href
		let id  = $(this).attr('href'),

			// находим высоту, на которой расположен блок
			top = $(id).offset().top;
		    top = top - 100;

		// анимируем переход к блоку, время: 800 мс
		$('body,html').animate({scrollTop: top}, 800);
	});
	});
})( jQuery );


document.addEventListener('DOMContentLoaded', function() {

	var box = document.getElementsByClassName('box')[0],
		box2 = document.getElementsByClassName('box2')[0],
		buttonDown = document.getElementsByClassName('toggle')[0],
	    buttonUp = document.getElementsByClassName('toggle')[1],

	buttonBoxDown = document.getElementById('other-brokers-button-down'),
	buttonBoxUp = document.getElementById('other-brokers-button-up');
//	ratingTable = document.getElementById('rating-table');
	if(box && box2 && buttonBoxDown && buttonBoxUp){
		buttonBoxUp.classList.add('box-hidden');
		box.classList.add('box-hidden');
		box2.classList.add('box-hidden');


		buttonDown.addEventListener('click', function(e) {

			buttonBoxDown.classList.add('box-hidden');
			buttonBoxUp.classList.remove('box-hidden');

			box.classList.add('box-transition');
			box.clientWidth; // force layout to ensure the now display: block and opacity: 0 values are taken into account when the CSS transition starts.
			box.classList.remove('box-hidden');

			box2.classList.add('box-transition');
			box2.clientWidth; // force layout to ensure the now display: block and opacity: 0 values are taken into account when the CSS transition starts.
			box2.classList.remove('box-hidden');


		}, false);

		buttonUp.addEventListener('click', function(e) {

			buttonBoxUp.classList.add('box-hidden');
			buttonBoxDown.classList.remove('box-hidden');

			box.classList.remove('box-transition');
			box.classList.add('box-hidden');

			box2.classList.remove('box-transition');
			box2.classList.add('box-hidden');

		}, false);


		box.addEventListener('transitionend', function() {
			box.classList.remove('box-transition');
		}, false);
	}

	const circle = new CircularProgressBar("pie");
	circle.initial();
	
	const circleAverage = new CircularProgressBar("pie-average");
	circleAverage.initial();

	// ratingTable.onclick = function(event) {
	// 	console.log(event.target);
	// };



});


function togglePopup(popupId) {
	var popup = document.getElementById(popupId);
	popup.classList.toggle("show");
}

function hideOrShow2() {

	// Select the element with id "theDIV"
	var x = document.getElementById("theDIV");
	var x2 = document.getElementById("theDIV2");
	var brok_desc_row = document.getElementById("brok-desc-row");

	// If selected element is hidden
	if (x.style.display === "none") {

		// Show the hidden element
		x.style.display = "block";

		// Else if the selected element is shown
	} else {

		// Hide the element
		x.style.display = "none";
	}

	// If selected element is hidden
	if (x2.style.display === "none") {

		// Show the hidden element
		x2.style.display = "block";

		// Else if the selected element is shown
	} else {

		// Hide the element
		x2.style.display = "none";
	}
}