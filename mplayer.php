<!DOCTYPE html>
<html lang="">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="mplayercss.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

</head>


<body>
    <audio id="player" src="Songs/PANI_DA_RANG.mp3" ontimeupdate="updateBar()">

    </audio>

    <div class="menuBar">
        <p>Music</p>
    </div>
    <div class="playlist">
        <div class="col"  ><h1>Collection</h1></div>
        <div class="col2"><ul>
            <?php
    $con = mysqli_connect("localhost","root","","musicplayer");
    $sql = "SELECT id,name,song,image from songs";
    $result = mysqli_query($con, $sql);

    while($row = mysqli_fetch_assoc($result)) {
    ?>

            <li data-audiourl="<?php echo $row['song']; ?>" class="musicCard" onclick="playSong(this)" id="<?php echo $row['id']; ?>">

                    <div class="image" style="background-image:url(<?php echo $row['image']; ?>)"></div>
                    <div class="name" id="musicCardname"><?php echo $row['name']; ?></div>

            </li>




        <?php } ?>
</ul></div>
    </div>

    <div class="volumeslider" id="vslider">
        <input type="range" orient="vertical" min="0" max="100" value="50" class="slider" id="myRange">
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
        <i class="fa fa-step-backward fa-lg" id="prev" title="prev" onclick="prevSong()"></i>
        <i class="fa fa-play fa-lg" onclick="playS(this)" id="play" title="Play"></i>
        <i class="fa fa-step-forward fa-lg" id="next" title="next" onclick="nextSong()"></i>
        <i class="glyphicon glyphicon-refresh fa-lg" onclick="enablerep(this)" id="rep" title="Turn repeat on"></i>

        <i class="material-icons" onclick="show()" id="volume" title="Mute">
            volume_up
        </i>
        <span id="svalue">50</span>

    </div>
    <script>
        var audio = document.getElementById("player");
        var currSong = null;
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

            if (audio.ended) {
                canvas.clearRect(0, 0, 300, 150);
                document.getElementById("current-time").innerHTML = "0:00";
                //document.getElementById("play").className="fa fa-play fa-lg";
               nextSong();
            }
            if (percentage >= 0.985 && audio.loop)
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
        var slider = document.getElementById("myRange");

        slider.oninput = function() {


            document.getElementById("svalue").innerHTML = this.value;
            audio.volume = (this.value) * 0.01;
        }

        function updateTime(event, x) {




            var percent = event.offsetX / x.offsetWidth;
            audio.currentTime = percent * audio.duration;
            x.value = percent / 100;
        }

        function playSong(x) {
            currSong = x
            audio.src = x.getAttribute('data-audiourl')
            audio.play();
            document.getElementById('play').title = "Pause";
            document.getElementById('play').className = "fa fa-pause fa-lg"
            var url = getComputedStyle(document.getElementById(x.id).firstElementChild, null).getPropertyValue('background-image')

            document.getElementById("currSongname").innerHTML = document.getElementById(x.id).lastElementChild.innerHTML
            document.getElementById('currImage').style.backgroundImage = url
        }
        function nextSong()
        {
            if(currSong.nextElementSibling!=null)
            playSong(currSong.nextElementSibling);
            else
                playSong(currSong.parentElement.firstElementChild);
        }
        function prevSong()
        {
            if(currSong.previousElementSibling!=null)
            playSong(currSong.previousElementSibling);
            else
                playSong(currSong.parentElement.lastElementChild);
        }
    </script>
</body>

</html>
