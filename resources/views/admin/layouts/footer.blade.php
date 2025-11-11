 <script>
 	$(document).ready(function() {
		$(".select2").select2();
 		// Datepicker
 		$('.input-daterange').datepicker({
 			autoclose: true,
 			format: 'dd/mm/yyyy',
 			defaultViewDate: {day: 1, month:0, year: <?php echo date("Y")-18; ?>},
 		});
 		$(".dataTables_wrapper").find(".col-sm-6").removeClass("col-sm-6").addClass("col-sm-3");
 		$(".dataTables_wrapper").find("select.input-sm").addClass("custom-select");
 		//$(".dataTables_wrapper").find(".row").first().addClass("custom-row");
 		$(".dataTables_wrapper").each(function() {
		    $(this).find(".row").first().addClass("custom-row");
		});
 	});

 	// right side chat open Close
 	if ($(document).width() <= 991) {
 		function openNav() {
 			document.getElementById("mySidenav").style.width = "380px";
 		}

 		function closeNav() {
 			document.getElementById("mySidenav").style.width = "0";
 		}
 	}
	

	$(".submenu").not('.submenu-child').click(function()
	{
		$(".submenu").not(this).find(".collapse").collapse('hide');

	});
 	if ($(document).width() <= 400) {
 		function openNav() {
 			document.getElementById("mySidenav").style.width = "320px";
 		}

 		function closeNav() {
 			document.getElementById("mySidenav").style.width = "0";
 		}
 	}

 	// right side chat on scroll
 	$(window).scroll(function() {
 		if ($(this).scrollTop() > 50) {
 			$('.sidenav').css('top', '0px');
 			$('.sidenav').css('height', '100vh');
 			$('.sidenav').css('transition', '.5s');
 		} else {
 			$('.sidenav').css('top', '90px');
 			$('.sidenav').css('height', 'calc(100vh - 90px)');
 			$('.sidenav').css('transition', '.5s');
 		}
 	});

 	if ($(document).width() <= 991) {
 		$(window).scroll(function() {
 			if ($(this).scrollTop() > 50) {

 			} else {
 				$('.sidenav').css('top', '70px');
 				$('.sidenav').css('height', 'calc(100vh - 70px)');
 			}
 		});
 	}

	window.Parsley.addValidator('parsehtml', {
		validateString: function(data) {
      
		var p = /^([^<]|<(?![A-z//])|(?![0-9//])>)+$/g; 
		if(data.match(p)){
			return true;
		}
		return false;
		
		},
		messages: {
		en: '<?php echo __('messages.invalidData'); ?>',
		}
  	});

 	
 </script>
<!-- <script src="{{ URL::asset('js/app.js')}}"></script> -->
<!-- mark as read function-->

<!-- <script>
        Echo.channel('test')
            .listen('TestEvent', e => {
                console.log(e)
            })
    </script> -->
 <footer class="main-footer">
<!--    The information provided is general advice only and has been prepared without taking account of your objectives, financial situations or needs. Before acting on this general advice, you should consider the appropriateness of the advice having regard to your own objectives, financial situation and needs. For more information please refer to the La Verne Terms and Conditions.-->
 <div class="row align-items-center">
  <div class="col-lg-3 col-md-4">
  <div class="media padd-left15">
     <div class="media-left">
        <div class="follow-txt">Follow Us</div>
     </div>
     <div class="media-body">
        <ul class="social-icon">
          <li><a href="{{ (Common::getWebsiteSettings()->facebook_link) ? Common::getWebsiteSettings()->facebook_link : '#' }}"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
         <!--  <li><a href="{{ (Common::getWebsiteSettings()->twitter_link) ? Common::getWebsiteSettings()->twitter_link : '#' }}"><i class="fa fa-twitter" aria-hidden="true"></i></a></li> -->
          <li><a href="{{ (Common::getWebsiteSettings()->linkedin_link) ? Common::getWebsiteSettings()->linkedin_link : '#' }}"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>    
        </ul>
     </div>
  </div>
  </div>  
  <div class="col-lg-5 col-md-3">
  <div class="powered-text text-center">
   Powered by   
  </div>   
  </div>     
  <div class="col-lg-4 col-md-5">
      <div class="powered-text text-right">
        Copyright © {{date("Y")}} . All Rights Reserved.   
      </div>
  </div>        
 </div>     
 </footer>