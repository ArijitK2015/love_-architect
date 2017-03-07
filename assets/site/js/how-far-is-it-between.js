setvaluea("");
setvalueb("");
var routePoints=new Array(0);
var total_distance=0;
var disttrans=0;
var map;
var bounds;
var arrMarkers=new Array(0);
var routePath = null;
var lineWidth=4;
var lineColor='#ff0000';
var directionsDisplay;
var addedmarker;
var mapListener;
var geocoder = null;
var geocoder1 = null;
var geocoder2 = null;
var CityToAdd;
var FMTkmlcoordinates="";

var unit_handler=MILES;


if (typeof getunits !== 'undefined') 
{
	if (getunits=="KM")
	{
		unit_handler=KMS;
	}
}

function GUnload(){}

function Gload() 
{
	var latlng = new google.maps.LatLng(0,0);
	var myOptions = {scaleControl:true,zoom:1,center:latlng,draggableCursor:'crosshair',mapTypeControlOptions:{style:google.maps.MapTypeControlStyle.DROPDOWN_MENU}};
	map = new google.maps.Map(document.getElementById("map_canvas"),myOptions);
	Funct1();
	document.getElementById("distance").value="";
	document.getElementById("transport").value="";	
	
	geocoder1 = new google.maps.Geocoder();	
	geocoder2 = new google.maps.Geocoder();	
	geocoder = new google.maps.Geocoder();	
	
	var input = /** @type {HTMLInputElement} */(document.getElementById('pointa'));
	var searchBoxa = new google.maps.places.Autocomplete(input);
	var input = /** @type {HTMLInputElement} */(document.getElementById('pointb'));
	var searchBoxb = new google.maps.places.Autocomplete(input);
	

	//preset myonoffswitch depending on settings_unit_handler state
	if (settings_unit_handler==KMS)
	{
		document.getElementById("myonoffswitch").checked = false;
		unit_handler=KMS;
	}
	else
	{
		document.getElementById("myonoffswitch").checked = true;
		unit_handler=MILES;	
	}
}

function setvaluea (thevalue)
{
	acObject = document.getElementById("autocompletediva");
	acObject.style.visibility = "hidden";
	acObject.style.height = "0px";
	acObject.style.width = "0px";
	document.forms["inp"]["pointa"].value = thevalue;
}

function setvalueb (thevalue)
{
	acObject = document.getElementById("autocompletedivb");
	acObject.style.visibility = "hidden";
	acObject.style.height = "0px";
	acObject.style.width = "0px";
	document.forms["inp"]["pointb"].value = thevalue;
}

//--------------------------------------------//
function findaandb (valuea,valueb)
{
	acObject = document.getElementById("autocompletediva");	
	acObject.style.visibility = "hidden";
	acObject.style.height = "0px";
	acObject.style.width = "0px";
	acObject = document.getElementById("autocompletedivb");	
	acObject.style.visibility = "hidden";
	acObject.style.height = "0px";
	acObject.style.width = "0px";
	total_distance=0.00;
	routePoints=[];
	
	if (!(routePath==undefined))
	{
		routePath.setMap(null);
	}
	if (arrMarkers) 
	{
		for (i in arrMarkers) 
		{
			arrMarkers[i].setMap(null);
		}
	}
	if (directionsDisplay)
	{
		directionsDisplay.setMap(null);
	}

	arrMarkers=new Array(0);
	bounds = new google.maps.LatLngBounds();
	
	
	document.getElementById("distance").value=0;

		var xmlHttp;
  		try
    	{
    		// Firefox, Opera 8.0+, Safari
    		xmlHttp=new XMLHttpRequest();
    	}
  		catch (e)
    	{
    		// Internet Explorer
    		try
      		{
     			 xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
      		}
    		catch (e)
      		{
      			try
        		{
       				xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
        		}
      		catch (e)
        	{
        		alert("Your browser does not support AJAX!");
        		return false;
        	}
		}
	}
	xmlHttp.onreadystatechange=function()
	{
		if(xmlHttp.readyState==4)
		{
			var xml = xmlHttp.responseXML;
			//alert(xmlHttp.responseText);
			var markers = xml.documentElement.getElementsByTagName("marker");
			acObject = document.getElementById("msg");
			acObject.style.visibility = "hidden";	
			acObject.innerHTML="";
			for (var i = 0; i < markers.length; i++) 
			{
				var name = markers[i].getAttribute("name");
				var id = markers[i].getAttribute("id");
				if (id==0)
				{	
					if (name=="nf1")
					{
						usePointFromPostcode1(document.forms["inp"]["pointa"].value, placeMarkerAtPoint);
					}
					if (name=="nf2")
					{
						var t=setTimeout('usePointFromPostcode2(document.forms["inp"]["pointb"].value, placeMarkerAtPoint);',1000);
					}
				}
				else
				{
					
    				var point = new google.maps.LatLng(parseFloat(markers[i].getAttribute("lat")),parseFloat(markers[i].getAttribute("lng")));
				

					if ((document.forms["inp"]["pointa"].value=="North Pole")||(document.forms["inp"]["pointb"].value=="North Pole"))
					{
						//SantaIcon
						var marker = placeMarker(point,'');
						arrMarkers.push(marker);
					}
					else
					{
						var marker = placeMarker(point,'');
						arrMarkers.push(marker);
					}
						
						routePoints.push(point);
						bounds.extend(point);
					}
		 		}
				
				updateURLdisplay();
						
				if (routePoints.length>1)
				{
					udisplay();
				}
			}
      };
	  
	var valueamod=valuea.replace(/, /g,",");
	var valuebmod=valueb.replace(/, /g,",");
	
	valueamod=valueamod.replace(/,/g,"_");
	valuebmod=valuebmod.replace(/,/g,"_");
		 
	var randomnumber=Math.floor(Math.random()*9999);
	//console.log("ajax/getaandb.php?a="+valueamod+"&b="+valuebmod+"&rn="+randomnumber);
	xmlHttp.open("GET","ajax/getaandb.php?a="+valueamod+"&b="+valuebmod+"&rn="+randomnumber,true);
	xmlHttp.send(null);

}

function updateURLdisplay()
{
	str1=document.forms["inp"]["pointa"].value.toLowerCase().replace(/,/,"_");
	//str1=str1.replace("'","\'",str1);
	str1 = str1.replace(/'/g, "\'");
	str1=str1.replace(/ /g,"-");
	
			
	str2=document.forms["inp"]["pointb"].value.toLowerCase().replace(/,/,"_");
	//str2=str2.replace("'","\'",str2);
	str2 = str2.replace(/'/g, "\'");
	str2=str2.replace(/ /g,"-");
	
	
	
	var textForKMunits="";
	
	if (unit_handler==KMS)
	{
		textForKMunits="?units=KM";
	}
	
	var urlformatted = "https://www.freemaptools.com/how-far-is-it-between-"+trim(str1)+"-and-"+trim(str2)+".htm"+textForKMunits;
	
				
	document.getElementById("message").innerHTML='<p class="box">You can <strong>link to this result</strong> : <a href="'+urlformatted+'">How Far is it Between '+Capital(document.forms["inp"]["pointa"].value)+' and '+Capital(document.forms["inp"]["pointb"].value)+'</a><input style="width: 100%;text-align: center;" onClick="this.select();" value="'+urlformatted+'" /></p>';	
}

function placeMarkerAtPoint(point)
{
	var marker;
		
	if ((document.forms["inp"]["pointa"].value=="North Pole")||(document.forms["inp"]["pointb"].value=="North Pole"))
	{
		var tmpPoint=new google.maps.LatLng(90,0);
		//var tmpMarketPoint=new GLatLng(83,0);
		//marker = new GMarker(tmpMarketPoint,SantaIcon);
		routePoints.push(tmpPoint);
		bounds.extend(new google.maps.LatLng(83,0));
		
		//SANTA
		marker = placeMarker(tmpPoint,'');
		arrMarkers.push(marker);
	}
	else
	{
		//marker = new GMarker(point,Icon);
		routePoints.push(point);
		bounds.extend(point);
		
		marker = placeMarker(point,'');
		arrMarkers.push(marker)
	}
	
	if (routePoints.length>1)
	{
		udisplay();
	}
}

//--------------------------------------------//
function placeMarker(location,text) 
{	
	var iconFile = 'images/markers/freemaptools.png';	var marker = new google.maps.Marker({position:location,map:map,icon:iconFile,title:text,draggable:false,opacity:FMTmarkeropacity});
	return marker;
}
//--------------------------------------------//
function udisplay()
{
	var lat1=routePoints[routePoints.length-1].lat();
	var lng1=routePoints[routePoints.length-1].lng();
	var lat2=routePoints[routePoints.length-2].lat();
	var lng2=routePoints[routePoints.length-2].lng();
	
	FMTkmlcoordinates="";
	FMTkmlcoordinates+=lng1 + "," + lat1 + ",0 ";
	FMTkmlcoordinates+=lng2 + "," + lat2 + ",0 ";

	//total_distance+=((LatLon.distHaversine(lat1,lng1,lat2,lng2).toPrecision(4))*1000);
	
	var point1 = new google.maps.LatLng(lat1,lng1);
	var point2 = new google.maps.LatLng(lat2,lng2);
	total_distance+=google.maps.geometry.spherical.computeDistanceBetween(point1,point2);
	
	if (lat1==90)
	{
		routePoints[routePoints.length-1]=new google.maps.LatLng(83,0);
	}
	if (lat2==90)
	{
		routePoints[routePoints.length-2]=new google.maps.LatLng(83,0);
	}
	
	//remove old polyline first
	if (!(routePath==undefined))
	{
		routePath.setMap(null);
	}
	routePath=getRoutePath();
	routePath.setMap(map);
	
	hmsgObject = document.getElementById("titlemsg");	
	hmsgObject.innerHTML="<h2>Map Showing the Distance Between "+document.forms["inp"]["pointa"].value+" and "+document.forms["inp"]["pointb"].value+"</h2>";
	updateDisplay();
	ZoomOut();		
}
//--------------------------------------------//
function getRoutePath()
{
	var routePath = new google.maps.Polyline({
		path: routePoints,
		strokeColor: lineColor,
		strokeOpacity: 1.0,
		strokeWeight: lineWidth,
		geodesic: true
	});
	return routePath;
}
//--------------------------------------------//
function ZoomOut()
{
	map.setCenter(bounds.getCenter());
	map.fitBounds(bounds);
}
//--------------------------------------------//
function trim(str) 
{ 
	str.replace(/^\s*/, '').replace(/\s*$/, ''); 
	return str;
}
//--------------------------------------------//
function clearMap()
{
	total_distance=0.00;
	disttrans=0.00;
	routePoints=[];
	if (!(routePath==undefined))
	{
		routePath.setMap(null);
	}
	if (arrMarkers) 
	{
		for (i in arrMarkers) 
		{
			arrMarkers[i].setMap(null)
		}
	}
	arrMarkers=new Array(0);
	updateDisplay();
}
//--------------------------------------------//

function updateDisplay()
{	
	var dist=unit_handler.f(total_distance);
	document.getElementById("distance").value=dist.toFixed(3);
	
	var directionsService = new google.maps.DirectionsService();
	directionsDisplay = new google.maps.DirectionsRenderer();
	directionsDisplay.setMap(map);
	
	var fromAddress=document.forms["inp"]["pointa"].value;
	var toAddress=document.forms["inp"]["pointb"].value;
		
	//avoidHighways
	var bool_avoidHighways;
	if (document.getElementById("cb_avoidhighways").checked)
	{
		bool_avoidHighways=true;
	}
	else
	{
		bool_avoidHighways=false;
	}
		
	var request = {origin:fromAddress,destination:toAddress,travelMode: google.maps.DirectionsTravelMode.DRIVING,avoidHighways:bool_avoidHighways};
  	directionsService.route(request, function(response, status) 
	{
    	if (status == google.maps.DirectionsStatus.OK) 
		{
      		directionsDisplay.setDirections(response); 
			transport_distance=response.routes[0].legs[0].distance.value;
	  		disttrans=unit_handler.f(transport_distance);
			document.getElementById("transport").value=disttrans.toFixed(3);
			/*
			FMTkmlcoordinates="";
			response.routes[0].legs.forEach(function(leg){
				leg.steps.forEach(function(step){
					step.path.forEach(function(points){
						//debug_panel.innerHTML+=point+"<br/>";
						FMTkmlcoordinates+=points.lng() + "," + points.lat() + ",0 ";
					});
				});
			});
			*/
			
    	}
		else
		{
			document.getElementById("transport").value="N/A"	
		}
  	});
}
//--------------------------------------------//

function Capital(value) {
	var pattern = value.replace(/,/g," , ");

    pattern = /(\w)(\w*,*)/; // a letter, and then one, none or more letters 

    var a = value.split(/\s+/g); // split the sentence into an array of words

    for (i = 0 ; i < a.length ; i ++ ) {
        var parts = a[i].match(pattern); // just a temp variable to store the fragments in.

        var firstLetter = parts[1].toUpperCase();
        var restOfWord = parts[2].toLowerCase();

        a[i] = firstLetter + restOfWord; // re-assign it back to the array and move on
    }
	
    var rtn=a.join(' '); // join it back together
    rtn = rtn.replace(/ , /g,",");
	return rtn;
}

function toggleUnits()
{
	if(unit_handler==MILES)
	{
		unit_handler=KMS;
		settings_unit_handler=KMS;
	}
	else
	{
		unit_handler=MILES;
		settings_unit_handler=MILES;
	}
	
	//save update
		xmlbuilder_initialise(xmlbuilderversion);
		xmlDoc=xmlreader(localStorage.fmtuserdata);
		xmlDoc.getElementsByTagName("fmtuserdata")[0].attributes["unitssetting"].value=settings_unit_handler.label;		
		var xmlString = (new XMLSerializer()).serializeToString(xmlDoc);
		localStorage.fmtuserdata=xmlString;
	//save update
				
	updateURLdisplay();
	updateDisplay();
}

function toggleUnits_NOLONGERUSED(arg)
{
	if(arg=="MILES")
	unit_handler=MILES;
	else
	unit_handler=KMS;
	updateDisplay();
}

function add(city)
{	
	CityToAdd=city;
	acObject = document.getElementById("msg");
	acObject.style.visibility = "visible";	
	acObject.innerHTML="OK, zoom and pan the map to find " + CityToAdd+". Then click to indicate its posistion.";
	clearMap();
	
	map.setCenter(new google.maps.LatLng(0,0));
	map.setZoom(1);
	
	mapListener=google.maps.event.addListener(map, 'click',mapclicked);
}


function mapclicked(event)
{
			addedmarker = placeMarker(event.latLng,'');
			
			xmlbuild="<markers><marker id=\"0\" name=\""+ CityToAdd +"\" lat=\""+ event.latLng.lat() +"\" lng=\""+ event.latLng.lng() +"\"/></markers>";
			//Create a boolean variable to check for a valid MS instance.
			var xmlhttp = false;
			//Check if we are using IE.
			try 
			{
				//If the javascript version is greater than 5.
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
			} 
			catch (e) 
			{
				//If not, then use the older active x object.
				try 
				{
					//If we are using IE.
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}		
				catch (E) 
				{
					//Else we must be using a non-IE browser.
					xmlhttp = false;
				}
			}
				//If we are using a non-IE browser, create a javascript instance of the object.
				if (!xmlhttp && typeof XMLHttpRequest != 'undefined') 
				{
					xmlhttp = new XMLHttpRequest();
				}
				xmlhttp.onreadystatechange=function()
			{
				if(xmlhttp.readyState==4)
				{
					// do stuff
					var xml = xmlhttp.responseXML;
					alert(xmlhttp.responseXML);
					acObject.innerHTML="Thanks! This point location for "+ CityToAdd +" has now been added to the system for you and others to use. <a href='#' onclick='added();'>continue...</a>";
				}
			}; 
    xmlhttp.open("GET","ajax/savepoint.php?xml="+xmlbuild,true);
	//alert ("ajax/savepoint.php?xml="+xmlbuild);
	
    xmlhttp.send(null);	
 	
}

function added()
{
	acObject = document.getElementById("msg");
	acObject.style.visibility = "hidden";	
	acObject.innerHTML="";
	findaandb (document.forms['inp']['pointa'].value,document.forms['inp']['pointb'].value);
	addedmarker.setMap(null);
	//GEvent.clearInstanceListeners(map);
	google.maps.event.removeListener(mapListener);
}


//---------------------------------------------//
function usePointFromPostcode1(postcode, callbackFunction) 
{
	geocoder1.geocode( { 'address': postcode}, function(results, status) 
	{
		if (status == google.maps.GeocoderStatus.OK) 
		{

			var point = results[0].geometry.location;
			callbackFunction(point);
			
			//save data?
			if (true)
			{
			//************************************
			//add this city to the db
			xmlbuild="<markers><marker id=\"0\" name=\""+ postcode +"\" lat=\""+ point.lat() +"\" lng=\""+ point.lng() +"\"/></markers>";
			//Create a boolean variable to check for a valid MS instance.
			var xmlhttp = false;
			//Check if we are using IE.
			try 
			{
				//If the javascript version is greater than 5.
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
			} 
			catch (e) 
			{
				//If not, then use the older active x object.
				try 
				{
					//If we are using IE.
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}		
				catch (E) 
				{
					//Else we must be using a non-IE browser.
					xmlhttp = false;
				}
			}
	//If we are using a non-IE browser, create a javascript instance of the object.
	if (!xmlhttp && typeof XMLHttpRequest != 'undefined') 
	{
		xmlhttp = new XMLHttpRequest();
	}
    xmlhttp.onreadystatechange=function()
	{
		if(xmlhttp.readyState==4)
        {
			// do stuff
			var xml = xmlhttp.responseXML;
        }
	};
    xmlhttp.open("GET","ajax/savepoint.php?xml="+xmlbuild,true);
	
    xmlhttp.send(null);	
	//************************************
			
			
		}
		}
		else
		{
			//alert("Postcode not found!");
			acObject.style.visibility = "visible";
		acObject.innerHTML+="Sorry, "+document.forms["inp"]["pointa"].value+" is not on our system at the current time.<br/> (<a href='#' onclick='add(document.forms[\"inp\"][\"pointa\"].value);'>Add it yourself</a>)";
		}
	});		
}
//---------------------------------------------//
function usePointFromPostcode2(postcode, callbackFunction) 
{
	//alert ("looking for - "+postcode);
	
	geocoder2.geocode( { 'address': postcode}, function(results, status) 
	{
		if (status == google.maps.GeocoderStatus.OK) 
		{

			var point = results[0].geometry.location;
			callbackFunction(point);
			
			//save data?
			if (true)
			{
			//************************************
			//add this city to the db
			xmlbuild="<markers><marker id=\"0\" name=\""+ postcode +"\" lat=\""+ point.lat() +"\" lng=\""+ point.lng() +"\"/></markers>";
			//Create a boolean variable to check for a valid MS instance.
			var xmlhttp = false;
			//Check if we are using IE.
			try 
			{
				//If the javascript version is greater than 5.
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
			} 
			catch (e) 
			{
				//If not, then use the older active x object.
				try 
				{
					//If we are using IE.
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}		
				catch (E) 
				{
					//Else we must be using a non-IE browser.
					xmlhttp = false;
				}
			}
	//If we are using a non-IE browser, create a javascript instance of the object.
	if (!xmlhttp && typeof XMLHttpRequest != 'undefined') 
	{
		xmlhttp = new XMLHttpRequest();
	}
    xmlhttp.onreadystatechange=function()
	{
		if(xmlhttp.readyState==4)
        {
			// do stuff
			var xml = xmlhttp.responseXML;
        }
	};
    xmlhttp.open("GET","ajax/savepointb.php?xml="+xmlbuild,true);
	//alert ("spb"+xmlbuild);
    xmlhttp.send(null);	
	//************************************

			
		};
		}
		else
		{
			//alert("Postcode not found!");
		if (acObject.innerHTML!="")
		{
			acObject.innerHTML+="<br/>";
		}
			acObject.style.visibility = "visible";
			acObject.innerHTML+="Sorry, "+document.forms["inp"]["pointb"].value+" is not on our system at the current time.<br/> (<a href='#' onclick='add(document.forms[\"inp\"][\"pointb\"].value);'>Add it yourself</a>)";
		}
	});		
}

