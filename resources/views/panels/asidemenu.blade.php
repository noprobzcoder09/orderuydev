<aside class="aside-menu">
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#timeline" role="tab"><i class="icon-list"></i></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#messages" role="tab"><i class="icon-speech"></i></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#settings" role="tab"><i class="icon-settings"></i></a>
        </li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane active" id="timeline" role="tabpanel">
            <div class="callout m-0 py-2 text-muted text-center bg-light text-uppercase">
                <small><b>Today</b></small>
            </div>
            <hr class="transparent mx-3 my-0">
            <div class="callout callout-warning m-0 py-3">
                <div class="avatar float-right">
                    <img src="{{asset('images/avatars/7.jpg')}}" class="img-avatar" alt="admin@bootstrapmaster.com">
                </div>
                <div>Meeting with
                    <strong>Lucas</strong>
                </div>
                <small class="text-muted mr-3"><i class="icon-calendar"></i>&nbsp; 1 - 3pm</small>
                <small class="text-muted"><i class="icon-location-pin"></i>&nbsp; Palo Alto, CA </small>
            </div>
        </div>
        <div class="tab-pane p-3" id="messages" role="tabpanel">
            <div class="message">
                <div class="py-3 pb-5 mr-3 float-left">
                    <div class="avatar">
                        <img src="{{asset('images/avatars/7.jpg')}}" class="img-avatar" alt="admin@bootstrapmaster.com">
                        <span class="avatar-status badge-success"></span>
                    </div>
                </div>
                <div>
                    <small class="text-muted">Lukasz Holeczek</small>
                    <small class="text-muted float-right mt-1">1:52 PM</small>
                </div>
                <div class="text-truncate font-weight-bold">Lorem ipsum dolor sit amet</div>
                <small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt...</small>
            </div>
            <hr>
            <div class="message">
                <div class="py-3 pb-5 mr-3 float-left">
                    <div class="avatar">
                        <img src="{{asset('images/avatars/7.jpg')}}" class="img-avatar" alt="admin@bootstrapmaster.com">
                        <span class="avatar-status badge-success"></span>
                    </div>
                </div>
                <div>
                    <small class="text-muted">Lukasz Holeczek</small>
                    <small class="text-muted float-right mt-1">1:52 PM</small>
                </div>
                <div class="text-truncate font-weight-bold">Lorem ipsum dolor sit amet</div>
                <small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt...</small>
            </div>
        </div>
        <div class="tab-pane p-3" id="settings" role="tabpanel">
        <h6>Settings</h6>
            <div class="aside-options">
                <div class="clearfix mt-4">
                    <small><b>Option 1</b></small>
                    <label class="switch switch-text switch-pill switch-success switch-sm float-right">
                    <input type="checkbox" class="switch-input" checked="">
                    <span class="switch-label" data-on="On" data-off="Off"></span>
                    <span class="switch-handle"></span>
                    </label>
                </div>
                <div>
                    <small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</small>
                </div>
            </div>
            <div class="aside-options">
                <div class="clearfix mt-3">
                    <small><b>Option 2</b></small>
                    <label class="switch switch-text switch-pill switch-success switch-sm float-right">
                    <input type="checkbox" class="switch-input">
                    <span class="switch-label" data-on="On" data-off="Off"></span>
                    <span class="switch-handle"></span>
                    </label>
                </div>
                <div>
                    <small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</small>
                </div>
            </div>
        </div>
    </div>
</aside>