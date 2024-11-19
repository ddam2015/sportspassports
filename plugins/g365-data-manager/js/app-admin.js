$(".sortable").sortable();
$(".sortable").disableSelection();

// function tabClick() {
//   var theElement = $(this);
//   theElement.addClass('selected').siblings().removeClass('selected');
//   theElement.parent().next().children().eq(theElement.index()).show().siblings().hide();
// }

// var thisHold = $('#rank-admin');
// $('.tab', thisHold).click(tabClick);
// $('.ranking', thisHold).hide();
// 
$('#ranking-tabs li:first-child a').click();

function addNote(note, dest) {
  var $noteTemplate = $('#note-template');
  var newContent = $noteTemplate.html().replace(/{{note}}/ig, note);
  $('#' + dest).prepend(newContent);
}

$( "#ranking-admin .notes" ).on( "keypress", ".noteAdder", function( ev ) {
  var keycode = (ev.keyCode ? ev.keyCode : ev.which);
  if (keycode == '13') {
    ev.preventDefault();
    var vals = $(this).val();
    if(vals !== '') addNote(vals, $(this).attr('data-target'));
    $(this).val('');
  }
});
$( "#ranking-admin .notes" ).on( "click", ".removeParent", function( ev ) {
  $(this).parent().remove();
});
$('#ranking-admin .ranking-count [data-focusToggler]').on('focus', function( ev ) {
  $('#' + $(this).attr('data-focusToggler')).removeClass('is-hidden');
});
$('#ranking-admin .notes [data-toggle-in-toggle]').on('click', function( ev ) {
  $('#' + $(this).attr('data-toggle-in-toggle')).toggleClass('is-hidden');
});