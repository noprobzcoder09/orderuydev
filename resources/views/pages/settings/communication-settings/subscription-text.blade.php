@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('users-new'))

@section('css')
	<link href="https://cdn.quilljs.com/1.1.6/quill.snow.css" rel="stylesheet">	
@endsection

@section('content')

<div class="row">
    <div class="col-md-6">
    @include('errors.messages')
    </div>
</div>
<div class="row" id="manage-subscription-text-wrapper">
	<div class="col-sm-6">
		<div class="card">
			<div class="card-header">
				<strong>Communication Settings</strong>
				<small>Manage Subscription</small>
			</div>
			<div class="card-body">
                <form class="form-horizontal" id="manage-subscription-text-form" action="{{url($actionUrl)}}" method="PUT" novalidate="novalidate">
                    
                    @csrf 

                    <div class="form-group">
                        <label for="current-password">Manage Subscription Text</label>
					   <div id="editor"></div>
					   <textarea name="manage_subscription_text" id="manage_subscription_text" style="display:none;" required>{{$manageSubscriptionText}}</textarea>
					</div>
                    <!--/.row-->

                    <div class="row">
                        <div class="col-sm-6 text-left">
                                    </div>
                        <div class="col-sm-6 text-right">
                            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-dot-circle-o"></i> 
                                @if(!empty($manageSubscriptionText))
                                    Update
                                @else
                                    Submit
                                @endif                               
                            </button>
                        </div>
                    </div>
                </form>
			</div>
		</div>
	</div>
	<!--/.col-->
</div>

@endsection


@section('css')

@endsection

@section('script')

<!-- Quill Assets -->

<script src="https://cdn.quilljs.com/1.1.6/quill.js"></script>

<!-- UY assets -->
<script src="{{asset('js/validator.js')}}"></script>
<script type="text/javascript">

	const manageSubscriptionTextarea = $('#manage_subscription_text');

    const url = {
		actionUrl: "{{url($actionUrl)}}",
	};

	const form = '#manage-subscription-text-form';

	const quill = new Quill('#editor', {
        theme: 'snow',
        modules: {
        	//'syntax': true,
			'toolbar': [
			[{ 'font': [] }, { 'size': [] }],
			[ 'bold', 'italic', 'underline', 'strike' ],
			[{ 'color': [] }, { 'background': [] }],
			[{ 'script': 'super' }, { 'script': 'sub' }],
			[{ 'header': '1' }, { 'header': '2' }, 'blockquote', 'code-block' ],
			[{ 'list': 'ordered' }, { 'list': 'bullet'}],
			[ 'direction', { 'align': [] }],
			[ 'image', 'video', 'formula' ],
			[ 'clean' ]
		]
        }
	});
	
	quill.on('text-change', function() {
		let content = quill.root.innerHTML;
		let strippedString = content.replace(/(<([^>]+)>)/ig,"");
		
		if(strippedString == '') {
			manageSubscriptionTextarea.val('');
		} else {
			manageSubscriptionTextarea.val(content);
		}
	});

	if (typeof manageSubscriptionTextarea.val() != 'undefined' && manageSubscriptionTextarea.val() != '' && manageSubscriptionTextarea.val() != 'undefined') {
		quill.root.innerHTML = manageSubscriptionTextarea.val();
	}

	$(function (){

		Validator.init(form, {
			rules: {
				manage_subscription_text: {
					required: true,
					minlength: 2
				},
			},
			messages: {
				manage_subscription_text: {
					required: 'Please enter a text',
				},
			},
			submitHandler: function () {
				System.setAjaxRequest(
					url.actionUrl,
					$(form).serialize(),
					'PUT',
					function(response) {
						if (response.status == 200)
						{
							if (response.success) {
								System.successMessage(response.message);
							} else {
								System.errorMessage(response.message);
							}
						}
					},
					function(error) {
						System.errorMessage();
					}
				);
				return false;
			}
		});
	});

</script>
@endsection