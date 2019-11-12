jQuery(document).ready(function($) {
  var $productModal = $('.product-modal')
  var $modalCtaBtn = $('.modal-cta-btn')
  var $productModalForm = $('.product-modal__form')
  var $inputContainer = $('.product-modal__input-container')
  var $errorText = $('.product-modal__error-text')
  var $emailField = $('.email-input')
  var $phoneField = $('.phone-input')

  var taClient = new TA({env: 'production'})

  var openModal = ''
  var successMessage =
    '<div class="product-modal__success-message__container">' +
      '<i class="product-modal__success-message__icon fa fa-check-circle"></i>' +
      '<h3 class="product-modal__success-message__header">Great! We\'re on the job!</h3>' +
      '<p class="product-modal__success-message__description">A Technology Advisor will be reaching out to you shortly.</p>' +
    '</div>'
  var buttonSuccessMessage = 'Thanks! <span class="hidden-xs">We\'ve received your submission</span>'
  var errorMessage =
    '<div class="alert alert-info">' +
      '<p><strong>Uh-oh!</strong> There was a problem submitting the form</p>' +
    '</div>'


  function parseQueryString(urlString) {
    var queryObj = {}
    if (urlString.split('?').length === 1) return queryObj

    var queryString = urlString.split('?')[1];
    var queryPairs = queryString.split('&');
    queryPairs.forEach(function(pair) {
      var query = pair.split('=');
      queryObj[query[0]] = query[1];
    });
    return queryObj;
  }

  function createUtmFields() {
    var utmFields = {};
    var utmFieldNames = ['utm_source', 'utm_medium', 'utm_content', 'utm_campaign', 'utm_term'];
    var urlQueryObj = parseQueryString(location.href);

    utmFieldNames.forEach(function(utmParam) {
      if (urlQueryObj[utmParam]) {
        utmFields[utmParam] = urlQueryObj[utmParam];
      }
    });
    return utmFields;
  }

  function serializeJSON($form) {
    var data = $form.serializeArray().reduce(function(acc, input) {
      var existingValue = acc[input.name]
      if (existingValue) {
        if (Array.isArray(existingValue)) {
          acc[input.name].push(input.value)
        } else {
          acc[input.name] = [existingValue, input.value]
        }
      } else {
        acc[input.name] = input.value
      }

      return acc
    }, {})

    // Acton form cannot handle multiple values, so we
    // concatenate arrays into a comma delimited string
    Object.keys(data).forEach(key => {
      if (Array.isArray(data[key])) {
        data[key] = data[key].join(', ')
      }
    })

    return data
  }

  function clearErrors() {
    $inputContainer.removeClass('has-error')
    $errorText.remove()
  }

  function validateRequiredFields($form) {
    var $requiredFields = $form.find('.required')
    return $requiredFields.filter(function() {
      return !$(this).val().length
    })
  }

  function validatePhoneField() {
    return $phoneField.filter(function () {
      var phoneDigits = $(this).val().match(/\d/g) || []
      return $(this).val() && phoneDigits.length < 10
    })
  }

  function validateEmailField() {
    return $emailField.filter(function () {
      return $(this).val() && !/.+@.+\.\S+/.test($(this).val())
    })
  }

  function displayErrors(fields, errorMessage) {
    fields.map(function() {
      $(this).parent()
        .addClass('has-error')
        .append(`<span class="product-modal__error-text">${errorMessage}</span>`)
    })
  }

  function submitForm($form, payload) {
    var $formContainer = $form.find('.product-modal__body--form-container')
    var $submitBtn = $form.find('.product-modal__submit')

    payload.form = createUtmFields()

    var model = serializeJSON($form)

    taClient.proxy({
      method: 'POST',
      uri: payload.uri,
      form: model,
    })
      .then(function() {
        $formContainer.empty()
        $(successMessage).appendTo($formContainer).fadeIn(500)
        $submitBtn.attr('disabled', 'disabled').html(buttonSuccessMessage)
        // Disables the modal trigger button
        $('a[data-target="' + openModal + '"]').attr('disabled', 'disabled').prepend('<i class="fa fa-check"></i> ')
      })
      .catch(function() {
        $formContainer.append(errorMessage)
      })
  }

  // Click events
  $productModal.on('shown.bs.modal', function() {
    return openModal = '#' + this.id
  })
  $modalCtaBtn.on('click', clearErrors)
  $productModalForm.on('submit', function(e) {
    e.preventDefault()
    var $form = $(this)
    var payload = {
      method: 'POST',
      uri: 'https://marketing2.technologyadvice.com/acton/eform/17538/006f/d-ext-0001',
      form: {},
    }

    clearErrors()

    var requiredFieldErrors = validateRequiredFields($form)
    displayErrors(requiredFieldErrors, 'This field is required.')

    var phoneFieldErrors = validatePhoneField()
    displayErrors(phoneFieldErrors, 'Phone number is not valid.')

    var emailFieldErrors = validateEmailField()
    displayErrors(emailFieldErrors, 'Email format is not valid.')

    if (requiredFieldErrors.length || phoneFieldErrors.length || emailFieldErrors.length) {
      return false
    }

    submitForm($form, payload)
  })

  //show/hide fetures list on updated product pages
  $('.product-features__item:first').addClass('open');

  $('.product-features__item:first .product-features__list').css('display',"block");

  var $title = $('.product-features__category-title');

  $title.click(function() {

    var $this = $(this);

    if ( $this.closest(".product-features__item").hasClass("open") ) {
    $this.closest(".product-features__item").removeClass("open");
      $this.parent().next().slideUp();
    } else {
    $this.closest(".product-features__item").addClass("open");
      $this.parent().next().slideDown();
    }
  });
});
