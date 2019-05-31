
<div class="form-group m-form__group" id="email-checker-form">
	<label>
		Enter Email
	</label>
	<div class="input-group">
		<input placeholder="Enter email here" class="form-control" id="email" name="email" type="email">
		<div class="input-group-append">
			<button class="btn btn-secondary" type="button" onclick="findEmail($('#email').val())">
				Go!
			</button>
		</div>
	</div>
	<em id="search-result" style="display: none;" class="">Search result here</em>
</div>