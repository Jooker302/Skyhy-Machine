 <!-- Sidebar -->
 <nav class="navbar-vertical navbar">
     <div class="nav-scroller">
         <!-- Brand logo -->
         <a class="navbar-brand" href="#">
             <img src="@@webRoot/assets/images/brand/logo/logo.svg" alt="" />
         </a>
         <!-- Navbar nav -->
         <ul class="navbar-nav flex-column" id="sideNavbar">
             <li class="nav-item">
                 <a class="nav-link has-arrow @@if (context.page ===  'dashboard') { active }"
                     href="{{ url('home') }}">
                     <i data-feather="home" class="nav-icon icon-xs me-2"></i> Dashboard
                 </a>

             </li>


             <!-- Nav item -->



             <!-- Nav item -->
             <li class="nav-item">
                 <a class="nav-link has-arrow @@if (context.page_group !== 'pages') { collapsed }"
                     href="#!" data-bs-toggle="collapse" data-bs-target="#navPages" aria-expanded="false"
                     aria-controls="navPages">
                     <i data-feather="layers" class="nav-icon icon-xs me-2">
                     </i> Pages
                 </a>

                 <div id="navPages"
                     class="collapse @@if (context.page_group === 'pages') { show }"
                     data-bs-parent="#sideNavbar">
                     <ul class="nav flex-column">
                         <li class="nav-item">
                             <a class="nav-link @@if (context.page === 'profile') { active }"
                                 href="{{ url('open-shifts') }}">
                                 Shifts

                             </a>
                         </li>
                         <li class="nav-item">
                             <a class="nav-link has-arrow  @@if (context.page === 'settings') { active } "
                                 href="{{ url('shift-request') }}">
                                 Shift Request

                             </a>

                         </li>


                         <li class="nav-item">
                             <a class="nav-link @@if (context.page === 'billing') { active }"
                                 href="{{ url('shift-request/approved-request') }}">
                                 Approved Applicants
                             </a>
                         </li>




                         <li class="nav-item">
                             <a class="nav-link @@if (context.page === 'pricing') { active }"
                                 href="{{ url('shift-request/rejected-request') }}">
                                 Rejected Applicants

                             </a>
                         </li>
                         <li class="nav-item">
                             <a class="nav-link @@if (context.page === 'pricing') { active }"
                                 href="{{ url('update_profile') }}">
                                 Update Profile

                             </a>
                         </li>

                     </ul>
                 </div>

             </li>




             <!-- Nav item -->





         </ul>

     </div>
 </nav>
