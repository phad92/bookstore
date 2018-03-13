
function openNav() {
	$('#mySidenav').css({
		"width": "40%"
	});
	$('body').css({
		'background-color': 'rgba(0,0,0,0.4)'
	});
	// document.getElementById("mySidenav").style.width = "40%";
	// // document.getElementById("flipkart-navbar").style.width = "50%";
	// document.body.style.backgroundColor = "rgba(0,0,0,0.4)";
}

function closeNav() {
	$('#mySidenav').css({
		"width": "0"
	});
	$('body').css({
		'background-color': 'rgba(0,0,0,0)'
	});
	// document.getElementById("mySidenav").style.width = "0";
	// document.body.style.backgroundColor = "rgba(0,0,0,0)";
}



//arrow key navigation 
$(document).keydown(function (e) {

	//jump from search field to search results on keydown 
	if (e.keyCode == 40) {
		$("#search").blur();
		return false;
	}

	//hide search results on ESC 
	if (e.keyCode == 27) {
		$("#search_result").hide();
		$("#search").blur();
		return false;
	}

	// //focus on search field on back arrow or backspace press 
	// if (e.keyCode == 37 || e.keyCode == 8) {  
	//     $("#search").focus(); 
	// } 

});
// 
var allow = true;
$(document).ready(function () {

	//clear search field & change search text color 
	$("#search").focus(function () {
		$("#search").css('color', '#333333');
		var sv = $("#search").val(); //get current value of search field 
		if (sv == 'Search') {
			$("#search").val('');
		}
	});
	// 

	$("#search").bind('keypress click', function (e) {
		if (e.which == '13') {
			e.preventDefault();
			loadData();
			$("#search_result").show();
		} else if ($(this).val().length >= 0) {
			loadData();
			$("#search_result").show();
		}
	});

	$('body').click(function () {
		$("#search_result").hide();
		// alert('hellow');
	})


});


/*
 * Author:      Marco Kuiper (http://www.marcofolio.net/)
 * Customizations by JBP noted in comments below
 */

var currentSelection = 0;
var currentUrl = '';


// Register keydown events on the whole document
$(document).keydown(function (e) {
	switch (e.keyCode) {
		// User pressed "up" arrow
		case 38:
			navigate('up');
			break;
			// User pressed "down" arrow
		case 40:
			navigate('down');
			break;
			// User pressed "enter"
		case 13:
			if (currentUrl != '') {
				window.location = currentUrl;
			}
			break;
	}
});

// Add data to let the hover know which index they have
for (var i = 0; i < $("#search_result a").length; i++) {
	$("#search_result a").eq(i).data("number", i);
}

// Simulate the "hover" effect with the mouse
$("#search_result a").hover(
	function () {
		currentSelection = $(this).data("number");
		setSelected(currentSelection);
	},
	function () {
		$("#search_result a").removeClass("search_hover");
		currentUrl = '';
	}
);


function navigate(direction) {

	// Check if any of the menu items is selected
	if ($("#search_result a .search_hover").length == 0) {
		currentSelection = -1;
	}

	//JBP - focus back on search field if up arrow pressed on top search result
	if (direction == 'up' && currentSelection == 0) {
		$("#search").focus();
	}
	//

	if (direction == 'up' && currentSelection != -1) {
		if (currentSelection != 0) {
			currentSelection--;
		}
	} else if (direction == 'down') {
		if (currentSelection != $("#search_result a").length - 1) {
			currentSelection++;
		}
	}
	setSelected(currentSelection);
}

function setSelected(menuitem) {

	//JBP - get search result to place in search field on hover
	var title = $("#search_result a").eq(menuitem).attr('title');
	$("#search").val(title);
	//

	$("#search_result a").removeClass("search_hover");
	$("#search_result a").eq(menuitem).addClass("search_hover");
	currentUrl = $("#search_result a").eq(menuitem).attr("href");
}

