
var warning_template_src =  '<div class="alert alert-{{type}}">' +
                              '<strong>Uh oh...</strong>' +
                              '{{#each message}}' +
                                '{{#each this}}' +
                                  '<p>{{this}}</p>' +
                                '{{/each}}' +
                              '{{/each}}' +
                            '</div>';
var warning_template = Handlebars.compile(warning_template_src);

function handleResponse(data, redirect) {
  /**
   * Handle redirection as long as the page is not where we are right now.
   */
  if (redirect !== false && data.responseJSON.redirect !== window.location.pathname.replace('/', '')) {
    window.location.href = data.responseJSON.redirect;
  }

  /**
   * make sure message value is an array of objects
   */
  if (typeof data.responseJSON.message === 'string') {
    data.responseJSON.message = [{err: data.responseJSON.message}];
  }


  /**
   * 4xx Client Error
   */
  if (data.status >= 400 && data.status <= 499) {
    var errors = data.responseJSON;
    errors.type = 'warning';
    $('#warnings').html(warning_template(errors));
    $('#regions').empty();
  }

  /**
   * 5xx Server Error
   */
  else if (data.status >= 500 && data.status <= 599) {
    var errors = data.responseJSON;
    errors.type = 'danger';
    $('#warnings').html(warning_template(errors));
    $('#regions').empty();
  }
}


function beforeAjax() {
  $('#warnings').empty();
}