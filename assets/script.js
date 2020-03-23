window.onload = function() {
  if(localStorage.length<1){
      navigator.geolocation.getCurrentPosition(
      function(position) {
      var lat = position.coords.latitude;
      var lng = position.coords.longitude;
      localStorage.setItem("user", "user");
      let form = document.createElement('form');
      form.action = '';
      form.method = 'POST';
      form.innerHTML = `<input name="coords_lng" value="lat=${lat}&lon=${lng}">`;
      document.body.append(form);
      form.submit();
      },
      function(error){console.log(error)}
      )
    }
  }
    
function searchName(f) {
  let name=document.querySelector(".ac_input").value;
  let data=localStorage.getItem(name);
  if(!data){
    let apiKey = "e3f5fe20e8765078cd50e687e25b7571";
    let link="http://api.openweathermap.org/data/2.5/weather?q="+name+"&appid="+apiKey;
    fetch(link).then(function (resp) {return resp.json() }).then(function (data) {
    data.time=Math.round(new Date().getTime()/1000);
    let d = JSON.stringify(data);
    localStorage.setItem(name, d); 
    f.submit();
    }).catch(function (er) {alert(er);});   
 }else{
   data=JSON.parse(data);
   let diff=Math.round(new Date().getTime()/1000)-data.time;
   if(diff<601){
     let wind="wind"+name;
     document.getElementById(wind).innerHTML = data.wind.speed+' m/s';
     document.getElementById(`temp_min${name}`).innerHTML = Math.round(data.main.temp_min-273)+'°C';
     document.getElementById(`temp_max${name}`).innerHTML = Math.round(data.main.temp_max-273)+'°C';
     document.getElementById(`clouds${name}`).innerHTML = data.clouds.all+'%';
     document.getElementById(`humidity${name}`).innerHTML = data.main.humidity+'%';
     document.getElementById(`sunrise${name}`).innerHTML = convert(data.sys.sunrise);
     document.getElementById(`sunset${name}`).innerHTML = convert(data.sys.sunset);
   }else{
     data.time=Math.round(new Date().getTime()/1000);
     data = JSON.stringify(data);
     localStorage.setItem(name, data); 
     f.submit();
  }
 }
}

function convert(time){
  let date = new Date(time* 1000);
  let d = [
    '0' + date.getHours(),
    '0' + date.getMinutes(),
    '0' + date.getSeconds()
  ].map(component => component.slice(-2));
  return d.slice(0).join(':');
}
