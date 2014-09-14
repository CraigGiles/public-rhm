<div class="dropdown {{ $dropdown_id }} ">
    <button class="btn btn-default btn-lg dropdown-toggle" style="width: 100%" type="button" data-toggle="dropdown">
        <div id="{{$dropdown_id}}-button">{{ $label or "Select ... "}}  <span class="caret"></span></div>
    </button>

    <ul class="dropdown-menu" id="{{ $dropdown_id }}-list" style="width: 100%; height: 300px; overflow-y: scroll" role="menu">
        <? //Search bar inside the dropdown menu for filtering?>
        {{ Form::open(['class' => 'navbar-form navbar-left', 'role' => 'search']) }}
          <div class="form-group">
            <input id="search-{{ $dropdown_id }}" type="text" class="form-control" placeholder="Search">
          </div>
        {{ Form::close() }}

        <? //poplate the dropdown menu with all the available ?>
        @foreach($items as $item)
            <li role="presentation"><a class="{{ $item_id }}" role="menuitem">{{{ $item }}}</a></li>
        @endforeach
    </ul>
</div>
