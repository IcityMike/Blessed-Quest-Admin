<input type="hidden" name="userType" value="{{Common::getUserType()}}" id="userType" />
<input type="hidden" name="notificationURL" value="{{Common::getNotificationURL()}}" id="notificationURL" />
{{-- <input type="hidden" name="forumStatus" value="{{Common::getUserForumStatus()}}" id="forumStatus" >--}}
<header class="main-header" id="header">
 
  <!-- Logo -->

        <a href="{{Common::getDashboardLink() }}" class="logo">
  
          <span class="logo-for-mobile"><img class="logo img-responsive" src="{{asset('public/logo/logo.png')}}"></span>

          
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>

          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <!-- Notifications: style can be found in dropdown.less -->
              <!--- Component from resources/js/components/NotificationList.vue --->
              <!-- <notification-list></notification-list> -->
              
              <li class="dropdown notifications-menu">
                <a href="#" class="dropdown-toggle header-profile" data-toggle="dropdown">
                  
                  <span class="user-pic">
                    <img class="img-responsive" src="{{Common::getUserPicture()}}">
                  </span>
                  <span class="hidden-xs" style="text-transform: capitalize;">
                    {{Common::getUserName()}}
                   
                  </span>
                </a>
                <ul class="dropdown-menu">
                  <li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu">
                      <li class="user-footer">
                        <div>
                         
                          <a href="{{ Common::getEditProfileLink() }}" class="btn btn-block btn-default btn-flat">Edit profile</a>
                         
                        </div>
                         <div>
                         
                          <a href="{{ Common::getChangePasswordLink() }}" class="btn btn-block btn-default btn-flat">Change password</a>
                         
                        </div>
                       
                        <div>
                        
                          <a href="{{ Common::getLogoutLink() }}" class="btn btn-block btn-default btn-flat">Logout</a>
                        </div>
                      </li>


                    </ul>
                  </li>
                </ul>
              </li>


            </ul>
          </div>
        </nav>
        
</header>
<div id="popUpContainer">
  <post-popup></post-popup>
  <!--- Component from resources/js/components/AllPopup.vue --->
  <all-popups></all-popups>
</div>