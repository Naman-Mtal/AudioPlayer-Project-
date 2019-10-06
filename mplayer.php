<!DOCTYPE html>
<html lang="">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../FullStackProject/mplayercss.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <script
  src="https://code.jquery.com/jquery-3.4.1.js"
  ></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" ></script>
</head>
<style>

</style>

<body onload="setVolume(0.5)">
    <audio id="player" src="Songs/PaniDaRang.MP3" ontimeupdate="updateBar()">

    </audio>

    <div class="menuBar">
        <br>
        <span id="title">Music</span>
        <i class="fa fa-bars" aria-hidden="true" onclick="minimize()"></i>

        <div class="search">
            <input type="text" id="searchBox" class="searchTerm" placeholder="Looking for Something?">
            <button type="submit" class="searchButton" onclick="search()">
                <i class="fa fa-search"></i>
            </button>
        </div>

        <br>
        <i class="fa fa-music" id="musicIcon" aria-hidden="true"></i><span onclick="change(this)" id="Songcol" class="selected">Collection</span>
        <i class="fa fa-play-circle" id="musicIcon" aria-hidden="true"></i>
        <span onclick="change(this)" id="Songinfo" class="unselected">Now Palying</span>
    </div>
    <div class="playlist">
        <div class="collec" id="collec">
            <div class="col">
                <h1>Collection</h1>
            </div>
            <div class="col2" id="Songs">
                <ul>
                    <?php
    $con = mysqli_connect("localhost","root","","musicplayer");
    $sql = "SELECT id,name,song,image,artist from songs order by name";
    $result = mysqli_query($con, $sql);

    while($row = mysqli_fetch_assoc($result)) {
    ?>

                    <li data-audiourl="<?php echo $row['song']; ?>" data-artist="<?php echo $row['artist']; ?>" class="musicCard" onclick="playSong(this)" id="<?php echo $row['id']; ?>">

                        <div class="image" style="background-image:url(<?php echo $row['image']; ?>)"></div>
                        <div class="name" id="musicCardname"><?php echo $row['name']; ?></div>

                    </li>




                    <?php } ?>
                </ul>
            </div>
        </div>
        <div class="info" id="in">

            <div id="infoImage">

            </div>
            <div id="text"></div>
            <div id="Artist">Artist : </div>

        </div>
    </div>


    <div class="curr" id="currSong">
        <div class="thumbnail" id="currImage"></div>
        <label class="sname" id="currSongname">Pani Da Rang</label>

        <div class="progressBar">
            <canvas id="progress" onclick="updateTime(event,this)"></canvas>
            <br>
            <span id="current-time">0:00</span>
            <span id="duration"></span>
        </div>
        <div class="options">
            <i class="fa fa-step-backward fa-lg" id="prev" title="prev" onclick="prevSong()"></i>
        <i class="fa fa-play fa-lg" onclick="playS(this)" id="play" title="Play"></i>
        <i class="fa fa-step-forward fa-lg" id="next" title="next" onclick="nextSong()"></i>
        <i class="glyphicon glyphicon-refresh fa-lg" onclick="enablerep(this)" id="rep" title="Turn repeat on"></i>
        </div>
        
        <div class="volumeSlider">
            <i class="fa fa-volume-down fa-lg"></i>
            <div id="volume"></div>
            <i class="fa fa-volume-up fa-lg"></i>

        </div>


    </div>
    <script>
        $("#volume").slider({
            min: 0,
            max: 100,
            value: 50,
            range: "min",
            slide: function(event, ui) {
                setVolume(ui.value/100);
            }
        });
        
        var audio = document.getElementById("player");
        var currSong = null;
        var minimized = false;

        function playS(x) {

            if (audio.paused) {

                x.title = "Pause";
                audio.play();
                x.className = "fa fa-pause fa-lg";

            } else {

                audio.pause();
                x.title = "Play";
                x.className = "fa fa-play fa-lg";
            }



        }
        function setVolume(x)
        {
           
            audio.volume = x;
             
        }
        function updateBar() {


            var canvas = document.getElementById("progress").getContext('2d');


            var currentTime = audio.currentTime;
            var duration = audio.duration;
            document.getElementById("duration").innerHTML = convertElapsedTime(duration);
            document.getElementById("current-time").innerHTML = convertElapsedTime(currentTime);
            var percentage = currentTime / duration;

            var progress = (300 * percentage);
            canvas.fillStyle = "#ffffff";
            canvas.clearRect(0, 0, 300, 150);
            canvas.fillRect(0, 0, progress, 150);

            if (audio.ended && !audio.loop) {
                canvas.clearRect(0, 0, 300, 150);
                document.getElementById("current-time").innerHTML = "0:00";
                //document.getElementById("play").className="fa fa-play fa-lg";
                nextSong();
            }
            if (audio.ended && audio.loop)
                canvas.clearRect(0, 0, 300, 150);

        }

        function convertElapsedTime(inputSeconds) {
            var seconds = Math.floor(inputSeconds % 60)
            if (seconds < 10) {
                seconds = "0" + seconds;
            }
            var minutes = Math.floor(inputSeconds / 60);
            return minutes + ":" + seconds;
        }

        function enablerep(x) {


            if (x.style.color == 'green') {
                x.title = 'Turn repeat on';
                x.style.color = 'white';
                audio.loop = false;
            } else {
                x.title = 'Turn repeat off';
                x.style.color = 'green';
                audio.loop = true;
            }



        }


        function mute(x) {

            if (audio.muted) {
                x.title = "Mute"
                audio.muted = false
                x.innerHTML = "volume_up"
            } else {
                x.title = "Unmute"
                audio.muted = true
                x.innerHTML = "volume_off"
            }



        }

        function show() {
            var vslider = document.getElementById("vslider");
            if (vslider.style.visibility != 'visible')
                vslider.style.visibility = 'visible'
            else
                vslider.style.visibility = 'hidden'
        }
       

        function updateTime(event, x) {




            var percent = event.offsetX / x.offsetWidth;
            audio.currentTime = percent * audio.duration;
            x.value = percent / 100;
        }

        function playSong(x) {
            currSong = x

            audio.src = x.getAttribute('data-audiourl')
            playS(document.getElementById('play'))

            var url = getComputedStyle(document.getElementById(x.id).firstElementChild, null).getPropertyValue('background-image')
            var songName = document.getElementById(x.id).lastElementChild.innerHTML
            document.getElementById("currSongname").innerHTML = songName
            document.getElementById('text').innerHTML = songName
            document.getElementById('Artist').innerHTML = 'Artist : ' + x.getAttribute('data-artist');
            document.getElementById('currImage').style.backgroundImage = url
            document.getElementById('infoImage').style.backgroundImage = url
        }

        function nextSong() {
            if (currSong.nextElementSibling != null)
                playSong(currSong.nextElementSibling);
            else
                playSong(currSong.parentElement.firstElementChild);
        }

        function prevSong() {
            if (currSong.previousElementSibling != null)
                playSong(currSong.previousElementSibling);
            else
                playSong(currSong.parentElement.lastElementChild);
        }

        function change(x) {


            x.classList.add('selected')
            x.classList.remove('unselected')

            if (x.id == "Songinfo") {
                document.getElementById('Songcol').classList.remove('selected')
                document.getElementById('Songcol').classList.add('unselected')
                document.getElementById('collec').style.display = 'none';
                document.getElementById('in').style.display = 'block';
            } else {
                document.getElementById('Songinfo').classList.remove('selected')
                document.getElementById('Songinfo').classList.add('unselected')
                document.getElementById('collec').style.display = 'block';
                document.getElementById('in').style.display = 'none';
            }
        }

        function search() {


        }

        $("#searchBox").keyup(function() {
            //Assigning search box value to javascript variable named as "name".
            var name = $('#searchBox').val();

            //Validating, if "name" is empty.

            //If name is not empty.


            $.ajax({
                //AJAX type is "Post".
                type: "POST",
                //Data will be sent to "ajax.php".
                url: "search.php",
                //Data, that will be sent to "ajax.php".
                data: {
                    //Assigning value of "name" into "search" variable.
                    search: name
                },
                //If result found, this funtion will be called.
                success: function(html) {
                    //Assigning result to "display" div in "search.php" file.

                    $("#Songs").html(html);
                }
            });

        });
        $('button').click(function() {
            if (minimized)
                minimize();
        });


        function minimize() {

            if (!minimized) {

                document.getElementById('title').innerHTML = '';
                document.getElementsByClassName('fa-bars')[0].style.left = '30px';
                document.getElementById('searchBox').style.display = 'none';
                document.getElementsByClassName('searchButton')[0].style.width = '100%';

                document.getElementById('Songcol').innerHTML = '';
                document.getElementById('Songinfo').innerHTML = '';
                document.getElementsByClassName('menuBar')[0].style.width = '5%';
                document.getElementsByClassName('playlist')[0].style.width = '95%';
                minimized = true;
            } else {
                document.getElementById('title').innerHTML = 'Music';
                document.getElementsByClassName('fa-bars')[0].style.left = '240px';
                document.getElementById('searchBox').style.display = 'block';
                document.getElementsByClassName('searchButton')[0].style.width = '20%';
                document.getElementById('Songcol').innerHTML = 'Collection';
                document.getElementById('Songinfo').innerHTML = 'Now Playing';
                document.getElementsByClassName('menuBar')[0].style.width = '20%';
                document.getElementsByClassName('playlist')[0].style.width = '80%';
                minimized = false;
            }

        }

    </script>
</body>

</html>
