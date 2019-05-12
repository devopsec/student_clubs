/**
 * Get the value of a querystring
 * @param  {String} field The field to get the value of
 * @param  {String} url   The URL to get the value from (optional)
 * @return {String}       The field value
 */
var getQueryString = function(field, url) {
  var href = url ? url : window.location.href;
  var reg = new RegExp('[?&]' + field + '=([^&#]*)', 'i');
  var string = reg.exec(href);
  return string ? string[1] : null;
};

/**
 * Disable / Re-enable a form submittable element
 * Use this instead of the HTML5 disabled prop
 * @param selector  String|jQuery Object    The selector for elem
 * @param disable   Boolean   Whether to disable or re-enable
 */
function toggleElemDisabled(selector, disable) {
  var select_elem = null;
  if (typeof selector === 'string' || selector instanceof String) {
    select_elem = $(selector);
  }
  else if (selector instanceof jQuery) {
    select_elem = selector;
  }
  else {
    console.err("toggleElemDisabled(): invalid selector argument");
    return;
  }
  if (disable) {
    select_elem.parent().css({
      'cursor': 'not-allowed'
    });
    select_elem.css({
      'background-color': '#EEEEEE',
      'opacity': '0.7',
      'pointer-events': 'none'
    });
    select_elem.prop('readonly', true);
    select_elem.prop('tabindex', -1);
  }
  else {
    select_elem.parent().removeAttr('style');
    select_elem.removeAttr('style');
    select_elem.prop('readonly', false);
    select_elem.prop('tabindex', 0);
  }
}

/**
 * Recursively search DOM tree until test is true
 * Starts at and includes selected node, tests each desc
 * Note that test callback applies to jQuery objects throughout
 * @param selector   String|jQuery Object  The selector for start node
 * @param test       function()            Test to apply to each node
 * @return           jQuery Object|null    Returns found node or null
 */
function descendingSearch(selector, test) {
  var select_node = null;
  if (typeof selector === 'string' || selector instanceof String) {
    select_node = $(selector);
  }
  else if (selector instanceof jQuery) {
    select_node = selector;
  }
  else {
    return null;
  }

  var num_nodes = select_node.length || 0;
  if (num_nodes > 1) {
    for (var i = 0; i < num_nodes; i++) {
        if (test(select_node[i])) {
          return select_node[i];
      }
    }
  }
  else {
    if (test(select_node)) {
        return select_node;
      }
  }

  node_list = select_node.children();
  if (node_list.length <= 0) {
    return null;
  }

  descendingSearch(node_list, test)
}

/* handle multiple modal stacking */
$(window).on('show.bs.modal', function(e) {
  modal = $(e.target);
  zIndexTop = Math.max.apply(null, $('.modal').map(function() {
    var z = parseInt($(this).css('z-index'));
    return isNaN(z, 10) ? 0 : z;
  }));
  modal.css('z-index', zIndexTop + 10);
  modal.addClass('modal-open');
});
$(window).on('hide.bs.modal', function(e) {
  modal = $(e.target);
  modal.css('z-index', '1050');
});

/* remove non-printable ascii chars on paste */
$('form input[type!="hidden"]').on("paste", function() {
  $(this).val(this.value.replace(/[^\x20-\x7E]+/g, ''))
});

/* make sure autofocus is honored on loaded modals */
$('.modal').on('shown.bs.modal', function() {
  $(this).find('[autofocus]').focus();
});

/* handle drop-down menus */
$('.dropdown-toggle').on('click', function() {
  var self = $(this);
  if (! self.attr('active')) {
    self.find('.dropdown-menu').css('display', 'block');
    self.attr('active', true)
  }
  else {
    self.find('.dropdown-menu').css('display', 'none');
    self.removeAttr('active')
  }
});
