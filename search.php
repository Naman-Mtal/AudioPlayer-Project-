<?php

$con = mysqli_connect("localhost","root","","musicplayer");

    $output='';

if (isset($_POST['search'])) {

   $Name = $_POST['search'];


    $output .= '<ul>';
    $con = mysqli_connect("localhost","root","","musicplayer");
    $sql = "SELECT id,name,song,image,artist from songs WHERE name LIKE '%$Name%' order by name";
    $result = mysqli_query($con, $sql);

    while($row = mysqli_fetch_assoc($result)) {


                   $output .= "<li data-audiourl=".$row["song"]." data-artist = ".$row['artist']." class='musicCard' onclick='playSong(this)' id=".$row["id"].">

                        <div class='image' style='background-image:url(".$row["image"].")'></div>
                        <div class='name' id='musicCardname'>".$row["name"]."</div>

                    </li>";





                     }
            $output .= '</ul>';

echo $output;
}
else
{
    echo 'No results found';
}
?>
