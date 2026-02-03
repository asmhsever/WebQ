<!DOCTYPE html>
<html>
<body>

<video id="video" autoplay></video>
<canvas id="canvas" style="display:none"></canvas>
<button onclick="scan()">สแกน</button>

<script>
const video = document.getElementById("video");
const canvas = document.getElementById("canvas");

navigator.mediaDevices.getUserMedia({ video: true })
.then(stream => video.srcObject = stream);

function scan() {
  canvas.width = video.videoWidth;
  canvas.height = video.videoHeight;
  canvas.getContext("2d").drawImage(video, 0, 0);

  const image = canvas.toDataURL("image/jpeg");

  fetch("scan_api.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded"
    },
    body: "image=" + encodeURIComponent(image)
  })
  .then(r => r.text())
  .then(d => {
    console.log("ตอบกลับจาก PHP:", d);

    // ✅ จุดสำคัญอยู่ตรงนี้
    // บอก dashboard ให้ refresh + ปิด popup
    window.parent.postMessage("refresh", "*");
  });
}
</script>

</body>
</html>
