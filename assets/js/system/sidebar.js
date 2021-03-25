"use strict";
var sideBarURL = window.location;
sideBarURL=String(sideBarURL).trim();
sideBarURL=sideBarURL.replace('#_=_',''); // redirct from facebook login return extra chars with url

function removeUrlLastPart(the_url)   // function that remove last segment of a url
{
    var theurl = String(the_url).split('/');
    theurl.pop();      
    var answer=theurl.join('/');
    return answer;
}

// get parent url of a custom url
function matchCustomUrl(find)
{
  var parentUrl='';
  var tempu1=find.replace(/\//g, 'FORWARDSLASHES'); // decoding special chars that was encoded to make js array
  tempu1=tempu1.replace(/:/g, 'COLONS');
  tempu1=tempu1.replace(/-/g, 'DASHES');
  tempu1=tempu1.replace(/\./g, 'DOTS');

  if(typeof(custom_links_assoc_JS[tempu1])!=='undefined')
  parentUrl=custom_links_assoc_JS[tempu1]; // getting parent value of custom link

  return parentUrl;
}

if(jQuery.inArray(sideBarURL, custom_links_JS) !== -1) // if the current link match custom urls
{    
  sideBarURL=matchCustomUrl(sideBarURL);
} 
else if(jQuery.inArray(sideBarURL, all_links_JS) !== -1) // if the current link match known urls, this check is done later becuase all_links_JS also contains custom urls
{
   sideBarURL=sideBarURL;
}
else // url does not match any of known urls
{  
  var remove_times=1;
  var temp_URL=sideBarURL;
  var temp_URL2="";
  var tempu2="";
  while(true) // trying to match known urls by remove last part of url or adding /index at the last
  {
    temp_URL=removeUrlLastPart(temp_URL); // url may match after removing last
    temp_URL2=temp_URL+'/index'; // url may match after removing last part and adding /index

    if(jQuery.inArray(temp_URL, custom_links_JS) !== -1) // trimmed url match custom urls
    {
      sideBarURL=matchCustomUrl(temp_URL);
      break;
    }
    else if(jQuery.inArray(temp_URL, all_links_JS) !== -1) //trimmed url match known links
    {
      sideBarURL=temp_URL;
      break;
    }
    else // trimmed url does not match known urls, lets try extending url by adding /index
    {
      if(jQuery.inArray(temp_URL2, custom_links_JS) !== -1) // extended url match custom urls
      {
        sideBarURL=matchCustomUrl(temp_URL2);
        break;
      }
      else if(jQuery.inArray(temp_URL2, all_links_JS) !== -1)  // extended url match known urls
      {
        sideBarURL=temp_URL2;
        break;
      }
    }
    remove_times++;
    if(temp_URL.trim()=="") break;
  }    
}

$('ul.sidebar-menu a').filter(function() {
   return this.href == sideBarURL;
}).parent().addClass('active');
$('ul.dropdown-menu a').filter(function() {
   return this.href == sideBarURL;
}).parentsUntil(".sidebar-menu > .dropdown-menu").addClass('active');