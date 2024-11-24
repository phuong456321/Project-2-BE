<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Play Song</title>
</head>
<body>
    <h1>Play Song</h1>
    
    <form id="songForm">
        <label for="songId">Enter Song ID:</label>
        <input type="number" id="songId" name="songId" required>
        <button type="submit">Get Song</button>
    </form>

    <div id="player" style="margin-top: 20px; display: none;">
        <audio id="audioPlayer" controls>
            <source id="audioSource" src="" type="audio/mpeg">
            Your browser does not support the audio element.
        </audio>
    </div>

    <div id="errorMessage" style="color: red; margin-top: 20px; display: none;"></div>

    <script>
        document.getElementById('songForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Ngăn form reload trang
            
            const songId = document.getElementById('songId').value;
            
            fetch(`/get-song/${songId}`)
                .then(response => {
                    if (response.ok) {
                        return response.json();
                    } else {
                        throw new Error('Song not found');
                    }
                })
                .then(audioPath => {
                    document.getElementById('audioSource').src = audioPath;
                    document.getElementById('audioPlayer').load();
                    document.getElementById('player').style.display = 'block';
                    document.getElementById('errorMessage').style.display = 'none';
                })
                .catch(error => {
                    document.getElementById('player').style.display = 'none';
                    document.getElementById('errorMessage').textContent = error.message;
                    document.getElementById('errorMessage').style.display = 'block';
                });
        });
    </script>
</body>
</html>
