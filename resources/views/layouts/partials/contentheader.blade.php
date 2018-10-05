<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">    
        <h4>
            @yield('contentheader_title', '')
            <small>@yield('contentheader_description')</small>
        </h4>
    </div>
    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
        <div class="btn-group pull-right" role="group">
            @yield('contentheader_buttons')
        </div>
    </div>
</section>