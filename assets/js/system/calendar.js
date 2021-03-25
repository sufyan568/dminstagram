$(function () {
   	"use strict";     
     var date = new Date()
     var d    = date.getDate(),
         m    = date.getMonth(),
         y    = date.getFullYear()


     $('#su').fullCalendar({
       header    : {
         left  : 'prev,next today',
         center: 'title',
         right : 'month,agendaWeek,agendaDay'
       },
       buttonText: {
         today: 'today',
         month: 'month',
         week : 'week',
         day  : 'day'
       },
       eventRender: function(eventObj, $el) {
         $el.popover({
           title: eventObj.title,
           content: eventObj.description,
           trigger: 'hover',
           placement: 'top',
           container: 'body',
           html:true
         });
       },
       //Random default events
       events    : calendar_events,
       editable  : false,
       droppable : false, // this allows things to be dropped onto the calendar !!!
       slotEventOverlap: false,
       eventRender: function(event, eventElement) {
           if (event.imageurl) {
               var str = event.imageurl;
               if(str.endsWith(".mov") || str.endsWith(".mp4"))
               eventElement.find("div.fc-content").parent().prepend("<video width='100%' height='100px'><source  src='" + event.imageurl +"'></source></video>");
               else eventElement.find("div.fc-content").parent().prepend("<img src='" + event.imageurl +"' width='100%' height='100px' class='d-block mx-auto'>");
           }
       }

     });
   });