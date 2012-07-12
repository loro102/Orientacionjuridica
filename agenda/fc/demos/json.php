<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>

<head>
<link rel='stylesheet' type='text/css' href='../fullcalendar/fullcalendar.css' />
<link rel='stylesheet' type='text/css' href='../fullcalendar/fullcalendar.print.css' media='print' />
<script type='text/javascript' src='../jquery/jquery-1.7.1.min.js'></script>
<script type='text/javascript' src='../jquery/jquery-ui-1.8.17.custom.min.js'></script>
<script type='text/javascript' src='../fullcalendar/fullcalendar.min.js'></script>
<script type="text/javascript" src="ajax.js"></script>


<script type='text/javascript'>

	$(document).ready(function() {
	    var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();
		
		$('#calendar').fullCalendar({
		    header: { left: 'prev,next today',
				      center: 'title',
				      right: 'month,agendaWeek,agendaDay'},
			
			selectable: true,
			selectHelper: true,
			select: function(start, end, allDay) {
				var title = prompt('Event Title:');
				var text = prompt('Text :');

				if (title) {  $('#calendar').fullCalendar('renderEvent',{
																	title: title,
																	text : text,
																	start: start,
																	end: end,
																	allDay: allDay,
																	},
														true // make the event "stick"
													);
						
							};
							
					$.ajax({ type: "GET", dataType: "html", url: "json-insert.php", data: "t="+title+"&s="+start+"&e="+end});
					
					alert("Scrittura DB andata a buon fine!");
					alert(""+title+ start+ end+"");
				calendar.fullCalendar('unselect');
			},
			
			editable: true,
			events: "json-events.php",
			
			eventDrop: function(event, delta) {
				alert(event.title + ' was moved ' + delta + ' days\n' +
					'(should probably update your database)');
			},
			
			loading: function(bool) {
				if (bool) $('#loading').show();
				else $('#loading').hide();
			}
			
		});
		
	});

</script>
<style type='text/css'>

	body {
		margin-top: 40px;
		text-align: center;
		font-size: 14px;
		font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
		}
		
	#loading {
		position: absolute;
		top: 5px;
		right: 5px;
		}

	#calendar {
		width: 900px;
		margin: 0 auto;
		}

</style>
</head>
<body>
<div id='loading' style='display:none'>loading...</div>
<div id='calendar'></div>
<p>json-events.php needs to be running in the same directory.</p>
</body>
</html>
