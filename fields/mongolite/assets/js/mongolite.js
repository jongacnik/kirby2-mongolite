(function($) {

  var Mongolite = function(el) {

    var element = $(el);
    var table = element.find('table')
    var columns = table.data('mongolite-columns');
    var entriesapi = table.data('mongolite-entries');
    var addapi = table.data('mongolite-add');
    var updateapi = table.data('mongolite-update');
    var deleteapi = table.data('mongolite-delete');
    var rows = table.data('mongolite-rows');

    var headers = Object.keys(columns).map(function (key) {
      return $('<th>' + columns[key] + '</th>')
    })

    table.append(tableHead(headers, $('<thead></thead>')))
    table.append(tableHead(headers, $('<tfoot></tfoot>')))

    var colCount = Object.keys(columns).length
    
    var defs =  [
      { orderable: false, targets: [colCount, colCount + 1] }
    ]

    var table = table.DataTable({
      columnDefs: defs,
      pageLength: rows,
      ajax: {
        url: entriesapi,
        dataSrc: function (json) {
          var formatted = json.map(function (i) {
            var result = []
            
            Object.keys(columns).forEach(function (key) {
              result.push(i[key])
            })

            return result.concat([editButton(i._id), deleteButton(i._id)])
          })

          return formatted
        }
      }
    });

    function editButton (id) {
      return ' \
        <a data-modal class="btn structure-edit-button" href="' + updateapi.replace('/update', '/' + id + '/update') + '"> \
          <i class="icon fa fa-pencil"></i> \
        </a> \
      '
    }

    function deleteButton (id) {
      return ' \
        <a data-modal class="btn structure-edit-button" href="' + deleteapi.replace('/delete', '/' + id + '/delete') + '"> \
          <i class="icon fa fa-trash"></i> \
        </a> \
      '
    }

    function tableHead (headers, $element) {
      var $row = $('<tr></tr>')
      headers.forEach(function ($header) {
        $row.append($header.clone())
      })

      // edit/del cols
      $row.append($('<th width="18"></th>'))
      $row.append($('<th width="18"></th>'))

      return $element.append($row);
    }

  };

  $.fn.mongolite = function() {

    return this.each(function() {

      if($(this).data('mongolite')) {
        return $(this);
      } else {
        var mongolite = new Mongolite(this);
        $(this).data('mongolite', mongolite);
        return $(this);
      }

    });

  };

})(jQuery);