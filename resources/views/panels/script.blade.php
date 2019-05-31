<!-- Bootstrap and necessary plugins -->
<script src="{{asset('vendors/js/jquery.min.js')}}"></script>
<script src="{{asset('vendors/js/popper.min.js')}}"></script>
<!-- <script src="{{asset('vendors/js/bootstrap.min.js')}}"></script> -->
<script src="{{asset('vendors/js/pace.min.js')}}"></script>	
<script src="{{asset('vendors/js/blockui.js')}}"></script>	
<script src="{{asset('vendors/js/iziToast.min.js')}}"></script>	


<!-- CoreUI Pro main scripts -->
<script src="{{asset('js/app.js')}}"></script>	
<script src="{{asset('js/global.js')}}"></script>	
<script src="{{asset('js/system.js')}}"></script>	
<script src="{{asset('js/alert.js')}}"></script>	

<!-- Extended Theme -->
<script src="{{ asset('/template/base/vendors.bundle.js') }}" type="text/javascript"></script>
<!-- <script src="{{ asset('/template/default/base/scripts.bundle.js') }}" type="text/javascript"></script> -->
<script src="{{ asset('/template/default/base/custom-scripts.bundle.js') }}" type="text/javascript"></script>

<script src="{{asset('vendors/js/jquery.easy-autocomplete.min.js')}}"></script> 
<script type="text/javascript">
    
    $(document).ready( function() {
        var url = "{{url('searchcustomer?phrase=')}}";
        var options = {
            url: function(phrase) {
                return url+phrase+'&format=json';
            },
            template: {
                type: "links",
                fields: {
                    link: "link"
                }
            },
            // template: {
            //     type: "description",
            //     fields: {
            //         description: "email"
            //     }
            // },
            getValue: "name",
            theme: "plate-dark"
        };

        $("#general-customer-search").easyAutocomplete(options);

        $("#general-customer-search-mobile").easyAutocomplete(options);

        $('.for-small-screen .navbar-search span').click(function(){
            
            toggleSearch(this);

            $('body').removeClass('sidebar-mobile-show');
            
        });

        $('.mobile-sidebar-toggler').click(function() {
            let elem = $('.for-small-screen .navbar-search span');
            if (elem.hasClass('active')) {
                toggleSearch(elem);
            }
            
        });


        function toggleSearch(element_obj) {
            if($(element_obj).hasClass('active')){
                $(element_obj).removeClass('active');
            }else{
                $(element_obj).addClass('active'); 
            }

            $('.for-small-screen .navbar-search #general-customer-search-mobile').slideToggle('fast');
        }

    });

</script>

@yield('script')