 <div class="row">
    <div class="col-md-12">
    @include('errors.messages',array('id' => 'card-message'))
    </div>
</div>
<div id="card-list-container">
    @include($view.'card-list')
</div>
<div id="card-form-container" style="display: none;">
    @include($view.'card-form')
</div>
