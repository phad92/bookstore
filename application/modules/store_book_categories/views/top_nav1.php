 
 <style>
    #search_result{
        /* border: 2px solid red; */
        /* background-color: silver; */
        color: black;
        z-index: 99 !important;
        display: block;
        position:absolute;
        margin-top: 44px;
        width: 100%;
        /* margin-top: 15px !important; */
    }
    #search_result li{
        margin: 0;
    }

    .search_hover { 
        background-color:#EBEBEB !important; 
        /* background-color:#EBEBEB!important;  */
        color:#232323!important; 
    }
 </style>
 <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
<div id="flipkart-navbar">
    <div class="container">
        <div class="row row1">
            <ul class="largenav pull-right">
                <li class="upper-links"><a class="links" href="http://clashhacks.in/">Facebook</a></li>
                <li class="upper-links"><a class="links" href="https://campusbox.org/">Twitter</a></li>
                <li class="upper-links"><a class="links" href="http://clashhacks.in/">Google+</a></li>
                <li class="upper-links"><a class="links" href="<?php echo base_url('contact_us/index')?>">Support</a></li>
                <?php if(!$is_logged_id):?>
                <li class="upper-links"><a class="links" href="<?php echo base_url('Your_account/start')?>">Sign up</a></li>
                <li class="upper-links"><a class="links" href="<?php echo base_url('Your_account/login')?>">Sign in</a></li>
                <?php else:?>
                <li class="upper-links"><a class="links" href="<?php echo base_url('Your_account/welcome')?>">My Account</a></li>
                <li class="upper-links"><a class="links" href="<?php echo base_url('Your_account/logout')?>">Logout</a></li>
                <?php endif?>
                <!--<li class="upper-links">-->
                <!--    <a class="links" href="http://clashhacks.in/">-->
                <!--        <svg class="" width="16px" height="12px" style="overflow: visible;">-->
                <!--            <path d="M8.037 17.546c1.487 0 2.417-.93 2.417-2.417H5.62c0 1.486.93 2.415 2.417 2.415m5.315-6.463v-2.97h-.005c-.044-3.266-1.67-5.46-4.337-5.98v-.81C9.01.622 8.436.05 7.735.05 7.033.05 6.46.624 6.46 1.325v.808c-2.667.52-4.294 2.716-4.338 5.98h-.005v2.972l-1.843 1.42v1.376h14.92v-1.375l-1.842-1.42z" fill="#fff"></path>-->
                <!--        </svg>-->
                <!--    </a>-->
                <!--</li>-->
                <!--<li class="upper-links dropdown"><a class="links" href="http://clashhacks.in/">Dropdown</a>-->
                <!--    <ul class="dropdown-menu">-->
                <!--        <li class="profile-li"><a class="profile-links" href="http://yazilife.com/">Link</a></li>-->
                <!--        <li class="profile-li"><a class="profile-links" href="http://hacksociety.tech/">Link</a></li>-->
                <!--        <li class="profile-li"><a class="profile-links" href="http://clashhacks.in/">Link</a></li>-->
                <!--        <li class="profile-li"><a class="profile-links" href="http://clashhacks.in/">Link</a></li>-->
                <!--        <li class="profile-li"><a class="profile-links" href="http://clashhacks.in/">Link</a></li>-->
                <!--        <li class="profile-li"><a class="profile-links" href="http://clashhacks.in/">Link</a></li>-->
                <!--        <li class="profile-li"><a class="profile-links" href="http://clashhacks.in/">Link</a></li>-->
                <!--    </ul>-->
                <!--</li>-->
            </ul>
        </div>
       
        <div class="row row2">
            <div class="col-sm-2">
                <h2 style="margin:0px;"><span class="smallnav menu" onclick="openNav()">☰ Brand</span></h2>
                <a href="<?php echo base_url()?>" style="text-decoration: none; color: white;">
                    <h1 style="margin:0px;font-size: 2.2em;text-align: center;"><span class="largenav">Project Ark</span></h1>
                </a>
            </div>
            <div class="flipkart-navbar-search smallsearch col-sm-8 col-xs-11">
                <div class="row">
                    <input class="flipkart-navbar-input col-xs-11" type="" placeholder="Search for Products, Brands and more" name="search" id="search">
                    <button class="flipkart-navbar-button col-xs-1">
                        <svg width="15px" height="15px">
                            <path d="M11.618 9.897l4.224 4.212c.092.09.1.23.02.312l-1.464 1.46c-.08.08-.222.072-.314-.02L9.868 11.66M6.486 10.9c-2.42 0-4.38-1.955-4.38-4.367 0-2.413 1.96-4.37 4.38-4.37s4.38 1.957 4.38 4.37c0 2.412-1.96 4.368-4.38 4.368m0-10.834C2.904.066 0 2.96 0 6.533 0 10.105 2.904 13 6.486 13s6.487-2.895 6.487-6.467c0-3.572-2.905-6.467-6.487-6.467 "></path>
                        </svg>
                    </button>
                    <ul class="list-group" id="search_result"></ul>
                </div>
            </div>
            <div class="dropdown cart largenav col-sm-2">
             <?php echo Modules::run('templates/_cart_btn');?>
            </div>
        </div>
        <div class="row row3">
            <ul class="largenav text-center">
              <?php 
                    $this->load->module('store_book_categories');
                    $this->load->module('site_settings');
                    $url_segment = base_url().$this->site_settings->_get_item_segments();
                    foreach($parent_categories as $key => $value):
                    $parent_cat_id = $key;
                    $parent_cat_title = $value;
                ?>
                <!-- <li class="upper-links dropdown"><a class="links" href="#" style="font-size: 1.2em;"><?php //echo strtoupper($parent_cat_title)?></a> -->
                    <!-- <ul class="dropdown-menu"> -->
                      <?php 
                        //   $query = $this->store_book_categories->get_where_custom('Book_parent_cat_id',$parent_cat_id);
                        //   foreach($query->result() as $row):
                        //       $cat_url = $row->book_cat_url;
                      ?>
                        <!-- <li class="profile-li"><a class="profile-links" href="<?php  //echo $target_url_start.$cat_url;?>" style="font-size: 1em"><?php //echo $row->cat_title?></a></li> -->
                            
                        <?php //endforeach?>
                    <!-- </ul> -->
                    <!-- </li> -->
                    <?php endforeach?>
                 <li class="upper-links dropdown"><a class="links" href="<?php echo base_url('store_books/all_books')?>" style="font-size: 1.2em;">Store<?php //echo strtoupper($parent_cat_title)?></a>
                 <li class="upper-links dropdown"><a class="links" href="<?php echo base_url('store_books/book_type/'.$ebook)?>" style="font-size: 1.2em;">Ebooks Books<?php //echo strtoupper($parent_cat_title)?></a>
                 <li class="upper-links dropdown"><a class="links" href="<?php echo base_url('store_books/book_type/'.$audio)?>" style="font-size: 1.2em;">Audio Books<?php //echo strtoupper($parent_cat_title)?></a>
                 <li class="upper-links dropdown"><a class="links" href="#" style="font-size: 1.2em;">Categories<?php //echo strtoupper($parent_cat_title)?></a>
                 <li class="upper-links dropdown"><a class="links" href="<?php echo base_url('blog/feed')?>" style="font-size: 1.2em;">Blog<?php //echo strtoupper($parent_cat_title)?></a>
                 <li class="upper-links dropdown"><a class="links" href="<?php echo base_url('contact_us/index')?>" style="font-size: 1.2em;">Contact Us<?php //echo strtoupper($parent_cat_title)?></a>
                 <!-- <li class="upper-links dropdown"><a class="links" href="#" style="font-size: 1.2em;">Support<?php //echo strtoupper($parent_cat_title)?></a> -->
                               
            </ul>
        </div>
    </div>
</div>
<div id="mySidenav" class="sidenav" style="z-index: 999999;">
    <div class="container" style="background-color: #000; padding-top: 10px;">
        <span class="sidenav-heading">Home</span>
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
    </div>
    <a href="<?php echo base_url('store_books/all_books')?>">Store</a>
    <a href="<?php echo base_url('store_books/book_type/'.$ebook)?>">Ebooks</a>
    <a href="<?php echo base_url('store_books/book_type/'.$audio)?>">Audio Books</a>
    <a href="<?php echo base_url('contact_us/index')?>">Contact Us</a>
</div>

<!-- end of file -->
<script type="text/javascript">
//arrow key navigation 
    $(document).keydown(function(e){ 
        
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
$(document).ready(function(){
    
    //clear search field & change search text color 
    $("#search").focus(function() { 
        $("#search").css('color','#333333'); 
        var sv = $("#search").val(); //get current value of search field 
        if (sv == 'Search') { 
            $("#search").val(''); 
        } 
    }); 
    // 

    $("#search").bind('keypress click',function(e){
        if(e.which == '13'){
            e.preventDefault();
            loadData();
            $("#search_result").show();
        }else if($(this).val().length >= 0){
            loadData();
            $("#search_result").show();
        }
    });

    $('body').click(function(){
        $("#search_result").hide();
        // alert('hellow');
    })

     
});


function loadData(){
    var url = "<?php echo base_url('store_book_categories/search_item/')?>";
    var image = "<?php echo base_url('public/images/books/big_pics/')?>";
    var book_url = "<?php echo $url_segment?>"
    if(allow){
        allow = false;
        $("#search_result").html('loading...');
        $.ajax({
            url: url + escape($("#search").val()),
            // url:'http://localhost/helpdesk?q='+escape($("#search").val()),
            success: function (data){
                
                $("#search_result").html('');
                if(data.length > 0){
                    console.log(data.length);
                    for(var i = 0;i < data.length;i++){
                        var result = "<a href='"+book_url + data[i].book_url+"' class='list-group-item'><div class='row'><div class='col-xs-2'><img class='img-responsive' width='57px' height='60px'  src='"+ image + data[i].big_pic +"'></div><div class='col-xs-10'><b>Title: </b>"+data[i].book_title+" <br> <b>Price:</b> $"+data[i].book_price+"</div></div></a>";
                        // Handlebars.compile($("#empty-template").html(data[i].book_title));
                        $('#search_result').append(result);
                        console.log($('#search_result a').length);
                    }
                }
                
                allow = true;
            }
        });
    }
}




/*
* Author:      Marco Kuiper (http://www.marcofolio.net/)
* Customizations by JBP noted in comments below
*/

var currentSelection = 0;
var currentUrl = '';


	// Register keydown events on the whole document
	$(document).keydown(function(e) {
		switch(e.keyCode) { 
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
				if(currentUrl != '') {
					window.location = currentUrl;
				}
			break;
		}
	});
	
	// Add data to let the hover know which index they have
	for(var i = 0; i < $("#search_result a").length; i++) {
		$("#search_result a").eq(i).data("number", i);
	}
	
	// Simulate the "hover" effect with the mouse
	$("#search_result a").hover(
		function () {
			currentSelection = $(this).data("number");
			setSelected(currentSelection);
		}, function() {
			$("#search_result a").removeClass("search_hover");
			currentUrl = '';
		}
	);


function navigate(direction) {

	// Check if any of the menu items is selected
	if($("#search_result a .search_hover").length == 0) {
		currentSelection = -1;
	}
	
	//JBP - focus back on search field if up arrow pressed on top search result
	if(direction == 'up' && currentSelection == 0) {
		$("#search").focus();
	}
	//

	if(direction == 'up' && currentSelection != -1) {
		if(currentSelection != 0) {
			currentSelection--;
		}
	} else if (direction == 'down') {
		if(currentSelection != $("#search_result a").length -1) {
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

























function openNav() {
    $('#mySidenav').css({"width":"40%"});
    $('body').css({'background-color':'rgba(0,0,0,0.4)'});
    // document.getElementById("mySidenav").style.width = "40%";
    // // document.getElementById("flipkart-navbar").style.width = "50%";
    // document.body.style.backgroundColor = "rgba(0,0,0,0.4)";
}

function closeNav() {
    $('#mySidenav').css({"width":"0"});
    $('body').css({'background-color':'rgba(0,0,0,0)'});
    // document.getElementById("mySidenav").style.width = "0";
    // document.body.style.backgroundColor = "rgba(0,0,0,0)";
}
</script>
