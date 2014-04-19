function handleResponse(data) {
  /**
   * 4xx Client Error
   */
  if (data.status >= 400 && data.status <= 499) {
    $('#warnings').html('<div class="alert alert-warning"><strong>That\'s strange.</strong> ' +
      'Something seems to have gone wrong. Give it another shot.</div>');
    $('#regions').empty();
  }

  /**
   * 5xx Server Error
   */
  else if (data.status >= 500 && data.status <= 599) {
    $('#warnings').html('<div class="alert alert-danger"><strong>That\'s strange.</strong> ' +
      'Something seems to have gone wrong on our end. Give it another shot. ' +
      'If this message keeps appearing wait a few minutes before trying again.</div>');
    $('#regions').empty();
  }
}
