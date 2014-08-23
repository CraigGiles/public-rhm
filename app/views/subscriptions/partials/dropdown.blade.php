<div class="dropdown {{ $dropdown_id }}">
    <button class="btn btn-default btn-lg dropdown-toggle " type="button" data-toggle="dropdown">
        {{ $label }}
        <span class="caret"></span>
    </button>

    <ul class="dropdown-menu" id="{{ $dropdown_id }}-list" role="menu">
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
