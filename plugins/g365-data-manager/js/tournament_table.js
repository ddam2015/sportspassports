$(function(){
//   $('tbody').sortable();
  $('.stat_table th').click(function(){
    var table = $(this).parents('table').eq(0)
    var tb_rows = table.find('tr:gt(0)').toArray().sort(compare_row($(this).index()))
    this.desc = !this.desc
    if (!this.asc){tb_rows = tb_rows.reverse()}
      for (var i = 0; i < tb_rows.length; i++){table.append(tb_rows[i])}
    });
  function compare_row(key){
    return function(i, j){
      var get_val_i = get_cell_val(i, key), get_val_j = get_cell_val(j, key)
      return $.isNumeric(get_val_i) && $.isNumeric(get_val_j) ? get_val_i - get_val_j : get_val_i.toString().localeCompare(get_val_j)
    }
  }
  function get_cell_val(row, key){ 
    return $(row).children('td').eq(key).text() 
  }
//   function formatDate(date){
//     var new_date = new Date(date),
//     month = '' + (new_date.getMonth() + 1),
//     day = '' + new_date.getDate(),
//     year = new_date.getFullYear();
//     if (month.length < 2){ month = '0' + month; }
//     if (day.length < 2){ day = '0' + day; }
//     return [year, month, day].join('-') + " 00:00:00";
//   }
//   $("#datepicker").datepicker({
//     onSelect: function(){
//       var dateObject = $(this).datepicker('getDate');
//       var formatedDate = new Date(dateObject).toLocaleDateString();
//       document.getElementById('passport_date').value = formatDate(formatedDate);
//     }
//   });
});