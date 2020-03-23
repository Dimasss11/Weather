<?php

class Weather{
  protected $link, $url, $timestamp, $city, $country, $coordinates;

  function __construct($link, $url, $timestamp, $city, $country, $coordinates, $id)
	{
    $this->link=$link;
		$this->url = $url;
    $this->timestamp = $timestamp;
    $this->city=$city;
    $this->country = $country;
    $this->coordinates = $coordinates;
    $this->id = $id;
  }
  
  function save(){
    $this->url =mysqli_real_escape_string($this->link, $this->url);
    $this->timestamp=mysqli_real_escape_string($this->link, $this->timestamp);
    $this->$city=mysqli_real_escape_string($this->link, $this->$city);
    $this->country=mysqli_real_escape_string($this->link, $this->country);
    $this->coordinates=mysqli_real_escape_string($this->link, $this->coordinates);
    $result=mysqli_query($this->link,"SELECT * FROM `weather` WHERE id_city='$this->id'");
    $rows = mysqli_num_rows($result);
    if($rows<1){
     $query ="INSERT INTO weather VALUES(NULL, '$this->url','$this->timestamp', '$this->city', '$this->country', '$this->coordinates', $this->id)";
    mysqli_query($this->link, $query) or die("error " . mysqli_error($this->link));  
    }
  }

  static function get($link){
    $artc=mysqli_query($link,"SELECT * FROM `weather`");
        return $artc;
  }

  static function delete($link, $id){
    $id = mysqli_real_escape_string($link, $id);
    $query ="DELETE FROM weather WHERE id_URL = '$id'";
    mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link)); 
    mysqli_close($link);
  }
}
?>