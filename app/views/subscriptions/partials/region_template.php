<script id="regions_template" type="text/x-handlebars-template">
    <div>
        @{{#each regions}}
        <p id="region-@{{this.id}}" onclick="selectRegion(@{{this.id}})" class="region @{{#if this.selected}}selected@{{/if}}">
            @{{#if this.selected}}
            <button type="button" class="btn btn-danger active">Unsubscribe</button>
            @{{else}}
            <button type="button" class="btn btn-primary">Subscribe</button>
            @{{/if}}
            @{{this.search_by}}
            <small class="type text-muted">@{{this.type}}</small>
        </p>
        @{{/each}}
    </div>
</script>
