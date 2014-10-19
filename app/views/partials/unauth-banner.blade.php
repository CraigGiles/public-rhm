  <div class="banner">
    <div class="container banner-image">
      <div class="row">
        <div class="col-sm-3">
          <div class="logo">
          </div>
        </div>
        @if (Auth::check())
       		<div class="logout-button">
       			{{ link_to('logout', 'Logout', ['class' => 'btn btn-primary btn-sm']) }}
       		</div>
		@endif
      </div>
      <div class="row">
      </div>
    </div>
  </div>
