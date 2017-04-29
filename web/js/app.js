var App = {
	postData:function(form)
	{
	
	 $.ajax({
		url: "?r=tasks/create",
  method: "POST",
  data: $(form).serialize(),
  dataType: "json" ,
  success:function(response)
  {
	  App.addRow(response);
  },
  error:function(err)
  {
	   console.log('ошибка');
  }
	 });
	 return false;
	},
	updateRow:function(model_id)
	{
		var data = {
			'Task[name]':$('#' + model_id + '_name').val(),
			'Task[done]':$('#' + model_id + '_done').is(":checked") ? 1 : 0,
			'_csrf':$('meta[name="csrf-token"]').attr("content")
		}
		 $.ajax({
		url: "?r=tasks/update&id=" + model_id,
  method: "POST",
  data: data,
  dataType: "json" ,
  success:function(response)
  {
	alert(response.text);
  },
  error:function(err)
  {
	  alert("Ошибка сервера");
	   console.log(err.responseText);
  }
	 });
		
	},
	addRow:function(model)
	{
	console.log(model);
	$('table .empty').parent().parent().remove();
		 var table = $('table tbody');
   
         var number = ((table.find('tr').size())%2 === 0)?'odd':'even';
         var html = '<tr class="'+number+'"> <td>' 
		 + model.id +'</td><td>' 
		 + '<input type="text" id="' + model.id + '_name" value="' + model.name+ '" /></td><td>' 
		 + model.create_date  + '</td><td>' 
		 + '<input type="checkbox" id="' + model.id +'_done" name="' + model.id +'_done" ' + (model.done == 1 ? 'checked' : '')+ ' value="1"></td> <td>'
		 + '<a data-confirm="Вы действительно хотите удалить эту запись?" class="btn btn-danger" href="/project/web/index.php?r=tasks%2Fdelete&amp;id=' + model.id +'">Удалить</a> '
		 + '<button type="button" class="btn btn-success" onclick="App.updateRow(' + model.id +')">Сохранить</button>'
		 +'</td></tr>'
         table.append(html);
         return false;
      
	}
	
}